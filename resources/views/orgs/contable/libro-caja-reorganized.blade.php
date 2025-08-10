@extends('layouts.app')

@section('content')
{{-- Incluir estilos modernos del módulo contable --}}
@include('orgs.contable.partials.contable-styles')

{{-- Estilos específicos para Libro de Caja --}}
<style>
/* === LIBRO DE CAJA - ESTILOS ESPECÍFICOS === */
.libro-caja-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: var(--spacing-xl);
}

.libro-caja-header {
    background: linear-gradient(135deg, var(--success-color) 0%, #20c997 100%);
    color: white;
    padding: var(--spacing-xl);
    border-radius: var(--radius-xl) var(--radius-xl) 0 0;
    margin: calc(-1 * var(--spacing-xl)) calc(-1 * var(--spacing-xl)) var(--spacing-xl) calc(-1 * var(--spacing-xl));
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    overflow: hidden;
}

.libro-caja-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
    pointer-events: none;
}

/* === FILTROS === */
.filtros-modernos {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-2xl);
    padding: var(--spacing-xl);
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-radius: var(--radius-xl);
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow-md);
}

.filtro-grupo {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
}

.filtro-label {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
}

.filtro-label i {
    color: var(--primary-color);
    font-size: 1.1rem;
}

/* === TABLA === */
.tabla-libro-caja {
    background: white;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    border: 1px solid var(--border-color);
    margin-bottom: var(--spacing-xl);
}

.tabla-libro-caja table {
    width: 100%;
    border-collapse: collapse;
}

.tabla-libro-caja thead {
    background: linear-gradient(135deg, var(--primary-color) 0%, #4299e1 100%);
    color: white;
}

.tabla-libro-caja thead th {
    padding: 18px 16px;
    text-align: left;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    position: relative;
}

.tabla-libro-caja tbody tr {
    transition: all 0.2s ease;
    border-bottom: 1px solid var(--border-light);
}

.tabla-libro-caja tbody tr:hover {
    background: var(--background-light);
    transform: translateX(2px);
}

.tabla-libro-caja tbody td {
    padding: 16px;
    vertical-align: middle;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

/* === RESUMEN === */
.resumen-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-xl);
}

.resumen-card {
    background: white;
    padding: var(--spacing-xl);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-color);
    text-align: center;
    transition: all 0.3s ease;
}

.resumen-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-xl);
}

.resumen-valor {
    font-size: 2rem;
    font-weight: 700;
    margin: var(--spacing-sm) 0;
}

.resumen-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* === ICONOS Y BADGES === */
.tipo-badge {
    padding: 6px 12px;
    border-radius: var(--radius-full);
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.tipo-ingreso {
    background: var(--success-light);
    color: var(--success-color);
}

.tipo-egreso {
    background: var(--danger-light);
    color: var(--danger-color);
}

/* === UTILIDADES === */
.text-success { color: var(--success-color) !important; }
.text-danger { color: var(--danger-color) !important; }
.text-center { text-align: center; }
.font-weight-bold { font-weight: 700; }

/* === RESPONSIVE === */
@media (max-width: 768px) {
    .libro-caja-container {
        padding: var(--spacing-md);
    }
    
    .filtros-modernos {
        grid-template-columns: 1fr;
        padding: var(--spacing-md);
    }
    
    .tabla-libro-caja {
        overflow-x: auto;
    }
    
    .resumen-container {
        grid-template-columns: 1fr;
    }
}
</style>

{{-- Contenido principal --}}
<div class="contable-page">
    {{-- Notificaciones --}}
    <div id="notification" class="contable-notification"></div>
    
    {{-- Header --}}
    <div class="contable-header">
        <div class="contable-header-content">
            <div class="contable-header-info">
                <h1 class="contable-title">
                    <i class="bi bi-journal-bookmark"></i>
                    Libro de Caja
                </h1>
                <p class="contable-subtitle">Gestión completa de ingresos y egresos</p>
            </div>
        </div>
    </div>

    {{-- Contenido principal --}}
    <div class="contable-content">
        {{-- Card principal --}}
        <div class="contable-card">
            {{-- Resumen de saldos --}}
            <div class="resumen-container">
                <div class="resumen-card">
                    <div class="resumen-valor text-success">
                        ${{ number_format($saldoTotal ?? 0, 0, ',', '.') }}
                    </div>
                    <div class="resumen-label">Saldo Total</div>
                </div>
                
                <div class="resumen-card">
                    <div class="resumen-valor text-success">
                        ${{ number_format($totalIngresos ?? 0, 0, ',', '.') }}
                    </div>
                    <div class="resumen-label">Total Ingresos</div>
                </div>
                
                <div class="resumen-card">
                    <div class="resumen-valor text-danger">
                        ${{ number_format($totalEgresos ?? 0, 0, ',', '.') }}
                    </div>
                    <div class="resumen-label">Total Egresos</div>
                </div>
                
                <div class="resumen-card">
                    <div class="resumen-valor">
                        {{ ($movimientos ?? collect())->count() }}
                    </div>
                    <div class="resumen-label">Total Movimientos</div>
                </div>
            </div>

            {{-- Filtros --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-funnel"></i>
                        Filtros de búsqueda
                    </h3>
                </div>

                <div class="filtros-container">
                    <div class="filtros-modernos">
                        <div class="filtro-grupo">
                            <label class="filtro-label">
                                <i class="bi bi-calendar-event"></i>
                                Fecha desde
                            </label>
                            <input type="date" id="fecha_desde" class="contable-input">
                        </div>

                        <div class="filtro-grupo">
                            <label class="filtro-label">
                                <i class="bi bi-calendar-check"></i>
                                Fecha hasta
                            </label>
                            <input type="date" id="fecha_hasta" class="contable-input">
                        </div>

                        <div class="filtro-grupo">
                            <label class="filtro-label">
                                <i class="bi bi-tag"></i>
                                Tipo de movimiento
                            </label>
                            <select id="tipo_movimiento" class="contable-select">
                                <option value="">Todos los tipos</option>
                                <option value="ingreso">Ingresos</option>
                                <option value="egreso">Egresos</option>
                            </select>
                        </div>

                        <div class="filtro-grupo">
                            <label class="filtro-label">
                                <i class="bi bi-bank"></i>
                                Cuenta
                            </label>
                            <select id="cuenta_id" class="contable-select">
                                <option value="">Todas las cuentas</option>
                                @foreach($bancos ?? [] as $banco)
                                    <option value="{{ $banco->id }}">{{ $banco->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filtro-grupo" style="display: flex; align-items: end;">
                            <button type="button" id="filtrar" class="contable-btn contable-btn-primary">
                                <i class="bi bi-search"></i>
                                Filtrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabla de movimientos --}}
            <div class="tabla-libro-caja">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Descripción</th>
                            <th>Tipo</th>
                            <th>Cuenta</th>
                            <th class="text-center">Ingreso</th>
                            <th class="text-center">Egreso</th>
                            <th class="text-center">Saldo</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-movimientos">
                        @if(isset($movimientos) && $movimientos->count() > 0)
                            @php $saldoAcumulado = 0; @endphp
                            @foreach($movimientos as $movimiento)
                                @php
                                    if($movimiento->tipo === 'ingreso') {
                                        $saldoAcumulado += $movimiento->monto;
                                    } else {
                                        $saldoAcumulado -= $movimiento->monto;
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $movimiento->fecha ? $movimiento->fecha->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $movimiento->descripcion ?? '-' }}</td>
                                    <td>
                                        <span class="tipo-badge tipo-{{ $movimiento->tipo }}">
                                            {{ ucfirst($movimiento->tipo) }}
                                        </span>
                                    </td>
                                    <td>{{ $movimiento->cuentaOrigen->banco->nombre ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        @if($movimiento->tipo === 'ingreso')
                                            <span class="text-success font-weight-bold">
                                                ${{ number_format($movimiento->monto, 0, ',', '.') }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($movimiento->tipo === 'egreso')
                                            <span class="text-danger font-weight-bold">
                                                ${{ number_format($movimiento->monto, 0, ',', '.') }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center font-weight-bold">
                                        ${{ number_format($saldoAcumulado, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <div class="contable-actions">
                                            <button type="button" class="contable-btn contable-btn-sm contable-btn-info"
                                                    onclick="verDetalleMovimiento({{ $movimiento->id }})">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button type="button" class="contable-btn contable-btn-sm contable-btn-warning"
                                                    onclick="editarMovimiento({{ $movimiento->id }})">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="text-center">
                                    <div class="contable-empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <h4>No hay movimientos registrados</h4>
                                        <p>Comienza agregando tu primer movimiento al libro de caja</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Acciones principales --}}
            <div class="contable-actions-main">
                <a href="{{ route('orgs.ingresos.index', ['id' => $orgId ?? auth()->user()->org_id]) }}" 
                   class="contable-btn contable-btn-success">
                    <i class="bi bi-plus-circle"></i>
                    Nuevo Ingreso
                </a>
                
                <a href="{{ route('orgs.egresos.index', ['id' => $orgId ?? auth()->user()->org_id]) }}" 
                   class="contable-btn contable-btn-danger">
                    <i class="bi bi-dash-circle"></i>
                    Nuevo Egreso
                </a>
                
                <a href="{{ route('orgs.contable.giros.depositos', ['id' => $orgId ?? auth()->user()->org_id]) }}" 
                   class="contable-btn contable-btn-info">
                    <i class="bi bi-bank"></i>
                    Giros y Depósitos
                </a>
                
                <button type="button" id="exportar-excel" class="contable-btn contable-btn-secondary">
                    <i class="bi bi-file-earmark-excel"></i>
                    Exportar Excel
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Scripts específicos --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // === FILTROS ===
    const filtrarBtn = document.getElementById('filtrar');
    const fechaDesde = document.getElementById('fecha_desde');
    const fechaHasta = document.getElementById('fecha_hasta');
    const tipoMovimiento = document.getElementById('tipo_movimiento');
    const cuentaId = document.getElementById('cuenta_id');

    if (filtrarBtn) {
        filtrarBtn.addEventListener('click', function() {
            filtrarMovimientos();
        });
    }

    function filtrarMovimientos() {
        const filtros = {
            fecha_desde: fechaDesde.value,
            fecha_hasta: fechaHasta.value,
            tipo: tipoMovimiento.value,
            cuenta_id: cuentaId.value
        };

        // Filtrar filas de la tabla
        const filas = document.querySelectorAll('#tabla-movimientos tr');
        filas.forEach(fila => {
            let mostrar = true;

            // Aplicar filtros
            if (filtros.fecha_desde || filtros.fecha_hasta || filtros.tipo || filtros.cuenta_id) {
                // Lógica de filtrado aquí
                // Por ahora mostrar todas
            }

            fila.style.display = mostrar ? '' : 'none';
        });
    }

    // === EXPORTAR EXCEL ===
    const exportarBtn = document.getElementById('exportar-excel');
    if (exportarBtn) {
        exportarBtn.addEventListener('click', function() {
            exportarExcel();
        });
    }

    function exportarExcel() {
        const orgId = {{ $orgId ?? auth()->user()->org_id }};
        const url = `/org/${orgId}/libro-caja/exportar-excel`;
        
        // Crear formulario temporal con filtros
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        
        // CSRF Token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);
        
        // Agregar filtros
        Object.keys({
            fecha_desde: fechaDesde.value,
            fecha_hasta: fechaHasta.value,
            tipo: tipoMovimiento.value,
            cuenta_id: cuentaId.value
        }).forEach(key => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = document.getElementById(key).value || '';
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }

    // === FUNCIONES GLOBALES ===
    window.verDetalleMovimiento = function(id) {
        // Implementar vista de detalle
        console.log('Ver detalle movimiento:', id);
    };

    window.editarMovimiento = function(id) {
        // Implementar edición
        console.log('Editar movimiento:', id);
    };

    // === NOTIFICACIONES ===
    window.mostrarNotificacion = function(mensaje, tipo = 'success') {
        const notification = document.getElementById('notification');
        if (notification) {
            notification.className = `contable-notification contable-notification-${tipo} show`;
            notification.textContent = mensaje;
            
            setTimeout(() => {
                notification.classList.remove('show');
            }, 4000);
        }
    };
});
</script>
@endsection
