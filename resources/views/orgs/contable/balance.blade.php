@extends('layouts.nice', ['active' => 'balance', 'title' => 'Balance Financiero'])

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
        <span class="contable-breadcrumb-item active">Balance Financiero</span>
    </nav>

    <!-- Header principal -->
    <div class="contable-section">
        <div class="contable-header">
            <div>
                <h1 class="contable-title">
                    <i class="bi bi-bar-chart-line"></i>
                    Balance Financiero
                </h1>
                <p class="contable-subtitle">Resumen gráfico y analítico de la situación financiera actual</p>
            </div>
            <div class="contable-header-actions">
                <button class="contable-btn contable-btn-outline">
                    <i class="bi bi-download"></i>
                    Exportar
                </button>
            </div>
        </div>

        <div class="contable-body">
            <!-- Resumen financiero modernizado -->
            <div class="resumen-totales contable-mb-xl">
                <div class="total-card total-card-ingreso">
                    <div class="total-card-icon">
                        <i class="bi bi-arrow-up-circle"></i>
                    </div>
                    <div class="total-valor contable-text-success" id="balanceTotalIngresos">$0</div>
                    <div class="total-label">Total Ingresos</div>
                </div>
                
                <div class="total-card total-card-egreso">
                    <div class="total-card-icon">
                        <i class="bi bi-arrow-down-circle"></i>
                    </div>
                    <div class="total-valor" style="color: var(--danger-color);" id="balanceTotalEgresos">$0</div>
                    <div class="total-label">Total Egresos</div>
                </div>
                
                <div class="total-card total-card-saldo">
                    <div class="total-card-icon">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div class="total-valor" style="color: var(--primary-color);" id="balanceSaldoFinal">$0</div>
                    <div class="total-label">Saldo Final</div>
                </div>
            </div>

            <!-- Gráficos modernizados -->
            <div class="graficos-grid">
                <div class="contable-card">
                    <div class="contable-card-header">
                        <h3 class="contable-card-title">
                            <i class="bi bi-pie-chart"></i>
                            Distribución de Ingresos
                        </h3>
                    </div>
                    <div class="contable-card-body">
                        <div class="grafico-moderno pie-chart">
                            <div class="pie-segment venta-agua" style="--percentage: 60%;" title="Venta de Agua: 60%">
                                <span class="segment-label">Venta de Agua</span>
                            </div>
                            <div class="pie-segment cuotas" style="--percentage: 25%;" title="Cuotas: 25%">
                                <span class="segment-label">Cuotas</span>
                                </div>
                                <div class="pie-segment otros-ing" style="--percentage: 15%;" title="Otros: 15%">
                                    <span class="segment-label">Otros</span>
                                </div>
                                <div class="chart-legend">
                                    <div class="legend-item"><span class="color-box venta-agua"></span> Venta de Agua (60%)</div>
                                    <div class="legend-item"><span class="color-box cuotas"></span> Cuotas (25%)</div>
                                    <div class="legend-item"><span class="color-box otros-ing"></span> Otros (15%)</div>
                                </div>
                            </div>
                        </div>
                        <div class="chart-card">
                            <h3>Distribución de Egresos</h3>
                            <div class="static-chart pie-chart">
                                <div class="pie-segment energia" style="--percentage: 35%;" title="Energía: 35%">
                                    <span class="segment-label">Energía</span>
                                </div>
                                <div class="pie-segment sueldos" style="--percentage: 40%;" title="Sueldos: 40%">
                                    <span class="segment-label">Sueldos</span>
                                </div>
                                <div class="pie-segment mantencion" style="--percentage: 15%;" title="Mantención: 15%">
                                    <span class="segment-label">Mantención</span>
                                </div>
                                <div class="pie-segment otros-egr" style="--percentage: 10%;" title="Otros: 10%">
                                    <span class="segment-label">Otros</span>
                                </div>
                                <div class="chart-legend">
                                    <div class="legend-item"><span class="color-box energia"></span> Energía (35%)</div>
                                    <div class="legend-item"><span class="color-box sueldos"></span> Sueldos (40%)</div>
                                    <div class="legend-item"><span class="color-box mantencion"></span> Mantención (15%)</div>
                                    <div class="legend-item"><span class="color-box otros-egr"></span> Otros (10%)</div>
                                </div>
                            </div>
                        </div>
                        <div class="chart-card">
                            <h3>Flujo Mensual</h3>
                            <div class="static-chart line-chart">
                                <div class="chart-container">
                                    <div class="chart-lines">
                                        <div class="line ingresos-line">
                                            <div class="point" style="left: 0%; bottom: 45%;" title="Ene: $450.000"></div>
                                            <div class="point" style="left: 16.6%; bottom: 38%;" title="Feb: $380.000"></div>
                                            <div class="point" style="left: 33.2%; bottom: 52%;" title="Mar: $520.000"></div>
                                            <div class="point" style="left: 49.8%; bottom: 46%;" title="Abr: $460.000"></div>
                                            <div class="point" style="left: 66.4%; bottom: 49%;" title="May: $490.000"></div>
                                            <div class="point" style="left: 83%; bottom: 53%;" title="Jun: $530.000"></div>
                                        </div>
                                        <div class="line egresos-line">
                                            <div class="point" style="left: 0%; bottom: 28%;" title="Ene: $280.000"></div>
                                            <div class="point" style="left: 16.6%; bottom: 31%;" title="Feb: $310.000"></div>
                                            <div class="point" style="left: 33.2%; bottom: 29%;" title="Mar: $290.000"></div>
                                            <div class="point" style="left: 49.8%; bottom: 32%;" title="Abr: $320.000"></div>
                                            <div class="point" style="left: 66.4%; bottom: 30%;" title="May: $300.000"></div>
                                            <div class="point" style="left: 83%; bottom: 35%;" title="Jun: $350.000"></div>
                                        </div>
                                    </div>
                                    <div class="chart-labels">
                                        <span>Ene</span><span>Feb</span><span>Mar</span><span>Abr</span><span>May</span><span>Jun</span>
                                    </div>
                                </div>
                                <div class="chart-legend">
                                    <div class="legend-item"><span class="color-box ingresos"></span> Ingresos</div>
                                    <div class="legend-item"><span class="color-box egresos"></span> Egresos</div>
                                </div>
                            </div>
                        </div>
                        <div class="chart-card">
                            <h3>Conciliación Bancaria</h3>
                            <div class="static-chart bar-chart">
                                <div class="bars-container">
                                    <div class="bar-group">
                                        <div class="bar registrado" style="height: 60%;" title="Caja Registrado: $120.000"></div>
                                        <div class="bar bancario" style="height: 57.5%;" title="Caja Bancario: $115.000"></div>
                                        <span class="bar-label">Caja</span>
                                    </div>
                                    <div class="bar-group">
                                        <div class="bar registrado" style="height: 100%;" title="Cta Cte 1 Registrado: $350.000"></div>
                                        <div class="bar bancario" style="height: 101.4%;" title="Cta Cte 1 Bancario: $355.000"></div>
                                        <span class="bar-label">Cta Cte 1</span>
                                    </div>
                                    <div class="bar-group">
                                        <div class="bar registrado" style="height: 80%;" title="Cta Cte 2 Registrado: $180.000"></div>
                                        <div class="bar bancario" style="height: 77.8%;" title="Cta Cte 2 Bancario: $175.000"></div>
                                        <span class="bar-label">Cta Cte 2</span>
                                    </div>
                                    <div class="bar-group">
                                        <div class="bar registrado" style="height: 45%;" title="Ahorro Registrado: $90.000"></div>
                                        <div class="bar bancario" style="height: 46%;" title="Ahorro Bancario: $92.000"></div>
                                        <span class="bar-label">Ahorro</span>
                                    </div>
                                </div>
                                <div class="chart-legend">
                                    <div class="legend-item"><span class="color-box registrado"></span> Saldo Registrado</div>
                                    <div class="legend-item"><span class="color-box bancario"></span> Saldo Bancario</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detalles por categoría -->
                    <div class="balance-grid">
                        <div class="balance-card">
                            <h3><i class="bi bi-arrow-up-circle text-success"></i> Ingresos por Categoría</h3>
                            <ul id="balanceIngresos" class="category-list">
                                <!-- Los ingresos por categoría se generarán aquí -->
                            </ul>
                        </div>

                        <div class="balance-card">
                            <h3><i class="bi bi-arrow-down-circle text-danger"></i> Egresos por Categoría</h3>
                            <ul id="balanceEgresos" class="category-list">
                                <!-- Los egresos por categoría se generarán aquí -->
                            </ul>
                        </div>

                        <div class="balance-card">
                            <h3><i class="bi bi-clock-history text-info"></i> Últimos Movimientos</h3>
                            <ul id="balanceMovimientos" class="movement-list">
                                <!-- Los últimos movimientos se generarán aquí -->
                            </ul>
                        </div>

                        <div class="balance-card">
                            <h3><i class="bi bi-graph-up text-primary"></i> Análisis Financiero</h3>
                            <div class="analysis-content">
                                <div class="analysis-item">
                                    <div class="analysis-label">
                                        <span>Flujo de efectivo:</span>
                                        <span id="flujoEfectivo" class="fw-bold">$0</span>
                                    </div>
                                    <div class="progress">
                                        <div id="flujoBar" class="progress-bar bg-success" style="width: 50%"></div>
                                    </div>
                                </div>

                                <div class="analysis-item">
                                    <div class="analysis-label">
                                        <span>Proporción ingresos/egresos:</span>
                                        <span id="proporcionIngEgr" class="fw-bold">1:1</span>
                                    </div>
                                    <div class="progress">
                                        <div id="proporcionBar" class="progress-bar bg-primary" style="width: 50%"></div>
                                    </div>
                                </div>

                                <div class="analysis-item">
                                    <div class="analysis-label">
                                        <span>Porcentaje de ahorro:</span>
                                        <span id="porcentajeAhorro" class="fw-bold">0%</span>
                                    </div>
                                    <div class="progress">
                                        <div id="ahorroBar" class="progress-bar bg-info" style="width: 0%"></div>
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

<style>
    .summary-card {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        margin: 20px 0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .summary-item {
        text-align: center;
        padding: 20px;
        border-radius: 8px;
        border-left: 4px solid;
    }

    .summary-item.ingresos {
        border-left-color: #28a745;
        background: rgba(40, 167, 69, 0.1);
    }

    .summary-item.egresos {
        border-left-color: #dc3545;
        background: rgba(220, 53, 69, 0.1);
    }

    .summary-item.saldo {
        border-left-color: #007bff;
        background: rgba(0, 123, 255, 0.1);
    }

    .summary-item .label {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 8px;
    }

    .summary-item .value {
        font-size: 1.8rem;
        font-weight: bold;
        color: #333;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin: 30px 0;
    }

    .chart-card {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-align: center;
    }

    .chart-card h3 {
        margin: 0 0 15px 0;
        color: #333;
        font-size: 1.1rem;
    }

    /* Estilos para gráficos estáticos */
    .static-chart {
        width: 100%;
        height: 200px;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* Gráfico de pie/dona */
    .pie-chart {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }

    .pie-chart::before {
        content: '';
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: conic-gradient(
            #28a745 0% 60%,
            #17a2b8 60% 85%, 
            #6c757d 85% 100%
        );
        margin-bottom: 10px;
    }

    /* Gráfico de pie para egresos (4 categorías) */
    .chart-card:nth-child(2) .pie-chart::before {
        background: conic-gradient(
            #dc3545 0% 35%,
            #fd7e14 35% 75%,
            #ffc107 75% 90%, 
            #6f42c1 90% 100%
        );
    }

    /* Leyenda de gráficos */
    .chart-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
        font-size: 0.85rem;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .color-box {
        width: 12px;
        height: 12px;
        border-radius: 2px;
    }

    .color-box.venta-agua { background-color: #28a745; }
    .color-box.cuotas { background-color: #17a2b8; }
    .color-box.otros-ing { background-color: #6c757d; }
    .color-box.energia { background-color: #dc3545; }
    .color-box.sueldos { background-color: #fd7e14; }
    .color-box.mantencion { background-color: #ffc107; }
    .color-box.otros-egr { background-color: #6f42c1; }
    .color-box.ingresos { background-color: #28a745; }
    .color-box.egresos { background-color: #dc3545; }
    .color-box.registrado { background-color: #007bff; }
    .color-box.bancario { background-color: #6c757d; }

    /* Gráfico de líneas */
    .line-chart .chart-container {
        width: 100%;
        height: 120px;
        position: relative;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        background: linear-gradient(to bottom, transparent 0%, transparent 100%),
                    repeating-linear-gradient(to bottom, transparent 0px, transparent 19px, #f0f0f0 19px, #f0f0f0 20px);
    }

    .chart-lines {
        position: relative;
        width: 100%;
        height: 100%;
    }

    .line {
        position: absolute;
        width: 100%;
        height: 100%;
    }

    .point {
        position: absolute;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        transform: translate(-50%, 50%);
        cursor: pointer;
    }

    .ingresos-line .point {
        background-color: #28a745;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #28a745;
    }

    .egresos-line .point {
        background-color: #dc3545;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #dc3545;
    }

    .chart-labels {
        display: flex;
        justify-content: space-between;
        padding: 5px 10px 0;
        font-size: 0.8rem;
        color: #666;
    }

    /* Gráfico de barras */
    .bar-chart .bars-container {
        display: flex;
        justify-content: space-around;
        align-items: flex-end;
        height: 120px;
        padding: 10px;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        background: linear-gradient(to bottom, transparent 0%, transparent 100%),
                    repeating-linear-gradient(to bottom, transparent 0px, transparent 19px, #f0f0f0 19px, #f0f0f0 20px);
    }

    .bar-group {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2px;
        width: 60px;
    }

    .bar {
        width: 18px;
        min-height: 5px;
        border-radius: 2px 2px 0 0;
        margin: 0 1px;
        cursor: pointer;
        transition: opacity 0.2s;
    }

    .bar:hover {
        opacity: 0.8;
    }

    .bar.registrado {
        background-color: #007bff;
    }

    .bar.bancario {
        background-color: #6c757d;
    }

    .bar-label {
        font-size: 0.75rem;
        color: #666;
        margin-top: 5px;
        text-align: center;
    }

    .balance-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin: 30px 0;
    }

    .balance-card {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .balance-card h3 {
        margin: 0 0 15px 0;
        color: #333;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .category-list, .movement-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .category-list li, .movement-list li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .category-list li:last-child, .movement-list li:last-child {
        border-bottom: none;
    }

    .analysis-content {
        padding: 15px 0;
    }

    .analysis-item {
        margin-bottom: 20px;
    }

    .analysis-item:last-child {
        margin-bottom: 0;
    }

    .analysis-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .progress {
        height: 8px;
        border-radius: 4px;
    }

    @media (max-width: 768px) {
        .summary-grid {
            grid-template-columns: 1fr;
        }
        
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
        
        .balance-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cargar datos del balance
    cargarDatosBalance();
});

function cargarDatosBalance() {
    // Obtener movimientos desde localStorage
    const movimientos = JSON.parse(localStorage.getItem('movimientos')) || [];
    
    // Calcular totales
    let totalIngresos = 0;
    let totalEgresos = 0;
    let ingresosPorCategoria = {};
    let egresosPorCategoria = {};
    
    movimientos.forEach(mov => {
        if (mov.tipo === 'ingreso') {
            totalIngresos += parseFloat(mov.monto);
            ingresosPorCategoria[mov.categoria] = (ingresosPorCategoria[mov.categoria] || 0) + parseFloat(mov.monto);
        } else if (mov.tipo === 'egreso') {
            totalEgresos += parseFloat(mov.monto);
            egresosPorCategoria[mov.categoria] = (egresosPorCategoria[mov.categoria] || 0) + parseFloat(mov.monto);
        }
    });
    
    const saldoFinal = totalIngresos - totalEgresos;
    
    // Actualizar resumen
    document.getElementById('balanceTotalIngresos').textContent = formatCurrency(totalIngresos);
    document.getElementById('balanceTotalEgresos').textContent = formatCurrency(totalEgresos);
    document.getElementById('balanceSaldoFinal').textContent = formatCurrency(saldoFinal);
    
    // Actualizar análisis financiero
    document.getElementById('flujoEfectivo').textContent = formatCurrency(saldoFinal);
    const proporcion = totalEgresos > 0 ? (totalIngresos / totalEgresos).toFixed(2) : '∞';
    document.getElementById('proporcionIngEgr').textContent = `${proporcion}:1`;
    const porcentajeAhorro = totalIngresos > 0 ? ((saldoFinal / totalIngresos) * 100).toFixed(1) : '0';
    document.getElementById('porcentajeAhorro').textContent = `${porcentajeAhorro}%`;
    
    // Actualizar barras de progreso
    const flujoBar = document.getElementById('flujoBar');
    const proporcionBar = document.getElementById('proporcionBar');
    const ahorroBar = document.getElementById('ahorroBar');
    
    if (saldoFinal >= 0) {
        flujoBar.className = 'progress-bar bg-success';
        flujoBar.style.width = '100%';
    } else {
        flujoBar.className = 'progress-bar bg-danger';
        flujoBar.style.width = '100%';
    }
    
    proporcionBar.style.width = Math.min(100, parseFloat(proporcion) * 50) + '%';
    ahorroBar.style.width = Math.min(100, Math.max(0, parseFloat(porcentajeAhorro))) + '%';
    
    // Llenar listas de categorías
    llenarListaCategorias('balanceIngresos', ingresosPorCategoria);
    llenarListaCategorias('balanceEgresos', egresosPorCategoria);
    
    // Llenar últimos movimientos
    const ultimosMovimientos = movimientos.slice(-5).reverse();
    const movimientosList = document.getElementById('balanceMovimientos');
    movimientosList.innerHTML = '';
    
    ultimosMovimientos.forEach(mov => {
        const li = document.createElement('li');
        const tipo = mov.tipo === 'ingreso' ? '+' : '-';
        const clase = mov.tipo === 'ingreso' ? 'text-success' : 'text-danger';
        li.innerHTML = `
            <span>${mov.descripcion || mov.detalle || 'Sin descripción'}</span>
            <span class="${clase}">${tipo}${formatCurrency(mov.monto)}</span>
        `;
        movimientosList.appendChild(li);
    });
}

function llenarListaCategorias(elementId, categorias) {
    const lista = document.getElementById(elementId);
    lista.innerHTML = '';
    
    Object.entries(categorias).forEach(([categoria, monto]) => {
        const li = document.createElement('li');
        li.innerHTML = `
            <span>${formatearCategoria(categoria)}</span>
            <span class="fw-bold">${formatCurrency(monto)}</span>
        `;
        lista.appendChild(li);
    });
}

function formatearCategoria(categoria) {
    const categorias = {
        'venta_agua': 'Venta de Agua',
        'cuotas_incorporacion': 'Cuotas de Incorporación',
        'energia_electrica': 'Energía Eléctrica',
        'sueldos': 'Sueldos/Leyes Sociales',
        'otras_cuentas': 'Otros Gastos de Operación',
        'mantencion': 'Gastos de Mantención',
        'trabajos_domicilio': 'Trabajos a Domicilio',
        'insumos_oficina': 'Insumos de Oficina',
        'materiales_red': 'Materiales de Red',
        'mejoramiento': 'Mejoramiento',
        'viaticos': 'Viáticos'
    };
    
    return categorias[categoria] || categoria.replace('_', ' ').toUpperCase();
}

function formatCurrency(value) {
    const num = parseFloat(value) || 0;
    return num.toLocaleString('es-CL', { style: 'currency', currency: 'CLP', minimumFractionDigits: 0 });
}
</script>
@endsection
