@extends('layouts.nice', ['active' => 'movimientos', 'title' => 'Movimientos'])

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
        <span class="contable-breadcrumb-item active">Movimientos</span>
    </nav>

    <!-- Sección principal -->
    <div class="contable-section">
        <div class="contable-header">
            <div>
                <h1 class="contable-title">
                    <i class="bi bi-arrow-left-right"></i>
                    Movimientos Financieros
                </h1>
                <p class="contable-subtitle">Consulta y gestión de todos los movimientos financieros del sistema</p>
            </div>
            <div>
                <button class="contable-btn contable-btn-success">
                    <i class="bi bi-plus-circle"></i>
                    Nuevo Movimiento
                </button>
            </div>
        </div>

        <div class="contable-body">
            <!-- Filtros modernizados -->
            <div class="contable-form-section contable-mb-xl">
                <div class="contable-form-header">
                    <h3>
                        <i class="bi bi-funnel"></i>
                        Filtros de Búsqueda
                    </h3>
                </div>
                <div class="contable-form-body">
                    <div class="contable-form-grid contable-form-grid-4">
                        <div class="contable-form-group">
                            <label class="contable-form-label">
                                <i class="bi bi-calendar-date"></i>
                                Fecha Desde
                            </label>
                            <input type="date" id="fechaDesde" class="contable-form-input">
                        </div>
                        <div class="contable-form-group">
                            <label class="contable-form-label">
                                <i class="bi bi-calendar-check"></i>
                                Fecha Hasta
                            </label>
                            <input type="date" id="fechaHasta" class="contable-form-input">
                        </div>
                        <div class="contable-form-group">
                            <label class="contable-form-label">
                                <i class="bi bi-tags"></i>
                                Tipo de Movimiento
                            </label>
                            <select id="tipoMovimiento" class="contable-form-input contable-form-select">
                                <option value="">Todos los tipos</option>
                                <option value="ingreso">💰 Ingresos</option>
                                <option value="egreso">💸 Egresos</option>
                                <option value="transferencia">🔄 Transferencias</option>
                            </select>
                        </div>
                        <div class="contable-form-group">
                            <label class="contable-form-label">&nbsp;</label>
                            <button onclick="filtrarMovimientos()" class="contable-btn contable-btn-primary contable-btn-full">
                                <i class="bi bi-search"></i>
                                Aplicar Filtros
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de movimientos modernizada -->
            <div class="contable-table-wrapper">
                <table class="contable-table">
                    <thead class="contable-table-header">
                        <tr>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Descripción</th>
                                    <th>Categoría</th>
                                    <th>Monto</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaMovimientos">
                                <!-- Se generará dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.filters-row {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    flex-direction: column;
    min-width: 120px;
}

.filter-group label {
    margin-bottom: 5px;
    font-weight: 600;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarMovimientos();
});

function cargarMovimientos() {
    const movimientos = JSON.parse(localStorage.getItem('movimientos')) || [];
    const tbody = document.getElementById('tablaMovimientos');
    tbody.innerHTML = '';
    
    movimientos.forEach(mov => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${mov.fecha}</td>
            <td><span class="badge bg-${mov.tipo === 'ingreso' ? 'success' : 'danger'}">${mov.tipo}</span></td>
            <td>${mov.descripcion || mov.detalle}</td>
            <td>${mov.categoria}</td>
            <td class="${mov.tipo === 'ingreso' ? 'text-success' : 'text-danger'}">
                ${formatCurrency(mov.monto)}
            </td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="editarMovimiento('${mov.id}')">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="eliminarMovimiento('${mov.id}')">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function filtrarMovimientos() {
    // Implementar filtros
    cargarMovimientos();
}

function editarMovimiento(id) {
    // Implementar edición
}

function eliminarMovimiento(id) {
    if (confirm('¿Eliminar este movimiento?')) {
        let movimientos = JSON.parse(localStorage.getItem('movimientos')) || [];
        movimientos = movimientos.filter(m => m.id !== id);
        localStorage.setItem('movimientos', JSON.stringify(movimientos));
        cargarMovimientos();
    }
}

function formatCurrency(value) {
    return parseFloat(value).toLocaleString('es-CL', { style: 'currency', currency: 'CLP', minimumFractionDigits: 0 });
}
</script>
@endsection
