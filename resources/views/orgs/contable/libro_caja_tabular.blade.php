@extends('layouts.nice', ['active' => 'orgs.libro.caja', 'title' => 'Libro de Caja Tabular'])

{{-- CSS personalizado para vista tabular --}}
<style>
:root {
  --primary-color: #2c5282;
  --secondary-color: #4CAF50;
  --danger-color: #e53e3e;
  --success-color: #48bb78;
  --warning-color: #dd6b20;
  --purple-color: #805ad5;
  --orange-color: #dd6b20;
  --light-bg: #f8f9fa;
}

.libro-caja-section {
  padding: 20px;
  max-width: 1400px;
  margin: 0 auto;
}

.section-header {
  text-align: center;
  margin: 20px 0 40px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 20px;
}

.section-header h1 {
  color: var(--primary-color);
  font-size: 2.5rem;
  margin-bottom: 10px;
  position: relative;
  display: inline-block;
  padding-bottom: 15px;
}

.section-header h1::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 150px;
  height: 4px;
  background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
  border-radius: 2px;
}

.section-header p {
  color: #4a5568;
  font-size: 1.1rem;
  max-width: 800px;
  margin: 0 auto;
}

.saldo-section {
  background: linear-gradient(135deg, #2c5282, #1a365d);
  border-radius: 12px;
  padding: 25px;
  color: white;
  margin-bottom: 30px;
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
}

.saldo-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
}

.saldo-item {
  background: rgba(255, 255, 255, 0.15);
  padding: 20px;
  border-radius: 10px;
  backdrop-filter: blur(5px);
}

.saldo-item label {
  color: #ebf8ff;
  font-size: 1rem;
  margin-bottom: 8px;
  display: block;
}

.saldo-item input {
  background: rgba(255, 255, 255, 0.2);
  border: 1px solid rgba(255, 255, 255, 0.3);
  color: white;
  font-size: 1.4rem;
  font-weight: 600;
  padding: 12px;
  width: 100%;
  border-radius: 6px;
}

/* TOTALES EN FILA HORIZONTAL */
.totals-container {
  display: flex;
  justify-content: space-between;
  gap: 20px;
  margin-bottom: 30px;
  flex-wrap: wrap;
}

.total-card {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
  text-align: center;
  transition: transform 0.3s ease;
  flex: 1;
  min-width: 280px;
}

.total-card:hover {
  transform: translateY(-5px);
}

.total-card.ingresos {
  border-top: 5px solid var(--success-color);
}

.total-card.egresos {
  border-top: 5px solid var(--danger-color);
}

.total-card.saldo {
  border-top: 5px solid var(--primary-color);
}

.total-card h3 {
  font-size: 1.2rem;
  color: #4a5568;
  margin-bottom: 15px;
}

.total-card .value {
  font-size: 2.2rem;
  font-weight: 700;
}

.total-card.ingresos .value {
  color: var(--success-color);
}

.total-card.egresos .value {
  color: var(--danger-color);
}

.total-card.saldo .value {
  color: var(--primary-color);
}

.table-container {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
  overflow-x: auto;
  margin-bottom: 30px;
}

.table-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
  flex-wrap: wrap;
  gap: 15px;
}

.table-header h2 {
  color: var(--primary-color);
  font-size: 1.6rem;
  font-weight: 600;
}

.filters {
  display: flex;
  gap: 15px;
  flex-wrap: wrap;
}

.filter-group {
  display: flex;
  flex-direction: column;
}

.filter-group label {
  font-size: 0.9rem;
  margin-bottom: 5px;
  color: #4a5568;
}

.filter-group input, .filter-group select {
  padding: 8px 12px;
  border: 1px solid #cbd5e0;
  border-radius: 6px;
  font-size: 14px;
}

table {
  width: 100%;
  border-collapse: collapse;
  min-width: 1000px;
}

thead {
  background: linear-gradient(120deg, var(--primary-color), #1a365d);
  color: white;
}

th {
  padding: 16px 12px;
  text-align: center;
  font-weight: 600;
  border: 1px solid #2c5282;
}

.ingresos-header {
  background: #2c5282;
}

.egresos-header {
  background: #1a365d;
}

tbody tr {
  border-bottom: 1px solid #e2e8f0;
  transition: background-color 0.2s;
}

tbody tr:nth-child(even) {
  background-color: #f8fafc;
}

tbody tr:hover {
  background-color: #ebf8ff;
}

td {
  padding: 14px 10px;
  text-align: center;
  border: 1px solid #e2e8f0;
  font-size: 14px;
}

.ingreso-cell {
  background-color: #f0fff4;
  color: #2f855a;
  font-weight: 500;
}

.egreso-cell {
  background-color: #fff5f5;
  color: #c53030;
  font-weight: 500;
}

.action-button {
  padding: 14px 30px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 16px;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
}

.action-button.volver {
  background: linear-gradient(120deg, #718096, #4a5568);
  color: white;
}

.button-group {
  display: flex;
  justify-content: center;
  gap: 20px;
  margin-top: 30px;
  flex-wrap: wrap;
}
</style>

@section('content')
<div class="libro-caja-section">
    <!-- Header de la sección -->
    <div class="section-header">
        <div>
            <h1>Libro de Caja Tabular</h1>
            <p>Registro diario de todos los movimientos financieros</p>
        </div>
        <a href="{{ route('orgs.dashboard', ['id' => $orgId]) }}" class="action-button volver">
            <i class="bi bi-arrow-left"></i> Volver al Panel
        </a>
    </div>
    
    <!-- Sección de saldos -->
    <div class="saldo-section">
        <div class="saldo-grid">
            <div class="saldo-item">
                <label>Saldo Caja General</label>
                <input type="text" value="${{ number_format($cuentaCajaGeneral->saldo_inicial ?? 0, 0, ',', '.') }}" readonly>
            </div>
            <div class="saldo-item">
                <label>Saldo Cuenta Corriente 1</label>
                <input type="text" value="${{ number_format($cuentaCorriente1->saldo_inicial ?? 0, 0, ',', '.') }}" readonly>
            </div>
            <div class="saldo-item">
                <label>Saldo Cuenta Corriente 2</label>
                <input type="text" value="${{ number_format($cuentaCorriente2->saldo_inicial ?? 0, 0, ',', '.') }}" readonly>
            </div>
            <div class="saldo-item">
                <label>Saldo Total</label>
                <input type="text" value="${{ number_format($saldoFinal ?? 0, 0, ',', '.') }}" readonly>
            </div>
        </div>
    </div>
    
    <!-- TOTALES EN FILA HORIZONTAL -->
    <div class="totals-container">
        <div class="total-card ingresos">
            <h3>Total Ingresos</h3>
            <div class="value">${{ number_format($totalIngresos ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="total-card egresos">
            <h3>Total Egresos</h3>
            <div class="value">${{ number_format($totalEgresos ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="total-card saldo">
            <h3>Saldo Final</h3>
            <div class="value">${{ number_format($saldoFinal ?? 0, 0, ',', '.') }}</div>
        </div>
    </div>
    
    <!-- Tabla de movimientos -->
    <div class="table-container">
        <div class="table-header">
            <h2>Movimientos Financieros</h2>
            <div class="filters">
                <div class="filter-group">
                    <label>Desde</label>
                    <input id="fechaDesde" type="date" value="{{ date('Y-m-01') }}">
                </div>
                <div class="filter-group">
                    <label>Hasta</label>
                    <input id="fechaHasta" type="date" value="{{ date('Y-m-d') }}">
                </div>
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <button class="action-button" style="padding: 10px 15px;">
                        <i class="bi bi-funnel"></i> Filtrar
                    </button>
                </div>
            </div>
        </div>
        
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th colspan="8" class="ingresos-header">Entradas Ingresos</th>
                        <th colspan="9" class="egresos-header">Salidas Egresos</th>
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
                <tbody>
                    @foreach($movimientos as $movimiento)
                    <tr>
                        <td>
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editModal{{ $movimiento->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($movimiento->fecha)->format('d/m/Y') }}</td>
                        <td style="text-align: left;">{{ $movimiento->descripcion }}</td>
                        <td class="{{ $movimiento->total_consumo > 0 ? 'ingreso-cell' : '' }}">
                            {{ $movimiento->total_consumo > 0 ? '$' . number_format($movimiento->total_consumo, 0, ',', '.') : '' }}
                        </td>
                        <td class="{{ $movimiento->cuotas_incorporacion > 0 ? 'ingreso-cell' : '' }}">
                            {{ $movimiento->cuotas_incorporacion > 0 ? '$' . number_format($movimiento->cuotas_incorporacion, 0, ',', '.') : '' }}
                        </td>
                        <td class="{{ $movimiento->otros_ingresos > 0 ? 'ingreso-cell' : '' }}">
                            {{ $movimiento->otros_ingresos > 0 ? '$' . number_format($movimiento->otros_ingresos, 0, ',', '.') : '' }}
                        </td>
                        <td class="{{ $movimiento->giros > 0 ? 'ingreso-cell' : '' }}">
                            {{ $movimiento->giros > 0 ? '$' . number_format($movimiento->giros, 0, ',', '.') : '' }}
                        </td>
                        <td class="ingreso-cell" style="font-weight: bold;">
                            ${{ number_format(($movimiento->total_consumo ?? 0) + ($movimiento->cuotas_incorporacion ?? 0) + ($movimiento->otros_ingresos ?? 0) + ($movimiento->giros ?? 0), 0, ',', '.') }}
                        </td>
                        <td class="{{ ($movimiento->energia_electrica ?? 0) > 0 ? 'egreso-cell' : '' }}">
                            {{ ($movimiento->energia_electrica ?? 0) > 0 ? '$' . number_format($movimiento->energia_electrica, 0, ',', '.') : '' }}
                        </td>
                        <td class="{{ ($movimiento->sueldos_leyes ?? 0) > 0 ? 'egreso-cell' : '' }}">
                            {{ ($movimiento->sueldos_leyes ?? 0) > 0 ? '$' . number_format($movimiento->sueldos_leyes, 0, ',', '.') : '' }}
                        </td>
                        <td class="{{ ($movimiento->otros_gastos_operacion ?? 0) > 0 ? 'egreso-cell' : '' }}">
                            {{ ($movimiento->otros_gastos_operacion ?? 0) > 0 ? '$' . number_format($movimiento->otros_gastos_operacion, 0, ',', '.') : '' }}
                        </td>
                        <td class="{{ ($movimiento->gastos_mantencion ?? 0) > 0 ? 'egreso-cell' : '' }}">
                            {{ ($movimiento->gastos_mantencion ?? 0) > 0 ? '$' . number_format($movimiento->gastos_mantencion, 0, ',', '.') : '' }}
                        </td>
                        <td class="{{ ($movimiento->gastos_administracion ?? 0) > 0 ? 'egreso-cell' : '' }}">
                            {{ ($movimiento->gastos_administracion ?? 0) > 0 ? '$' . number_format($movimiento->gastos_administracion, 0, ',', '.') : '' }}
                        </td>
                        <td class="{{ ($movimiento->gastos_mejoramiento ?? 0) > 0 ? 'egreso-cell' : '' }}">
                            {{ ($movimiento->gastos_mejoramiento ?? 0) > 0 ? '$' . number_format($movimiento->gastos_mejoramiento, 0, ',', '.') : '' }}
                        </td>
                        <td class="{{ ($movimiento->otros_egresos ?? 0) > 0 ? 'egreso-cell' : '' }}">
                            {{ ($movimiento->otros_egresos ?? 0) > 0 ? '$' . number_format($movimiento->otros_egresos, 0, ',', '.') : '' }}
                        </td>
                        <td class="{{ ($movimiento->depositos ?? 0) > 0 ? 'egreso-cell' : '' }}">
                            {{ ($movimiento->depositos ?? 0) > 0 ? '$' . number_format($movimiento->depositos, 0, ',', '.') : '' }}
                        </td>
                        <td class="egreso-cell" style="font-weight: bold;">
                            ${{ number_format(($movimiento->energia_electrica ?? 0) + ($movimiento->sueldos_leyes ?? 0) + ($movimiento->otros_gastos_operacion ?? 0) + ($movimiento->gastos_mantencion ?? 0) + ($movimiento->gastos_administracion ?? 0) + ($movimiento->gastos_mejoramiento ?? 0) + ($movimiento->otros_egresos ?? 0) + ($movimiento->depositos ?? 0), 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Botón de retorno -->
    <div class="button-group">
        <a href="{{ route('orgs.dashboard', ['id' => $orgId]) }}" class="action-button volver">
            <i class="bi bi-arrow-left"></i> Volver al Panel
        </a>
    </div>
</div>
@endsection
