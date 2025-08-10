@extends('layouts.app')

@section('content')
{{-- Incluir estilos modernos del módulo contable --}}
@include('orgs.contable.partials.contable-styles')

<style>
/* === ESTILOS ESPECÍFICOS PARA LIBRO DE CAJA TABULAR === */
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

/* Estilos para la vista de libro de caja */
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

.saldo-item input:focus {
  background: rgba(255, 255, 255, 0.3);
  box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.2);
  outline: none;
}

.saldo-item input[readonly] {
  background: rgba(255, 255, 255, 0.1);
}

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

.action-button.pdf {
  background: linear-gradient(120deg, #e53e3e, #c53030);
  color: white;
}

.action-button.excel {
  background: linear-gradient(120deg, #2e7d32, #1b5e20);
  color: white;
}

.action-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

.button-group {
  display: flex;
  justify-content: center;
  gap: 20px;
  margin-top: 30px;
  flex-wrap: wrap;
}

/* Responsive */
@media (max-width: 768px) {
  .section-header {
    flex-direction: column;
    text-align: center;
  }
  
  .section-header h1 {
    font-size: 2rem;
  }
  
  .filters {
    justify-content: center;
  }
  
  .action-button {
    font-size: 14px;
    padding: 12px 20px;
  }
}
</style>

{{-- Contenido principal --}}
<div class="libro-caja-section">
  {{-- Header de la sección --}}
  <div class="section-header">
    <div>
      <h1>Libro de Caja Tabular</h1>
      <p>Registro diario de todos los movimientos financieros</p>
    </div>
    <a href="{{ route('orgs.dashboard', ['id' => $orgId ?? auth()->user()->org_id]) }}" 
       class="action-button volver">
      <i class="bi bi-arrow-left"></i> Volver al Panel
    </a>
  </div>
  
  {{-- Sección de saldos --}}
  <div class="saldo-section">
    <div class="saldo-grid">
      <div class="saldo-item">
        <label>Saldo Caja General</label>
        <input id="saldoCajaGeneral" type="text" value="${{ number_format($saldoCajaGeneral ?? 0, 0, ',', '.') }}" readonly>
      </div>
      <div class="saldo-item">
        <label>Saldo Cuenta Corriente 1</label>
        <input id="saldoCuentaCorriente1" type="text" value="${{ number_format($saldoCuentaCorriente1 ?? 0, 0, ',', '.') }}" readonly>
      </div>
      <div class="saldo-item">
        <label>Saldo Cuenta Corriente 2</label>
        <input id="saldoCuentaCorriente2" type="text" value="${{ number_format($saldoCuentaCorriente2 ?? 0, 0, ',', '.') }}" readonly>
      </div>
      <div class="saldo-item">
        <label>Saldo Total</label>
        <input id="saldoTotal" type="text" value="${{ number_format($saldoTotal ?? 0, 0, ',', '.') }}" readonly>
      </div>
    </div>
  </div>
   
  {{-- Totales --}}
  <div class="totals-container">
    <div class="total-card ingresos">
      <h3>Total Ingresos</h3>
      <div class="value" id="totalIngresos">${{ number_format($totalIngresos ?? 0, 0, ',', '.') }}</div>
    </div>
    <div class="total-card egresos">
      <h3>Total Egresos</h3>
      <div class="value" id="totalEgresos">${{ number_format($totalEgresos ?? 0, 0, ',', '.') }}</div>
    </div>
    <div class="total-card saldo">
      <h3>Saldo Final</h3>
      <div class="value" id="saldoFinal">${{ number_format(($totalIngresos ?? 0) - ($totalEgresos ?? 0), 0, ',', '.') }}</div>
    </div>
  </div>
  
  {{-- Tabla de movimientos --}}
  <div class="table-container">
    <div class="table-header">
      <h2>Movimientos Financieros</h2>
      <div class="filters">
        <div class="filter-group">
          <label>Desde</label>
          <input id="fechaDesde" type="date">
        </div>
        <div class="filter-group">
          <label>Hasta</label>
          <input id="fechaHasta" type="date">
        </div>
        <div class="filter-group">
          <label>&nbsp;</label>
          <button onclick="filtrarPorFechas()" class="action-button" style="padding: 10px 15px;">
            <i class="bi bi-funnel"></i> Filtrar
          </button>
        </div>
      </div>
    </div>
    
    <div style="overflow-x: auto;">
      <table>
        <thead>
          <tr>
            <th colspan="7" class="ingresos-header">Entradas Ingresos</th>
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
        <tbody id="tablaMovimientos">
          @if(isset($movimientos) && $movimientos->count() > 0)
            @php 
              $saldoAcumulado = 0;
              $totalIngresosAcum = 0;
              $totalEgresosAcum = 0;
            @endphp
            @foreach($movimientos as $movimiento)
              @php
                $totalConsumo = 0;
                $cuotasIncorporacion = 0;
                $otrosIngresos = 0;
                $giros = 0;
                
                $energiaElectrica = 0;
                $sueldosLeyes = 0;
                $otrosGastosOperacion = 0;
                $gastosMantencion = 0;
                $gastosAdministracion = 0;
                $gastosMejoramiento = 0;
                $otrosEgresos = 0;
                $depositos = 0;
                
                if($movimiento->tipo === 'ingreso') {
                  // Clasificar ingresos según categoría o descripción
                  if(str_contains(strtolower($movimiento->descripcion ?? ''), 'consumo')) {
                    $totalConsumo = $movimiento->monto;
                  } elseif(str_contains(strtolower($movimiento->descripcion ?? ''), 'cuota') || str_contains(strtolower($movimiento->descripcion ?? ''), 'incorporacion')) {
                    $cuotasIncorporacion = $movimiento->monto;
                  } elseif(str_contains(strtolower($movimiento->descripcion ?? ''), 'giro')) {
                    $giros = $movimiento->monto;
                  } else {
                    $otrosIngresos = $movimiento->monto;
                  }
                  $totalIngresosAcum += $movimiento->monto;
                  $saldoAcumulado += $movimiento->monto;
                } else {
                  // Clasificar egresos según categoría o descripción
                  if(str_contains(strtolower($movimiento->descripcion ?? ''), 'energia') || str_contains(strtolower($movimiento->descripcion ?? ''), 'electrica')) {
                    $energiaElectrica = $movimiento->monto;
                  } elseif(str_contains(strtolower($movimiento->descripcion ?? ''), 'sueldo') || str_contains(strtolower($movimiento->descripcion ?? ''), 'social')) {
                    $sueldosLeyes = $movimiento->monto;
                  } elseif(str_contains(strtolower($movimiento->descripcion ?? ''), 'mantencion') || str_contains(strtolower($movimiento->descripcion ?? ''), 'mantención')) {
                    $gastosMantencion = $movimiento->monto;
                  } elseif(str_contains(strtolower($movimiento->descripcion ?? ''), 'administracion') || str_contains(strtolower($movimiento->descripcion ?? ''), 'administración')) {
                    $gastosAdministracion = $movimiento->monto;
                  } elseif(str_contains(strtolower($movimiento->descripcion ?? ''), 'mejoramiento')) {
                    $gastosMejoramiento = $movimiento->monto;
                  } elseif(str_contains(strtolower($movimiento->descripcion ?? ''), 'deposito') || str_contains(strtolower($movimiento->descripcion ?? ''), 'depósito')) {
                    $depositos = $movimiento->monto;
                  } else {
                    $otrosEgresos = $movimiento->monto;
                  }
                  $totalEgresosAcum += $movimiento->monto;
                  $saldoAcumulado -= $movimiento->monto;
                }
                
                $totalIngresosLinea = $totalConsumo + $cuotasIncorporacion + $otrosIngresos + $giros;
                $totalEgresosLinea = $energiaElectrica + $sueldosLeyes + $otrosGastosOperacion + $gastosMantencion + $gastosAdministracion + $gastosMejoramiento + $otrosEgresos + $depositos;
              @endphp
              <tr>
                <td>
                  <button class="btn btn-sm btn-info" onclick="editarMovimiento({{ $movimiento->id }})">
                    <i class="bi bi-pencil"></i>
                  </button>
                </td>
                <td>{{ $movimiento->fecha ? $movimiento->fecha->format('d/m/Y') : '-' }}</td>
                <td style="text-align: left; max-width: 200px;">{{ $movimiento->descripcion ?? '-' }}</td>
                
                {{-- Ingresos --}}
                <td class="{{ $totalConsumo > 0 ? 'ingreso-cell' : '' }}">
                  {{ $totalConsumo > 0 ? '$' . number_format($totalConsumo, 0, ',', '.') : '' }}
                </td>
                <td class="{{ $cuotasIncorporacion > 0 ? 'ingreso-cell' : '' }}">
                  {{ $cuotasIncorporacion > 0 ? '$' . number_format($cuotasIncorporacion, 0, ',', '.') : '' }}
                </td>
                <td class="{{ $otrosIngresos > 0 ? 'ingreso-cell' : '' }}">
                  {{ $otrosIngresos > 0 ? '$' . number_format($otrosIngresos, 0, ',', '.') : '' }}
                </td>
                <td class="{{ $giros > 0 ? 'ingreso-cell' : '' }}">
                  {{ $giros > 0 ? '$' . number_format($giros, 0, ',', '.') : '' }}
                </td>
                <td class="{{ $totalIngresosLinea > 0 ? 'ingreso-cell' : '' }}" style="font-weight: bold;">
                  {{ $totalIngresosLinea > 0 ? '$' . number_format($totalIngresosLinea, 0, ',', '.') : '' }}
                </td>
                
                {{-- Egresos --}}
                <td class="{{ $energiaElectrica > 0 ? 'egreso-cell' : '' }}">
                  {{ $energiaElectrica > 0 ? '$' . number_format($energiaElectrica, 0, ',', '.') : '' }}
                </td>
                <td class="{{ $sueldosLeyes > 0 ? 'egreso-cell' : '' }}">
                  {{ $sueldosLeyes > 0 ? '$' . number_format($sueldosLeyes, 0, ',', '.') : '' }}
                </td>
                <td class="{{ $otrosGastosOperacion > 0 ? 'egreso-cell' : '' }}">
                  {{ $otrosGastosOperacion > 0 ? '$' . number_format($otrosGastosOperacion, 0, ',', '.') : '' }}
                </td>
                <td class="{{ $gastosMantencion > 0 ? 'egreso-cell' : '' }}">
                  {{ $gastosMantencion > 0 ? '$' . number_format($gastosMantencion, 0, ',', '.') : '' }}
                </td>
                <td class="{{ $gastosAdministracion > 0 ? 'egreso-cell' : '' }}">
                  {{ $gastosAdministracion > 0 ? '$' . number_format($gastosAdministracion, 0, ',', '.') : '' }}
                </td>
                <td class="{{ $gastosMejoramiento > 0 ? 'egreso-cell' : '' }}">
                  {{ $gastosMejoramiento > 0 ? '$' . number_format($gastosMejoramiento, 0, ',', '.') : '' }}
                </td>
                <td class="{{ $otrosEgresos > 0 ? 'egreso-cell' : '' }}">
                  {{ $otrosEgresos > 0 ? '$' . number_format($otrosEgresos, 0, ',', '.') : '' }}
                </td>
                <td class="{{ $depositos > 0 ? 'egreso-cell' : '' }}">
                  {{ $depositos > 0 ? '$' . number_format($depositos, 0, ',', '.') : '' }}
                </td>
                <td class="{{ $totalEgresosLinea > 0 ? 'egreso-cell' : '' }}" style="font-weight: bold;">
                  {{ $totalEgresosLinea > 0 ? '$' . number_format($totalEgresosLinea, 0, ',', '.') : '' }}
                </td>
              </tr>
            @endforeach
          @else
            <tr>
              <td colspan="17" style="text-align: center; padding: 40px; color: #718096;">
                <i class="bi bi-inbox" style="font-size: 3rem; margin-bottom: 10px; display: block;"></i>
                No hay movimientos registrados
              </td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>
  
  {{-- Botones de acción --}}
  <div class="button-group">
    <button onclick="exportarPDF()" class="action-button pdf">
      <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
    </button>
    <button onclick="exportarExcel()" class="action-button excel">
      <i class="bi bi-file-earmark-excel"></i> Exportar Excel
    </button>
  </div>
</div>

{{-- Scripts --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar fechas por defecto
    const fechaDesde = document.getElementById('fechaDesde');
    const fechaHasta = document.getElementById('fechaHasta');
    
    if (fechaDesde && fechaHasta) {
        const hoy = new Date();
        const primerDiaMes = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
        
        fechaDesde.value = primerDiaMes.toISOString().split('T')[0];
        fechaHasta.value = hoy.toISOString().split('T')[0];
    }
});

function filtrarPorFechas() {
    const fechaDesde = document.getElementById('fechaDesde').value;
    const fechaHasta = document.getElementById('fechaHasta').value;
    
    if (!fechaDesde || !fechaHasta) {
        alert('Por favor selecciona ambas fechas');
        return;
    }
    
    const filas = document.querySelectorAll('#tablaMovimientos tr');
    filas.forEach(fila => {
        const celdaFecha = fila.cells[1]; // Segunda columna es la fecha
        if (celdaFecha) {
            const fechaTexto = celdaFecha.textContent.trim();
            if (fechaTexto && fechaTexto !== '-') {
                const parteFecha = fechaTexto.split('/');
                if (parteFecha.length === 3) {
                    const fechaMovimiento = new Date(parteFecha[2], parteFecha[1] - 1, parteFecha[0]);
                    const desde = new Date(fechaDesde);
                    const hasta = new Date(fechaHasta);
                    
                    if (fechaMovimiento >= desde && fechaMovimiento <= hasta) {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                }
            }
        }
    });
}

function editarMovimiento(id) {
    // Implementar edición de movimiento
    console.log('Editar movimiento:', id);
    alert('Función de edición en desarrollo');
}

function exportarPDF() {
    // Implementar exportación a PDF
    window.print();
}

function exportarExcel() {
    // Implementar exportación a Excel
    const tabla = document.querySelector('table');
    const orgId = {{ $orgId ?? auth()->user()->org_id }};
    
    // Crear formulario para descarga
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/org/${orgId}/libro-caja/exportar-excel`;
    
    // CSRF Token
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    form.appendChild(csrfToken);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
</script>
@endsection
