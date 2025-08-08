@extends('layouts.nice', ['active' => 'orgs.orders.show', 'title' => 'Detalles de la Orden'])

@push('styles')
<style>
    .service-detail-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    
    .service-detail-card:hover {
        border-left-color: #0d6efd;
        background-color: #f8f9fa;
    }
    
    .badge-document {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    .total-amount {
        font-size: 1.25rem;
        font-weight: 700;
    }
    
    .service-info {
        font-size: 0.875rem;
        line-height: 1.4;
    }
    
    .detail-icon {
        color: #6c757d;
        width: 16px;
        text-align: center;
    }
</style>
@endpush

@section('content')
    <div class="pagetitle">
      <h1>{{$org->name}}</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.dashboard',$org->id)}}">{{$org->name}}</a></li>
          <li class="breadcrumb-item active">Orden de Pago</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <!-- Order Summary Section -->
    <section id="inscription" class="py-4">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row g-4">

                <!-- Order Summary Card -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 rounded-3 h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-receipt-cutoff me-2"></i>
                                Orden de Pago
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-muted mb-0">Código de Orden</h6>
                                <span class="badge bg-primary fs-6">{{ $order->order_code }}</span>
                            </div>
                            
                            @if($order->payment_status == 1)
                                <div class="alert alert-success d-flex align-items-center" role="alert">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    <strong>¡Pago confirmado!</strong> La orden ha sido procesada exitosamente.
                                </div>
                            @endif

                            <h6 class="mb-3 text-primary">
                                <i class="bi bi-list-ul me-2"></i>
                                Detalles de Servicios
                            </h6>

                            @if($items && $items->count() > 0)
                                @foreach($items as $item)
                                    <div class="service-detail-card p-3 mb-3 border rounded-2">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="mb-0 fw-semibold text-primary">
                                                    <i class="bi bi-receipt detail-icon me-1"></i>
                                                    {{ $item->type_dte ?? 'Documento' }}
                                                    @if($item->folio)
                                                        #{{ $item->folio }}
                                                    @endif
                                                </h6>
                                                <span class="badge badge-document bg-{{ $item->type_dte == 'Factura' ? 'warning' : 'info' }}">
                                                    {{ $item->type_dte ?? 'Boleta' }}
                                                </span>
                                            </div>
                                            <div class="text-end">
                                                <div class="total-amount text-primary">@money($item->total)</div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <p class="mb-1 text-muted service-info">
                                                <i class="bi bi-geo-alt detail-icon me-1"></i>
                                                <strong>Servicio:</strong> 
                                                @if($item->service)
                                                    Nro. {{ str_pad($item->service->nro ?? 0, 5, '0', STR_PAD_LEFT) }}
                                                    @if($item->service->locality)
                                                        - {{ $item->service->locality->name }}
                                                    @endif
                                                @else
                                                    Servicio ID: {{ $item->service_id }}
                                                @endif
                                            </p>
                                            
                                            @if($item->reading)
                                                <p class="mb-1 text-muted service-info">
                                                    <i class="bi bi-calendar3 detail-icon me-1"></i>
                                                    <strong>Período:</strong> {{ $item->reading->period ?? 'N/A' }}
                                                </p>
                                                <p class="mb-1 text-muted service-info">
                                                    <i class="bi bi-speedometer2 detail-icon me-1"></i>
                                                    <strong>Lectura ID:</strong> {{ $item->reading->id }}
                                                </p>
                                            @endif
                                        </div>
                                        
                                        <div class="text-muted small">
                                            {!! $item->description !!}
                                        </div>
                                        
                                        <div class="mt-2">
                                            <span class="text-muted small">
                                                @if($item->type_dte == 'Factura')
                                                    Subtotal: @money($item->price)
                                                    <small class="text-warning">(+ 19% IVA = @money($item->total - $item->price))</small>
                                                @else
                                                    Subtotal: @money($item->price)
                                                    <small class="text-info">(Exento de IVA)</small>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-warning" role="alert">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    No se encontraron servicios asociados a esta orden.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Payment Summary Card -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 rounded-3 h-100">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-calculator me-2"></i>
                                Resumen de Pago
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $boletas_count = $items->where('type_dte', 'Boleta')->count();
                                $facturas_count = $items->where('type_dte', 'Factura')->count();
                                
                                $subtotal_boletas = $items->where('type_dte', 'Boleta')->sum('price');
                                $subtotal_facturas = $items->where('type_dte', 'Factura')->sum('price');
                                $subtotal_total = $subtotal_boletas + $subtotal_facturas;
                                
                                $iva_total = $items->where('type_dte', 'Factura')->sum(function($item) {
                                    return $item->total - $item->price;
                                });
                            @endphp

                            <ul class="list-group list-group-flush">
                                @if($boletas_count > 0)
                                    <li class="list-group-item px-0 d-flex justify-content-between py-2 bg-light bg-opacity-30">
                                        <div>
                                            <span class="text-muted small">
                                                <i class="bi bi-receipt me-1"></i>
                                                Boletas ({{ $boletas_count }})
                                            </span>
                                        </div>
                                        <span class="text-muted small">@money($subtotal_boletas) (Exento IVA)</span>
                                    </li>
                                @endif

                                @if($facturas_count > 0)
                                    <li class="list-group-item px-0 d-flex justify-content-between py-2 bg-light bg-opacity-30">
                                        <div>
                                            <span class="text-muted small">
                                                <i class="bi bi-receipt-cutoff me-1"></i>
                                                Facturas ({{ $facturas_count }})
                                            </span>
                                        </div>
                                        <span class="text-muted small">@money($subtotal_facturas) + IVA</span>
                                    </li>
                                @endif

                                @if($facturas_count > 0)
                                    <li class="list-group-item px-0 d-flex justify-content-between py-2">
                                        <div>
                                            <span class="text-muted">Subtotal sin IVA</span>
                                        </div>
                                        <span class="text-muted">@money($subtotal_total)</span>
                                    </li>
                                    <li class="list-group-item px-0 d-flex justify-content-between py-2">
                                        <div>
                                            <span class="text-muted">IVA (19%)</span>
                                        </div>
                                        <span class="text-muted">@money($iva_total)</span>
                                    </li>
                                @endif

                                <li class="list-group-item px-0 d-flex justify-content-between py-3 bg-light bg-opacity-50 rounded my-2">
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Total de Servicios</h6>
                                        <p class="text-muted mb-0 small">{{ $order->qty }} servicio(s)</p>
                                    </div>
                                    <span class="text-primary fw-bold">@money($order->total)</span>
                                </li>

                                <li class="list-group-item px-0 d-flex justify-content-between py-3 border-top">
                                    <h5 class="fw-bold mb-0">Total</h5>
                                    <h5 class="fw-bold mb-0 text-primary">@money($order->total)</h5>
                                </li>
                            </ul>

                            @if($order->payment_method_id == 1)
                                <a href="{{ route('orgs.voucher.create', [$org->id, $order->order_code]) }}" class="btn btn-primary btn-lg w-100 fw-semibold mb-3" target="_blank">
                                    <i class="bi bi-credit-card me-2"></i>Generar Voucher POS
                                </a>
                            @elseif($order->payment_method_id == 2)
                                <a href="{{ route('orgs.voucher.create', [$org->id, $order->order_code]) }}" class="btn btn-primary btn-lg w-100 fw-semibold mb-3" target="_blank">
                                    <i class="bi bi-receipt me-2"></i>Generar Voucher Efectivo
                                </a>
                            @elseif($order->payment_method_id == 3)
                                <a href="{{ route('orgs.voucher.create', [$org->id, $order->order_code]) }}" class="btn btn-primary btn-lg w-100 fw-semibold mb-3" target="_blank">
                                    <i class="bi bi-receipt me-2"></i>Generar Voucher Transferencia
                                </a>
                            @endif
                            
                            <!-- Botón para volver a la lista de pagos -->
                            <div class="d-grid gap-2 mt-3">
                                <button onclick="returnToPayments()" class="btn btn-outline-success btn-lg">
                                    <i class="bi bi-arrow-left me-2"></i>Volver a Lista de Pagos
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Information Card -->
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-person-circle me-2"></i>
                                Información del Cliente
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="firstName" class="form-label small text-muted">Nombre completo</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control bg-light" id="firstName" value="{{ $order->name }}" disabled readonly>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="dni" class="form-label small text-muted">RUT/DNI</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-card-text"></i></span>
                                        <input type="text" class="form-control bg-light" id="dni" value="{{ $order->dni }}" disabled readonly>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label small text-muted">Teléfono</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-phone"></i></span>
                                        <input type="text" class="form-control bg-light" id="phone" value="{{ $order->phone }}" disabled readonly>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label small text-muted">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control bg-light" id="email" value="{{ $order->email }}" disabled readonly>
                                    </div>
                                </div>

                                <div class="col-12 mt-4">
                                    <div class="alert alert-info d-flex align-items-center" role="alert">
                                        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                                        <div>
                                            Los datos se utilizarán para generar la orden de pago.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        function copy_date() {
            document.getElementById('ticket_firstName[]').value = document.getElementById('firstName').value;
            document.getElementById('ticket_lastName[]').value = document.getElementById('lastName').value;
            document.getElementById('ticket_email[]').value = document.getElementById('email').value;
        }

        // Opcional: Animación para destacar el total
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const totalElement = document.querySelector('.list-group-item:last-child .text-primary');
                if (totalElement) {
                    totalElement.classList.add('animate__animated', 'animate__pulse');
                }
            }, 500);

            // Marcar que el pago se completó exitosamente
            if (sessionStorage.getItem('payment_in_progress') === 'true') {
                sessionStorage.setItem('payment_completed', 'true');
                sessionStorage.removeItem('payment_in_progress');
            }
        });

        // Función para el botón "Volver a Lista de Pagos"
        function returnToPayments() {
            // Marcar que el pago se completó antes de navegar
            sessionStorage.setItem('payment_completed', 'true');
            window.location.href = "{{ route('orgs.payments.index', $org->id) }}";
        }
    </script>
@endsection