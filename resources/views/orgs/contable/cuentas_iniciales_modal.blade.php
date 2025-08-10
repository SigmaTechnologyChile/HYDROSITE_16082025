@extends('layouts.nice', ['active' => 'cuentas_iniciales'])

@section('title', 'Configuración de Cuentas Iniciales')

{{-- Incluir estilos del módulo contable --}}
@include('orgs.contable.partials.contable-styles')

@section('content')
<div class="contable-container">
    <!-- Breadcrumbs -->
    <nav class="contable-breadcrumb">
        <a href="{{ route('orgs.dashboard', ['id' => $orgId]) }}" class="contable-breadcrumb-item">
            <i class="bi bi-house-door"></i> Inicio
        </a>
        <span class="contable-breadcrumb-separator">
            <i class="bi bi-chevron-right"></i>
        </span>
        <a href="#" class="contable-breadcrumb-item">Contable</a>
        <span class="contable-breadcrumb-separator">
            <i class="bi bi-chevron-right"></i>
        </span>
        <span class="contable-breadcrumb-item active">Configuración de Cuentas Iniciales</span>
    </nav>

    <!-- Header Principal -->
    <div class="contable-header">
        <div>
            <h1 class="contable-title">
                <i class="bi bi-bank"></i>
                Configuración de Cuentas Iniciales
            </h1>
            <p class="contable-subtitle">Configure los saldos iniciales y datos bancarios de su organización</p>
        </div>
    </div>

    <!-- Mensajes de éxito/error -->
    @if(session('success'))
        <div class="contable-alert contable-alert-success">
            <i class="bi bi-check-circle"></i>
            <div>
                <strong>¡Éxito!</strong> {{ session('success') }}
                @if(session('cuentas_procesadas'))
                    <br><small>Total de cuentas procesadas: {{ session('cuentas_procesadas') }}</small>
                @endif
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="contable-alert contable-alert-danger">
            <i class="bi bi-exclamation-triangle"></i>
            <div>
                <strong>Error:</strong> {{ session('error') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="contable-alert contable-alert-danger">
            <i class="bi bi-exclamation-triangle"></i>
            <div>
                <strong>Errores de validación:</strong>
                <ul class="mt-2 mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Formulario Principal -->
    <form method="POST" action="{{ route('cuentas_iniciales.store', $orgId) }}" class="contable-form">
        @csrf
        <input type="hidden" name="orgId" value="{{ $orgId }}">
        
        <!-- Card 1: Configuración Principal -->
        <div class="contable-card mb-4">
            <div class="contable-card-header">
                <h2 class="contable-card-title">
                    <i class="bi bi-gear-fill"></i>
                    Configuración de Cuentas Iniciales
                </h2>
            </div>
        </div>

        <!-- Card 2: Saldo Inicial Caja -->
        <div class="contable-card mb-4">
            <div class="contable-card-header">
                <h3 class="contable-card-title">
                    <i class="bi bi-cash-stack"></i>
                    Saldo Inicial Caja
                </h3>
            </div>
            <div class="contable-card-body">
                <div class="contable-form-group">
                    <label class="contable-form-label">Monto Inicial</label>
                    <input type="number" class="contable-form-input" placeholder="0" step="1" min="0" name="saldo_caja" required>
                </div>
            </div>
        </div>        <!-- Card 3: Cuenta Corriente 1 -->
        <div class="contable-card mb-4">
            <div class="contable-card-header">
                <h3 class="contable-card-title">
                    <i class="bi bi-bank"></i>
                    Cuenta Corriente 1
                </h3>
            </div>
            <div class="contable-card-body">
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="number" class="form-control contable-form-input" placeholder="Saldo inicial" step="1" min="0" name="saldo_cc1">
                    </div>
                    <div class="col-md-4">
                        <select class="form-control contable-form-input" name="banco_cc1">
                            <option value="">Seleccionar Banco</option>
                            @foreach($bancos as $banco)
                                <option value="{{ $banco->id }}">{{ $banco->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control contable-form-input" placeholder="Número de cuenta" name="numero_cc1">
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Cuenta Corriente 2 -->
        <div class="contable-card mb-4">
            <div class="contable-card-header">
                <h3 class="contable-card-title">
                    <i class="bi bi-bank"></i>
                    Cuenta Corriente 2
                </h3>
            </div>
            <div class="contable-card-body">
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="number" class="form-control contable-form-input" placeholder="Saldo inicial" step="1" min="0" name="saldo_cc2">
                    </div>
                    <div class="col-md-4">
                        <select class="form-control contable-form-input" name="banco_cc2">
                            <option value="">Seleccionar Banco</option>
                            @foreach($bancos as $banco)
                                <option value="{{ $banco->id }}">{{ $banco->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control contable-form-input" placeholder="Número de cuenta" name="numero_cc2">
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 5: Cuenta de Ahorro -->
        <div class="contable-card mb-4">
            <div class="contable-card-header">
                <h3 class="contable-card-title">
                    <i class="bi bi-piggy-bank"></i>
                    Cuenta de Ahorro
                </h3>
            </div>
            <div class="contable-card-body">
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="number" class="form-control contable-form-input" placeholder="Saldo inicial" step="1" min="0" name="saldo_ahorro">
                    </div>
                    <div class="col-md-4">
                        <select class="form-control contable-form-input" name="banco_ahorro">
                            <option value="">Seleccionar Banco</option>
                            @foreach($bancos as $banco)
                                <option value="{{ $banco->id }}">{{ $banco->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control contable-form-input" placeholder="Número de cuenta" name="numero_ahorro">
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 6: Configuración General -->
        <div class="contable-card mb-4">
            <div class="contable-card-header">
                <h3 class="contable-card-title">
                    <i class="bi bi-person-gear"></i>
                    Configuración General
                </h3>
            </div>
            <div class="contable-card-body">
                <div class="contable-form-group">
                    <label class="contable-form-label">Responsable</label>
                    <input type="text" class="contable-form-input" placeholder="Nombre del responsable" name="responsable">
                </div>

                <div class="contable-form-actions">
                    <button type="submit" class="contable-btn contable-btn-primary">
                        <i class="bi bi-check-circle"></i>
                        Guardar Configuración
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection