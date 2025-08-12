<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\Service;
use App\Models\Reading;
use App\Models\Member;
use App\Models\Org;
use Illuminate\Support\Str;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\PaymentMethod;
use App\Http\Controllers\Org\DB;
use App\Http\Controllers\Org\Log;

class OrdersController extends Controller
{
    protected $_param;
    public $org;

    public function __construct()
    {
        $this->middleware('auth');
        // El parámetro 'id' debe obtenerse dentro de los métodos, no en el constructor
        $this->org = null;
    }


    public function store(Request $request, $org_id)
    {
        //tienes que revisar store para corregir show(la vista del voucher, es problema de como se guarda la orden)
        $validated = $request->validate([
            'services' => 'required|array',
            'services.*' => 'exists:services,id',
            'payment_method_id' => 'required|string|in:1,2,3',
        ]);
        if (!$request->has('services') || empty($request->services)) {
            return redirect()->back()->with('error', 'Debes seleccionar al menos un servicio');
        }

        // $member = Member::where('rut', $request->input('dni'))->first();
        $firstService = Service::find($validated['services'][0]);
        if (!$firstService) {
            return redirect()->back()->with('error', 'No se encontró el servicio seleccionado');
        }

        $member = Member::find($firstService->member_id);

        if (!$member) {
            return redirect()->back()->with('error', 'No se encontró el socio asociado al servicio');
        }

        $payment_method_id = $request->input('payment_method_id');
        $payment_status = in_array($payment_method_id, [1, 2, 3]) ? 1 : 0;

        $order = new Order();
        $order->order_code = Str::upper(Str::random(9));
        $order->dni = $member->rut;
        $order->name = $member->first_name . ' ' . $member->last_name;
        $order->email = $member->email;
        $order->phone = $member->phone;
        $order->status = 1;
        $order->payment_method_id = $payment_method_id;
        $order->payment_status = $payment_status;
        $order->save();

        $order_id = $order->id;
        $services = $request->input('services');

        $sumTotal = 0;
        $qty = 0;
        $invoiceType = 'boleta'; // Valor por defecto

        // NUEVA LÓGICA: Procesar solo las lecturas seleccionadas por el usuario
        $selectedReadingIds = $request->input('services'); // Contiene reading_ids del formulario
        
        if (empty($selectedReadingIds)) {
            return redirect()->back()->with('error', 'Debes seleccionar al menos una lectura para procesar.');
        }
        
        // Obtener las lecturas seleccionadas con el monto correcto que se mostró en la vista
        $selectedReadings = Reading::join('services', 'services.id', 'readings.service_id')
            ->whereIn('readings.id', $selectedReadingIds)
            ->where('readings.payment_status', 0)
            ->where('readings.org_id', $org_id)
            ->select(
                'readings.*',
                'services.invoice_type',
                'services.locality_id'
            )
            ->get();
            
        \Log::info('Selected readings for processing', [
            'member_id' => $member->id,
            'selected_reading_ids' => $selectedReadingIds,
            'selected_readings_count' => $selectedReadings->count(),
            'readings_by_service' => $selectedReadings->groupBy('service_id')->map(function($group) {
                return $group->count();
            })->toArray()
        ]);
        
        if ($selectedReadings->isEmpty()) {
            \Log::error('No valid readings found for selected IDs', [
                'member_id' => $member->id,
                'selected_ids' => $selectedReadingIds
            ]);
            return redirect()->back()->with('error', 'No se encontraron lecturas válidas para procesar.');
        }
        
        // Procesar SOLO las lecturas seleccionadas por el usuario
        foreach ($selectedReadings as $reading) {
            \Log::info('Processing Pending Reading', [
                'reading_id' => $reading->id, 
                'total' => $reading->total,
                'invoice_type' => $reading->invoice_type ?? 'boleta'
            ]);
            
            $qty++;
            
            // Capturar el tipo de factura de la primera lectura
            if ($qty === 1) {
                $invoiceType = $reading->invoice_type ?? 'boleta';
            }
            
            // Usar directamente el monto que se mostró en la vista (readings.total)
            // Este es el mismo monto que ve el usuario en la tabla de servicios
            $montoReal = $reading->total;
            
            try {
                $orderItem = new OrderItem;
                $orderItem->order_id = $order_id;
                $orderItem->org_id = $reading->org_id;
                $orderItem->member_id = $reading->member_id;
                $orderItem->service_id = $reading->service_id;
                $orderItem->reading_id = $reading->id;
                $orderItem->locality_id = $reading->locality_id ?? 1;
                $orderItem->folio = $reading->folio;
                $orderItem->type_dte = ($reading->invoice_type == 'factura') ? 'Factura' : 'Boleta';
                $orderItem->price = $montoReal; // Mismo monto que se muestra en la vista
                $orderItem->total = $montoReal; // Mismo monto que se muestra en la vista
                $orderItem->status = 1;
                $orderItem->payment_method_id = $payment_method_id;
                $orderItem->description = "Pago de servicio - Periodo <b>" . ($reading->period ?? 'N/A') . "</b>, lectura <b>" . $reading->id . "</b>";
                $orderItem->payment_status = $payment_status;
                
                \Log::info('Saving OrderItem with exact amount from view', [
                    'reading_id' => $reading->id,
                    'reading_total' => $reading->total,
                    'monto_real' => $montoReal,
                    'monto_real_type' => gettype($montoReal),
                    'type_dte' => $orderItem->type_dte,
                    'price_before_save' => $orderItem->price,
                    'total_before_save' => $orderItem->total
                ]);
                $orderItem->save();
                
                // Verificar qué se guardó realmente
                $savedItem = OrderItem::find($orderItem->id);
                \Log::info('OrderItem saved - verification', [
                    'id' => $orderItem->id,
                    'price_after_save' => $savedItem->price,
                    'total_after_save' => $savedItem->total,
                    'original_reading_total' => $reading->total
                ]);

                // Acumular el total usando el mismo monto de la vista
                $sumTotal += $montoReal;

                // Marcar la lectura como pagada
                $reading->payment_status = $payment_status;
                $reading->save();
                    // Registrar ingreso contable por cada lectura pagada
                    \App\Models\ContableIngreso::create([
                        'fecha' => now(),
                        'monto' => $montoReal,
                        'concepto' => $orderItem->description ?? 'Pago',
                        'detalle' => 'Pago registrado desde OrdersController',
                        'order_id' => $order->id,
                        'user_id' => auth()->id(),
                        'reading_id' => $reading->id,
                        'categoria' => 'ingreso',
                        'tipo' => ((string)$payment_method_id === '1' ? 'pos' : ((string)$payment_method_id === '2' ? 'efectivo' : 'otro')),
                        'grupo' => 'general', // Ajusta el valor si necesitas algo específico
                    ]);
                    // Registrar movimiento para el libro de caja tabular
                    // Determinar la cuenta destino según el método de pago
                    $cuentaDestinoId = ((string)$payment_method_id === '1') ? 6 : 5; // 1=POS→6, 2=Efectivo→5

                    // Abonar el monto a la cuenta destino correspondiente
                    $cuentaDestino = \App\Models\Cuenta::find($cuentaDestinoId);
                    if ($cuentaDestino) {
                        $cuentaDestino->saldo_actual += $montoReal;
                        $cuentaDestino->save();
                    }

                    \App\Models\Movimiento::create([
                        'org_id' => $reading->org_id,
                        'fecha' => now()->toDateString(),
                        'tipo' => 'ingreso',
                        'subtipo' => ((string)$payment_method_id === '1' ? 'pos' : ((string)$payment_method_id === '2' ? 'efectivo' : 'otro')),
                        'monto' => $montoReal,
                        'descripcion' => ((string)$payment_method_id === '1' ? 'VTA POS' : 'VTA EFECTIVO'),
                        'grupo' => 'Total Consumo',
                        'estado' => 'confirmado',
                        'categoria_id' => 1,
                        'cuenta_destino_id' => $cuentaDestinoId,
                    ]);
                
            } catch (\Exception $e) {
                \Log::error('Error saving OrderItem', [
                    'error' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile(),
                    'reading_id' => $reading->id
                ]);
                throw $e;
            }
        }

        $order->qty = $qty;
        // Usar directamente la suma de los montos reales mostrados en la vista
        $order->total = $sumTotal;
        $order->save();
        
        \Log::info('Order completed with exact amounts from view', [
            'order_id' => $order->id,
            'order_code' => $order->order_code,
            'qty' => $qty,
            'total' => $sumTotal,
            'items_created' => OrderItem::where('order_id', $order->id)->count(),
            'readings_processed' => $selectedReadings->count()
        ]);

        return redirect()->route('orgs.orders.show', ['id' => $org_id, 'order_code' => $order->order_code]);
    }


    public function show($org_id, $order_code)
    {
        $org = Org::findOrFail($org_id);
        
        // Usar la misma lógica exitosa del OrderController
        $order = Order::where('order_code', $order_code)->firstOrFail();
        $items = OrderItem::where('order_id', $order->id)->with(['service.locality', 'reading'])->get();
        
        // Debug: Log para verificar que los datos se están cargando
        \Log::info('Order Items Debug - Direct Query', [
            'order_code' => $order_code,
            'order_id' => $order->id,
            'items_count' => $items->count(),
            'items_data' => $items->map(function($item) {
                return [
                    'id' => $item->id,
                    'service_id' => $item->service_id,
                    'reading_id' => $item->reading_id,
                    'description' => $item->description,
                    'price' => $item->price,
                    'total' => $item->total,
                    'has_service' => !is_null($item->service),
                    'has_reading' => !is_null($item->reading),
                    'service_nro' => $item->service?->nro,
                    'locality_name' => $item->service?->locality?->name
                ];
            })
        ]);

        return view('orgs.orders.show', compact('org', 'order', 'items'));
    }

    // Método temporal de debug
    public function debug($org_id, $order_code)
    {
        $org = Org::findOrFail($org_id);
        $order = Order::where('order_code', $order_code)->first();
        
        if (!$order) {
            dd('Orden no encontrada');
        }
        
        $items = OrderItem::where('order_id', $order->id)->get();
        $readings = Reading::where('payment_status', 0)->take(5)->get();
        $services = Service::take(5)->get();
        
        // Verificar si hay lecturas para el miembro de esta orden
        $member = Member::where('rut', $order->dni)->first();
        
        if ($member) {
            $member_readings = Reading::where('member_id', $member->id)
                ->where('payment_status', 0)
                ->get();
            $member_services = Service::where('member_id', $member->id)->get();
        } else {
            $member_readings = collect();
            $member_services = collect();
        }
        
        dd([
            'order' => $order->toArray(),
            'items_count' => $items->count(),
            'items' => $items->toArray(),
            'member' => $member ? $member->toArray() : 'No encontrado',
            'member_readings_pending' => $member_readings->toArray(),
            'member_services' => $member_services->toArray(),
            'sample_readings_all' => $readings->toArray(),
            'sample_services_all' => $services->toArray(),
            'order_items_table_exists' => \Schema::hasTable('order_items'),
            'order_items_columns' => \Schema::hasTable('order_items') ? \Schema::getColumnListing('order_items') : []
        ]);
    }

    // Método helper para crear item genérico cuando no hay lecturas pendientes
    private function createGenericOrderItem($order_id, $service, $member, $payment_method_id, $payment_status)
    {
        $orderItem = new OrderItem;
        $orderItem->order_id = $order_id;
        $orderItem->org_id = $service->org_id;
        $orderItem->member_id = $member->id;
        $orderItem->service_id = $service->id;
        $orderItem->reading_id = null; // No hay lectura específica
        $orderItem->locality_id = $service->locality_id;
        $orderItem->folio = 'TEMP-' . time();
        $orderItem->type_dte = 'Boleta';
        $orderItem->price = '1000.00'; // Precio temporal
        $orderItem->total = '1000.00';
        $orderItem->status = 1;
        $orderItem->payment_method_id = $payment_method_id;
        $orderItem->description = "Pago de servicio nro <b>" . str_pad($service->nro ?? 0, 5, '0', STR_PAD_LEFT) . "</b> - Item temporal";
        $orderItem->payment_status = $payment_status;
        $orderItem->save();
        
        \Log::info('Generic OrderItem created', ['id' => $orderItem->id, 'service_id' => $service->id]);
    }

    private function getPaymentMethodId($paymentMethod)
    {
        if ($paymentMethod = PaymentMethod::where('title', $paymentMethod)->first()) {
            return $paymentMethod->id;
        } else {
            return 0;
        }
    }

    private function createOrderItems($order, $services)
    {
        $total = 0;
        foreach ($services as $service) {
            dd($service->total_amount, $service->price);
            // Verifica si el servicio tiene un precio válido
            $price = $service->total_amount ?? $service->price ?? 0;

            // Si el precio es 0, puedes optar por lanzar un error o hacer algo específico
            if ($price == 0) {
                return redirect()->back()->with('error', 'El servicio ' . $service->sector . ' no tiene un precio válido.');
            }

            $item = new OrderItem();
            $item->order_id = $order->id;
            $item->org_id = $order->org_id;
            $item->service_id = $service->id;
            $item->doc_id = $service->doc_id ?? 1;
            $item->description = "Pago de servicio: " . $service->sector;
            $item->price = $price;
            $item->qty = 1;
            $item->total = $price;
            $item->status = 1;
            $item->save();

            $total += $price;
        }


        $order->qty = $services->count();
        $order->total = $total;
        $order->save();
    }
}
