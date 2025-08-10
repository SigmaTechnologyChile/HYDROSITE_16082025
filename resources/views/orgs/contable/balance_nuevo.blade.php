@extends('layouts.nice', ['active' => 'balance', 'title' => 'Balance Financiero'])

{{-- CSS personalizado para la vista de balance --}}
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

.balance-container {
  padding: 20px;
  max-width: 1400px;
  margin: 0 auto;
}

.balance-header {
  text-align: center;
  margin-bottom: 40px;
}

.balance-header h1 {
  color: var(--primary-color);
  font-size: 2.5rem;
  margin-bottom: 10px;
  font-weight: 700;
}

.balance-header p {
  color: #718096;
  font-size: 1.1rem;
  margin: 0;
}

/* Sección de resumen principal */
.resumen-principal {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 25px;
  margin-bottom: 40px;
}

.resumen-card {
  background: white;
  border-radius: 15px;
  padding: 30px;
  text-align: center;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  border-top: 5px solid;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  position: relative;
  overflow: hidden;
}

.resumen-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.resumen-card.ingresos {
  border-top-color: var(--success-color);
}

.resumen-card.egresos {
  border-top-color: var(--danger-color);
}

.resumen-card.saldo {
  border-top-color: var(--primary-color);
}

.resumen-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 5px;
  background: linear-gradient(90deg, currentColor, transparent);
  opacity: 0.1;
}

.resumen-icon {
  font-size: 3rem;
  margin-bottom: 15px;
  opacity: 0.8;
}

.resumen-card.ingresos .resumen-icon {
  color: var(--success-color);
}

.resumen-card.egresos .resumen-icon {
  color: var(--danger-color);
}

.resumen-card.saldo .resumen-icon {
  color: var(--primary-color);
}

.resumen-valor {
  font-size: 2.5rem;
  font-weight: 700;
  margin-bottom: 10px;
  line-height: 1;
}

.resumen-card.ingresos .resumen-valor {
  color: var(--success-color);
}

.resumen-card.egresos .resumen-valor {
  color: var(--danger-color);
}

.resumen-card.saldo .resumen-valor {
  color: var(--primary-color);
}

.resumen-label {
  font-size: 1.1rem;
  color: var(--dark-text);
  font-weight: 600;
  margin: 0;
}

/* Sección de gráficos */
.graficos-section {
  margin-bottom: 40px;
}

.section-title {
  font-size: 1.8rem;
  color: var(--primary-color);
  font-weight: 600;
  margin-bottom: 25px;
  text-align: center;
}

.graficos-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  gap: 30px;
  margin-bottom: 40px;
}

.grafico-card {
  background: white;
  border-radius: 15px;
  padding: 25px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  transition: transform 0.3s ease;
}

.grafico-card:hover {
  transform: translateY(-3px);
}

.grafico-header {
  text-align: center;
  margin-bottom: 25px;
}

.grafico-title {
  font-size: 1.3rem;
  color: var(--dark-text);
  font-weight: 600;
  margin: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
}

.grafico-title i {
  color: var(--primary-color);
}

/* Gráfico de barras simple */
.chart-bars {
  display: flex;
  align-items: end;
  justify-content: space-around;
  height: 200px;
  margin-bottom: 20px;
  background: linear-gradient(to top, #f8f9fa 0%, transparent 100%);
  border-radius: 10px;
  padding: 20px;
}

.bar {
  width: 40px;
  background: linear-gradient(to top, var(--primary-color), var(--info-color));
  border-radius: 5px 5px 0 0;
  position: relative;
  transition: all 0.3s ease;
  display: flex;
  align-items: end;
  justify-content: center;
}

.bar:hover {
  filter: brightness(1.1);
  transform: scale(1.05);
}

.bar.ingreso-bar {
  background: linear-gradient(to top, var(--success-color), #68d391);
}

.bar.egreso-bar {
  background: linear-gradient(to top, var(--danger-color), #fc8181);
}

.bar-label {
  position: absolute;
  bottom: -25px;
  font-size: 0.9rem;
  color: var(--dark-text);
  font-weight: 500;
}

.bar-value {
  position: absolute;
  top: -25px;
  font-size: 0.8rem;
  color: var(--dark-text);
  font-weight: 600;
  white-space: nowrap;
}

/* Tabla de detalles */
.detalles-section {
  background: white;
  border-radius: 15px;
  padding: 30px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  overflow-x: auto;
}

.tabla-detalles {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.tabla-detalles th {
  background: linear-gradient(135deg, var(--primary-color), #1a365d);
  color: white;
  padding: 15px;
  text-align: center;
  font-weight: 600;
  border: none;
}

.tabla-detalles td {
  padding: 12px 15px;
  text-align: center;
  border-bottom: 1px solid var(--border-color);
}

.tabla-detalles tbody tr:nth-child(even) {
  background-color: #f8f9fa;
}

.tabla-detalles tbody tr:hover {
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

/* Botones */
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

/* Responsive */
@media (max-width: 768px) {
  .resumen-principal {
    grid-template-columns: 1fr;
  }
  
  .graficos-grid {
    grid-template-columns: 1fr;
  }
  
  .chart-bars {
    height: 150px;
  }
  
  .balance-header h1 {
    font-size: 2rem;
  }
}
</style>

@section('content')
<div class="balance-container">
    <!-- Header -->
    <div class="balance-header">
        <h1><i class="bi bi-bar-chart-line"></i> Balance Financiero</h1>
        <p>Resumen completo de la situación financiera de la organización</p>
    </div>

    <!-- Resumen Principal -->
    <div class="resumen-principal">
        <div class="resumen-card ingresos">
            <div class="resumen-icon">
                <i class="bi bi-arrow-up-circle-fill"></i>
            </div>
            <div class="resumen-valor" id="totalIngresos">
                ${{ number_format($totalIngresos ?? 0, 0, ',', '.') }}
            </div>
            <h3 class="resumen-label">Total Ingresos</h3>
        </div>

        <div class="resumen-card egresos">
            <div class="resumen-icon">
                <i class="bi bi-arrow-down-circle-fill"></i>
            </div>
            <div class="resumen-valor" id="totalEgresos">
                ${{ number_format($totalEgresos ?? 0, 0, ',', '.') }}
            </div>
            <h3 class="resumen-label">Total Egresos</h3>
        </div>

        <div class="resumen-card saldo">
            <div class="resumen-icon">
                <i class="bi bi-wallet2"></i>
            </div>
            <div class="resumen-valor" id="saldoFinal">
                ${{ number_format($saldoFinal ?? 0, 0, ',', '.') }}
            </div>
            <h3 class="resumen-label">Saldo Final</h3>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="graficos-section">
        <h2 class="section-title">Análisis Visual</h2>
        
        <div class="graficos-grid">
            <!-- Gráfico de Ingresos vs Egresos -->
            <div class="grafico-card">
                <div class="grafico-header">
                    <h3 class="grafico-title">
                        <i class="bi bi-bar-chart"></i>
                        Ingresos vs Egresos
                    </h3>
                </div>
                <div class="chart-bars">
                    <div class="bar ingreso-bar" style="height: 70%;">
                        <span class="bar-value">${{ number_format($totalIngresos ?? 0, 0, ',', '.') }}</span>
                        <span class="bar-label">Ingresos</span>
                    </div>
                    <div class="bar egreso-bar" style="height: 45%;">
                        <span class="bar-value">${{ number_format($totalEgresos ?? 0, 0, ',', '.') }}</span>
                        <span class="bar-label">Egresos</span>
                    </div>
                </div>
            </div>

            <!-- Gráfico de Saldos de Cuentas -->
            <div class="grafico-card">
                <div class="grafico-header">
                    <h3 class="grafico-title">
                        <i class="bi bi-bank"></i>
                        Distribución de Saldos
                    </h3>
                </div>
                <div class="chart-bars">
                    <div class="bar" style="height: 60%;">
                        <span class="bar-value">${{ number_format($cuentaCajaGeneral->saldo_inicial ?? 0, 0, ',', '.') }}</span>
                        <span class="bar-label">Caja General</span>
                    </div>
                    <div class="bar" style="height: 80%;">
                        <span class="bar-value">${{ number_format($cuentaCorriente1->saldo_inicial ?? 0, 0, ',', '.') }}</span>
                        <span class="bar-label">Cta. Cte. 1</span>
                    </div>
                    <div class="bar" style="height: 35%;">
                        <span class="bar-value">${{ number_format($cuentaCorriente2->saldo_inicial ?? 0, 0, ',', '.') }}</span>
                        <span class="bar-label">Cta. Cte. 2</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Detalles -->
    <div class="detalles-section">
        <h2 class="section-title">Movimientos Recientes</h2>
        
        <table class="tabla-detalles">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Descripción</th>
                    <th>Tipo</th>
                    <th>Monto</th>
                    <th>Saldo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movimientos->take(10) as $movimiento)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($movimiento->fecha)->format('d/m/Y') }}</td>
                    <td style="text-align: left;">{{ $movimiento->descripcion }}</td>
                    <td>
                        @if($movimiento->total_consumo > 0 || $movimiento->cuotas_incorporacion > 0 || $movimiento->otros_ingresos > 0)
                            <span style="color: var(--success-color); font-weight: 600;">Ingreso</span>
                        @else
                            <span style="color: var(--danger-color); font-weight: 600;">Egreso</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $monto = ($movimiento->total_consumo ?? 0) + ($movimiento->cuotas_incorporacion ?? 0) + ($movimiento->otros_ingresos ?? 0) + ($movimiento->giros ?? 0);
                        @endphp
                        <span class="{{ $monto > 0 ? 'monto-positivo' : 'monto-negativo' }}">
                            ${{ number_format($monto, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="monto-positivo">
                        ${{ number_format($saldoFinal ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #718096; padding: 40px;">
                        <i class="bi bi-inbox" style="font-size: 2rem; margin-bottom: 10px; display: block;"></i>
                        No hay movimientos registrados
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Botones de acción -->
    <div style="text-align: center; margin-top: 40px;">
        <a href="{{ route('orgs.dashboard', ['id' => $orgId]) }}" class="btn-balance btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver al Panel
        </a>
        <button class="btn-balance btn-primary" onclick="window.print()">
            <i class="bi bi-printer"></i> Imprimir Reporte
        </button>
    </div>
</div>
@endsection
