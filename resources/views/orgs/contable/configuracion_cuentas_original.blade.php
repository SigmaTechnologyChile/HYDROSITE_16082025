@extends('layouts.nice')

@section('title', 'Configuración de Cuentas Iniciales')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Configuración de Cuentas Iniciales</title>

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Nunito:300,400,600,700|Poppins:300,400,500,600,700" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="https://hydrosite.cl/public/theme/nice/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://hydrosite.cl/public/theme/nice/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/contable/style.css') }}">
  
  <style>
    .notification {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 9999;
      min-width: 320px;
      max-width: 90vw;
      padding: 20px 40px;
      background: #222;
      color: #fff;
      border-radius: 10px;
      font-size: 1.2rem;
      text-align: center;
      box-shadow: 0 4px 24px rgba(0,0,0,0.25);
      display: none;
      opacity: 0.97;
      transition: opacity 0.2s;
    }
    .notification.success { background: #28a745; color: #fff; }
    .notification.error { background: #dc3545; color: #fff; }

    /* Estilos para el modal de Cuentas Iniciales */
    .modal {
      display: flex;
      position: fixed;
      z-index: 9999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.6);
      align-items: center;
      justify-content: center;
    }

    .modal-content {
      max-width: 700px;
      width: 90%;
      background: #fff;
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.15);
      position: relative;
    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 2px solid #e9ecef;
      margin-bottom: 25px;
      padding-bottom: 15px;
    }

    .modal-header h2 {
      font-size: 1.8rem;
      color: #495057;
      margin: 0;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .modal-header h2 i {
      font-size: 1.6rem;
      color: #007bff;
    }

    .modal-close {
      background: #dc3545;
      color: #fff;
      border: none;
      border-radius: 5px;
      padding: 8px 16px;
      font-weight: 500;
      cursor: pointer;
      font-size: 0.9rem;
    }

    .modal-close:hover {
      background: #c82333;
    }

    .warning-box {
      background-color: #fff3cd;
      border-left: 4px solid #ffc107;
      padding: 10px 15px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
    }
    
    .warning-box i {
      font-size: 24px;
      color: #ffc107;
      margin-right: 10px;
    }
    
    .form-section {
      margin-bottom: 20px;
      padding: 15px;
      border: 1px solid #eee;
      border-radius: 8px;
      background: #f8f9fa;
    }

    .form-section h3 {
      margin-bottom: 15px;
      color: #495057;
      font-size: 1.2rem;
      font-weight: 600;
    }
    
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 15px;
    }
    
    .form-group {
      margin-bottom: 15px;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: 500;
      color: #495057;
    }
    
    .form-group input,
    .form-group select {
      width: 100%;
      padding: 8px 12px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 0.9rem;
      background: #fff;
      transition: border-color 0.2s;
    }

    .form-group input:focus,
    .form-group select:focus {
      border-color: #007bff;
      outline: none;
      box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
    }

    .required {
      color: #dc3545;
      font-weight: bold;
    }

    .button-group {
      text-align: center;
      margin-top: 25px;
    }

    .submit-btn {
      background: #007bff;
      color: #fff;
      border: none;
      border-radius: 5px;
      padding: 12px 24px;
      font-size: 1rem;
      font-weight: 500;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .submit-btn:hover {
      background: #0056b3;
    }

    .submit-btn i {
      font-size: 1.1rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .form-grid {
        grid-template-columns: 1fr;
      }
      
      .modal-content {
        width: 95%;
        padding: 20px;
        margin: 10px;
      }
    }

    @media (max-width: 600px) {
      .form-grid {
        grid-template-columns: 1fr;
      }
    }

    /* Container principal */
    .main-container {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
    }

    .page-header {
      text-align: center;
      margin-bottom: 30px;
      padding: 20px;
      background: linear-gradient(135deg, #007bff, #0056b3);
      color: white;
      border-radius: 15px;
    }

    .page-header h1 {
      margin: 0;
      font-size: 2.2rem;
      font-weight: 700;
    }

    .page-header p {
      margin: 10px 0 0 0;
      opacity: 0.9;
      font-size: 1.1rem;
    }
  </style>
</head>

<body>
  <div class="main-container">
    <div class="page-header">
      <h1><i class="bi bi-journal-plus"></i> Configuración de Cuentas Iniciales</h1>
      <p>Configure los saldos iniciales de sus cuentas bancarias</p>
    </div>

    <div class="modal-content">
      <form id="cuentasInicialesForm">
        <div class="warning-box">
          <i class="bi bi-exclamation-triangle"></i>
          <p>¿Está seguro(a) de guardar los cambios? Esta operación solo se podrá realizar una sola vez.</p>
        </div>
        
        <div class="form-section">
          <h3>Saldo Inicial de Cuentas</h3>
          <div class="form-grid">
            <div class="form-group">
              <label for="saldo-caja-general">Saldo Caja General</label>
              <input type="number" id="saldo-caja-general" name="saldo_caja_general" step="0.01" min="0" required>
            </div>
            
            <div class="form-group">
              <label for="banco-caja-general">Banco</label>
              <select id="banco-caja-general" name="banco_caja_general">
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
            
            <div class="form-group">
              <label for="numero-caja-general">Número de Cuenta</label>
              <input type="text" id="numero-caja-general" name="numero_caja_general">
            </div>
          </div>
        </div>
        
        <div class="form-section">
          <div class="form-grid">
            <div class="form-group">
              <label for="saldo-cta-corriente-1">Saldo Cuenta Corriente 1</label>
              <input type="number" id="saldo-cta-corriente-1" name="saldo_cta_corriente_1" step="0.01" min="0" required>
            </div>
            
            <div class="form-group">
              <label for="banco-cta-corriente-1">Banco</label>
              <select id="banco-cta-corriente-1" name="banco_cta_corriente_1">
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
            
            <div class="form-group">
              <label for="numero-cta-corriente-1">Número de Cuenta</label>
              <input type="text" id="numero-cta-corriente-1" name="numero_cta_corriente_1">
            </div>
          </div>
        </div>
        
        <div class="form-section">
          <div class="form-grid">
            <div class="form-group">
              <label for="saldo-cta-corriente-2">Saldo Cuenta Corriente 2</label>
              <input type="number" id="saldo-cta-corriente-2" name="saldo_cta_corriente_2" step="0.01" min="0" required>
            </div>
            
            <div class="form-group">
              <label for="banco-cta-corriente-2">Banco</label>
              <select id="banco-cta-corriente-2" name="banco_cta_corriente_2">
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
            
            <div class="form-group">
              <label for="numero-cta-corriente-2">Número de Cuenta</label>
              <input type="text" id="numero-cta-corriente-2" name="numero_cta_corriente_2">
            </div>
          </div>
        </div>
        
        <div class="form-section">
          <div class="form-grid">
            <div class="form-group">
              <label for="saldo-cuenta-ahorro">Saldo Cuenta de Ahorro</label>
              <input type="number" id="saldo-cuenta-ahorro" name="saldo_cuenta_ahorro" step="0.01" min="0" required>
            </div>
            
            <div class="form-group">
              <label for="banco-cuenta-ahorro">Banco</label>
              <select id="banco-cuenta-ahorro" name="banco_cuenta_ahorro">
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
            
            <div class="form-group">
              <label for="numero-cuenta-ahorro">Número de Cuenta</label>
              <input type="text" id="numero-cuenta-ahorro" name="numero_cuenta_ahorro">
            </div>
          </div>
        </div>
        
        <div class="form-section">
          <div class="form-group">
            <label for="responsable">Nombre Responsable <span class="required">*</span></label>
            <input type="text" id="responsable" name="responsable" required>
          </div>
        </div>
        
        <div class="button-group">
          <button type="submit" class="submit-btn">
            <i class="bi bi-save"></i> Guardar Cuentas Iniciales
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Notification div -->
  <div id="notification" class="notification"></div>

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
      notification.className = `notification ${tipo}`;
      notification.style.display = 'block';
      
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
</body>
</html>
@endsection
