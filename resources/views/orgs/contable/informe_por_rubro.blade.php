@extends('layouts.nice', ['active' => 'informe-por-rubro', 'title' => 'Informe por Rubro'])

{{-- Incluir estilos modernos del módulo contable --}}
@include('orgs.contable.partials.contable-styles')

@section('content')
<div class="contable-container">
    <!-- Breadcrumbs modernos -->
    <nav class="contable-breadcrumb">
        <a href="{{ route('orgs.dashboard', ['id' => auth()->user()->org_id ?? 864]) }}" class="contable-breadcrumb-item">
            <i class="bi bi-house-door"></i> Inicio
        </a>
        <span class="contable-breadcrumb-separator">
            <i class="bi bi-chevron-right"></i>
        </span>
        <a href="#" class="contable-breadcrumb-item">Contable</a>
        <span class="contable-breadcrumb-separator">
            <i class="bi bi-chevron-right"></i>
        </span>
        <span class="contable-breadcrumb-item active">Informe por Rubro</span>
    </nav>

    <!-- Sección principal -->
    <div class="contable-section">
        <div class="contable-header">
            <div>
                <h1 class="contable-title">
                    <i class="bi bi-pie-chart"></i>
                    Informe por Rubro
                </h1>
                <p class="contable-subtitle">Comparación de ingresos y egresos por categorías</p>
            </div>
            <div class="contable-header-actions">
                <button class="contable-btn contable-btn-outline-primary" onclick="exportarInforme()">
                    <i class="bi bi-download"></i> Exportar
                </button>
                <button class="contable-btn contable-btn-primary" onclick="actualizarDatos()">
                    <i class="bi bi-arrow-clockwise"></i> Actualizar
                </button>
            </div>
        </div>

        <div class="contable-body">
            <!-- Filtros de período -->
            <div class="contable-card contable-mb-xl">
                <div class="contable-card-body">
                    <div class="contable-form-grid contable-form-grid-3">
                        <div class="contable-form-group">
                            <label for="fechaDesde" class="contable-form-label">Fecha Desde</label>
                            <input type="date" id="fechaDesde" class="contable-form-control">
                        </div>
                        <div class="contable-form-group">
                            <label for="fechaHasta" class="contable-form-label">Fecha Hasta</label>
                            <input type="date" id="fechaHasta" class="contable-form-control">
                        </div>
                        <div class="contable-form-group">
                            <label for="tipoRubro" class="contable-form-label">Tipo de Rubro</label>
                            <select id="tipoRubro" class="contable-form-control">
                                <option value="todos">Todos los rubros</option>
                                <option value="operaciones">Operaciones</option>
                                <option value="administracion">Administración</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen principal -->
            <div class="contable-card contable-mb-xl">
                <div class="contable-card-header">
                    <h3 class="contable-card-title">
                        <i class="bi bi-bar-chart"></i>
                        Resumen por Rubros
                    </h3>
                </div>
                <div class="contable-card-body">
                    <div class="contable-rubro-container">
                        <div class="contable-rubro-item">
                            <div class="contable-rubro-header">
                                <div class="contable-rubro-title">
                                    <i class="bi bi-gear-fill" style="color: var(--primary-color);"></i>
                                    Operaciones vs. Administración
                                </div>
                                <div class="contable-rubro-value">$<span id="operacionesTotal">0</span> / $<span id="administracionTotal">0</span></div>
                            </div>
                            <div class="contable-progress contable-mt-sm">
                                <div class="contable-progress-bar contable-progress-primary" id="operacionesBar" style="width: 50%"></div>
                            </div>
                            <div class="contable-flex contable-justify-between contable-mt-xs">
                                <span class="contable-text-xs contable-text-secondary">Operaciones</span>
                                <span class="contable-text-xs contable-text-secondary">Administración</span>
                            </div>
                        </div>
                    </div>

                    <!-- Detalle por categorías -->
                    <div class="contable-mt-xl">
                        <div class="contable-form-grid contable-form-grid-2">
                            <!-- Ingresos por rubro -->
                            <div class="contable-card">
                                <div class="contable-card-header">
                                    <h4 class="contable-card-title" style="color: var(--success-color);">
                                        <i class="bi bi-arrow-up-circle"></i>
                                        Ingresos por Rubro
                                    </h4>
                                </div>
                                <div class="contable-card-body">
                                    <div id="ingresosRubro" class="contable-rubro-list">
                                        <div class="contable-text-center contable-text-secondary contable-py-lg">
                                            <i class="bi bi-inbox" style="font-size: 2rem; opacity: 0.5;"></i>
                                            <p>No hay datos disponibles</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Egresos por rubro -->
                            <div class="contable-card">
                                <div class="contable-card-header">
                                    <h4 class="contable-card-title" style="color: var(--danger-color);">
                                        <i class="bi bi-arrow-down-circle"></i>
                                        Egresos por Rubro
                                    </h4>
                                </div>
                                <div class="contable-card-body">
                                    <div id="egresosRubro" class="contable-rubro-list">
                                        <div class="contable-text-center contable-text-secondary contable-py-lg">
                                            <i class="bi bi-inbox" style="font-size: 2rem; opacity: 0.5;"></i>
                                            <p>No hay datos disponibles</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos específicos para el informe por rubros */
    .contable-rubro-container {
        padding: var(--spacing-lg);
        background: var(--gray-50);
        border-radius: var(--border-radius-md);
        border: 1px solid var(--gray-200);
    }

    .contable-rubro-item {
        margin-bottom: var(--spacing-lg);
    }

    .contable-rubro-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--spacing-sm);
    }

    .contable-rubro-title {
        font-weight: 600;
        color: var(--gray-700);
        display: flex;
        align-items: center;
        gap: var(--spacing-sm);
    }

    .contable-rubro-value {
        font-weight: 700;
        color: var(--primary-color);
        font-size: 1.1rem;
    }

    .contable-rubro-list {
        max-height: 300px;
        overflow-y: auto;
    }

    .contable-rubro-list .contable-rubro-item {
        padding: var(--spacing-md);
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: var(--border-radius-sm);
        margin-bottom: var(--spacing-sm);
    }

    .contable-rubro-list .contable-rubro-item:last-child {
        margin-bottom: 0;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarDatosRubros();
});

function cargarDatosRubros() {
    const movimientos = JSON.parse(localStorage.getItem('movimientos')) || [];
    // Lógica para procesar por rubros
}
</script>
@endsection
