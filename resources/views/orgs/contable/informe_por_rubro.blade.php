@extends('layouts.nice', ['active' => 'informe_por_rubro'])

@section('title', 'Informe por Rubro')

{{-- Incluir estilos modernos del módulo contable --}}
@include('orgs.contable.partials.contable-styles')

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

<style>
/* Variables basadas en balance.blade.php */
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
  --shadow-card: 0 4px 12px rgba(0, 0, 0, 0.08);
  --shadow-hover: 0 8px 20px rgba(0, 0, 0, 0.12);
}

/* Contenedor principal estilo balance */
.balance-section {
  padding: 20px;
  max-width: 1400px;
  margin: 0 auto;
  background: var(--light-bg);
}

/* Header de la sección estilo balance */
.section-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 30px;
  background: white;
  padding: 25px;
  border-radius: 15px;
  box-shadow: var(--shadow-card);
}

.section-header h1 {
  color: var(--primary-color);
  font-size: 2.5rem;
  margin: 0;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 15px;
}

.section-header p {
  color: #718096;
  font-size: 1.1rem;
  margin: 0;
  margin-top: 5px;
}

.section-header-actions {
  display: flex;
  gap: 15px;
  align-items: center;
}

/* Tarjetas estilo balance */
.card-balance {
  background: white;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: var(--shadow-card);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  margin-bottom: 25px;
}

.card-balance:hover {
  transform: translateY(-3px);
  box-shadow: var(--shadow-hover);
}

.card-balance.filters {
  border-top: 5px solid var(--info-color);
}

.card-balance.summary {
  border-top: 5px solid var(--primary-color);
}

/* Headers de las tarjetas estilo balance */
.card-header-balance {
  background: linear-gradient(135deg, var(--primary-color) 0%, #1a365d 100%);
  color: white;
  padding: 20px 25px;
  position: relative;
  overflow: hidden;
}

.card-header-balance.filters {
  background: linear-gradient(135deg, var(--info-color) 0%, #2c5282 100%);
}

.card-header-balance.summary {
  background: linear-gradient(135deg, var(--primary-color) 0%, #1a365d 100%);
}

.card-header-balance::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events: none;
}

.card-header-balance h3,
.card-header-balance h4 {
  font-size: 1.3rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 12px;
  margin: 0;
  position: relative;
  z-index: 1;
}

/* Cuerpo de las tarjetas estilo balance */
.card-body-balance {
  padding: 25px;
  background: #fafbfc;
}

/* Grid de formularios estilo balance */
.form-grid-balance {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 20px;
}

.form-grid-balance-2 {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  gap: 30px;
  margin-top: 30px;
}

.form-grid-balance-3 {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
}

/* Grupos de formulario estilo balance */
.form-group-balance {
  margin-bottom: 20px;
}

.form-label-balance {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 8px;
  font-weight: 600;
  color: var(--dark-text);
  font-size: 0.95rem;
}

/* Inputs estilo balance */
.form-input-balance,
.form-select-balance {
  width: 100%;
  padding: 15px 18px;
  border: 2px solid var(--border-color);
  border-radius: 12px;
  font-size: 16px;
  color: var(--dark-text);
  background-color: #f8fafc;
  transition: all 0.3s ease;
  font-weight: 500;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
}

.form-input-balance:focus,
.form-select-balance:focus {
  border-color: var(--primary-color);
  background-color: #fff;
  box-shadow: 0 0 0 4px rgba(44, 82, 130, 0.15);
  outline: none;
  transform: translateY(-2px);
}

.form-input-balance:hover,
.form-select-balance:hover {
  background-color: #fff;
  border-color: #cbd5e0;
  transform: translateY(-1px);
}

/* Botones estilo balance */
.btn-balance-primary {
  background: linear-gradient(135deg, var(--primary-color) 0%, #1a365d 100%);
  color: white;
  border: none;
  border-radius: 12px;
  padding: 12px 25px;
  font-size: 16px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  text-decoration: none;
  position: relative;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(44, 82, 130, 0.3);
}

.btn-balance-outline {
  background: transparent;
  color: var(--primary-color);
  border: 2px solid var(--primary-color);
  border-radius: 12px;
  padding: 12px 25px;
  font-size: 16px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  text-decoration: none;
}

.btn-balance-primary::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
  transition: left 0.6s;
}

.btn-balance-primary:hover,
.btn-balance-outline:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 25px rgba(44, 82, 130, 0.4);
}

.btn-balance-outline:hover {
  background: var(--primary-color);
  color: white;
}

.btn-balance-primary:hover::before {
  left: 100%;
}

/* Contenedor de rubros estilo balance */
.rubro-container-balance {
  padding: 25px;
  background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
  border-radius: 15px;
  border: 2px solid var(--border-color);
  margin-bottom: 20px;
}

.rubro-item-balance {
  background: white;
  border-radius: 12px;
  padding: 20px;
  margin-bottom: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
}

.rubro-item-balance:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.rubro-header-balance {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.rubro-title-balance {
  font-weight: 700;
  color: var(--dark-text);
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 1.1rem;
}

.rubro-value-balance {
  font-weight: 700;
  color: var(--primary-color);
  font-size: 1.3rem;
}

/* Progress bar estilo balance */
.progress-balance {
  width: 100%;
  height: 12px;
  background: #e2e8f0;
  border-radius: 10px;
  overflow: hidden;
  margin: 15px 0;
}

.progress-bar-balance {
  height: 100%;
  background: linear-gradient(135deg, var(--primary-color) 0%, #1a365d 100%);
  border-radius: 10px;
  transition: width 0.6s ease;
  position: relative;
}

.progress-bar-balance::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
  animation: shimmer 2s infinite;
}

@keyframes shimmer {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(100%); }
}

/* Tarjetas de ingresos/egresos estilo balance */
.income-card-balance {
  border-top: 5px solid var(--success-color);
}

.expense-card-balance {
  border-top: 5px solid var(--danger-color);
}

.income-header-balance {
  background: linear-gradient(135deg, var(--success-color) 0%, #388e3c 100%);
  color: white;
  padding: 20px 25px;
  position: relative;
  overflow: hidden;
}

.expense-header-balance {
  background: linear-gradient(135deg, var(--danger-color) 0%, #c53030 100%);
  color: white;
  padding: 20px 25px;
  position: relative;
  overflow: hidden;
}

.income-header-balance::before,
.expense-header-balance::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events: none;
}

/* Lista de rubros estilo balance */
.rubro-list-balance {
  max-height: 350px;
  overflow-y: auto;
  padding-right: 10px;
}

.rubro-list-balance::-webkit-scrollbar {
  width: 6px;
}

.rubro-list-balance::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 3px;
}

.rubro-list-balance::-webkit-scrollbar-thumb {
  background: var(--primary-color);
  border-radius: 3px;
}

.empty-state-balance {
  text-align: center;
  color: #718096;
  padding: 40px 20px;
}

.empty-state-balance i {
  font-size: 3rem;
  opacity: 0.3;
  margin-bottom: 15px;
  display: block;
}

/* Responsivo */
@media (max-width: 768px) {
  .balance-section {
    padding: 15px;
  }

  .section-header {
    padding: 20px;
    flex-direction: column;
    text-align: center;
    gap: 15px;
  }

  .section-header h1 {
    font-size: 2rem;
    flex-direction: column;
    gap: 10px;
  }

  .section-header-actions {
    width: 100%;
    justify-content: center;
  }

  .form-grid-balance,
  .form-grid-balance-2,
  .form-grid-balance-3 {
    grid-template-columns: 1fr;
    gap: 15px;
  }

  .card-header-balance,
  .card-body-balance {
    padding: 15px 20px;
  }

  .btn-balance-primary,
  .btn-balance-outline {
    font-size: 14px;
    padding: 10px 20px;
  }
}

@media (max-width: 480px) {
  .section-header h1 {
    font-size: 1.75rem;
  }

  .card-body-balance {
    padding: 15px;
  }

  .form-input-balance,
  .form-select-balance {
    padding: 12px 15px;
  }

  .rubro-header-balance {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }
}
</style>

@section('content')
{{-- ALERTA: Módulo de movimientos deshabilitado --}}
<div class="alert alert-info" role="alert">
    <i class="bi bi-info-circle"></i>
    <strong>Información:</strong> El módulo de movimientos está temporalmente deshabilitado.
    Las funcionalidades de cuentas y balances básicos siguen disponibles.
</div>
<div class="contable-container">
  <div class="balance-section">
    <!-- Header de la sección estilo balance -->
    <div class="section-header">
      <div>
        <h1>
          <i class="bi bi-pie-chart"></i>
          Informe por Rubro
        </h1>
        <p>Comparación de ingresos y egresos por categorías</p>
      </div>
      <div class="section-header-actions">
        <button class="btn-balance-outline" onclick="exportarInforme()">
          <i class="bi bi-download"></i> Exportar
        </button>
        <button class="btn-balance-primary" onclick="actualizarDatos()">
          <i class="bi bi-arrow-clockwise"></i> Actualizar
        </button>
      </div>
    </div>

    <!-- Filtros de período -->
    <div class="card-balance filters">
      <div class="card-body-balance">
        <div class="form-grid-balance-3">
          <div class="form-group-balance">
            <label for="fechaDesde" class="form-label-balance">
              <i class="bi bi-calendar3"></i>
              Fecha Desde
            </label>
            <input type="date" id="fechaDesde" class="form-input-balance">
          </div>
          <div class="form-group-balance">
            <label for="fechaHasta" class="form-label-balance">
              <i class="bi bi-calendar3"></i>
              Fecha Hasta
            </label>
            <input type="date" id="fechaHasta" class="form-input-balance">
          </div>
          <div class="form-group-balance">
            <label for="tipoRubro" class="form-label-balance">
              <i class="bi bi-tags"></i>
              Tipo de Rubro
            </label>
            <select id="tipoRubro" class="form-select-balance">
              <option value="todos">Todos los rubros</option>
              <option value="operaciones">Operaciones</option>
              <option value="administracion">Administración</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <!-- Resumen principal -->
    <div class="card-balance summary">
      <div class="card-header-balance summary">
        <h3>
          <i class="bi bi-bar-chart"></i>
          Resumen por Rubros
        </h3>
      </div>
      <div class="card-body-balance">
        <div class="rubro-container-balance">
          <div class="rubro-item-balance">
            <div class="rubro-header-balance">
              <div class="rubro-title-balance">
                <i class="bi bi-gear-fill" style="color: var(--primary-color);"></i>
                Operaciones vs. Administración
              </div>
              <div class="rubro-value-balance">$<span id="operacionesTotal">0</span> / $<span id="administracionTotal">0</span></div>
            </div>
            <div class="progress-balance">
              <div class="progress-bar-balance" id="operacionesBar" style="width: 50%"></div>
            </div>
            <div style="display: flex; justify-content: space-between; margin-top: 10px; font-size: 0.85rem; color: #718096;">
              <span>Operaciones</span>
              <span>Administración</span>
            </div>
          </div>
        </div>

        <!-- Detalle por categorías -->
        <div class="form-grid-balance-2">
          <!-- Ingresos por rubro -->
          <div class="card-balance income-card-balance">
            <div class="income-header-balance">
              <h4>
                <i class="bi bi-arrow-up-circle"></i>
                Ingresos por Rubro
              </h4>
            </div>
            <div class="card-body-balance">
              <div id="ingresosRubro" class="rubro-list-balance">
                <div class="empty-state-balance">
                  <i class="bi bi-inbox"></i>
                  <p>No hay datos disponibles</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Egresos por rubro -->
          <div class="card-balance expense-card-balance">
            <div class="expense-header-balance">
              <h4>
                <i class="bi bi-arrow-down-circle"></i>
                Egresos por Rubro
              </h4>
            </div>
            <div class="card-body-balance">
              <div id="egresosRubro" class="rubro-list-balance">
                <div class="empty-state-balance">
                  <i class="bi bi-inbox"></i>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar fechas por defecto
    const hoy = new Date();
    const primerDiaMes = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
    
    document.getElementById('fechaDesde').value = primerDiaMes.toISOString().split('T')[0];
    document.getElementById('fechaHasta').value = hoy.toISOString().split('T')[0];
    
    cargarDatosRubros();
    
    // Event listeners para filtros
    document.getElementById('fechaDesde').addEventListener('change', cargarDatosRubros);
    document.getElementById('fechaHasta').addEventListener('change', cargarDatosRubros);
    document.getElementById('tipoRubro').addEventListener('change', cargarDatosRubros);
});

function cargarDatosRubros() {
    const fechaDesde = document.getElementById('fechaDesde').value;
    const fechaHasta = document.getElementById('fechaHasta').value;
    const tipoRubro = document.getElementById('tipoRubro').value;
    
    // Obtener movimientos del localStorage
    const movimientos = JSON.parse(localStorage.getItem('movimientos')) || [];
    const ingresos = JSON.parse(localStorage.getItem('ingresos')) || [];
    const egresos = JSON.parse(localStorage.getItem('egresos')) || [];
    
    // Filtrar por fechas
    const movimientosFiltrados = [...movimientos, ...ingresos, ...egresos].filter(mov => {
        if (!mov.fecha) return false;
        const fechaMov = new Date(mov.fecha);
        const desde = new Date(fechaDesde);
        const hasta = new Date(fechaHasta);
        return fechaMov >= desde && fechaMov <= hasta;
    });
    
    // Procesar rubros
    const rubrosIngresos = procesarRubrosPorTipo(movimientosFiltrados, 'ingreso');
    const rubrosEgresos = procesarRubrosPorTipo(movimientosFiltrados, 'egreso');
    
    // Actualizar interfaz
    actualizarResumenRubros(rubrosIngresos, rubrosEgresos);
    mostrarRubrosIngresos(rubrosIngresos);
    mostrarRubrosEgresos(rubrosEgresos);
    
    console.log('📊 Datos de rubros actualizados:', { rubrosIngresos, rubrosEgresos });
}

function procesarRubrosPorTipo(movimientos, tipo) {
    const rubros = {};
    
    movimientos.forEach(mov => {
        if (mov.tipo === tipo || (tipo === 'ingreso' && mov.monto > 0) || (tipo === 'egreso' && mov.monto < 0)) {
            const rubro = mov.rubro || mov.categoria || 'Sin categoría';
            if (!rubros[rubro]) {
                rubros[rubro] = {
                    nombre: rubro,
                    total: 0,
                    movimientos: []
                };
            }
            rubros[rubro].total += Math.abs(mov.monto || 0);
            rubros[rubro].movimientos.push(mov);
        }
    });
    
    // Convertir a array y ordenar por total
    return Object.values(rubros).sort((a, b) => b.total - a.total);
}

function actualizarResumenRubros(rubrosIngresos, rubrosEgresos) {
    const totalIngresos = rubrosIngresos.reduce((sum, rubro) => sum + rubro.total, 0);
    const totalEgresos = rubrosEgresos.reduce((sum, rubro) => sum + rubro.total, 0);
    
    // Actualizar totales (ejemplo con operaciones/administración)
    const operacionesTotal = Math.floor(totalIngresos * 0.7); // 70% operaciones
    const administracionTotal = totalIngresos - operacionesTotal;
    
    document.getElementById('operacionesTotal').textContent = operacionesTotal.toLocaleString('es-CL');
    document.getElementById('administracionTotal').textContent = administracionTotal.toLocaleString('es-CL');
    
    // Actualizar barra de progreso
    const porcentajeOperaciones = totalIngresos > 0 ? (operacionesTotal / totalIngresos) * 100 : 50;
    document.getElementById('operacionesBar').style.width = porcentajeOperaciones + '%';
}

function mostrarRubrosIngresos(rubros) {
    const container = document.getElementById('ingresosRubro');
    
    if (rubros.length === 0) {
        container.innerHTML = `
            <div class="empty-state-balance">
                <i class="bi bi-inbox"></i>
                <p>No hay ingresos registrados</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = rubros.map(rubro => `
        <div class="rubro-item-balance">
            <div class="rubro-header-balance">
                <div class="rubro-title-balance">
                    <i class="bi bi-tag-fill" style="color: var(--success-color);"></i>
                    ${rubro.nombre}
                </div>
                <div class="rubro-value-balance" style="color: var(--success-color);">
                    $${rubro.total.toLocaleString('es-CL')}
                </div>
            </div>
            <div style="font-size: 0.85rem; color: #718096;">
                ${rubro.movimientos.length} movimiento${rubro.movimientos.length !== 1 ? 's' : ''}
            </div>
        </div>
    `).join('');
}

function mostrarRubrosEgresos(rubros) {
    const container = document.getElementById('egresosRubro');
    
    if (rubros.length === 0) {
        container.innerHTML = `
            <div class="empty-state-balance">
                <i class="bi bi-inbox"></i>
                <p>No hay egresos registrados</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = rubros.map(rubro => `
        <div class="rubro-item-balance">
            <div class="rubro-header-balance">
                <div class="rubro-title-balance">
                    <i class="bi bi-tag-fill" style="color: var(--danger-color);"></i>
                    ${rubro.nombre}
                </div>
                <div class="rubro-value-balance" style="color: var(--danger-color);">
                    $${rubro.total.toLocaleString('es-CL')}
                </div>
            </div>
            <div style="font-size: 0.85rem; color: #718096;">
                ${rubro.movimientos.length} movimiento${rubro.movimientos.length !== 1 ? 's' : ''}
            </div>
        </div>
    `).join('');
}

function actualizarDatos() {
    cargarDatosRubros();
    
    // Mostrar notificación
    const notification = document.createElement('div');
    notification.className = 'notification-balance success';
    notification.innerHTML = '<i class="bi bi-check-circle"></i> Datos actualizados correctamente';
    notification.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;
        min-width: 350px;
        padding: 20px 25px;
        background: var(--success-color);
        color: white;
        border-radius: 12px;
        font-size: 1.1rem;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        document.body.removeChild(notification);
    }, 3000);
}

function exportarInforme() {
    const notification = document.createElement('div');
    notification.className = 'notification-balance success';
    notification.innerHTML = '<i class="bi bi-download"></i> Funcionalidad de exportación próximamente';
    notification.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;
        min-width: 350px;
        padding: 20px 25px;
        background: var(--info-color);
        color: white;
        border-radius: 12px;
        font-size: 1.1rem;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        document.body.removeChild(notification);
    }, 3000);
}

console.log('📊 Informe por Rubro con estilos del Balance inicializado!');
</script>
@endsection
