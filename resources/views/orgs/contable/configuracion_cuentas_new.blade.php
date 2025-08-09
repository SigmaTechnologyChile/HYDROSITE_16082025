@extends('layouts.app')

@section('content')
@include('orgs.contable.partials.contable-styles')

<style>
/* Estilos específicos para notificaciones */
.contable-notification {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
    min-width: 320px;
    max-width: 90vw;
    padding: var(--spacing-xl);
    background: var(--success-color);
    color: white;
    border-radius: var(--radius-lg);
    font-size: 1.1rem;
    text-align: center;
    box-shadow: var(--shadow-2xl);
    opacity: 0.97;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-md);
}

.contable-notification.error {
    background: var(--danger-color);
}

.contable-notification.success {
    background: var(--success-color);
}
</style>

<div class="contable-page">
  <div class="contable-header">
    <div class="contable-header-content">
      <div class="contable-header-info">
        <h1 class="contable-title">
          <i class="bi bi-journal-plus"></i>
          Configuración de Cuentas Iniciales
        </h1>
        <p class="contable-subtitle">Configure los saldos iniciales de sus cuentas bancarias</p>
      </div>
    </div>
  </div>

  <div class="contable-content">
    <div class="contable-card">
      <form id="cuentasInicialesForm">
        <div class="contable-alert contable-alert-warning contable-mb-xl">
          <div class="contable-alert-icon">
            <i class="bi bi-exclamation-triangle"></i>
          </div>
          <div class="contable-alert-content">
            <strong>Atención:</strong> ¿Está seguro(a) de guardar los cambios? Esta operación solo se podrá realizar una sola vez.
          </div>
        </div>
        
        <!-- Sección: Caja General -->
        <div class="contable-form-section contable-mb-xl">
          <div class="contable-form-header">
            <h4>
              <i class="bi bi-cash-stack"></i>
              Saldo Inicial de Caja General
            </h4>
          </div>
          <div class="contable-form-body">
            <div class="contable-form-grid-3">
              <div class="contable-form-group">
                <label for="saldo-caja-general" class="contable-form-label">
                  <i class="bi bi-currency-dollar"></i>
                  Saldo Caja General
                </label>
                <input type="number" id="saldo-caja-general" name="saldo_caja_general" 
                       class="contable-form-input" step="0.01" min="0" required>
              </div>
              
              <div class="contable-form-group">
                <label for="banco-caja-general" class="contable-form-label">
                  <i class="bi bi-bank"></i>
                  Banco
                </label>
                <select id="banco-caja-general" name="banco_caja_general" class="contable-form-select">
                  <option value="">Sin banco</option>
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
                </select>
              </div>
              
              <div class="contable-form-group">
                <label for="numero-caja-general" class="contable-form-label">
                  <i class="bi bi-credit-card"></i>
                  Número de Cuenta
                </label>
                <input type="text" id="numero-caja-general" name="numero_caja_general" 
                       class="contable-form-input">
              </div>
            </div>
          </div>
        </div>
        
        <!-- Sección: Cuenta Corriente 1 -->
        <div class="contable-form-section contable-mb-xl">
          <div class="contable-form-header">
            <h4>
              <i class="bi bi-bank2"></i>
              Cuenta Corriente 1
            </h4>
          </div>
          <div class="contable-form-body">
            <div class="contable-form-grid-3">
              <div class="contable-form-group">
                <label for="saldo-cta-corriente-1" class="contable-form-label">
                  <i class="bi bi-currency-dollar"></i>
                  Saldo Cuenta Corriente 1
                </label>
                <input type="number" id="saldo-cta-corriente-1" name="saldo_cta_corriente_1" 
                       class="contable-form-input" step="0.01" min="0" required>
              </div>
              
              <div class="contable-form-group">
                <label for="banco-cta-corriente-1" class="contable-form-label">
                  <i class="bi bi-bank"></i>
                  Banco
                </label>
                <select id="banco-cta-corriente-1" name="banco_cta_corriente_1" class="contable-form-select">
                  <option value="">Sin banco</option>
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
                </select>
              </div>
              
              <div class="contable-form-group">
                <label for="numero-cta-corriente-1" class="contable-form-label">
                  <i class="bi bi-credit-card"></i>
                  Número de Cuenta
                </label>
                <input type="text" id="numero-cta-corriente-1" name="numero_cta_corriente_1" 
                       class="contable-form-input">
              </div>
            </div>
          </div>
        </div>
        
        <!-- Sección: Cuenta Corriente 2 -->
        <div class="contable-form-section contable-mb-xl">
          <div class="contable-form-header">
            <h4>
              <i class="bi bi-bank2"></i>
              Cuenta Corriente 2
            </h4>
          </div>
          <div class="contable-form-body">
            <div class="contable-form-grid-3">
              <div class="contable-form-group">
                <label for="saldo-cta-corriente-2" class="contable-form-label">
                  <i class="bi bi-currency-dollar"></i>
                  Saldo Cuenta Corriente 2
                </label>
                <input type="number" id="saldo-cta-corriente-2" name="saldo_cta_corriente_2" 
                       class="contable-form-input" step="0.01" min="0" required>
              </div>
              
              <div class="contable-form-group">
                <label for="banco-cta-corriente-2" class="contable-form-label">
                  <i class="bi bi-bank"></i>
                  Banco
                </label>
                <select id="banco-cta-corriente-2" name="banco_cta_corriente_2" class="contable-form-select">
                  <option value="">Sin banco</option>
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
                </select>
              </div>
              
              <div class="contable-form-group">
                <label for="numero-cta-corriente-2" class="contable-form-label">
                  <i class="bi bi-credit-card"></i>
                  Número de Cuenta
                </label>
                <input type="text" id="numero-cta-corriente-2" name="numero_cta_corriente_2" 
                       class="contable-form-input">
              </div>
            </div>
          </div>
        </div>
        
        <!-- Sección: Cuenta de Ahorro -->
        <div class="contable-form-section contable-mb-xl">
          <div class="contable-form-header">
            <h4>
              <i class="bi bi-piggy-bank"></i>
              Cuenta de Ahorro
            </h4>
          </div>
          <div class="contable-form-body">
            <div class="contable-form-grid-3">
              <div class="contable-form-group">
                <label for="saldo-cuenta-ahorro" class="contable-form-label">
                  <i class="bi bi-currency-dollar"></i>
                  Saldo Cuenta de Ahorro
                </label>
                <input type="number" id="saldo-cuenta-ahorro" name="saldo_cuenta_ahorro" 
                       class="contable-form-input" step="0.01" min="0" required>
              </div>
              
              <div class="contable-form-group">
                <label for="banco-cuenta-ahorro" class="contable-form-label">
                  <i class="bi bi-bank"></i>
                  Banco
                </label>
                <select id="banco-cuenta-ahorro" name="banco_cuenta_ahorro" class="contable-form-select">
                  <option value="">Sin banco</option>
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
                </select>
              </div>
              
              <div class="contable-form-group">
                <label for="numero-cuenta-ahorro" class="contable-form-label">
                  <i class="bi bi-credit-card"></i>
                  Número de Cuenta
                </label>
                <input type="text" id="numero-cuenta-ahorro" name="numero_cuenta_ahorro" 
                       class="contable-form-input">
              </div>
            </div>
          </div>
        </div>
        
        <!-- Sección: Responsable -->
        <div class="contable-form-section contable-mb-xl">
          <div class="contable-form-header">
            <h4>
              <i class="bi bi-person-check"></i>
              Información del Responsable
            </h4>
          </div>
          <div class="contable-form-body">
            <div class="contable-form-group">
              <label for="responsable" class="contable-form-label">
                <i class="bi bi-person"></i>
                Nombre Responsable
                <span class="contable-form-required">*</span>
              </label>
              <input type="text" id="responsable" name="responsable" 
                     class="contable-form-input" required>
            </div>
          </div>
        </div>
        
        <!-- Botón de envío -->
        <div class="contable-form-actions">
          <button type="submit" class="contable-btn contable-btn-primary contable-btn-lg">
            <i class="bi bi-save"></i>
            Guardar Cuentas Iniciales
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Notification div -->
  <div id="notification" class="contable-notification"></div>
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

// Función para mostrar notificaciones
function mostrarNotificacion(mensaje, tipo = 'success') {
  const notification = document.getElementById('notification');
  notification.textContent = mensaje;
  notification.className = `contable-notification ${tipo}`;
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
    // Deshabilitar formulario si ya fue configurado
    const inputs = document.querySelectorAll('#cuentasInicialesForm input, #cuentasInicialesForm select');
    inputs.forEach(input => {
      input.disabled = true;
    });
    
    // Cargar valores en el formulario
    document.getElementById('saldo-caja-general').value = saldosCuentas.caja_general;
    document.getElementById('saldo-cta-corriente-1').value = saldosCuentas.cuenta_corriente_1;
    document.getElementById('saldo-cta-corriente-2').value = saldosCuentas.cuenta_corriente_2;
    document.getElementById('saldo-cuenta-ahorro').value = saldosCuentas.cuenta_ahorro;

    // Cargar detalles de cuentas
    document.getElementById('banco-caja-general').value = accountDetails.caja_general.banco;
    document.getElementById('numero-caja-general').value = accountDetails.caja_general.numero;
    document.getElementById('banco-cta-corriente-1').value = accountDetails.cuenta_corriente_1.banco;
    document.getElementById('numero-cta-corriente-1').value = accountDetails.cuenta_corriente_1.numero;
    document.getElementById('banco-cta-corriente-2').value = accountDetails.cuenta_corriente_2.banco;
    document.getElementById('numero-cta-corriente-2').value = accountDetails.cuenta_corriente_2.numero;
    document.getElementById('banco-cuenta-ahorro').value = accountDetails.cuenta_ahorro.banco;
    document.getElementById('numero-cuenta-ahorro').value = accountDetails.cuenta_ahorro.numero;
  }
}

// Función para configurar cuentas iniciales
function configurarCuentasIniciales(e) {
  e.preventDefault();
  
  if (!confirm('¿Está seguro(a) de guardar los cambios? Esta operación solo se podrá realizar una sola vez.')) {
    return;
  }
  
  // Obtener valores del formulario
  saldosCuentas.caja_general = parseFloat(document.getElementById('saldo-caja-general').value) || 0;
  saldosCuentas.cuenta_corriente_1 = parseFloat(document.getElementById('saldo-cta-corriente-1').value) || 0;
  saldosCuentas.cuenta_corriente_2 = parseFloat(document.getElementById('saldo-cta-corriente-2').value) || 0;
  saldosCuentas.cuenta_ahorro = parseFloat(document.getElementById('saldo-cuenta-ahorro').value) || 0;
  
  // Guardar detalles de cuentas
  accountDetails.caja_general = {
    banco: document.getElementById('banco-caja-general').value,
    numero: document.getElementById('numero-caja-general').value
  };
  
  accountDetails.cuenta_corriente_1 = {
    banco: document.getElementById('banco-cta-corriente-1').value,
    numero: document.getElementById('numero-cta-corriente-1').value
  };
  
  accountDetails.cuenta_corriente_2 = {
    banco: document.getElementById('banco-cta-corriente-2').value,
    numero: document.getElementById('numero-cta-corriente-2').value
  };
  
  accountDetails.cuenta_ahorro = {
    banco: document.getElementById('banco-cuenta-ahorro').value,
    numero: document.getElementById('numero-cuenta-ahorro').value
  };
  
  // Guardar en localStorage
  localStorage.setItem('saldosCuentas', JSON.stringify(saldosCuentas));
  guardarDetallesCuentas();
  localStorage.setItem('initialAccountsSet', 'true');
  initialAccountsSet = true;
  
  // Bloquear formulario
  const inputs = document.querySelectorAll('#cuentasInicialesForm input, #cuentasInicialesForm select');
  inputs.forEach(input => {
    input.disabled = true;
  });
  
  mostrarNotificacion('Cuentas iniciales guardadas correctamente', 'success');
}

// Inicializar cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
  cargarDatosGuardados();
  
  // Event listener para el formulario
  document.getElementById('cuentasInicialesForm').addEventListener('submit', configurarCuentasIniciales);
});
</script>
@endsection
