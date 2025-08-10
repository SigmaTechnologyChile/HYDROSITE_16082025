@extends('layouts.nice')

@section('title', 'Configuración de Cuentas Iniciales')

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

/* Botón secundario estilo balance */
.btn-config-secondary {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  color: var(--primary-color);
  border: 2px solid var(--primary-color);
  border-radius: 8px;
  padding: 12px 20px;
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
  margin-top: 15px;
}

.btn-config-secondary:hover {
  background: linear-gradient(135deg, var(--primary-color) 0%, #1976d2 100%);
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
}

.btn-config-secondary:active {
  transform: translateY(0);
}

/* Botón rojo estilo balance */
.btn-config-danger {
  background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
  color: white;
  border: none;
  border-radius: 8px;
  padding: 12px 20px;
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
  margin-top: 15px;
  margin-left: 10px;
  box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

.btn-config-danger:hover {
  background: linear-gradient(135deg, #c82333 0%, #a71e2a 100%);
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(220, 53, 69, 0.4);
}

.btn-config-danger:active {
  transform: translateY(0);
}

.btn-config-danger:disabled {
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

@section('content')
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
      <form id="cuentasInicialesForm" method="POST" action="/org/{{ $orgId }}/contable/cuentas-iniciales">
        @csrf
        <input type="hidden" name="orgId" value="{{ $orgId }}">
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
        <div class="form-section-balance">
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
                       class="form-input-balance" step="1" min="0" required>
                <small class="form-text text-muted">Solo efectivo - No requiere banco</small>
              </div>
              
              <!-- Caja General solo maneja efectivo - No requiere banco ni número de cuenta -->
              
            </div>
          </div>
        </div>
        
        <!-- Sección: Cuenta Corriente 1 estilo balance -->
        <div class="form-section-balance">
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
                  Saldo Cuenta Corriente 1
                </label>
                <input type="hidden" name="cuentas_corrientes[1][nombre]" value="Cuenta Corriente 1">
                <input type="number" id="saldo-cta-corriente-1" name="cuentas_corrientes[1][saldo_inicial]" 
                       class="form-input-balance" step="1" min="0" required>
              </div>
              
              <div class="form-group-balance">
                <label for="banco-cta-corriente-1" class="form-label-balance">
                  <i class="bi bi-bank"></i>
                  Banco
                </label>
                <select id="banco-cta-corriente-1" name="cuentas_corrientes[1][banco_id]" class="form-select-balance" required>
                  <option value="">Sin banco</option>
                  @if(isset($bancos) && $bancos->count() > 0)
                    @foreach($bancos as $banco)
                      <option value="{{ $banco->id }}">{{ $banco->nombre }}</option>
                    @endforeach
                  @else
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
                <input type="text" id="numero-cta-corriente-1" name="cuentas_corrientes[1][numero_cuenta]" 
                       class="form-input-balance" required>
              </div>
            </div>
            
            <!-- Botón Agregar Cuenta -->
            <div style="text-align: center; margin-top: 20px;">
              <button type="button" class="btn-config-secondary" id="agregar-cuenta-btn">
                <i class="bi bi-plus-circle"></i>
                Agregar Cuenta
              </button>
              <button type="button" class="btn-config-danger" id="guardar-cuentas-btn">
                <i class="bi bi-floppy"></i>
                Guardar
              </button>
            </div>
          </div>
        </div>
        
        <!-- Sección: Cuenta de Ahorro estilo balance -->
        <div class="form-section-balance">
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
                <input type="hidden" name="cuentas_ahorro[1][nombre]" value="Cuenta de Ahorro 1">
                <input type="number" id="saldo-cuenta-ahorro" name="cuentas_ahorro[1][saldo_inicial]" 
                       class="form-input-balance" step="1" min="0" required>
              </div>
              
              <div class="form-group-balance">
                <label for="banco-cuenta-ahorro" class="form-label-balance">
                  <i class="bi bi-bank"></i>
                  Banco
                </label>
                <select id="banco-cuenta-ahorro" name="cuentas_ahorro[1][banco_id]" class="form-select-balance" required>
                  <option value="">Sin banco</option>
                  @if(isset($bancos) && $bancos->count() > 0)
                    @foreach($bancos as $banco)
                      <option value="{{ $banco->id }}">{{ $banco->nombre }}</option>
                    @endforeach
                  @else
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
                <input type="text" id="numero-cuenta-ahorro" name="cuentas_ahorro[1][numero_cuenta]" 
                       class="form-input-balance" required>
              </div>
            </div>
            
            <!-- Botones Agregar Cuenta y Guardar para Cuenta de Ahorro -->
            <div style="text-align: center; margin-top: 20px;">
              <button type="button" class="btn-config-secondary" id="agregar-cuenta-ahorro-btn">
                <i class="bi bi-plus-circle"></i>
                Agregar Cuenta
              </button>
              <button type="button" class="btn-config-danger" id="guardar-cuentas-ahorro-btn">
                <i class="bi bi-floppy"></i>
                Guardar
              </button>
            </div>
          </div>
        </div>
        
        <!-- Sección: Responsable estilo balance -->
        <div class="form-section-balance">
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
          <button type="submit" class="btn-config-primary">
            <i class="bi bi-save"></i>
            Guardar Configuración de Cuentas
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
  cuenta_corriente_2: 0,
  cuenta_ahorro: 0
};

let accountDetails = {
  caja_general: { banco: '', numero: '' },
  cuenta_corriente_1: { banco: '', numero: '' },
  cuenta_corriente_2: { banco: '', numero: '' },
  cuenta_ahorro: { banco: '', numero: '' }
};

let initialAccountsSet = false;

// Función para mostrar notificaciones estilo balance
function mostrarNotificacion(mensaje, tipo = 'success') {
  const notification = document.getElementById('notification');
  let icono = 'bi-check-circle';
  
  if (tipo === 'error') {
    icono = 'bi-exclamation-triangle';
  } else if (tipo === 'warning') {
    icono = 'bi-exclamation-circle';
  } else if (tipo === 'info') {
    icono = 'bi-info-circle';
  }
  
  notification.innerHTML = `<i class="bi ${icono}"></i> ${mensaje}`;
  notification.className = `notification-balance ${tipo}`;
  notification.style.display = 'flex';
  
  setTimeout(() => {
    notification.style.display = 'none';
  }, tipo === 'error' ? 5000 : 3000); // Errores se muestran más tiempo
}

// Función para guardar detalles de cuentas
function guardarDetallesCuentas() {
  localStorage.setItem('accountDetails', JSON.stringify(accountDetails));
}

// Función para cargar datos guardados
function cargarDatosGuardados() {
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
    // Temporalmente deshabilitado para pruebas
    // initialAccountsSet = true;
    // // Deshabilitar formulario si ya fue configurado
    // const inputs = document.querySelectorAll('#cuentasInicialesForm input, #cuentasInicialesForm select');
    // inputs.forEach(input => {
    //   input.disabled = true;
    // });
    
    // Cargar valores en el formulario (comentado para pruebas)
    // document.getElementById('saldo-caja-general').value = saldosCuentas.caja_general;
    // document.getElementById('saldo-cta-corriente-1').value = saldosCuentas.cuenta_corriente_1;
    // document.getElementById('saldo-cta-corriente-2').value = saldosCuentas.cuenta_corriente_2;
    // document.getElementById('saldo-cuenta-ahorro').value = saldosCuentas.cuenta_ahorro;

    // Cargar detalles de cuentas (comentado para pruebas)
    // document.getElementById('banco-caja-general').value = accountDetails.caja_general.banco;
    // document.getElementById('numero-caja-general').value = accountDetails.caja_general.numero;
    // document.getElementById('banco-cta-corriente-1').value = accountDetails.cuenta_corriente_1.banco;
    // document.getElementById('numero-cta-corriente-1').value = accountDetails.cuenta_corriente_1.numero;
    // document.getElementById('banco-cta-corriente-2').value = accountDetails.cuenta_corriente_2.banco;
    // document.getElementById('numero-cta-corriente-2').value = accountDetails.cuenta_corriente_2.numero;
    // document.getElementById('banco-cuenta-ahorro').value = accountDetails.cuenta_ahorro.banco;
    // document.getElementById('numero-cuenta-ahorro').value = accountDetails.cuenta_ahorro.numero;

    // Cambiar el botón (comentado para pruebas)
    // const submitBtn = document.querySelector('button[type="submit"]');
    // submitBtn.innerHTML = '<i class="bi bi-check-circle"></i> Configuración Guardada';
    // submitBtn.disabled = true;
    
    // mostrarNotificacion('La configuración ya ha sido guardada anteriormente', 'success');
  }
}

// Función para configurar cuentas iniciales
function configurarCuentasIniciales(e) {
  e.preventDefault();
  
  if (initialAccountsSet) {
    mostrarNotificacion('La configuración ya fue realizada anteriormente', 'error');
    return;
  }
  
  if (!confirm('¿Está seguro(a) de guardar los cambios? Esta operación solo se podrá realizar una sola vez.')) {
    return;
  }
  
  // Validar que al menos un campo esté lleno
  const saldoCaja = parseFloat(document.getElementById('saldo-caja-general').value) || 0;
  const saldoCta1 = parseFloat(document.getElementById('saldo-cta-corriente-1').value) || 0;
  const saldoAhorro = parseFloat(document.getElementById('saldo-cuenta-ahorro').value) || 0;
  const responsable = document.getElementById('responsable').value.trim();
  
  // Recoger también cuentas corrientes dinámicas
  let totalSaldos = saldoCaja + saldoCta1 + saldoAhorro;
  for (let i = 2; i <= 10; i++) {
    const saldoField = document.getElementById(`saldo-cta-corriente-${i}`);
    if (saldoField) {
      totalSaldos += parseFloat(saldoField.value) || 0;
    }
  }
  
  if (totalSaldos === 0) {
    mostrarNotificacion('Debe ingresar al menos un saldo inicial', 'error');
    return;
  }
  
  if (!responsable) {
    mostrarNotificacion('Debe ingresar el nombre del responsable', 'error');
    return;
  }
  
  // Deshabilitar el botón para evitar doble envío
  const submitBtn = document.querySelector('button[type="submit"]');
  const originalText = submitBtn.innerHTML;
  submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Guardando...';
  submitBtn.disabled = true;
  
  // Recopilar todos los datos del formulario
  const formData = new FormData(document.getElementById('cuentasInicialesForm'));
  
  // Enviar al servidor
  fetch(document.getElementById('cuentasInicialesForm').action, {
    method: 'POST',
    body: formData,
    headers: {
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Marcar como configurado (temporalmente deshabilitado para pruebas)
      // localStorage.setItem('initialAccountsSet', 'true');
      // initialAccountsSet = true;
      
      // Bloquear formulario (temporalmente deshabilitado para pruebas)
      // const inputs = document.querySelectorAll('#cuentasInicialesForm input, #cuentasInicialesForm select');
      // inputs.forEach(input => {
      //   input.disabled = true;
      // });
      
      mostrarNotificacion(data.message || 'Configuración guardada exitosamente', 'success');
    } else {
      throw new Error(data.message || 'Error al guardar la configuración');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    mostrarNotificacion(error.message || 'Error al guardar la configuración', 'error');
    
    // Restaurar el botón
    submitBtn.innerHTML = originalText;
    submitBtn.disabled = false;
  });
}

// Inicializar cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
  cargarDatosGuardados();
  
  // Event listener para el formulario
  document.getElementById('cuentasInicialesForm').addEventListener('submit', configurarCuentasIniciales);
  
  // Event listener para el botón Agregar Cuenta
  document.getElementById('agregar-cuenta-btn').addEventListener('click', function() {
    agregarNuevaCuenta();
  });
  
  // Event listener para el botón Guardar
  document.getElementById('guardar-cuentas-btn').addEventListener('click', function() {
    guardarCuentasEnBaseDatos();
  });
  
  // Event listeners para botones de Cuenta de Ahorro
  document.getElementById('agregar-cuenta-ahorro-btn').addEventListener('click', function() {
    agregarNuevaCuentaAhorro();
  });
  
  document.getElementById('guardar-cuentas-ahorro-btn').addEventListener('click', function() {
    guardarCuentasEnBaseDatos();
  });
  
  console.log('🎉 Configuración de Cuentas Iniciales con estilos del Balance inicializada!');
});

// Función para agregar una nueva cuenta corriente
function agregarNuevaCuenta() {
  // Obtener el contenedor de la sección de cuenta corriente
  const cuentaCorrienteSection = document.querySelector('.form-section-balance:has([id*="cta-corriente"])');
  const cuentaAhorroSection = document.querySelector('.form-section-balance:has([id*="cuenta-ahorro"])');
  
  // Contar cuántas cuentas corrientes ya existen
  const cuentasExistentes = document.querySelectorAll('[id*="saldo-cta-corriente"]').length;
  const nuevaId = cuentasExistentes + 1;
  
  // Crear el HTML para la nueva cuenta
  const nuevaCuentaHTML = `
    <div class="form-section-balance" id="cuenta-corriente-${nuevaId}">
      <div class="form-section-header-balance">
        <h4>
          <i class="bi bi-bank2"></i>
          Cuenta Corriente ${nuevaId}
          <button type="button" class="btn-eliminar-cuenta" onclick="eliminarCuenta('cuenta-corriente-${nuevaId}')" style="float: right; background: #dc3545; color: white; border: none; border-radius: 4px; padding: 5px 10px; font-size: 12px;">
            <i class="bi bi-trash"></i>
          </button>
        </h4>
      </div>
      <div class="form-section-body-balance">
        <div class="form-grid-balance">
          <div class="form-group-balance">
            <label for="saldo-cta-corriente-${nuevaId}" class="form-label-balance">
              <i class="bi bi-currency-dollar"></i>
              Saldo Cuenta Corriente ${nuevaId}
            </label>
            <input type="number" id="saldo-cta-corriente-${nuevaId}" name="cuentas_corrientes[${nuevaId}][saldo_inicial]" 
                   class="form-input-balance" step="1" min="0" required>
          </div>
          
          <div class="form-group-balance">
            <label for="banco-cta-corriente-${nuevaId}" class="form-label-balance">
              <i class="bi bi-bank"></i>
              Banco
            </label>
            <select id="banco-cta-corriente-${nuevaId}" name="cuentas_corrientes[${nuevaId}][banco_id]" class="form-select-balance" required>
              <option value="">Sin banco</option>
              ${generarOpcionesBancos()}
            </select>
          </div>
          
          <div class="form-group-balance">
            <label for="numero-cta-corriente-${nuevaId}" class="form-label-balance">
              <i class="bi bi-credit-card"></i>
              Número de Cuenta
            </label>
            <input type="text" id="numero-cta-corriente-${nuevaId}" name="cuentas_corrientes[${nuevaId}][numero_cuenta]" 
                   class="form-input-balance" required>
          </div>
        </div>
      </div>
    </div>
  `;
  
  // Insertar la nueva cuenta antes de la sección de Cuenta de Ahorro
  cuentaAhorroSection.insertAdjacentHTML('beforebegin', nuevaCuentaHTML);
  
  // Mostrar notificación
  mostrarNotificacion(`Cuenta Corriente ${nuevaId} agregada exitosamente`, 'success');
}

// Función para agregar nueva cuenta de ahorro
function agregarNuevaCuentaAhorro() {
  const cuentasAhorroContainer = document.getElementById('cuentas-ahorro-container');
  const cuentasAhorro = cuentasAhorroContainer.children;
  
  // Calcular nuevo ID basado en las cuentas existentes
  let nuevaId = 1;
  if (cuentasAhorro.length > 0) {
    const ultimaCuenta = cuentasAhorro[cuentasAhorro.length - 1];
    const ultimoId = parseInt(ultimaCuenta.id.replace('cuenta-ahorro-', ''));
    nuevaId = ultimoId + 1;
  }
  
  // Crear HTML de la nueva cuenta de ahorro
  const nuevaCuentaHTML = `
    <div class="cuenta-item" id="cuenta-ahorro-${nuevaId}">
      <div class="cuenta-header d-flex justify-content-between align-items-center">
        <h6 class="cuenta-title">Cuenta de Ahorro ${nuevaId}</h6>
        <button type="button" class="btn btn-danger btn-sm" onclick="eliminarCuentaAhorro(${nuevaId})" title="Eliminar cuenta">
          <i class="bi bi-trash"></i>
        </button>
      </div>
      <div class="row">
        <div class="col-md-4">
          <label for="banco-id-cuenta-ahorro-${nuevaId}" class="form-label">Banco</label>
          <select class="form-select" id="banco-id-cuenta-ahorro-${nuevaId}" name="cuentas_ahorro[${nuevaId}][banco_id]" required>
            <option value="">Seleccionar banco</option>
            ${generarOpcionesBancos()}
          </select>
        </div>
        <div class="col-md-4">
          <label for="nombre-cuenta-ahorro-${nuevaId}" class="form-label">Nombre de la Cuenta</label>
          <input type="text" class="form-control" id="nombre-cuenta-ahorro-${nuevaId}" name="cuentas_ahorro[${nuevaId}][nombre]" required>
        </div>
        <div class="col-md-4">
          <label for="numero-cuenta-ahorro-${nuevaId}" class="form-label">Número de Cuenta</label>
          <input type="text" class="form-control" id="numero-cuenta-ahorro-${nuevaId}" name="cuentas_ahorro[${nuevaId}][numero_cuenta]" required>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-6">
          <label for="saldo-inicial-cuenta-ahorro-${nuevaId}" class="form-label">Saldo Inicial</label>
          <input type="number" class="form-control saldo-input" id="saldo-inicial-cuenta-ahorro-${nuevaId}" name="cuentas_ahorro[${nuevaId}][saldo_inicial]" min="0" step="1" required>
        </div>
      </div>
    </div>
  `;
  
  // Insertar la nueva cuenta al final del contenedor
  cuentasAhorroContainer.insertAdjacentHTML('beforeend', nuevaCuentaHTML);
  
  // Mostrar notificación
  mostrarNotificacion(`Cuenta de Ahorro ${nuevaId} agregada exitosamente`, 'success');
}

// Función para eliminar cuenta de ahorro
function eliminarCuentaAhorro(id) {
  const cuenta = document.getElementById(`cuenta-ahorro-${id}`);
  if (cuenta) {
    cuenta.remove();
    mostrarNotificacion(`Cuenta de Ahorro ${id} eliminada exitosamente`, 'info');
  }
}

// Función para generar las opciones de bancos
function generarOpcionesBancos() {
  // Intentar obtener los bancos del select existente
  const selectExistente = document.querySelector('select[name*="banco"]');
  if (selectExistente) {
    const opciones = Array.from(selectExistente.options)
      .filter(option => option.value !== '') // Excluir la opción vacía
      .map(option => `<option value="${option.value}">${option.textContent}</option>`)
      .join('');
    return opciones;
  }
  
  // Fallback con bancos estáticos si no hay selects existentes
  const bancos = [
    'banco_estado', 'banco_chile', 'banco_bci', 'banco_santander', 
    'banco_itau', 'banco_scotiabank', 'banco_bice', 'banco_security',
    'banco_falabella', 'banco_ripley', 'banco_consorcio', 'otro'
  ];
  
  const nombresBancos = {
    'banco_estado': 'Banco Estado',
    'banco_chile': 'Banco de Chile',
    'banco_bci': 'BCI',
    'banco_santander': 'Santander',
    'banco_itau': 'Itaú',
    'banco_scotiabank': 'Scotiabank',
    'banco_bice': 'BICE',
    'banco_security': 'Security',
    'banco_falabella': 'Falabella',
    'banco_ripley': 'Ripley',
    'banco_consorcio': 'Consorcio',
    'otro': 'Otro'
  };
  
  return bancos.map(banco => 
    `<option value="${banco}">${nombresBancos[banco]}</option>`
  ).join('');
}

// Función para eliminar una cuenta
function eliminarCuenta(cuentaId) {
  if (confirm('¿Estás seguro de que deseas eliminar esta cuenta?')) {
    document.getElementById(cuentaId).remove();
    mostrarNotificacion('Cuenta eliminada exitosamente', 'success');
  }
}

// Función para guardar todas las cuentas en la base de datos
function guardarCuentasEnBaseDatos() {
  // Validar que al menos un campo esté lleno
  const todasLasCuentas = document.querySelectorAll('[id*="saldo-"]');
  let tieneAlMenosUnSaldo = false;
  
  todasLasCuentas.forEach(campo => {
    const valor = parseFloat(campo.value) || 0;
    if (valor > 0) {
      tieneAlMenosUnSaldo = true;
    }
  });
  
  if (!tieneAlMenosUnSaldo) {
    mostrarNotificacion('Debe ingresar al menos un saldo inicial', 'error');
    return;
  }
  
  // Validar responsable
  const responsable = document.getElementById('responsable').value.trim();
  if (!responsable) {
    mostrarNotificacion('Debe ingresar el nombre del responsable', 'error');
    return;
  }
  
  // Confirmación
  if (!confirm('¿Está seguro de que desea guardar todas las cuentas en la base de datos?')) {
    return;
  }
  
  // Deshabilitar el botón para evitar doble envío
  const guardarBtn = document.getElementById('guardar-cuentas-btn');
  const originalText = guardarBtn.innerHTML;
  guardarBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Guardando...';
  guardarBtn.disabled = true;
  
  // Recopilar todos los datos del formulario
  const formData = new FormData(document.getElementById('cuentasInicialesForm'));
  
  // Agregar todos los campos de cuentas dinámicas al FormData
  const cuentasDinamicas = document.querySelectorAll('[id*="cta-corriente-"]:not([id*="cta-corriente-1"])');
  cuentasDinamicas.forEach(campo => {
    if (campo.name && campo.value) {
      formData.set(campo.name, campo.value);
    }
  });
  
  // Enviar al servidor
  fetch(document.getElementById('cuentasInicialesForm').action, {
    method: 'POST',
    body: formData,
    headers: {
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Marcar como configurado (temporalmente deshabilitado para pruebas)
      // localStorage.setItem('initialAccountsSet', 'true');
      // initialAccountsSet = true;
      
      // Bloquear todos los formularios (temporalmente deshabilitado para pruebas)
      // const inputs = document.querySelectorAll('#cuentasInicialesForm input, #cuentasInicialesForm select, button');
      // inputs.forEach(input => {
      //   input.disabled = true;
      // });
      
      // Cambiar el botón (temporalmente deshabilitado para pruebas)
      // guardarBtn.innerHTML = '<i class="bi bi-check-circle"></i> Guardado';
      
      // También deshabilitar el botón del formulario principal si existe (temporalmente deshabilitado para pruebas)
      // const submitBtn = document.querySelector('button[type="submit"]');
      // if (submitBtn) {
      //   submitBtn.innerHTML = '<i class="bi bi-check-circle"></i> Configuración Guardada';
      //   submitBtn.disabled = true;
      // }
      
      mostrarNotificacion(data.message || 'Cuentas guardadas exitosamente en la base de datos', 'success');
    } else {
      throw new Error(data.message || 'Error al guardar las cuentas');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    mostrarNotificacion(error.message || 'Error al guardar las cuentas en la base de datos', 'error');
    
    // Restaurar el botón
    guardarBtn.innerHTML = originalText;
    guardarBtn.disabled = false;
  });
}
</script>
@endsection
