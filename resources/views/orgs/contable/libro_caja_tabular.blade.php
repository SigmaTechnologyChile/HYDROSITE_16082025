@extends('layouts.nice', ['active' => 'libro-caja-tabular', 'title' => 'Libro de Caja Tabular'])

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
        <span class="contable-breadcrumb-item active">Libro de Caja Tabular</span>
    </nav>

    <!-- Sección principal -->
    <div class="contable-section">
        <div class="contable-header">
            <div>
                <h1 class="contable-title">
                    <i class="bi bi-table"></i>
                    Libro de Caja Tabular
                </h1>
                <p class="contable-subtitle">Registro diario de todos los movimientos financieros en formato tabular</p>
            </div>
            <div>
                <button class="contable-btn contable-btn-outline">
                    <i class="bi bi-download"></i>
                    Exportar
                </button>
            </div>
        </div>

        <div class="contable-body">
            <!-- Saldos modernizados -->
            <div class="contable-form-section contable-mb-xl">
                <div class="contable-form-header">
                    <h3>
                        <i class="bi bi-wallet"></i>
                        Saldos de Cuentas
                    </h3>
                </div>
                <div class="contable-form-body">
                    <div class="contable-form-grid contable-form-grid-3">
                        <div class="contable-form-group">
                            <label class="contable-form-label">
                                <i class="bi bi-cash-stack"></i>
                                Saldo Caja General
                            </label>
                            <input id="saldoCajaGeneral" type="text" value="$0" readonly class="contable-form-input">
                        </div>
                        <div class="contable-form-group">
                            <label class="contable-form-label">
                                <i class="bi bi-credit-card"></i>
                                Saldo Cuenta Corriente 1
                            </label>
                            <input id="saldoCuentaCorriente1" type="text" value="$0" readonly class="contable-form-input">
                        </div>
                        <div class="contable-form-group">
                            <label class="contable-form-label">
                                <i class="bi bi-credit-card-2-front"></i>
                                Saldo Cuenta Corriente 2
                            </label>
                            <input id="saldoCuentaCorriente2" type="text" value="$0" readonly class="contable-form-input">
                        </div>
                        <div class="contable-form-group">
                            <label class="contable-form-label">
                                <i class="bi bi-piggy-bank"></i>
                                Cuenta Ahorro
                            </label>
                            <input id="saldoCuentaAhorro" type="text" value="$0" readonly class="contable-form-input">
                        </div>
                        <div class="contable-form-group">
                            <label class="contable-form-label">
                                <i class="bi bi-calculator"></i>
                                Saldo Total
                            </label>
                            <input id="saldoTotal" type="text" readonly class="contable-form-input" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); font-weight: 700; color: var(--primary-color);">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Totales modernizados -->
            <div class="resumen-totales contable-mb-xl">
                        <div class="total-card ingresos">
                            <h3>Total Ingresos</h3>
                            <div class="value" id="totalIngresos">$0</div>
                        </div>
                        <div class="total-card egresos">
                            <h3>Total Egresos</h3>
                            <div class="value" id="totalEgresos">$0</div>
                        </div>
                        <div class="total-card saldo">
                            <h3>Saldo Final</h3>
                            <div class="value" id="saldoFinal">$0</div>
                        </div>
                    </div>

                    <!-- Tabla de movimientos -->
                    <div class="table-container">
                        <div class="table-header">
                            <h2>Movimientos Financieros</h2>
                            <div class="filters">
                                <div class="filter-group">
                                    <label>Desde</label>
                                    <input id="fechaDesde" type="date" class="form-control">
                                </div>
                                <div class="filter-group">
                                    <label>Hasta</label>
                                    <input id="fechaHasta" type="date" class="form-control">
                                </div>
                                <div class="filter-group">
                                    <label>&nbsp;</label>
                                    <button onclick="filtrarPorFechas()" class="btn btn-primary">
                                        <i class="bi bi-funnel"></i> Filtrar
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div style="overflow-x: auto;">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th colspan="7" class="ingresos-header text-center bg-success text-white">Entradas Ingresos</th>
                                        <th colspan="9" class="egresos-header text-center bg-danger text-white">Salidas Egresos</th>
                                    </tr>
                                    <tr>
                                        <th>Acciones</th>
                                        <th>Fecha</th>
                                        <th>Descripción</th>
                                        <th>Total Consumo</th>
                                        <th>Cuotas Incorporación</th>
                                        <th>Otros Ingresos</th>
                                        <th>Giros</th>
                                        <th>TOTAL INGRESOS</th>
                                        <th>ENERGÍA ELÉCTRICA</th>
                                        <th>SUELDOS/LEYES SOCIALES</th>
                                        <th>OTROS GASTOS DE OPERACIÓN</th>
                                        <th>GASTOS MANTENCION</th>
                                        <th>GASTOS ADMINISTRACION</th>
                                        <th>GASTOS MEJORAMIENTO</th>
                                        <th>OTROS EGRESOS</th>
                                        <th>DEPÓSITOS</th>
                                        <th>TOTAL EGRESOS</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaMovimientos">
                                    <!-- Los movimientos se generarán dinámicamente aquí -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .saldo-section {
        margin: 20px 0;
    }

    .saldo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin: 20px 0;
    }

    .saldo-item label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        color: #333;
    }

    .totals-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin: 30px 0;
    }

    .total-card {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-left: 4px solid;
    }

    .total-card.ingresos {
        border-left-color: #28a745;
    }

    .total-card.egresos {
        border-left-color: #dc3545;
    }

    .total-card.saldo {
        border-left-color: #007bff;
    }

    .total-card h3 {
        margin: 0 0 10px 0;
        font-size: 1.1rem;
        color: #666;
    }

    .total-card .value {
        font-size: 1.5rem;
        font-weight: bold;
        color: #333;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 20px 0;
        flex-wrap: wrap;
        gap: 15px;
    }

    .filters {
        display: flex;
        gap: 15px;
        align-items: end;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        margin-bottom: 5px;
        font-weight: 600;
        color: #333;
    }

    .filter-group input {
        width: 150px;
    }

    @media (max-width: 768px) {
        .saldo-grid {
            grid-template-columns: 1fr;
        }
        
        .totals-container {
            grid-template-columns: 1fr;
        }
        
        .table-header {
            flex-direction: column;
            align-items: stretch;
        }
        
        .filters {
            justify-content: center;
        }
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const hoy = new Date();
    const primerDiaMes = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
    const ultimoDiaMes = new Date(hoy.getFullYear(), hoy.getMonth() + 1, 0);

    document.getElementById('fechaDesde').valueAsDate = primerDiaMes;
    document.getElementById('fechaHasta').valueAsDate = ultimoDiaMes;

    // Reflejar saldos desde localStorage
    const saldoCajaGeneral = localStorage.getItem('saldoCajaGeneral') || 0;
    const saldoCuentaCorriente1 = localStorage.getItem('saldoCuentaCorriente1') || 0;
    const saldoCuentaCorriente2 = localStorage.getItem('saldoCuentaCorriente2') || 0;
    const saldoCuentaAhorro = localStorage.getItem('saldoCuentaAhorro') || 0;
    const saldoTotal =
        parseFloat(saldoCajaGeneral) +
        parseFloat(saldoCuentaCorriente1) +
        parseFloat(saldoCuentaCorriente2) +
        parseFloat(saldoCuentaAhorro);

    document.getElementById('saldoCajaGeneral').value = formatCurrency(saldoCajaGeneral);
    document.getElementById('saldoCuentaCorriente1').value = formatCurrency(saldoCuentaCorriente1);
    document.getElementById('saldoCuentaCorriente2').value = formatCurrency(saldoCuentaCorriente2);
    document.getElementById('saldoCuentaAhorro').value = formatCurrency(saldoCuentaAhorro);
    document.getElementById('saldoTotal').value = formatCurrency(saldoTotal);

    actualizarTablaLibroCaja();
});

function formatCurrency(value) {
    const num = parseFloat(value) || 0;
    return num.toLocaleString('es-CL', { style: 'currency', currency: 'CLP', minimumFractionDigits: 0 });
}

function filtrarPorFechas() {
    actualizarTablaLibroCaja();
}

function actualizarTablaLibroCaja() {
    const tbody = document.getElementById('tablaMovimientos');
    if (!tbody) return;
    tbody.innerHTML = '';

    // Obtener movimientos y fechas de filtro
    const movimientos = JSON.parse(localStorage.getItem('movimientos')) || [];
    const fechaDesde = document.getElementById('fechaDesde').value;
    const fechaHasta = document.getElementById('fechaHasta').value;

    // Filtrar por fecha
    const filtrados = movimientos.filter(m => {
        return (!fechaDesde || m.fecha >= fechaDesde) && (!fechaHasta || m.fecha <= fechaHasta);
    });

    let totalIngresosSum = 0;
    let totalEgresosSum = 0;

    filtrados.forEach(mov => {
        // Columnas de ingresos
        let totalConsumo = '';
        let cuotasIncorporacion = '';
        let otrosIngresos = '';
        let giros = '';
        let totalIngresos = '';
        // Columnas de egresos
        let energia = '';
        let sueldos = '';
        let otrosGastosOperacion = '';
        let gastosMantencion = '';
        let gastosAdministracion = '';
        let gastosMejoramiento = '';
        let otrosEgresos = '';
        let depositos = '';
        let totalEgresos = '';

        // Mapeo de ingresos
        if (mov.tipo === 'ingreso') {
            if (mov.categoria === 'venta_agua') totalConsumo = formatCurrency(mov.monto);
            else if (mov.categoria === 'cuotas_incorporacion') cuotasIncorporacion = formatCurrency(mov.monto);
            else otrosIngresos = formatCurrency(mov.monto);
            totalIngresos = formatCurrency(mov.monto);
            totalIngresosSum += parseFloat(mov.monto);
        }
        // Mapeo de egresos
        else if (mov.tipo === 'egreso') {
            if (mov.categoria === 'energia_electrica') energia = formatCurrency(mov.monto);
            else if (mov.categoria === 'sueldos') sueldos = formatCurrency(mov.monto);
            else if (mov.categoria === 'otras_cuentas') otrosGastosOperacion = formatCurrency(mov.monto);
            else if (mov.categoria === 'mantencion' || mov.categoria === 'trabajos_domicilio') gastosMantencion = formatCurrency(mov.monto);
            else if (mov.categoria === 'insumos_oficina') gastosAdministracion = formatCurrency(mov.monto);
            else if (mov.categoria === 'materiales_red' || mov.categoria === 'mejoramiento') gastosMejoramiento = formatCurrency(mov.monto);
            else if (mov.categoria === 'viaticos') otrosEgresos = formatCurrency(mov.monto);
            totalEgresos = formatCurrency(mov.monto);
            totalEgresosSum += parseFloat(mov.monto);
        }
        // Mapeo de transferencias
        else if (mov.tipo === 'transferencia') {
            if (mov.subtipo === 'giro') giros = formatCurrency(mov.monto);
            else if (mov.subtipo === 'deposito') depositos = formatCurrency(mov.monto);
        }

        // Fila
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <button class="btn btn-sm btn-outline-danger" onclick="eliminarMovimiento('${mov.id || Date.now()}')">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
            <td>${mov.fecha || ''}</td>
            <td>${mov.descripcion || mov.detalle || ''}</td>
            <td>${totalConsumo}</td>
            <td>${cuotasIncorporacion}</td>
            <td>${otrosIngresos}</td>
            <td>${giros}</td>
            <td class="text-success fw-bold">${totalIngresos}</td>
            <td>${energia}</td>
            <td>${sueldos}</td>
            <td>${otrosGastosOperacion}</td>
            <td>${gastosMantencion}</td>
            <td>${gastosAdministracion}</td>
            <td>${gastosMejoramiento}</td>
            <td>${otrosEgresos}</td>
            <td>${depositos}</td>
            <td class="text-danger fw-bold">${totalEgresos}</td>
        `;
        tbody.appendChild(tr);
    });

    // Actualizar totales
    document.getElementById('totalIngresos').textContent = formatCurrency(totalIngresosSum);
    document.getElementById('totalEgresos').textContent = formatCurrency(totalEgresosSum);
    document.getElementById('saldoFinal').textContent = formatCurrency(totalIngresosSum - totalEgresosSum);
}

function eliminarMovimiento(id) {
    if (confirm('¿Está seguro de que desea eliminar este movimiento?')) {
        let movimientos = JSON.parse(localStorage.getItem('movimientos')) || [];
        movimientos = movimientos.filter(m => m.id != id);
        localStorage.setItem('movimientos', JSON.stringify(movimientos));
        actualizarTablaLibroCaja();
    }
}
</script>
@endsection
