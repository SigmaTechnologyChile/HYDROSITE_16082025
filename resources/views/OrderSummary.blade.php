@extends('layouts.app',['active'=>'orders.summary','title'=>'Resumen de la pago'])

@section('content')
    <!-- Page Title -->
    <div class="page-title light-background">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Resumen de la pago</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li class="current">Cuenta</li>
            <li class="current">Resumen de la pago</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->
    <section id="inscription" class="contact section transparent-background">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="card">
                <div class="card-header">
                    <h4>Detalle de Pago de servicio <span class="badge bg-secondary float-end">Orden de Pago: {{ $order->order_code }} </span></h4>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-sm-4">
                          <label for="firstName" class="form-label">RUT/DNI</label>
                          <div class="form-control">{{$order->dni}}</div>
                        </div>

                        <div class="col-sm-8">
                          <label for="firstName" class="form-label">Nombres</label>
                          <div class="form-control">{{$order->name}}</div>
                        </div>

                        <div class="col-8">
                          <label for="email" class="form-label">Email <span class="text-muted"></span></label>
                          <div class="form-control">{{$order->email}}</div>
                        </div>

                        <div class="col-4">
                          <label for="email" class="form-label">Teléfono <span class="text-muted"></span></label>
                          <div class="form-control">{{$order->phone}}</div>
                        </div>
                    </div>
                    <div class="col-12 m-2">
                        <h5>Servicios a Pagar</h5>
                        
                        @if($orderitems && $orderitems->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>N° Servicio</th>
                                        <th>Sector/Localidad</th>
                                        <th>Período</th>
                                        <th>Tipo Documento</th>
                                        <th class="text-end">Subtotal</th>
                                        <th class="text-end">IVA</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $subtotalBoletas = 0;
                                        $subtotalFacturas = 0;
                                        $totalIva = 0;
                                        $granTotal = 0;
                                    @endphp
                                    @foreach($orderitems as $item)
                                        @php
                                            $isFactura = ($item->type_dte == 'Factura');
                                            $subtotal = floatval($item->price ?? 0);
                                            $total = floatval($item->total ?? 0);
                                            $iva = $isFactura ? ($total - $subtotal) : 0;
                                            
                                            if ($isFactura) {
                                                $subtotalFacturas += $subtotal;
                                                $totalIva += $iva;
                                            } else {
                                                $subtotalBoletas += $total;
                                            }
                                            $granTotal += $total;
                                        @endphp
                                        <tr>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ str_pad($item->service?->nro ?? 'N/A', 5, '0', STR_PAD_LEFT) }}
                                                </span>
                                            </td>
                                            <td>{{ $item->service?->locality?->name ?? $item->service?->sector ?? 'N/A' }}</td>
                                            <td>
                                                <small class="text-muted">{{ $item->reading?->period ?? 'N/A' }}</small>
                                            </td>
                                            <td>
                                                <span class="badge {{ $isFactura ? 'bg-warning' : 'bg-success' }}">
                                                    {{ $item->type_dte ?? 'Boleta' }}
                                                </span>
                                            </td>
                                            <td class="text-end">@money($subtotal)</td>
                                            <td class="text-end">
                                                @if($isFactura)
                                                    @money($iva)
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-end"><strong>@money($total)</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-secondary">
                                    @if($subtotalBoletas > 0)
                                    <tr>
                                        <td colspan="6" class="text-end"><strong>Subtotal Boletas (sin IVA):</strong></td>
                                        <td class="text-end"><strong>@money($subtotalBoletas)</strong></td>
                                    </tr>
                                    @endif
                                    @if($subtotalFacturas > 0)
                                    <tr>
                                        <td colspan="6" class="text-end"><strong>Subtotal Facturas (sin IVA):</strong></td>
                                        <td class="text-end"><strong>@money($subtotalFacturas)</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-end"><strong>IVA (19%):</strong></td>
                                        <td class="text-end"><strong>@money($totalIva)</strong></td>
                                    </tr>
                                    @endif
                                    <tr class="table-dark">
                                        <td colspan="6" class="text-end"><strong>TOTAL A PAGAR:</strong></td>
                                        <td class="text-end"><strong>@money($granTotal)</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-warning" role="alert">
                            <h6><i class="bi bi-exclamation-triangle"></i> No se encontraron servicios en esta orden</h6>
                            <p class="mb-0">La orden <code>{{ $order->order_code }}</code> no contiene elementos para mostrar. Esto puede deberse a un problema en el procesamiento de la orden.</p>
                        </div>
                        
                        <!-- Debug info para desarrolladores -->
                        @if(config('app.debug'))
                        <div class="card mt-3">
                            <div class="card-header bg-secondary text-white">
                                <small>Debug Info (solo en desarrollo)</small>
                            </div>
                            <div class="card-body">
                                <small>
                                    <strong>Order ID:</strong> {{ $order->id }}<br>
                                    <strong>Order Code:</strong> {{ $order->order_code }}<br>
                                    <strong>Order Items Count:</strong> {{ $orderitems ? $orderitems->count() : 'null' }}<br>
                                    <strong>Order Total:</strong> @money($order->total)
                                </small>
                            </div>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1">
                                <strong>Estado:</strong> 
                                {!!($order->payment_status==1? '<span class="badge bg-success">Pagado</span>' : ($order->payment_status==2? '<span class="badge bg-danger">Rechazado</span>' : '<span class="badge bg-warning text-dark">Pendiente de Pago</span>'))!!}
                            </p>
                            @if($order->commission > 0)
                            <p class="mb-1">
                                <strong>Comisión:</strong> @money($order->commission)
                            </p>
                            @endif
                        </div>
                        <div class="col-md-6 text-end">
                            <p class="mb-1"><strong>Cantidad de Items:</strong> {{ $orderitems->count() }}</p>
                            <h4 class="text-primary mb-0">
                                <strong>Total Orden: @money($order->total)</strong>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </section>
@endsection
