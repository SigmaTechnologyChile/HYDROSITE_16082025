@extends('layouts.nice', ['active' => 'balance', 'title' => 'Balance Financiero'])

{{-- Agregar Chart.js para gráficos profesionales --}}
@section('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

{{-- CSS basado en el archivo de referencia y mejoras profesionales --}}
<style>
:root {
  --primary-color: #2c5282;
  --secondary-color: #4CAF50;
  --danger-color: #e53e3e;
  --success-color: #48bb78;
  --warning-color: #dd6b20;
  --info-color: #3182ce;
  --light-bg: #f8f9fa;
  --dark-text: #2d3748;
  --border-color: #e2e8f0;
}

/* Contenedor principal */
.balance-section {
  padding: 20px;
  max-width: 1400px;
  margin: 0 auto;
  background: var(--light-bg);
}

/* Header de la sección */
.section-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 30px;
  background: white;
  padding: 25px;
  border-radius: 15px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.section-header h1 {
  color: var(--primary-color);
  font-size: 2.5rem;
  margin: 0;
  font-weight: 700;
}

.section-header p {
  color: #718096;
  font-size: 1.1rem;
  margin: 0;
  margin-top: 5px;
}

/* Tarjeta de resumen principal */
.summary-card {
  background: white;
  border-radius: 15px;
  padding: 30px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  margin-bottom: 30px;
}

.summary-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 25px;
}

.summary-item {
  padding: 25px;
  border-radius: 12px;
  text-align: center;
  border-top: 5px solid;
  transition: transform 0.3s ease;
  position: relative;
  overflow: hidden;
}

.summary-item:hover {
  transform: translateY(-3px);
}

.summary-item.ingresos {
  background: linear-gradient(135deg, #f0fff4, #e6fffa);
  border-top-color: var(--success-color);
}

.summary-item.egresos {
  background: linear-gradient(135deg, #fff5f5, #fed7d7);
  border-top-color: var(--danger-color);
}

.summary-item.saldo {
  background: linear-gradient(135deg, #ebf8ff, #bee3f8);
  border-top-color: var(--primary-color);
}

.summary-item .label {
  font-size: 1.1rem;
  color: var(--dark-text);
  font-weight: 600;
  margin-bottom: 10px;
}

.summary-item .value {
  font-size: 2.5rem;
  font-weight: 700;
  line-height: 1;
}

.summary-item.ingresos .value {
  color: var(--success-color);
}

.summary-item.egresos .value {
  color: var(--danger-color);
}

.summary-item.saldo .value {
  color: var(--primary-color);
}

/* Grid para gráficos dashboard */
.dashboard-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  gap: 25px;
  margin-bottom: 30px;
}

.chart-card {
  background: white;
  border-radius: 15px;
  padding: 25px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  transition: transform 0.3s ease;
}

.chart-card:hover {
  transform: translateY(-3px);
}

.chart-card h3 {
  font-size: 1.3rem;
  color: var(--primary-color);
  margin-bottom: 20px;
  text-align: center;
  font-weight: 600;
}

.chart-card canvas {
  max-height: 300px;
}

/* Grid para cards de balance */
.balance-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 25px;
  margin-bottom: 30px;
}

.balance-card {
  background: white;
  border-radius: 15px;
  padding: 25px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  transition: transform 0.3s ease;
}

.balance-card:hover {
  transform: translateY(-5px);
}

.balance-card h3 {
  font-size: 1.3rem;
  color: var(--primary-color);
  margin-bottom: 20px;
  padding-bottom: 10px;
  border-bottom: 2px solid #e2e8f0;
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 600;
}

.balance-card ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.balance-card li {
  padding: 12px 0;
  border-bottom: 1px solid #edf2f7;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.balance-card li:last-child {
  border-bottom: none;
}

.balance-card .category {
  font-weight: 500;
  color: #4a5568;
}

.balance-card .amount {
  font-weight: 600;
}

.balance-card .ingreso {
  color: var(--success-color);
}

.balance-card .egreso {
  color: var(--danger-color);
}

/* Análisis financiero */
.analysis-bar {
  display: flex;
  justify-content: space-between;
  margin-bottom: 5px;
  font-size: 0.95rem;
}

.progress-bar {
  height: 8px;
  background: #e2e8f0;
  border-radius: 4px;
  overflow: hidden;
  margin-bottom: 20px;
}

.progress-fill {
  height: 100%;
  transition: width 0.3s ease;
  border-radius: 4px;
}

.progress-fill.success {
  background: linear-gradient(90deg, var(--success-color), #68d391);
}

.progress-fill.primary {
  background: linear-gradient(90deg, var(--primary-color), var(--info-color));
}

.progress-fill.secondary {
  background: linear-gradient(90deg, var(--secondary-color), #68d391);
}

/* Botones */
.action-button {
  padding: 12px 25px;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
}

.action-button.volver {
  background: #e2e8f0;
  color: var(--dark-text);
}

.action-button.volver:hover {
  background: #cbd5e0;
  transform: translateY(-2px);
}

.btn-balance {
  padding: 12px 25px;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
  margin: 0 10px;
}

.btn-primary {
  background: linear-gradient(135deg, var(--primary-color), #1a365d);
  color: white;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(44, 82, 130, 0.3);
}

.btn-secondary {
  background: #e2e8f0;
  color: var(--dark-text);
}

.btn-secondary:hover {
  background: #cbd5e0;
}

/* Ratios financieros */
.ratios-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.ratio-card {
  background: white;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  border-left: 5px solid var(--info-color);
}

.ratio-card h4 {
  color: var(--primary-color);
  margin-bottom: 10px;
  font-size: 1.1rem;
}

.ratio-value {
  font-size: 1.8rem;
  font-weight: 700;
  color: var(--info-color);
  margin-bottom: 5px;
}

.ratio-description {
  font-size: 0.9rem;
  color: #718096;
}

/* Responsive */
@media (max-width: 768px) {
  .section-header {
    flex-direction: column;
    text-align: center;
    gap: 15px;
  }
  
  .summary-grid {
    grid-template-columns: 1fr;
  }
  
  .dashboard-grid {
    grid-template-columns: 1fr;
  }
  
  .balance-grid {
    grid-template-columns: 1fr;
  }
  
  .section-header h1 {
    font-size: 2rem;
  }
}

/* Tabla de movimientos */
.movements-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 15px;
}

.movements-table th {
  background: linear-gradient(135deg, var(--primary-color), #1a365d);
  color: white;
  padding: 12px 8px;
  text-align: center;
  font-weight: 600;
  font-size: 0.9rem;
}

.movements-table td {
  padding: 10px 8px;
  text-align: center;
  border-bottom: 1px solid var(--border-color);
  font-size: 0.85rem;
}

.movements-table tbody tr:nth-child(even) {
  background-color: #f8f9fa;
}

.movements-table tbody tr:hover {
  background-color: #e6fffa;
}

.monto-positivo {
  color: var(--success-color);
  font-weight: 600;
}

.monto-negativo {
  color: var(--danger-color);
  font-weight: 600;
}
</style>

@section('content')
<div class="balance-section">
    <!-- Header -->
    <div class="section-header">
        <div>
            <h1><i class="bi bi-bar-chart-line"></i> Balance Financiero</h1>
            <p>Resumen gráfico y analítico de la situación financiera</p>
        </div>
        <a href="{{ route('orgs.contable.nice', ['id' => $orgId]) }}" class="action-button volver">
            <i class="bi bi-arrow-left"></i> Volver al Panel
        </a>
    </div>
    
    <!-- Resumen Principal -->
    <div class="summary-card">
        <div class="summary-grid">
            <div class="summary-item ingresos">
                <div class="label">Total Ingresos</div>
        <div class="value" id="balanceTotalIngresos">
          @if(isset($totalIngresos) && $totalIngresos !== null)
            ${{ number_format($totalIngresos, 0, ',', '.') }}
          @else
            <span style="color: var(--warning-color);">Sin datos</span>
          @endif
        </div>
            </div>
            <div class="summary-item egresos">
                <div class="label">Total Egresos</div>
        <div class="value" id="balanceTotalEgresos">
          @if(isset($totalEgresos) && $totalEgresos !== null)
            ${{ number_format($totalEgresos, 0, ',', '.') }}
          @else
            <span style="color: var(--warning-color);">Sin datos</span>
          @endif
        </div>
            </div>
            <div class="summary-item saldo">
                <div class="label">Saldo Final</div>
        <div class="value" id="balanceSaldoFinal">
          @if(isset($saldoFinal) && $saldoFinal !== null)
            ${{ number_format($saldoFinal, 0, ',', '.') }}
          @else
            <span style="color: var(--warning-color);">Sin datos</span>
          @endif
        </div>
            </div>
        </div>
    </div>

    <!-- Ratios Financieros Profesionales -->
    <div class="ratios-grid">
    <div class="ratio-card">
      <h4><i class="bi bi-droplet"></i> Liquidez Corriente</h4>
  <div class="ratio-value">{{ isset($ratioLiquidez) && $ratioLiquidez !== null ? number_format($ratioLiquidez, 2) : 'Sin datos' }}</div>
      <div class="ratio-description">
        Capacidad de pago a corto plazo
        @if(isset($ratioLiquidez) && $ratioLiquidez >= 1.5)
          <span style="color: var(--success-color);">✓ Saludable</span>
        @elseif(isset($ratioLiquidez))
          <span style="color: var(--warning-color);">⚠ Revisar</span>
        @else
          <span style="color: var(--warning-color);">Sin datos</span>
        @endif
      </div>
    </div>
    <div class="ratio-card">
      <h4><i class="bi bi-graph-up"></i> ROE</h4>
  <div class="ratio-value">{{ isset($roe) && $roe !== null ? number_format($roe, 1) . '%' : 'Sin datos' }}</div>
      <div class="ratio-description">Rentabilidad del patrimonio</div>
    </div>
    <div class="ratio-card">
      <h4><i class="bi bi-shield-check"></i> Endeudamiento</h4>
  <div class="ratio-value">{{ isset($ratioEndeudamiento) && $ratioEndeudamiento !== null ? number_format($ratioEndeudamiento, 1) . '%' : 'Sin datos' }}</div>
      <div class="ratio-description">
        Proporción de deuda sobre activos
        @if(isset($ratioEndeudamiento) && $ratioEndeudamiento <= 40)
          <span style="color: var(--success-color);">✓ Bajo riesgo</span>
        @elseif(isset($ratioEndeudamiento))
          <span style="color: var(--danger-color);">⚠ Alto riesgo</span>
        @else
          <span style="color: var(--warning-color);">Sin datos</span>
        @endif
      </div>
    </div>
    <div class="ratio-card">
      <h4><i class="bi bi-percent"></i> Margen Operacional</h4>
  <div class="ratio-value">{{ isset($margenOperacional) && $margenOperacional !== null ? number_format($margenOperacional, 1) . '%' : 'Sin datos' }}</div>
      <div class="ratio-description">Eficiencia operacional</div>
    </div>
    </div>
    
    <!-- Dashboard con Gráficos -->
    <div class="dashboard-grid">
        <div class="chart-card">
            <h3><i class="bi bi-pie-chart"></i> Distribución de Ingresos</h3>
            <canvas id="ingresosChart"></canvas>
        </div>
        <div class="chart-card">
            <h3><i class="bi bi-pie-chart-fill"></i> Distribución de Egresos</h3>
            <canvas id="egresosChart"></canvas>
        </div>
        <div class="chart-card">
            <h3><i class="bi bi-graph-up"></i> Flujo Mensual</h3>
            <canvas id="flujoChart"></canvas>
        </div>
        <div class="chart-card">
            <h3><i class="bi bi-bank"></i> Conciliación Bancaria</h3>
            <canvas id="conciliacionChart"></canvas>
        </div>
    </div>
    
    <!-- Grid de Cards de Análisis -->
    <div class="balance-grid">
        <div class="balance-card">
            <h3><i class="bi bi-arrow-up-circle"></i> Ingresos por Categoría</h3>
            <ul id="balanceIngresos">
        @php
          $categoriasIngresos = [
            'Cuotas de Incorporación' => $totalCuotasIncorporacion ?? null,
            'Consumo de Agua' => $totalConsumo ?? null,
            'Otros Ingresos' => $otrosIngresos ?? null,
            'Multas y Recargos' => $multasRecargos ?? null
          ];
        @endphp
        @foreach($categoriasIngresos as $categoria => $monto)
        <li>
          <span class="category">{{ $categoria }}</span>
          <span class="amount ingreso">
            @if(!is_numeric($monto) || $monto === null)
              <span style="color: var(--warning-color);">Sin datos</span>
            @else
              ${{ number_format($monto, 0, ',', '.') }}
            @endif
          </span>
        </li>
        @endforeach
            </ul>
        </div>
        
        <div class="balance-card">
            <h3><i class="bi bi-arrow-down-circle"></i> Egresos por Categoría</h3>
            <ul id="balanceEgresos">
        @php
          $categoriasEgresos = [
            'Giros y Transferencias' => $totalGiros ?? null,
            'Gastos Operacionales' => $gastosOperacionales ?? null,
            'Gastos Administrativos' => $gastosAdministrativos ?? null,
            'Mantenimiento' => $gastosMantenimiento ?? null
          ];
        @endphp
        @foreach($categoriasEgresos as $categoria => $monto)
        <li>
          <span class="category">{{ $categoria }}</span>
          <span class="amount egreso">
            @if(is_null($monto))
              <span style="color: var(--warning-color);">Sin datos</span>
            @elseif(is_numeric($monto))
              ${{ number_format($monto, 0, ',', '.') }}
            @else
              <span style="color: var(--warning-color);">Sin datos</span>
            @endif
          </span>
        </li>
        @endforeach
            </ul>
        </div>
        
        <div class="balance-card">
            <h3><i class="bi bi-clock-history"></i> Últimos Movimientos</h3>
            {{-- COMENTADO: tabla movimientos eliminada --}}
            {{-- @if(isset($movimientos) && $movimientos->count() > 0) --}}
            @if(false) {{-- Temporalmente deshabilitado --}}
                <div style="overflow-x: auto;">
                    <table class="movements-table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Descripción</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach($movimientos->take(8) as $movimiento) --}}
                            {{-- COMENTADO: tabla movimientos eliminada --}}
                            {{-- <tr>
                                <td>{{ \Carbon\Carbon::parse($movimiento->fecha)->format('d/m') }}</td>
                                <td style="text-align: left; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ Str::limit($movimiento->descripcion, 25) }}
                                </td>
                                <td>
                                    @php
                                        $monto = ($movimiento->total_consumo ?? 0) + ($movimiento->cuotas_incorporacion ?? 0) + ($movimiento->otros_ingresos ?? 0) + ($movimiento->giros ?? 0);
                                    @endphp
                                    <span class="{{ $monto >= 0 ? 'monto-positivo' : 'monto-negativo' }}">
                                        ${{ number_format(abs($monto), 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach --}}
                            {{-- COMENTADO: loop de movimientos --}}
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align: center; padding: 30px; color: #718096;">
                    <i class="bi bi-inbox" style="font-size: 2rem; margin-bottom: 10px; display: block;"></i>
                    {{-- No hay movimientos registrados --}}
                    Módulo de movimientos deshabilitado temporalmente
                </div>
            @endif
        </div>
        
        <div class="balance-card">
            <h3><i class="bi bi-graph-up"></i> Análisis Financiero</h3>
            <div style="padding: 15px;">
                <div style="margin-bottom: 20px;">
                    <div class="analysis-bar">
                        <span>Flujo de efectivo:</span>
                        <span id="flujoEfectivo" style="font-weight: 600;">
                            ${{ number_format($saldoFinal ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="progress-bar">
                        @php $flujoPercent = $saldoFinal > 0 ? min(100, ($saldoFinal / ($totalIngresos ?: 1)) * 100) : 0; @endphp
                        <div class="progress-fill success" style="width: {{ $flujoPercent }}%;"></div>
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <div class="analysis-bar">
                        <span>Proporción ingresos/egresos:</span>
                        @php 
                            $proporcion = $totalEgresos > 0 ? $totalIngresos / $totalEgresos : 1;
                            $proporcionTexto = number_format($proporcion, 1) . ':1';
                        @endphp
                        <span id="proporcionIngEgr" style="font-weight: 600;">{{ $proporcionTexto }}</span>
                    </div>
                    <div class="progress-bar">
                        @php $proporcionPercent = min(100, ($proporcion / 2) * 100); @endphp
                        <div class="progress-fill primary" style="width: {{ $proporcionPercent }}%;"></div>
                    </div>
                </div>
                
                <div>
                    <div class="analysis-bar">
                        <span>Porcentaje de ahorro:</span>
                        @php 
                            $porcentajeAhorro = $totalIngresos > 0 ? (($totalIngresos - $totalEgresos) / $totalIngresos) * 100 : 0;
                            $porcentajeAhorro = max(0, $porcentajeAhorro);
                        @endphp
                        <span id="porcentajeAhorro" style="font-weight: 600;">{{ number_format($porcentajeAhorro, 1) }}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill secondary" style="width: {{ min(100, $porcentajeAhorro) }}%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Botones de acción -->
    <div style="text-align: center; margin-top: 40px;">
        <a href="{{ route('orgs.contable.nice', ['id' => $orgId]) }}" class="btn-balance btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver al Panel
        </a>
        <button class="btn-balance btn-primary" onclick="window.print()">
            <i class="bi bi-printer"></i> Imprimir Reporte
        </button>
        <button class="btn-balance btn-primary" onclick="exportToPDF()">
            <i class="bi bi-file-pdf"></i> Exportar PDF
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuración común para todos los gráficos
    Chart.defaults.responsive = true;
    Chart.defaults.maintainAspectRatio = false;
    Chart.defaults.plugins.legend.position = 'bottom';
    
  // Datos dinámicos desde backend
  const datosIngresos = {
    labels: {!! isset($datosIngresos['labels']) ? json_encode($datosIngresos['labels']) : '[]' !!},
    data: {!! isset($datosIngresos['data']) ? json_encode($datosIngresos['data']) : '[]' !!}
  };
  const datosEgresos = {
    labels: {!! isset($datosEgresos['labels']) ? json_encode($datosEgresos['labels']) : '[]' !!},
    data: {!! isset($datosEgresos['data']) ? json_encode($datosEgresos['data']) : '[]' !!}
  };
    
    // Gráfico de Distribución de Ingresos
    new Chart(document.getElementById('ingresosChart'), {
        type: 'doughnut',
        data: {
            labels: datosIngresos.labels,
            datasets: [{
                data: datosIngresos.data,
                backgroundColor: [
                    '#48bb78',
                    '#38a169',
                    '#2f855a',
                    '#276749'
                ],
                borderWidth: 0
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            }
        }
    });
    
    // Gráfico de Distribución de Egresos
    new Chart(document.getElementById('egresosChart'), {
        type: 'doughnut',
        data: {
            labels: datosEgresos.labels,
            datasets: [{
                data: datosEgresos.data,
                backgroundColor: [
                    '#e53e3e',
                    '#c53030',
                    '#9c1c1c',
                    '#742a2a'
                ],
                borderWidth: 0
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            }
        }
    });
    
    // Gráfico de Flujo Mensual
  // Flujo mensual dinámico
  const flujoLabels = {!! isset($flujoLabels) ? json_encode($flujoLabels) : '[]' !!};
  const flujoIngresos = {!! isset($flujoIngresos) ? json_encode($flujoIngresos) : '[]' !!};
  const flujoEgresos = {!! isset($flujoEgresos) ? json_encode($flujoEgresos) : '[]' !!};
  new Chart(document.getElementById('flujoChart'), {
    type: 'line',
    data: {
      labels: flujoLabels,
      datasets: [{
        label: 'Ingresos',
        data: flujoIngresos,
        borderColor: '#48bb78',
        backgroundColor: 'rgba(72, 187, 120, 0.1)',
        tension: 0.4
      }, {
        label: 'Egresos',
        data: flujoEgresos,
        borderColor: '#e53e3e',
        backgroundColor: 'rgba(229, 62, 62, 0.1)',
        tension: 0.4
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              return '$' + (value / 1000000).toFixed(0) + 'M';
            }
          }
        }
      }
    }
  });
    
    // Gráfico de Conciliación Bancaria
  // Conciliación bancaria dinámica
  const conciliacionLabels = {!! isset($conciliacionLabels) ? json_encode($conciliacionLabels) : '[]' !!};
  const conciliacionRegistrado = {!! isset($conciliacionRegistrado) ? json_encode($conciliacionRegistrado) : '[]' !!};
  const conciliacionBancario = {!! isset($conciliacionBancario) ? json_encode($conciliacionBancario) : '[]' !!};
  new Chart(document.getElementById('conciliacionChart'), {
    type: 'bar',
    data: {
      labels: conciliacionLabels,
      datasets: [{
        label: 'Saldo Registrado',
        data: conciliacionRegistrado,
        backgroundColor: '#3182ce'
      }, {
        label: 'Saldo Bancario',
        data: conciliacionBancario,
        backgroundColor: '#2c5282'
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              return '$' + (value / 1000000).toFixed(0) + 'M';
            }
          }
        }
      }
    }
  });
});

// Función para exportar a PDF
function exportToPDF() {
    alert('Función de exportación a PDF en desarrollo. Será implementada próximamente.');
}
</script>
@endsection
