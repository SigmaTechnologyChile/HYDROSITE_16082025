@extends('layouts.nice')

{{-- Incluir estilos modernos del módulo contable --}}
@include('orgs.contable.partials.contable-styles')

@section('content')
<div class="contable-container">
    <!-- Sección principal del módulo contable -->
    <div class="contable-section">
        <div class="contable-header">
            <div>
                <h1 class="contable-title">
                    <i class="bi bi-calculator"></i>
                    Módulo Contable
                </h1>
                <p class="contable-subtitle">Sistema de gestión contable y financiera</p>
            </div>
        </div>

        <div class="contable-body">
            <!-- Card principal modernizada -->
            <div class="contable-card">
                <div class="contable-card-header">
                    <h3 class="contable-card-title">
                        <i class="bi bi-gear"></i>
                        Configuración Principal
                    </h3>
                </div>
                <div class="contable-card-body">
                    <div class="contable-text-center contable-py-xl">
                        <div style="margin-bottom: var(--spacing-xl);">
                            <div style="color: var(--primary-color); font-size: 4rem; margin-bottom: var(--spacing-lg);">
                                <i class="bi bi-journal-plus"></i>
                            </div>
                            <h3 style="margin-bottom: var(--spacing-md);">Configuración de Cuentas Iniciales</h3>
                            <p class="contable-text-secondary contable-mb-xl">
                                Configure las cuentas iniciales del sistema contable para comenzar a operar
                            </p>
                        </div>
                        
                        <a href="{{ route('cuentas_iniciales.show') }}" class="contable-btn contable-btn-primary contable-btn-lg">
                            <i class="bi bi-journal-plus"></i> 
                            Ir a Configuración de Cuentas
                        </a>
                    </div>
                </div>
            </div>

            <!-- Enlaces rápidos -->
            <div class="contable-mt-xl">
                <h3 class="contable-text-lg contable-text-medium contable-mb-lg">Accesos Rápidos</h3>
                <div class="contable-form-grid contable-form-grid-3">
                    <div class="contable-card contable-hover-card">
                        <div class="contable-card-body contable-text-center">
                            <div style="color: var(--success-color); font-size: 2.5rem; margin-bottom: var(--spacing-md);">
                                <i class="bi bi-arrow-up-circle"></i>
                            </div>
                            <h4 class="contable-card-title">Movimientos</h4>
                            <p class="contable-text-secondary contable-text-sm">Gestionar ingresos y egresos</p>
                        </div>
                    </div>
                    
                    <div class="contable-card contable-hover-card">
                        <div class="contable-card-body contable-text-center">
                            <div style="color: var(--info-color); font-size: 2.5rem; margin-bottom: var(--spacing-md);">
                                <i class="bi bi-journal-text"></i>
                            </div>
                            <h4 class="contable-card-title">Libro Caja</h4>
                            <p class="contable-text-secondary contable-text-sm">Consultar libro de caja tabular</p>
                        </div>
                    </div>
                    
                    <div class="contable-card contable-hover-card">
                        <div class="contable-card-body contable-text-center">
                            <div style="color: var(--warning-color); font-size: 2.5rem; margin-bottom: var(--spacing-md);">
                                <i class="bi bi-bank"></i>
                            </div>
                            <h4 class="contable-card-title">Conciliación</h4>
                            <p class="contable-text-secondary contable-text-sm">Conciliación bancaria</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Incluir el modal de cuentas iniciales --}}
@include('orgs.contable.cuentas_iniciales_modal')
@endsection
