@extends('layouts.nice')

@section('title', 'Configuración de Cuentas Iniciales')

{{-- Incluir estilos modernos del módulo contable --}}
@include('orgs.contable.partials.contable-styles')

@section('content')

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

/* Tarjeta de configuración principal */
.config-card {
  background: white;
  border-radius: 15px;
  padding: 30px;
  box-shadow: var(--shadow-card);
  margin-bottom: 30px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.config-card:hover {
  transform: translateY(-3px);
  box-shadow: var(--shadow-hover);
}

/* Alerta estilo balance */
.alert-warning-balance {
  background: linear-gradient(135deg, #fff3cd 0%, #fef3cd 100%);
  border: 1px solid #f4c430;
  border-left: 5px solid var(--warning-color);
  border-radius: 12px;
  padding: 20px 25px;
  margin-bottom: 30px;
  display: flex;
  align-items: flex-start;
  gap: 15px;
  box-shadow: var(--shadow-card);
}

.alert-warning-balance .alert-icon {
  color: var(--warning-color);
  font-size: 1.5rem;
  flex-shrink: 0;
  margin-top: 2px;
}

.alert-warning-balance .alert-content {
  color: #8b4513;
  font-weight: 500;
  line-height: 1.6;
}

/* Secciones del formulario estilo balance */
.form-section-balance {
  background: white;
  border-radius: 15px;
  margin-bottom: 25px;
  overflow: hidden;
  box-shadow: var(--shadow-card);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border-top: 5px solid var(--primary-color);
}

.form-section-balance:hover {
  transform: translateY(-3px);
  box-shadow: var(--shadow-hover);
}

.form-section-header-balance {
  background: linear-gradient(135deg, var(--primary-color) 0%, #1a365d 100%);
  color: white;
  padding: 20px 25px;
  position: relative;
  overflow: hidden;
}

.form-section-header-balance::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
  pointer-events: none;
}

.form-section-header-balance h4 {
  font-size: 1.3rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 12px;
  margin: 0;
  position: relative;
  z-index: 1;
}

.form-section-body-balance {
  padding: 25px;
  background: #fafbfc;
}

/* Grid del formulario estilo balance */
.form-grid-balance {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
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

.form-required-balance {
  color: var(--danger-color);
  margin-left: 4px;
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

.form-input-balance:disabled,
.form-select-balance:disabled {
  background-color: #f1f5f9;
  color: #64748b;
  cursor: not-allowed;
  opacity: 0.7;
}

/* Botón principal estilo balance */
.btn-config-primary {
  background: linear-gradient(135deg, var(--secondary-color) 0%, #388e3c 100%);
  color: white;
  border: none;
  border-radius: 12px;
  padding: 18px 35px;
  font-size: 18px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  text-decoration: none;
  position: relative;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
  width: 100%;
  max-width: 400px;
  margin: 0 auto;
}

/* Botón Agregar Cuenta en azul */
.btn-agregar-cuenta-azul {
  background: linear-gradient(135deg, #3182ce 0%, #2c5282 100%);
  color: white;
  border: none;
  border-radius: 10px;
  padding: 12px 25px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  text-decoration: none;
  position: relative;
  overflow: hidden;
  box-shadow: 0 3px 10px rgba(49, 130, 206, 0.3);
  min-width: 160px;
}

.btn-agregar-cuenta-azul::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
  transition: left 0.6s;
}

.btn-agregar-cuenta-azul:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 18px rgba(49, 130, 206, 0.4);
  background: linear-gradient(135deg, #2c5282 0%, #1a365d 100%);
}

.btn-agregar-cuenta-azul:hover::before {
  left: 100%;
}

.btn-agregar-cuenta-azul:active {
  transform: translateY(0px);
}

.btn-agregar-cuenta-azul:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none !important;
}

.btn-config-primary::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
  transition: left 0.6s;
}

.btn-config-primary:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 25px rgba(76, 175, 80, 0.4);
}

.btn-config-primary:hover::before {
  left: 100%;
}

.btn-config-primary:active {
  transform: translateY(-1px);
}

.btn-config-primary:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none !important;
}

/* Acciones del formulario estilo balance */
.form-actions-balance {
  text-align: center;
  padding-top: 30px;
  border-top: 2px solid var(--border-color);
  margin-top: 30px;
}

/* Notificaciones estilo balance */
.notification-balance {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 9999;
  min-width: 350px;
  max-width: 90vw;
  padding: 20px 25px;
  background: var(--success-color);
  color: white;
  border-radius: 12px;
  font-size: 1.1rem;
  text-align: center;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
  opacity: 0.97;
  transition: all 0.3s ease;
  display: none;
  align-items: center;
  justify-content: center;
  gap: 12px;
}

.notification-balance.error {
  background: var(--danger-color);
}

.notification-balance.success {
  background: var(--success-color);
}

.notification-balance.info {
  background: var(--info-color);
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
    gap: 10px;
  }

  .section-header h1 {
    font-size: 2rem;
    flex-direction: column;
    gap: 10px;
  }

  .config-card {
    padding: 20px;
  }

  .form-section-header-balance,
  .form-section-body-balance {
    padding: 15px 20px;
  }

  .form-grid-balance {
    grid-template-columns: 1fr;
    gap: 15px;
  }

  .btn-config-primary {
    font-size: 16px;
    padding: 15px 25px;
  }
}

@media (max-width: 480px) {
  .section-header h1 {
    font-size: 1.75rem;
  }

  .config-card {
    padding: 15px;
  }

  .form-input-balance,
  .form-select-balance {
    padding: 12px 15px;
  }
}
</style>

<div class="contable-container">
  <div class="balance-section">
    <!-- Header de la sección estilo balance -->
    <div class="section-header">
      <div>
        <h1>
          <i class="bi bi-journal-plus"></i>
          Configuración de Cuentas Iniciales
        </h1>
        <p>Configure los saldos iniciales de sus cuentas bancarias</p>
      </div>
    </div>

    <!-- Tarjeta de configuración principal -->
    <div class="config-card">
      <form id="cuentasInicialesForm" method="POST" action="#">
        @csrf
        <!-- Alerta estilo balance -->
        <div class="alert-warning-balance">
          <div class="alert-icon">
            <i class="bi bi-exclamation-triangle"></i>
          </div>
          <div class="alert-content">
            <strong>Atención:</strong> ¿Está seguro(a) de guardar los cambios? Esta operación solo se podrá realizar una sola vez.
          </div>
        </div>
        
        <!-- Sección: Caja General estilo balance -->
        <div class="form-section-balance" data-tipo="caja_general">
          <div class="form-section-header-balance">
            <h4>
              <i class="bi bi-cash-stack"></i>
              Saldo Inicial de Caja General
            </h4>
          </div>
          <div class="form-section-body-balance">
            <div class="form-grid-balance">
              <div class="form-group-balance">
                <label for="saldo-caja-general" class="form-label-balance">
                  <i class="bi bi-currency-dollar"></i>
                  Saldo Caja General (Efectivo)
                </label>
                <input type="number" id="saldo-caja-general" name="saldo_caja_general" 
                       class="form-input-balance" step="0.01" min="0" required>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Sección: Cuenta Corriente 1 estilo balance -->
        <div class="form-section-balance" data-tipo="cuenta_corriente" data-cuenta-id="cuenta_corriente_1">
          <div class="form-section-header-balance">
            <h4>
              <i class="bi bi-bank2"></i>
              Cuenta Corriente
            </h4>
          </div>
          <div class="form-section-body-balance">
            <div class="form-grid-balance">
              <div class="form-group-balance">
                <label for="saldo-cta-corriente-1" class="form-label-balance">
                  <i class="bi bi-currency-dollar"></i>
                  Saldo Cuenta Corriente
                </label>
                <input type="number" id="saldo-cta-corriente-1" name="saldo_cta_corriente_1" 
                       class="form-input-balance" step="0.01" min="0" required>
              </div>
              
              <div class="form-group-balance">
                <label for="banco-cta-corriente-1" class="form-label-balance">
                  <i class="bi bi-bank"></i>
                  Banco
                </label>
                <select id="banco-cta-corriente-1" name="banco_cta_corriente_1" class="form-select-balance">
                  <option value="">Sin banco</option>
                  @if(isset($bancos) && $bancos->count() > 0)
                    @foreach($bancos as $banco)
                      <option value="{{ $banco->codigo }}">{{ $banco->nombre }}</option>
                    @endforeach
                  @else
                    <!-- Fallback en caso de que no haya datos de bancos -->
                    <option value="banco_estado">Banco Estado</option>
                    <option value="banco_chile">Banco de Chile</option>
                    <option value="banco_bci">BCI</option>
                    <option value="banco_santander">Santander</option>
                    <option value="banco_itau">Itaú</option>
                    <option value="banco_scotiabank">Scotiabank</option>
                    <option value="banco_bice">BICE</option>
                    <option value="banco_security">Security</option>
                    <option value="banco_falabella">Falabella</option>
                    <option value="banco_ripley">Ripley</option>
                    <option value="banco_consorcio">Consorcio</option>
                    <option value="otro">Otro</option>
                  @endif
                </select>
              </div>
              
              <div class="form-group-balance">
                <label for="numero-cta-corriente-1" class="form-label-balance">
                  <i class="bi bi-credit-card"></i>
                  Número de Cuenta
                </label>
                <input type="text" id="numero-cta-corriente-1" name="numero_cta_corriente_1" 
                       class="form-input-balance">
              </div>
            </div>
            
            <!-- Botón Agregar Cuenta -->
            <div style="margin-top: 20px; text-align: center;">
              <button type="button" class="btn-agregar-cuenta-azul" onclick="agregarNuevaCuenta('corriente')">
                <i class="bi bi-plus-circle"></i>
                Agregar Otra Cuenta Corriente
              </button>
            </div>
          </div>
        </div>
        
        <!-- Sección: Cuenta de Ahorro estilo balance -->
        <div class="form-section-balance" data-tipo="cuenta_ahorro" data-cuenta-id="cuenta_ahorro">
          <div class="form-section-header-balance">
            <h4>
              <i class="bi bi-piggy-bank"></i>
              Cuenta de Ahorro
            </h4>
          </div>
          <div class="form-section-body-balance">
            <div class="form-grid-balance">
              <div class="form-group-balance">
                <label for="saldo-cuenta-ahorro" class="form-label-balance">
                  <i class="bi bi-currency-dollar"></i>
                  Saldo Cuenta de Ahorro
                </label>
                <input type="number" id="saldo-cuenta-ahorro" name="saldo_cuenta_ahorro" 
                       class="form-input-balance" step="0.01" min="0" required>
              </div>
              
              <div class="form-group-balance">
                <label for="banco-cuenta-ahorro" class="form-label-balance">
                  <i class="bi bi-bank"></i>
                  Banco
                </label>
                <select id="banco-cuenta-ahorro" name="banco_cuenta_ahorro" class="form-select-balance">
                  <option value="">Sin banco</option>
                  @if(isset($bancos) && $bancos->count() > 0)
                    @foreach($bancos as $banco)
                      <option value="{{ $banco->codigo }}">{{ $banco->nombre }}</option>
                    @endforeach
                  @else
                    <!-- Fallback en caso de que no haya datos de bancos -->
                    <option value="banco_estado">Banco Estado</option>
                    <option value="banco_chile">Banco de Chile</option>
                    <option value="banco_bci">BCI</option>
                    <option value="banco_santander">Santander</option>
                    <option value="banco_itau">Itaú</option>
                    <option value="banco_scotiabank">Scotiabank</option>
                    <option value="banco_bice">BICE</option>
                    <option value="banco_security">Security</option>
                    <option value="banco_falabella">Falabella</option>
                    <option value="banco_ripley">Ripley</option>
                    <option value="banco_consorcio">Consorcio</option>
                    <option value="otro">Otro</option>
                  @endif
                </select>
              </div>
              
              <div class="form-group-balance">
                <label for="numero-cuenta-ahorro" class="form-label-balance">
                  <i class="bi bi-credit-card"></i>
                  Número de Cuenta
                </label>
                <input type="text" id="numero-cuenta-ahorro" name="numero_cuenta_ahorro" 
                       class="form-input-balance">
              </div>
            </div>
            
            <!-- Botón Agregar Cuenta -->
            <div style="margin-top: 20px; text-align: center;">
              <button type="button" class="btn-agregar-cuenta-azul" onclick="agregarNuevaCuenta('ahorro')">
                <i class="bi bi-plus-circle"></i>
                Agregar Otra Cuenta de Ahorro
              </button>
            </div>
          </div>
        </div>
        
        <!-- Sección: Responsable estilo balance -->
        <div class="form-section-balance" data-tipo="responsable">
          <div class="form-section-header-balance">
            <h4>
              <i class="bi bi-person-check"></i>
              Información del Responsable
            </h4>
          </div>
          <div class="form-section-body-balance">
            <div class="form-group-balance">
              <label for="responsable" class="form-label-balance">
                <i class="bi bi-person"></i>
                Nombre Responsable
                <span class="form-required-balance">*</span>
              </label>
              <input type="text" id="responsable" name="responsable" 
                     class="form-input-balance" required>
            </div>
          </div>
        </div>
        
        <!-- Botón de envío estilo balance -->
        <div class="form-actions-balance">
          <!-- BOTÓN TEMPORAL: Limpiar localStorage -->
          <button type="button" onclick="limpiarTodo()" style="background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 8px; margin-right: 15px; cursor: pointer;">
            🧹 Limpiar Todo & Recargar
          </button>
          
          <!-- BOTÓN ESPECIAL: Actualizar solo bancos -->
          <button type="button" onclick="actualizarSoloBancos()" style="background: #ffc107; color: #000; border: none; padding: 10px 20px; border-radius: 8px; margin-right: 15px; cursor: pointer;">
            🏦 Actualizar Solo Bancos
          </button>
          
          <button type="submit" class="btn-config-primary">
            <i class="bi bi-save"></i>
            Guardar Cuentas Iniciales
          </button>
        </div>
      </form>
    </div>

    <!-- Notification div -->
    <div id="notification" class="notification-balance"></div>
  </div>
</div>

<script>
// Variables globales
let saldosCuentas = {
  caja_general: 0,
  cuenta_corriente_1: 0,
  cuenta_ahorro: 0
};

let accountDetails = {
  caja_general: { banco: '', numero: '' },
  cuenta_corriente_1: { banco: '', numero: '' },
  cuenta_ahorro: { banco: '', numero: '' }
};

let initialAccountsSet = false;
let contadorCuentasCorrientes = 1; // Contador para cuentas corrientes adicionales
let contadorCuentasAhorro = 1; // Contador para cuentas de ahorro adicionales

// Función para agregar nueva cuenta
function agregarNuevaCuenta(tipoCuenta) {
  console.log('Agregando nueva cuenta del tipo:', tipoCuenta);
  
  if (tipoCuenta === 'corriente') {
    contadorCuentasCorrientes++;
    crearSeccionCuentaCorriente(contadorCuentasCorrientes);
  } else if (tipoCuenta === 'ahorro') {
    contadorCuentasAhorro++;
    crearSeccionCuentaAhorro(contadorCuentasAhorro);
  }
  
  mostrarNotificacion(`Nueva cuenta ${tipoCuenta} agregada`, 'success');
}

// Función para crear nueva sección de cuenta corriente
function crearSeccionCuentaCorriente(numero) {
  const cuentaId = `cuenta_corriente_${numero}`;
  
  // Buscar el contenedor donde insertar la nueva sección (después de la última cuenta corriente)
  const ultimaCuentaCorriente = document.querySelector('[data-tipo="cuenta_corriente"]:last-of-type') || 
                                document.querySelector('[data-tipo="cuenta_corriente"]');
  
  const nuevaSeccion = document.createElement('div');
  nuevaSeccion.setAttribute('data-tipo', 'cuenta_corriente');
  nuevaSeccion.className = 'form-section-balance';
  nuevaSeccion.setAttribute('data-cuenta-id', cuentaId);
  
  nuevaSeccion.innerHTML = `
    <div class="form-section-header-balance">
      <h4>
        <i class="bi bi-bank2"></i>
        Cuenta Corriente ${numero}
        <button type="button" class="btn btn-sm btn-danger float-end" onclick="eliminarCuenta('${cuentaId}', this)" style="font-size: 12px; padding: 4px 8px; margin-left: auto;">
          <i class="bi bi-trash"></i> Eliminar
        </button>
      </h4>
    </div>
    <div class="form-section-body-balance">
      <div class="form-grid-balance">
        <div class="form-group-balance">
          <label for="saldo-${cuentaId}" class="form-label-balance">
            <i class="bi bi-currency-dollar"></i>
            Saldo Cuenta Corriente ${numero}
          </label>
          <input type="number" id="saldo-${cuentaId}" name="saldo_${cuentaId}" 
                 class="form-input-balance" step="0.01" min="0" required>
        </div>
        
        <div class="form-group-balance">
          <label for="banco-${cuentaId}" class="form-label-balance">
            <i class="bi bi-bank"></i>
            Banco
          </label>
          <select id="banco-${cuentaId}" name="banco_${cuentaId}" class="form-select-balance">
            <option value="">Sin banco</option>
            ${generarOpcionesBanco()}
          </select>
        </div>
        
        <div class="form-group-balance">
          <label for="numero-${cuentaId}" class="form-label-balance">
            <i class="bi bi-credit-card"></i>
            Número de Cuenta
          </label>
          <input type="text" id="numero-${cuentaId}" name="numero_${cuentaId}" 
                 class="form-input-balance">
        </div>
      </div>
      
      <!-- Botón Agregar Cuenta Corriente -->
      <div style="margin-top: 20px; text-align: center;">
        <button type="button" class="btn-agregar-cuenta-azul" onclick="agregarNuevaCuenta('corriente')">
          <i class="bi bi-plus-circle"></i>
          Agregar Otra Cuenta Corriente
        </button>
      </div>
    </div>
  `;
  
  // Insertar después de la última cuenta corriente
  if (ultimaCuentaCorriente) {
    ultimaCuentaCorriente.parentNode.insertBefore(nuevaSeccion, ultimaCuentaCorriente.nextSibling);
  } else {
    // Si no hay cuentas corrientes, insertar después de caja general
    const cajaGeneral = document.querySelector('[data-tipo="caja_general"]') || 
                       document.querySelector('.form-section-balance');
    cajaGeneral.parentNode.insertBefore(nuevaSeccion, cajaGeneral.nextSibling);
  }
  
  // Actualizar variables globales
  saldosCuentas[cuentaId] = 0;
  accountDetails[cuentaId] = { banco: '', numero: '' };
  
  // Configurar auto-guardado para la nueva cuenta
  configurarAutoGuardadoCuenta(cuentaId);
}

// Función para crear nueva sección de cuenta de ahorro
function crearSeccionCuentaAhorro(numero) {
  const cuentaId = `cuenta_ahorro_${numero}`;
  
  // Buscar el contenedor donde insertar la nueva sección (después de la última cuenta de ahorro)
  const ultimaCuentaAhorro = document.querySelector('[data-tipo="cuenta_ahorro"]:last-of-type') || 
                            document.querySelector('[data-tipo="cuenta_ahorro"]');
  
  const nuevaSeccion = document.createElement('div');
  nuevaSeccion.setAttribute('data-tipo', 'cuenta_ahorro');
  nuevaSeccion.className = 'form-section-balance';
  nuevaSeccion.setAttribute('data-cuenta-id', cuentaId);
  
  nuevaSeccion.innerHTML = `
    <div class="form-section-header-balance">
      <h4>
        <i class="bi bi-piggy-bank"></i>
        Cuenta de Ahorro ${numero}
        <button type="button" class="btn btn-sm btn-danger float-end" onclick="eliminarCuenta('${cuentaId}', this)" style="font-size: 12px; padding: 4px 8px; margin-left: auto;">
          <i class="bi bi-trash"></i> Eliminar
        </button>
      </h4>
    </div>
    <div class="form-section-body-balance">
      <div class="form-grid-balance">
        <div class="form-group-balance">
          <label for="saldo-${cuentaId}" class="form-label-balance">
            <i class="bi bi-currency-dollar"></i>
            Saldo Cuenta de Ahorro ${numero}
          </label>
          <input type="number" id="saldo-${cuentaId}" name="saldo_${cuentaId}" 
                 class="form-input-balance" step="0.01" min="0" required>
        </div>
        
        <div class="form-group-balance">
          <label for="banco-${cuentaId}" class="form-label-balance">
            <i class="bi bi-bank"></i>
            Banco
          </label>
          <select id="banco-${cuentaId}" name="banco_${cuentaId}" class="form-select-balance">
            <option value="">Sin banco</option>
            ${generarOpcionesBanco()}
          </select>
        </div>
        
        <div class="form-group-balance">
          <label for="numero-${cuentaId}" class="form-label-balance">
            <i class="bi bi-credit-card"></i>
            Número de Cuenta
          </label>
          <input type="text" id="numero-${cuentaId}" name="numero_${cuentaId}" 
                 class="form-input-balance">
        </div>
      </div>
      
      <!-- Botón Agregar Cuenta -->
      <div style="margin-top: 20px; text-align: center;">
        <button type="button" class="btn-agregar-cuenta-azul" onclick="agregarNuevaCuenta('ahorro')">
          <i class="bi bi-plus-circle"></i>
          Agregar Otra Cuenta de Ahorro
        </button>
      </div>
    </div>
  `;
  
  // Insertar después de la última cuenta de ahorro
  if (ultimaCuentaAhorro) {
    ultimaCuentaAhorro.parentNode.insertBefore(nuevaSeccion, ultimaCuentaAhorro.nextSibling);
  } else {
    // Si no hay cuentas de ahorro, insertar antes de la sección de responsable
    const seccionResponsable = document.querySelector('[data-tipo="responsable"]') || 
                              document.querySelector('.form-section-balance:last-of-type');
    seccionResponsable.parentNode.insertBefore(nuevaSeccion, seccionResponsable);
  }
  
  // Actualizar variables globales
  saldosCuentas[cuentaId] = 0;
  accountDetails[cuentaId] = { banco: '', numero: '' };
  
  // Configurar auto-guardado para la nueva cuenta
  configurarAutoGuardadoCuenta(cuentaId);
}

// Función para generar opciones de banco
function generarOpcionesBanco() {
  const bancos = @json(isset($bancos) ? $bancos : []);
  let opciones = '';
  
  if (bancos && bancos.length > 0) {
    bancos.forEach(banco => {
      opciones += `<option value="${banco.codigo}">${banco.nombre}</option>`;
    });
  } else {
    // Fallback en caso de que no haya datos de bancos
    const bancosFallback = [
      { codigo: 'banco_estado', nombre: 'Banco Estado' },
      { codigo: 'banco_chile', nombre: 'Banco de Chile' },
      { codigo: 'banco_bci', nombre: 'BCI' },
      { codigo: 'banco_santander', nombre: 'Santander' },
      { codigo: 'banco_itau', nombre: 'Itaú' },
      { codigo: 'banco_scotiabank', nombre: 'Scotiabank' },
      { codigo: 'banco_bice', nombre: 'BICE' },
      { codigo: 'banco_security', nombre: 'Security' },
      { codigo: 'banco_falabella', nombre: 'Falabella' },
      { codigo: 'banco_ripley', nombre: 'Ripley' },
      { codigo: 'banco_consorcio', nombre: 'Consorcio' },
      { codigo: 'otro', nombre: 'Otro' }
    ];
    
    bancosFallback.forEach(banco => {
      opciones += `<option value="${banco.codigo}">${banco.nombre}</option>`;
    });
  }
  
  return opciones;
}

// Función para guardar cuenta individual automáticamente
function guardarCuentaIndividual(tipoCuenta, elemento) {
  const orgId = window.location.pathname.split('/')[2];
  
  // Determinar el prefijo del ID basado en el tipo de cuenta
  let prefijo = '';
  if (tipoCuenta === 'caja_general') {
    prefijo = 'caja-general';
  } else if (tipoCuenta === 'cuenta_corriente_1') {
    prefijo = 'cta-corriente-1';
  } else if (tipoCuenta === 'cuenta_ahorro') {
    prefijo = 'cuenta-ahorro';
  } else {
    // Para cuentas dinámicas
    prefijo = tipoCuenta.replace(/_/g, '-');
  }
  
  // Recopilar datos de la cuenta específica
  const saldo = document.getElementById(`saldo-${prefijo}`)?.value || 
                document.getElementById(`saldo-${tipoCuenta}`)?.value || 0;
  const banco = document.getElementById(`banco-${prefijo}`)?.value || 
                document.getElementById(`banco-${tipoCuenta}`)?.value || '';
  const numero = document.getElementById(`numero-${prefijo}`)?.value || 
                 document.getElementById(`numero-${tipoCuenta}`)?.value || '';
  const responsable = document.getElementById('responsable')?.value || '';
  
  // Usar el formato tradicional que espera el controlador
  const formData = new FormData();
  formData.append('orgId', orgId);
  formData.append('_token', '{{ csrf_token() }}');
  
  // Enviar en el formato que espera el controlador original
  if (tipoCuenta === 'caja_general') {
    formData.append('saldo_caja_general', saldo);
    formData.append('responsable_caja_general', responsable);
  } else if (tipoCuenta === 'cuenta_corriente_1') {
    formData.append('saldo_cta_corriente_1', saldo);
    formData.append('banco_cta_corriente_1', banco);
    formData.append('numero_cta_corriente_1', numero);
    formData.append('responsable_cta_corriente_1', responsable);
  } else if (tipoCuenta === 'cuenta_ahorro') {
    formData.append('saldo_cuenta_ahorro', saldo);
    formData.append('banco_cuenta_ahorro', banco);
    formData.append('numero_cuenta_ahorro', numero);
    formData.append('responsable_cuenta_ahorro', responsable);
  }
  
  console.log('Guardando cuenta individual:', {
    tipo: tipoCuenta,
    saldo: saldo,
    banco: banco,
    numero: numero,
    responsable: responsable
  });
  
  // Enviar al backend con el mismo endpoint
  fetch(`/org/${orgId}/cuentas-iniciales`, {
    method: 'POST',
    body: formData,
    headers: {
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
  .then(response => {
    console.log('Respuesta del servidor:', response.status);
    if (response.ok) {
      return response.json().catch(() => ({ success: true }));
    } else {
      return response.text().then(text => {
        console.error('Error del servidor:', text);
        throw new Error('Error en la respuesta del servidor: ' + response.status);
      });
    }
  })
  .then(data => {
    console.log('Datos guardados exitosamente:', data);
    
    // Actualizar variables globales
    saldosCuentas[tipoCuenta] = parseFloat(saldo) || 0;
    accountDetails[tipoCuenta] = {
      banco: banco,
      numero: numero
    };
    
    // Actualizar localStorage
    localStorage.setItem('saldosCuentas', JSON.stringify(saldosCuentas));
    guardarDetallesCuentas();
    
    // Mostrar indicador visual de guardado
    mostrarIndicadorGuardado(elemento);
    
    console.log('Cuenta guardada exitosamente:', tipoCuenta);
  })
  .catch(error => {
    console.error('Error al guardar cuenta individual:', error);
    mostrarNotificacion('Error al guardar: ' + error.message, 'error');
  });
}

// Función para mostrar indicador visual de guardado
function mostrarIndicadorGuardado(elemento) {
  // Crear o encontrar el indicador de guardado
  let indicador = elemento.parentNode.querySelector('.guardado-indicator');
  
  if (!indicador) {
    indicador = document.createElement('span');
    indicador.className = 'guardado-indicator';
    indicador.style.cssText = `
      color: #48bb78;
      font-size: 12px;
      margin-left: 8px;
      opacity: 0;
      transition: opacity 0.3s ease;
    `;
    indicador.innerHTML = '<i class="bi bi-check-circle"></i> Guardado';
    elemento.parentNode.appendChild(indicador);
  }
  
  // Mostrar el indicador
  indicador.style.opacity = '1';
  
  // Ocultar después de 2 segundos
  setTimeout(() => {
    indicador.style.opacity = '0';
  }, 2000);
}

// Función para configurar listeners de auto-guardado
function configurarAutoGuardado() {
  // DESACTIVADO TEMPORALMENTE - Activar después de verificar que funciona el guardado completo
  console.log('Auto-guardado desactivado temporalmente para debugging');
  return;
  
  // Obtener todos los inputs del formulario
  const inputs = document.querySelectorAll('#cuentasInicialesForm input, #cuentasInicialesForm select');
  
  inputs.forEach(input => {
    // Determinar el tipo de cuenta basado en el ID del input
    let tipoCuenta = '';
    const inputId = input.id;
    
    if (inputId.includes('caja-general')) {
      tipoCuenta = 'caja_general';
    } else if (inputId.includes('cta-corriente-1')) {
      tipoCuenta = 'cuenta_corriente_1';
    } else if (inputId.includes('cuenta-ahorro') && !inputId.includes('cuenta_ahorro_')) {
      tipoCuenta = 'cuenta_ahorro';
    } else if (inputId.includes('cuenta_corriente_') || inputId.includes('cuenta_ahorro_')) {
      // Para cuentas dinámicas, extraer el tipo del ID
      const matches = inputId.match(/(cuenta_corriente_\d+|cuenta_ahorro_\d+)/);
      if (matches) {
        tipoCuenta = matches[1];
      }
    }
    
    if (tipoCuenta && inputId !== 'responsable') {
      // Configurar evento de guardado automático (con debounce)
      let timeoutId;
      
      input.addEventListener('input', function() {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
          guardarCuentaIndividual(tipoCuenta, this);
        }, 1000); // Esperar 1 segundo después de que el usuario deje de escribir
      });
      
      input.addEventListener('change', function() {
        clearTimeout(timeoutId);
        guardarCuentaIndividual(tipoCuenta, this);
      });
    }
  });
  
  // Configurar evento especial para el campo responsable (afecta todas las cuentas)
  const responsableInput = document.getElementById('responsable');
  if (responsableInput) {
    let timeoutId;
    
    responsableInput.addEventListener('input', function() {
      clearTimeout(timeoutId);
      timeoutId = setTimeout(() => {
        guardarTodasLasCuentas();
      }, 1000);
    });
    
    responsableInput.addEventListener('change', function() {
      clearTimeout(timeoutId);
      guardarTodasLasCuentas();
    });
  }
}

// Función para eliminar cuenta
function eliminarCuenta(cuentaId, botonElement) {
  if (confirm('¿Está seguro de eliminar esta cuenta?')) {
    // Encontrar el elemento padre de la sección
    const seccionCuenta = botonElement.closest('.form-section-balance');
    
    // Eliminar de la base de datos
    const orgId = window.location.pathname.split('/')[2];
    const formData = new FormData();
    formData.append('orgId', orgId);
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('cuenta_eliminar', cuentaId);
    formData.append('accion', 'eliminar_cuenta');
    
    fetch(`/org/${orgId}/cuentas-iniciales`, {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.json().catch(() => ({ success: true })))
    .then(data => {
      // Eliminar de las variables globales
      delete saldosCuentas[cuentaId];
      delete accountDetails[cuentaId];
      
      // Eliminar del DOM
      seccionCuenta.remove();
      
      // Actualizar localStorage
      localStorage.setItem('saldosCuentas', JSON.stringify(saldosCuentas));
      guardarDetallesCuentas();
      
      mostrarNotificacion('Cuenta eliminada correctamente', 'info');
    })
    .catch(error => {
      console.error('Error al eliminar cuenta:', error);
      mostrarNotificacion('Error al eliminar cuenta: ' + error.message, 'error');
    });
  }
}

// Función para configurar auto-guardado en nuevas cuentas dinámicas
function configurarAutoGuardadoCuenta(cuentaId) {
  // AUTO-GUARDADO DESACTIVADO - Solo guardado manual con botón
  console.log('Auto-guardado desactivado para:', cuentaId);
}

// Función para mostrar notificaciones
function mostrarNotificacion(mensaje, tipo = 'success') {
  const notification = document.getElementById('notification');
  notification.textContent = mensaje;
  notification.className = `notification-balance ${tipo}`;
  notification.style.display = 'flex';
  
  setTimeout(() => {
    notification.style.display = 'none';
  }, 3000);
}

// Función para guardar detalles de cuentas
function guardarDetallesCuentas() {
  localStorage.setItem('accountDetails', JSON.stringify(accountDetails));
}

// Función para cargar datos guardados
function cargarDatosGuardados() {
  // Primero intentar cargar desde datos del servidor (PHP a JavaScript)
  @if(isset($configuraciones) && $configuraciones->count() > 0)
    const configuracionesServidor = @json($configuraciones);
    console.log('Configuraciones encontradas en servidor:', configuracionesServidor);
    
    // DEBUG: Mostrar detalles de cada configuración
    configuracionesServidor.forEach((config, index) => {
      console.log(`📋 Config ${index}:`, {
        tipo: config.cuenta ? config.cuenta.tipo : 'SIN TIPO',
        banco: config.banco,
        numero_cuenta: config.numero_cuenta,
        saldo_inicial: config.saldo_inicial
      });
    });
    
    // Verificar si hay configuraciones guardadas en el servidor
    if (configuracionesServidor && configuracionesServidor.length > 0) {
      // Marcar como ya configurado
      initialAccountsSet = true;
      
      // Cargar cada configuración
      configuracionesServidor.forEach(config => {
        const tipocuenta = config.cuenta.tipo;
        
        switch(tipocuenta) {
          case 'caja_general':
            document.getElementById('saldo-caja-general').value = config.saldo_inicial || 0;
            if (config.responsable) document.getElementById('responsable').value = config.responsable;
            saldosCuentas.caja_general = parseFloat(config.saldo_inicial) || 0;
            accountDetails.caja_general = { banco: '', numero: '' };
            break;
            
          case 'cuenta_corriente_1':
            document.getElementById('saldo-cta-corriente-1').value = config.saldo_inicial || 0;
            document.getElementById('banco-cta-corriente-1').value = config.banco || '';
            document.getElementById('numero-cta-corriente-1').value = config.numero_cuenta || '';
            saldosCuentas.cuenta_corriente_1 = parseFloat(config.saldo_inicial) || 0;
            accountDetails.cuenta_corriente_1 = { banco: config.banco || '', numero: config.numero_cuenta || '' };
            break;
            
          case 'cuenta_ahorro':
            document.getElementById('saldo-cuenta-ahorro').value = config.saldo_inicial || 0;
            document.getElementById('banco-cuenta-ahorro').value = config.banco || '';
            document.getElementById('numero-cuenta-ahorro').value = config.numero_cuenta || '';
            saldosCuentas.cuenta_ahorro = parseFloat(config.saldo_inicial) || 0;
            accountDetails.cuenta_ahorro = { banco: config.banco || '', numero: config.numero_cuenta || '' };
            break;
        }
      });
      
      // Mantener formulario habilitado para permitir modificaciones
      // Los inputs permanecen activos para edición
      
      // Actualizar localStorage con datos del servidor
      localStorage.setItem('saldosCuentas', JSON.stringify(saldosCuentas));
      guardarDetallesCuentas();
      localStorage.setItem('initialAccountsSet', 'true');
      
      console.log('✅ Datos cargados desde el servidor. Formulario listo para modificaciones.');
      console.log('🔧 Puedes actualizar bancos y otros datos, luego guardar cambios.');
      return; // No cargar desde localStorage si hay datos del servidor
    }
  @endif
  
  // Si no hay datos del servidor, cargar desde localStorage como respaldo
  const savedSaldos = localStorage.getItem('saldosCuentas');
  const savedDetails = localStorage.getItem('accountDetails');
  const savedInitial = localStorage.getItem('initialAccountsSet');

  if (savedSaldos) {
    saldosCuentas = JSON.parse(savedSaldos);
  }

  if (savedDetails) {
    accountDetails = JSON.parse(savedDetails);
  }

  if (savedInitial === 'true') {
    initialAccountsSet = true;
    // Mantener formulario habilitado para permitir modificaciones
    // Los inputs permanecen activos para edición
    
    // Cargar valores en el formulario
    document.getElementById('saldo-caja-general').value = saldosCuentas.caja_general;
    document.getElementById('saldo-cta-corriente-1').value = saldosCuentas.cuenta_corriente_1;
    document.getElementById('saldo-cuenta-ahorro').value = saldosCuentas.cuenta_ahorro;

    // Cargar detalles de cuentas
    document.getElementById('banco-cta-corriente-1').value = accountDetails.cuenta_corriente_1.banco;
    document.getElementById('numero-cta-corriente-1').value = accountDetails.cuenta_corriente_1.numero;
    document.getElementById('banco-cuenta-ahorro').value = accountDetails.cuenta_ahorro.banco;
    document.getElementById('numero-cuenta-ahorro').value = accountDetails.cuenta_ahorro.numero;
    
    console.log('Datos cargados desde localStorage');
  }
}

// Función para configurar cuentas iniciales
function configurarCuentasIniciales(e) {
  e.preventDefault(); // Prevenir envío normal para controlar el proceso
  
  if (!confirm('¿Está seguro(a) de guardar los cambios? Esta operación solo se podrá realizar una sola vez.')) {
    return;
  }
  
  // Obtener valores del formulario
  const formData = new FormData();
  
  // Obtener orgId desde la URL (asumir formato /org/{id}/cuentas-iniciales)
  const urlParts = window.location.pathname.split('/');
  const orgId = urlParts[2]; // Posición del id en la URL
  
  formData.append('orgId', orgId);
  formData.append('_token', '{{ csrf_token() }}');
  
  // Usar formato tradicional que espera el controlador
  formData.append('saldo_caja_general', document.getElementById('saldo-caja-general').value || 0);
  
  formData.append('saldo_cta_corriente_1', document.getElementById('saldo-cta-corriente-1').value || 0);
  
  // DEBUG: Verificar si existe el elemento banco
  const bancoCorriente1 = document.getElementById('banco-cta-corriente-1');
  console.log('🔍 Elemento banco corriente 1:', bancoCorriente1);
  console.log('🔍 Valor banco corriente 1:', bancoCorriente1 ? bancoCorriente1.value : 'ELEMENTO NO ENCONTRADO');
  
  // FORZAR CAPTURA: Si el elemento existe pero el valor está vacío, tomar el primer valor disponible
  let valorBancoCorriente1 = '';
  if (bancoCorriente1) {
    valorBancoCorriente1 = bancoCorriente1.value;
    console.log('🔍 Valor directo del elemento:', valorBancoCorriente1);
    
    // Si está vacío, NO forzar - permitir que esté vacío
    if (!valorBancoCorriente1) {
      console.log('⚠️ Campo banco corriente 1 está vacío - se enviará vacío');
    }
  }
  
  formData.append('banco_cta_corriente_1', valorBancoCorriente1);
  formData.append('numero_cta_corriente_1', document.getElementById('numero-cta-corriente-1').value || '');
  
  formData.append('saldo_cuenta_ahorro', document.getElementById('saldo-cuenta-ahorro').value || 0);
  
  // DEBUG: Verificar si existe el elemento banco ahorro
  const bancoAhorro = document.getElementById('banco-cuenta-ahorro');
  console.log('🔍 Elemento banco ahorro:', bancoAhorro);
  console.log('🔍 Valor banco ahorro:', bancoAhorro ? bancoAhorro.value : 'ELEMENTO NO ENCONTRADO');
  
  // FORZAR CAPTURA: Si el elemento existe pero el valor está vacío, tomar el primer valor disponible
  let valorBancoAhorro = '';
  if (bancoAhorro) {
    valorBancoAhorro = bancoAhorro.value;
    console.log('🔍 Valor directo del elemento ahorro:', valorBancoAhorro);
    
    // Si está vacío, NO forzar - permitir que esté vacío
    if (!valorBancoAhorro) {
      console.log('⚠️ Campo banco ahorro está vacío - se enviará vacío');
    }
  }
  
  formData.append('banco_cuenta_ahorro', valorBancoAhorro);
  formData.append('numero_cuenta_ahorro', document.getElementById('numero-cuenta-ahorro').value || '');
  
  // Responsable (común para todas las cuentas por ahora)
  const responsable = document.getElementById('responsable').value || '';
  formData.append('responsable_caja_general', responsable);
  formData.append('responsable_cta_corriente_1', responsable);
  formData.append('responsable_cuenta_ahorro', responsable);
  
  // Recopilar cuentas dinámicas adicionales
  const cuentasDinamicas = [];
  
  // Buscar todas las cuentas corrientes adicionales
  document.querySelectorAll('[data-tipo="cuenta_corriente"][data-cuenta-id]').forEach(seccion => {
    const cuentaId = seccion.getAttribute('data-cuenta-id');
    if (cuentaId && cuentaId !== 'cuenta_corriente_1') {
      const saldo = document.getElementById(`saldo-${cuentaId}`)?.value || 0;
      const banco = document.getElementById(`banco-${cuentaId}`)?.value || '';
      const numero = document.getElementById(`numero-${cuentaId}`)?.value || '';
      
      cuentasDinamicas.push({
        tipo: cuentaId,
        saldo: saldo,
        banco: banco === 'undefined' ? '' : banco, // Corregir valores undefined
        numero: numero === 'undefined' ? '' : numero,
        responsable: responsable
      });
    }
  });
  
  // Buscar todas las cuentas de ahorro adicionales
  document.querySelectorAll('[data-tipo="cuenta_ahorro"][data-cuenta-id]').forEach(seccion => {
    const cuentaId = seccion.getAttribute('data-cuenta-id');
    if (cuentaId && cuentaId !== 'cuenta_ahorro') {
      const saldo = document.getElementById(`saldo-${cuentaId}`)?.value || 0;
      const banco = document.getElementById(`banco-${cuentaId}`)?.value || '';
      const numero = document.getElementById(`numero-${cuentaId}`)?.value || '';
      
      cuentasDinamicas.push({
        tipo: cuentaId,
        saldo: saldo,
        banco: banco === 'undefined' ? '' : banco, // Corregir valores undefined
        numero: numero === 'undefined' ? '' : numero,
        responsable: responsable
      });
    }
  });
  
  // Agregar cuentas dinámicas si existen
  if (cuentasDinamicas.length > 0) {
    formData.append('cuentas_dinamicas', JSON.stringify(cuentasDinamicas));
  }
  
  // Debug: Mostrar lo que se va a enviar
  console.log('=== DATOS A ENVIAR ===');
  console.log('orgId:', orgId);
  
  // IMPORTANTE: Actualizar accountDetails antes de procesar
  actualizarAccountDetails();
  
  console.log('Cuentas dinámicas encontradas:', cuentasDinamicas.length);
  console.log('Cuentas dinámicas detalle:', cuentasDinamicas);
  for (let [key, value] of formData.entries()) {
    console.log(key + ': ' + value);
  }
  console.log('======================');
  
  // DEBUG ESPECÍFICO: Verificar todos los valores justo antes de enviar
  console.log('🔍 DEBUGGING CAMPOS DE BANCO:');
  const bancoCorriente1Element = document.getElementById('banco-cta-corriente-1');
  const bancoAhorroElement = document.getElementById('banco-cuenta-ahorro');
  
  console.log('Elemento banco corriente 1:', bancoCorriente1Element);
  console.log('Valor seleccionado corriente 1:', bancoCorriente1Element ? bancoCorriente1Element.value : 'NO ENCONTRADO');
  console.log('Selected index corriente 1:', bancoCorriente1Element ? bancoCorriente1Element.selectedIndex : 'NO ENCONTRADO');
  console.log('Selected option corriente 1:', bancoCorriente1Element ? bancoCorriente1Element.options[bancoCorriente1Element.selectedIndex] : 'NO ENCONTRADO');
  
  console.log('Elemento banco ahorro:', bancoAhorroElement);
  console.log('Valor seleccionado ahorro:', bancoAhorroElement ? bancoAhorroElement.value : 'NO ENCONTRADO');
  console.log('Selected index ahorro:', bancoAhorroElement ? bancoAhorroElement.selectedIndex : 'NO ENCONTRADO');
  console.log('Selected option ahorro:', bancoAhorroElement ? bancoAhorroElement.options[bancoAhorroElement.selectedIndex] : 'NO ENCONTRADO');
  
  console.log('🔍 FIN DEBUG BANCOS');
  console.log('======================');
  
  // Enviar al backend usando el mismo endpoint
  fetch(`/org/${orgId}/cuentas-iniciales`, {
    method: 'POST',
    body: formData,
    headers: {
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
  .then(response => {
    console.log('Status de respuesta:', response.status);
    
    if (response.ok) {
      return response.json().catch(() => {
        // Si no es JSON, intentar obtener como texto para debug
        return response.text().then(text => {
          console.log('Respuesta como texto:', text);
          return { success: true, message: 'Guardado exitosamente' };
        });
      });
    } else if (response.status === 422) {
      // Errores de validación
      return response.json().then(data => {
        console.error('Errores de validación:', data);
        throw new Error('Errores de validación: ' + Object.values(data.errors).flat().join(', '));
      });
    } else {
      return response.text().then(text => {
        console.error('Error del servidor:', text);
        throw new Error('Error en la respuesta del servidor (código: ' + response.status + ')');
      });
    }
  })
  .then(data => {
    console.log('✅ Respuesta exitosa del servidor:', data);
    
    // Mostrar información de debug si está disponible
    if (data.debug_info) {
      console.log('=== INFORMACIÓN DE DEBUG ===');
      console.log('Total cuentas guardadas:', data.debug_info.total_cuentas_guardadas);
      console.log('Cuentas dinámicas guardadas:', data.debug_info.cuentas_dinamicas_guardadas);
      console.log('Detalle de cuentas:', data.debug_info.cuentas_detalle);
      console.log('Timestamp:', data.debug_info.timestamp);
      console.log('============================');
    }
    
    // Guardar también en localStorage como respaldo
    saldosCuentas.caja_general = parseFloat(document.getElementById('saldo-caja-general').value) || 0;
    saldosCuentas.cuenta_corriente_1 = parseFloat(document.getElementById('saldo-cta-corriente-1').value) || 0;
    saldosCuentas.cuenta_ahorro = parseFloat(document.getElementById('saldo-cuenta-ahorro').value) || 0;
    
    accountDetails.caja_general = {
      banco: '',
      numero: ''
    };
    
    accountDetails.cuenta_corriente_1 = {
      banco: document.getElementById('banco-cta-corriente-1').value,
      numero: document.getElementById('numero-cta-corriente-1').value
    };
    
    accountDetails.cuenta_ahorro = {
      banco: document.getElementById('banco-cuenta-ahorro').value,
      numero: document.getElementById('numero-cuenta-ahorro').value
    };
    
    localStorage.setItem('saldosCuentas', JSON.stringify(saldosCuentas));
    guardarDetallesCuentas();
    localStorage.setItem('initialAccountsSet', 'true');
    initialAccountsSet = true;
    
    mostrarNotificacion(data.message || 'Cuentas iniciales guardadas correctamente en la base de datos', 'success');
  })
  .catch(error => {
    console.error('Error completo:', error);
    mostrarNotificacion('Error al guardar las cuentas iniciales: ' + error.message, 'error');
  });
}

// Inicializar cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
  // LIMPIEZA TEMPORAL - Eliminar después de usar
  localStorage.removeItem('saldosCuentas');
  localStorage.removeItem('accountDetails');
  localStorage.removeItem('initialAccountsSet');
  console.log('LocalStorage limpiado para empezar de nuevo');
  
  cargarDatosGuardados();
  
  // AUTO-GUARDADO DESACTIVADO - Solo guardado manual con botón
  // configurarAutoGuardado();
  
  // Event listener para el formulario (guardado manual solamente)
  document.getElementById('cuentasInicialesForm').addEventListener('submit', configurarCuentasIniciales);
  
  // Test: Verificar que las funciones estén disponibles
  console.log('Funciones cargadas:', {
    agregarNuevaCuenta: typeof agregarNuevaCuenta,
    crearSeccionCuentaCorriente: typeof crearSeccionCuentaCorriente,
    crearSeccionCuentaAhorro: typeof crearSeccionCuentaAhorro
  });
  
  // Función para actualizar accountDetails con valores actuales del formulario
  function actualizarAccountDetails() {
    console.log('🔄 Actualizando accountDetails con valores del formulario...');
    
    accountDetails.caja_general = {
      banco: '',
      numero: ''
    };
    
    const bancoCorriente1 = document.getElementById('banco-cta-corriente-1');
    const numeroCorriente1 = document.getElementById('numero-cta-corriente-1');
    console.log('📍 CAPTURA DIRECTA - Corriente 1:');
    console.log('  - Elemento banco:', bancoCorriente1);
    console.log('  - Valor banco:', bancoCorriente1 ? bancoCorriente1.value : 'NULL');
    console.log('  - Selected index:', bancoCorriente1 ? bancoCorriente1.selectedIndex : 'NULL');
    console.log('  - Selected text:', bancoCorriente1 && bancoCorriente1.selectedIndex >= 0 ? bancoCorriente1.options[bancoCorriente1.selectedIndex].text : 'NULL');
    
    accountDetails.cuenta_corriente_1 = {
      banco: bancoCorriente1 ? bancoCorriente1.value : '',
      numero: numeroCorriente1 ? numeroCorriente1.value : ''
    };
    
    const bancoAhorro = document.getElementById('banco-cuenta-ahorro');
    const numeroAhorro = document.getElementById('numero-cuenta-ahorro');
    console.log('📍 CAPTURA DIRECTA - Ahorro:');
    console.log('  - Elemento banco:', bancoAhorro);
    console.log('  - Valor banco:', bancoAhorro ? bancoAhorro.value : 'NULL');
    console.log('  - Selected index:', bancoAhorro ? bancoAhorro.selectedIndex : 'NULL');
    console.log('  - Selected text:', bancoAhorro && bancoAhorro.selectedIndex >= 0 ? bancoAhorro.options[bancoAhorro.selectedIndex].text : 'NULL');
    
    accountDetails.cuenta_ahorro = {
      banco: bancoAhorro ? bancoAhorro.value : '',
      numero: numeroAhorro ? numeroAhorro.value : ''
    };
    
    console.log('✅ accountDetails actualizado:', accountDetails);
    guardarDetallesCuentas();
  }
  
  // LISTENER: Monitorear cambios en selects de banco
  const bancoCorriente1Select = document.getElementById('banco-cta-corriente-1');
  const bancoAhorroSelect = document.getElementById('banco-cuenta-ahorro');
  
  if (bancoCorriente1Select) {
    bancoCorriente1Select.addEventListener('change', function() {
      console.log('🏦 Banco Corriente 1 cambiado a:', this.value);
      // Actualizar accountDetails inmediatamente
      if (!accountDetails.cuenta_corriente_1) {
        accountDetails.cuenta_corriente_1 = {};
      }
      accountDetails.cuenta_corriente_1.banco = this.value;
      guardarDetallesCuentas(); // Guardar en localStorage
      console.log('✅ accountDetails actualizado para corriente 1');
    });
  }
  
  if (bancoAhorroSelect) {
    bancoAhorroSelect.addEventListener('change', function() {
      console.log('🏦 Banco Ahorro cambiado a:', this.value);
      // Actualizar accountDetails inmediatamente
      if (!accountDetails.cuenta_ahorro) {
        accountDetails.cuenta_ahorro = {};
      }
      accountDetails.cuenta_ahorro.banco = this.value;
      guardarDetallesCuentas(); // Guardar en localStorage
      console.log('✅ accountDetails actualizado para ahorro');
    });
  }
  
  // FUNCIÓN TEMPORAL: Limpiar todo localStorage y recargar
  window.limpiarTodo = function() {
    console.log('🧹 Limpiando TODO el localStorage...');
    
    // Método 1: localStorage.clear()
    localStorage.clear();
    console.log('Paso 1 - localStorage.clear() ejecutado. Items:', localStorage.length);
    
    // Método 2: Eliminar items específicos por si acaso
    const keysToRemove = ['saldosCuentas', 'accountDetails', 'initialAccountsSet'];
    keysToRemove.forEach(key => {
      localStorage.removeItem(key);
      console.log(`Removido: ${key}`);
    });
    
    // Método 3: Eliminar TODOS los items manualmente
    const allKeys = Object.keys(localStorage);
    allKeys.forEach(key => {
      localStorage.removeItem(key);
      console.log(`Removido manualmente: ${key}`);
    });
    
    // Verificar que esté completamente limpio
    console.log('✅ localStorage limpio. Items restantes:', localStorage.length);
    console.log('✅ Verificación final:', {
      length: localStorage.length,
      saldosCuentas: localStorage.getItem('saldosCuentas'),
      accountDetails: localStorage.getItem('accountDetails'),
      initialAccountsSet: localStorage.getItem('initialAccountsSet')
    });
    
    // Mostrar mensaje y recargar
    alert('✅ localStorage limpiado completamente. La página se recargará.');
    location.reload();
  };
  
  // FUNCIÓN ESPECIAL: Actualizar solo bancos
  window.actualizarSoloBancos = function() {
    console.log('🏦 ACTUALIZANDO SOLO BANCOS...');
    
    // Obtener orgId desde la URL
    const orgId = window.location.pathname.split('/')[2];
    console.log('🔍 orgId detectado:', orgId);
    
    // Crear FormData solo con bancos
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('orgId', orgId);
    formData.append('solo_bancos', 'true'); // Indicar que solo actualizamos bancos
    
    // Capturar bancos actuales
    const bancoCorriente1 = document.getElementById('banco-cta-corriente-1');
    const bancoAhorro = document.getElementById('banco-cuenta-ahorro');
    
    console.log('🔍 Bancos capturados:');
    console.log('  - Corriente 1:', bancoCorriente1 ? bancoCorriente1.value : 'NO ENCONTRADO');
    console.log('  - Ahorro:', bancoAhorro ? bancoAhorro.value : 'NO ENCONTRADO');
    
    // Verificar que al menos un banco tenga valor
    const tieneUnBanco = (bancoCorriente1 && bancoCorriente1.value) || (bancoAhorro && bancoAhorro.value);
    if (!tieneUnBanco) {
      alert('⚠️ Debes seleccionar al menos un banco antes de actualizar');
      return;
    }
    
    // Solo enviar bancos si tienen valor
    if (bancoCorriente1 && bancoCorriente1.value) {
      formData.append('banco_cta_corriente_1', bancoCorriente1.value);
      console.log('✅ Agregado banco_cta_corriente_1:', bancoCorriente1.value);
    }
    if (bancoAhorro && bancoAhorro.value) {
      formData.append('banco_cuenta_ahorro', bancoAhorro.value);
      console.log('✅ Agregado banco_cuenta_ahorro:', bancoAhorro.value);
    }
    
    // Debug: Mostrar todo el FormData
    console.log('📦 FormData completo:');
    for (let [key, value] of formData.entries()) {
      console.log(`  ${key}: ${value}`);
    }
    
    // Enviar al servidor
    const url = `/org/${orgId}/cuentas-iniciales`;
    console.log('🌐 URL de envío:', url);
    
    fetch(url, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: formData
    })
    .then(response => {
      console.log('📡 Respuesta recibida:', response);
      console.log('📡 Status:', response.status);
      console.log('📡 OK:', response.ok);
      console.log('📡 Content-Type:', response.headers.get('Content-Type'));
      
      // Obtener el texto completo de la respuesta primero
      return response.text().then(text => {
        console.log('📄 Respuesta completa del servidor:', text);
        
        if (!response.ok) {
          throw new Error(`Error HTTP: ${response.status} - ${response.statusText}. Respuesta: ${text.substring(0, 500)}`);
        }
        
        // Intentar parsear como JSON
        try {
          return JSON.parse(text);
        } catch (parseError) {
          console.error('❌ Error parseando JSON:', parseError);
          console.error('📄 Texto que no se pudo parsear:', text.substring(0, 1000));
          throw new Error(`Respuesta no es JSON válido. Servidor devolvió HTML/texto: ${text.substring(0, 200)}...`);
        }
      });
    })
    .then(data => {
      console.log('✅ Bancos actualizados exitosamente:', data);
      alert('✅ Bancos actualizados correctamente: ' + data.message);
      console.log('🔄 Recargando página...');
      location.reload();
    })
    .catch(error => {
      console.error('❌ Error completo:', error);
      console.error('❌ Error stack:', error.stack);
      alert('❌ Error actualizando bancos: ' + error.message);
    });
  };
});
</script>
@endsection
