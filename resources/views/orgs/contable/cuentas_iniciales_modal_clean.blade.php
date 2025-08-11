@extends('layouts.nice', ['active' => 'cuentas_iniciales'])

@section('title', 'Configuración de Cuentas Iniciales')

{{-- Incluir estilos modernos del módulo contable --}}
@include('orgs.contable.partials.contable-styles')

@section('content')

<div class="contable-container">
    <!-- Breadcrumbs modernos -->
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

    <!-- Sección principal -->
    <div class="contable-section">
        <div class="contable-header">
            <div>
                <h1 class="contable-title">
                    <i class="bi bi-gear-fill"></i>
                    Configuración de Cuentas Iniciales
                </h1>
                <p class="contable-subtitle">Configure los saldos iniciales y datos bancarios de su organización</p>
            </div>
        </div>

        <div class="contable-body">
            {{-- Verificar si ya existen configuraciones --}}
            @if(isset($configuraciones) && $configuraciones->count() > 0)
                
                {{-- MOSTRAR CONFIGURACIONES EXISTENTES --}}
                <div class="contable-alert contable-alert-success contable-mb-xl">
                    <i class="bi bi-check-circle"></i>
                    <div>
                        <p><strong>Configuración Existente:</strong> Ya tienes cuentas configuradas. Puedes editarlas a continuación.</p>
                    </div>
                </div>

                {{-- Resumen de saldos actual --}}
                @include('orgs.contable.partials.resumen-saldos')

                {{-- Tabla de configuraciones existentes --}}
                <div class="contable-card contable-mb-xl">
                    <div class="contable-card-header">
                        <h3 class="contable-card-title">
                            <i class="bi bi-table"></i>
                            Cuentas Configuradas
                        </h3>
                    </div>
                    <div class="contable-card-body">
                        <div class="contable-table-responsive">
                            <table class="contable-table contable-table-striped">
                                <thead>
                                    <tr>
                                        <th>Tipo de Cuenta</th>
                                        <th>Saldo Inicial</th>
                                        <th>Banco</th>
                                        <th>N° Cuenta</th>
                                        <th>Responsable</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($configuraciones as $config)
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center;">
                                                @switch($config->tipo_cuenta)
                                                    @case('caja_general')
                                                        <i class="bi bi-cash-stack" style="color: var(--success-color); margin-right: 8px;"></i>
                                                        Caja General
                                                        @break
                                                    @case('cuenta_corriente_1')
                                                        <i class="bi bi-credit-card" style="color: var(--primary-color); margin-right: 8px;"></i>
                                                        Cuenta Corriente 1
                                                        @break
                                                    @case('cuenta_corriente_2')
                                                        <i class="bi bi-credit-card-2-front" style="color: var(--info-color); margin-right: 8px;"></i>
                                                        Cuenta Corriente 2
                                                        @break
                                                    @case('cuenta_ahorro')
                                                        <i class="bi bi-piggy-bank-fill" style="color: var(--warning-color); margin-right: 8px;"></i>
                                                        Cuenta de Ahorro
                                                        @break
                                                    @default
                                                        <i class="bi bi-bank" style="color: var(--text-muted); margin-right: 8px;"></i>
                                                        {{ ucfirst(str_replace('_', ' ', $config->tipo_cuenta)) }}
                                                @endswitch
                                            </div>
                                        </td>
                                        <td>
                                            <span class="contable-badge contable-badge-primary">
                                                ${{ number_format($config->saldo_inicial, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($config->nombre_banco)
                                                <div style="display: flex; align-items: center;">
                                                    <i class="bi bi-bank2" style="color: var(--text-muted); margin-right: 4px;"></i>
                                                    {{ $config->nombre_banco }}
                                                </div>
                                            @else
                                                <span style="color: var(--text-muted);">Sin banco</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($config->numero_cuenta)
                                                <code style="color: var(--text-primary);">{{ $config->numero_cuenta }}</code>
                                            @else
                                                <span style="color: var(--text-muted);">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div style="display: flex; align-items: center;">
                                                <i class="bi bi-person-fill" style="color: var(--text-secondary); margin-right: 4px;"></i>
                                                {{ $config->responsable }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($config->copiado_a_cuentas)
                                                <span class="contable-badge contable-badge-success">
                                                    <i class="bi bi-check-circle"></i>
                                                    Activo
                                                </span>
                                            @else
                                                <span class="contable-badge contable-badge-warning">
                                                    <i class="bi bi-clock"></i>
                                                    Pendiente
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div style="display: flex; gap: 8px;">
                                                <button class="contable-btn contable-btn-sm contable-btn-outline" 
                                                        onclick="alert('Función de edición en desarrollo')">
                                                    <i class="bi bi-pencil"></i>
                                                    Editar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            @else
                
                {{-- FORMULARIO INICIAL CUANDO NO HAY CONFIGURACIONES --}}
                <div class="contable-alert contable-alert-info contable-mb-xl">
                    <i class="bi bi-info-circle"></i>
                    <div>
                        <p><strong>Primera Configuración:</strong> Configure los saldos iniciales de sus cuentas. Esta información es fundamental para el correcto funcionamiento del sistema contable.</p>
                    </div>
                </div>

                <form action="{{ route('contable.store', ['id' => $orgId]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="orgId" value="{{ $orgId }}">

                    {{-- Caja General --}}
                    <div class="contable-card contable-mb-xl">
                        <div class="contable-card-header">
                            <h3 class="contable-card-title">
                                <i class="bi bi-cash-stack"></i>
                                Caja General (Efectivo)
                            </h3>
                        </div>
                        <div class="contable-card-body">
                            <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
                                <div class="contable-form-group">
                                    <label class="contable-form-label">
                                        <i class="bi bi-currency-dollar"></i>
                                        Saldo Inicial
                                        <span style="color: var(--danger-color);">*</span>
                                    </label>
                                    <input type="number" 
                                           name="saldo_caja_general" 
                                           class="contable-form-input"
                                           step="1" 
                                           min="0" 
                                           placeholder="Ingrese el saldo inicial en efectivo"
                                           required>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Cuenta Corriente 1 --}}
                    <div class="contable-card contable-mb-xl">
                        <div class="contable-card-header">
                            <h3 class="contable-card-title">
                                <i class="bi bi-credit-card"></i>
                                Cuenta Corriente 1
                            </h3>
                        </div>
                        <div class="contable-card-body">
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                                <div class="contable-form-group">
                                    <label class="contable-form-label">
                                        <i class="bi bi-currency-dollar"></i>
                                        Saldo Inicial
                                        <span style="color: var(--danger-color);">*</span>
                                    </label>
                                    <input type="number" 
                                           name="saldo_cta_corriente_1" 
                                           class="contable-form-input"
                                           step="1" 
                                           min="0" 
                                           placeholder="Ingrese el saldo inicial"
                                           required>
                                </div>
                                <div class="contable-form-group">
                                    <label class="contable-form-label">
                                        <i class="bi bi-bank"></i>
                                        Banco
                                    </label>
                                    <select name="banco_cta_corriente_1" class="contable-form-input">
                                        <option value="">Seleccionar banco</option>
                                        @if(isset($bancos))
                                            @foreach($bancos as $banco)
                                                <option value="{{ $banco->id }}">{{ $banco->nombre }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="contable-form-group">
                                    <label class="contable-form-label">
                                        <i class="bi bi-credit-card"></i>
                                        Número de Cuenta
                                    </label>
                                    <input type="text" 
                                           name="numero_cta_corriente_1" 
                                           class="contable-form-input"
                                           placeholder="Ej: 12345678-9">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Cuenta Corriente 2 --}}
                    <div class="contable-card contable-mb-xl">
                        <div class="contable-card-header">
                            <h3 class="contable-card-title">
                                <i class="bi bi-credit-card-2-front"></i>
                                Cuenta Corriente 2
                            </h3>
                        </div>
                        <div class="contable-card-body">
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                                <div class="contable-form-group">
                                    <label class="contable-form-label">
                                        <i class="bi bi-currency-dollar"></i>
                                        Saldo Inicial
                                        <span style="color: var(--danger-color);">*</span>
                                    </label>
                                    <input type="number" 
                                           name="saldo_cta_corriente_2" 
                                           class="contable-form-input"
                                           step="1" 
                                           min="0" 
                                           placeholder="Ingrese el saldo inicial"
                                           required>
                                </div>
                                <div class="contable-form-group">
                                    <label class="contable-form-label">
                                        <i class="bi bi-bank"></i>
                                        Banco
                                    </label>
                                    <select name="banco_cta_corriente_2" class="contable-form-input">
                                        <option value="">Seleccionar banco</option>
                                        @if(isset($bancos))
                                            @foreach($bancos as $banco)
                                                <option value="{{ $banco->id }}">{{ $banco->nombre }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="contable-form-group">
                                    <label class="contable-form-label">
                                        <i class="bi bi-credit-card"></i>
                                        Número de Cuenta
                                    </label>
                                    <input type="text" 
                                           name="numero_cta_corriente_2" 
                                           class="contable-form-input"
                                           placeholder="Ej: 12345678-9">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Cuenta de Ahorro --}}
                    <div class="contable-card contable-mb-xl">
                        <div class="contable-card-header">
                            <h3 class="contable-card-title">
                                <i class="bi bi-piggy-bank-fill"></i>
                                Cuenta de Ahorro
                            </h3>
                        </div>
                        <div class="contable-card-body">
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                                <div class="contable-form-group">
                                    <label class="contable-form-label">
                                        <i class="bi bi-currency-dollar"></i>
                                        Saldo Inicial
                                        <span style="color: var(--danger-color);">*</span>
                                    </label>
                                    <input type="number" 
                                           name="saldo_cuenta_ahorro" 
                                           class="contable-form-input"
                                           step="1" 
                                           min="0" 
                                           placeholder="Ingrese el saldo inicial"
                                           required>
                                </div>
                                <div class="contable-form-group">
                                    <label class="contable-form-label">
                                        <i class="bi bi-bank"></i>
                                        Banco
                                    </label>
                                    <select name="banco_cuenta_ahorro" class="contable-form-input">
                                        <option value="">Seleccionar banco</option>
                                        @if(isset($bancos))
                                            @foreach($bancos as $banco)
                                                <option value="{{ $banco->id }}">{{ $banco->nombre }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="contable-form-group">
                                    <label class="contable-form-label">
                                        <i class="bi bi-credit-card"></i>
                                        Número de Cuenta
                                    </label>
                                    <input type="text" 
                                           name="numero_cuenta_ahorro" 
                                           class="contable-form-input"
                                           placeholder="Ej: 12345678-9">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Responsable --}}
                    <div class="contable-card contable-mb-xl">
                        <div class="contable-card-header">
                            <h3 class="contable-card-title">
                                <i class="bi bi-person-check-fill"></i>
                                Información del Responsable
                            </h3>
                        </div>
                        <div class="contable-card-body">
                            <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
                                <div class="contable-form-group">
                                    <label class="contable-form-label">
                                        <i class="bi bi-person"></i>
                                        Nombre del Responsable
                                        <span style="color: var(--danger-color);">*</span>
                                    </label>
                                    <input type="text" 
                                           name="responsable" 
                                           class="contable-form-input"
                                           placeholder="Ingrese el nombre completo del responsable"
                                           required>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Botón de envío --}}
                    <div class="contable-actions">
                        <button type="submit" class="contable-btn contable-btn-primary contable-btn-lg">
                            <i class="bi bi-check-circle"></i>
                            Guardar Configuración
                        </button>
                    </div>
                </form>

            @endif
        </div>
    </div>
</div>

@endsection
