<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function show($order_code)
    {
        $order = Order::where('order_code', $order_code)->firstOrFail();
        
        // Cargar OrderItems con todas las relaciones necesarias
        $orderitems = OrderItem::where('order_id', $order->id)
            ->with([
                'service.locality', // Para obtener el nombre de la localidad
                'reading',           // Para obtener información del período
                'member',           // Para información del miembro
                'org'               // Para información de la organización
            ])
            ->get();
            
        $paymentStatus = $order->payment_status; // obtener el valor de payment_status de la orden
        $commission = $order->commission;
        
        \Log::info('OrderSummary Debug - Datos enviados a la vista', [
            'order_code' => $order_code,
            'order_id' => $order->id,
            'orderitems_count' => $orderitems->count(),
            'orderitems_data' => $orderitems->map(function($item) {
                return [
                    'id' => $item->id,
                    'service_id' => $item->service_id,
                    'reading_id' => $item->reading_id,
                    'type_dte' => $item->type_dte,
                    'price' => $item->price,
                    'total' => $item->total,
                    'description' => $item->description,
                    'has_service' => !is_null($item->service),
                    'has_reading' => !is_null($item->reading),
                    'service_nro' => $item->service?->nro,
                    'service_sector' => $item->service?->sector,
                    'locality_name' => $item->service?->locality?->name,
                    'reading_period' => $item->reading?->period
                ];
            })
        ]);
        
        return view('OrderSummary', compact('order', 'paymentStatus','orderitems','commission'));
        //return view('OrderSummary', compact('order', 'orderTickets','paymentStatus'));
    }
    
    
       
    
}