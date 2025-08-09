@extends('layouts.nice', ['active' => 'registro-ingresos-egresos', 'title' => 'Registro de Ingresos y Egresos'])

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
        <span class="contable-breadcrumb-item active">Registro de Ingresos y Egresos</span>
    </nav>

    <!-- Sección principal -->
    <div class="contable-section">
        <div class="contable-header">
            <div>
                <h1 class="contable-title">
                    <i class="bi bi-journal-text"></i>
                    Registro de Ingresos y Egresos
                </h1>
                <p class="contable-subtitle">Registre y gestione los movimientos de entrada y salida de dinero</p>
            </div>
        </div>

        <div class="contable-body">
            <!-- Alerta informativa -->
            <div class="contable-alert contable-alert-info contable-mb-xl">
                <i class="bi bi-info-circle"></i>
                <div>
                    <p><strong>Seleccione el tipo de registro:</strong> Elija entre registro de ingresos (entradas de dinero) o egresos (salidas de dinero) para continuar.</p>
                </div>
            </div>
            
            <!-- Botones de selección modernizados -->
            <div class="contable-text-center">
                <div style="display: flex; gap: var(--spacing-xl); justify-content: center; flex-wrap: wrap;">
                    <button id="ingresosBtn" class="contable-btn contable-btn-success contable-btn-lg" style="min-width: 250px;">
                        <i class="bi bi-arrow-up-circle"></i> 
                        Registro de Ingresos
                    </button>
                    <button id="egresosBtn" class="contable-btn contable-btn-danger contable-btn-lg" style="min-width: 250px;">
                        <i class="bi bi-arrow-down-circle"></i> 
                        Registro de Egresos
                    </button>
                </div>
                
                <!-- Cards informativos -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: var(--spacing-xl); margin-top: var(--spacing-2xl);">
                    <div class="contable-card">
                        <div class="contable-card-body contable-text-center">
                            <div style="color: var(--success-color); font-size: 3rem; margin-bottom: var(--spacing-md);">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                            <h3 class="contable-card-title" style="color: var(--success-color);">Ingresos</h3>
                            <p class="contable-text-secondary">Registre entradas de dinero como pagos de clientes, cuotas, ventas y otros ingresos.</p>
                        </div>
                    </div>
                    
                    <div class="contable-card">
                        <div class="contable-card-body contable-text-center">
                            <div style="color: var(--danger-color); font-size: 3rem; margin-bottom: var(--spacing-md);">
                                <i class="bi bi-credit-card"></i>
                            </div>
                            <h3 class="contable-card-title" style="color: var(--danger-color);">Egresos</h3>
                            <p class="contable-text-secondary">Registre salidas de dinero como gastos operacionales, compras, servicios y otros pagos.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Efectos adicionales para los botones de selección */
    #ingresosBtn:hover {
        box-shadow: 0 8px 25px rgba(72, 187, 120, 0.4) !important;
    }
    
    #egresosBtn:hover {
        box-shadow: 0 8px 25px rgba(229, 62, 62, 0.4) !important;
    }
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

    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.7);
      backdrop-filter: blur(5px);
    }

    .modal.show {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .modal-content {
      background: #fff;
      border-radius: 12px;
      width: 90%;
      max-width: 800px;
      max-height: 90vh;
      overflow-y: auto;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
      animation: modalSlideIn 0.3s ease-out;
    }

    @keyframes modalSlideIn {
      from {
        opacity: 0;
        transform: scale(0.9) translateY(-20px);
      }
      to {
        opacity: 1;
        transform: scale(1) translateY(0);
      }
    }

    .modal-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 20px 30px;
      border-radius: 12px 12px 0 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .modal-header h2 {
      margin: 0;
      color: #fff;
      font-size: 1.5rem;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .modal-close {
      background: #dc3545;
      color: #fff;
      border: none;
      border-radius: 4px;
      padding: 6px 12px;
      cursor: pointer;
    }

    .modal-close:hover {
      background: #c82333;
    }

    .modal form {
      padding: 30px;
    }

    .modal label {
      display: block;
      margin-bottom: 8px;
      margin-top: 16px;
      font-weight: 600;
      color: #333;
    }

    .modal input,
    .modal select,
    .modal textarea {
      width: 100%;
      padding: 12px;
      border: 2px solid #e1e5e9;
      border-radius: 8px;
      font-size: 14px;
      transition: border-color 0.3s ease;
    }

    .modal input:focus,
    .modal select:focus,
    .modal textarea:focus {
      border-color: #667eea;
      outline: none;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .submit-btn {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: transform 0.2s ease;
      display: flex;
      align-items: center;
      gap: 8px;
      justify-content: center;
    }

    .submit-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    @media (max-width: 768px) {
      .modal-content {
        width: 95%;
        margin: 20px;
      }
      
      .modal form {
        padding: 20px;
      }
      
      .modal form > div[style*="grid"] {
        grid-template-columns: 1fr !important;
      }
    }
</style>

<!-- Modal de Ingresos -->
<div id="ingresosModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2><i class="bi bi-cash-coin"></i> Registro de Ingresos</h2>
      <button class="modal-close" id="closeIngresosModal">Cerrar</button>
    </div>

    <form id="ingresosForm">
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <!-- Columna 1 -->
        <div>
          <label for="fecha-ingresos">Fecha</label>
          <input type="date" id="fecha-ingresos" name="fecha" required style="width: 100%;">

          <label for="nro-dcto-ingresos">N° Comprobante</label>
          <input type="text" id="nro-dcto-ingresos" name="nro_dcto" placeholder="Ingrese número de comprobante" required style="width: 100%;">

          <label for="categoria-ingresos">Categoría de Ingreso</label>
          <select id="categoria-ingresos" name="categoria" required style="width: 100%;">
            <option value="">-- Selecciona una categoría --</option>
            <option value="venta_agua">Venta de Agua (Total Consumo)</option>
            <option value="cuotas_incorporacion">Cuotas de Incorporación (Cuotas de Incorporación)</option>
            <option value="venta_medidores">Venta de Medidores (Otros Ingresos)</option>
            <option value="trabajos_domicilio">Trabajos en Domicilio (Otros Ingresos)</option>
            <option value="subsidios">Subsidios (Otros Ingresos)</option>
            <option value="otros_aportes">Otros Aportes (Otros Ingresos)</option>
            <option value="multas_inasistencia">Multas Inasistencia (Otros Ingresos)</option>
            <option value="otras_multas">Otras Multas (Otros Ingresos)</option>
          </select>

          <label for="cuenta-destino">Cuenta Destino</label>
          <select id="cuenta-destino" name="cuenta_destino" required style="width: 100%;">
            <option value="">-- Selecciona una cuenta --</option>
            <option value="caja_general">Caja General</option>
            <option value="cuenta_corriente_1">Cuenta Corriente 1</option>
            <option value="cuenta_corriente_2">Cuenta Corriente 2</option>
            <option value="cuenta_ahorro">Cuenta de Ahorro</option>
          </select>
        </div>

        <!-- Columna 2 -->
        <div>
          <label for="descripcion-ingresos">Descripción</label>
          <textarea id="descripcion-ingresos" name="descripcion" required
                    style="width: 100%; padding: 14px; min-height: 100px; border: 1px solid #ddd; border-radius: 8px; resize: vertical;"></textarea>

          <label for="monto-ingresos">Monto</label>
          <input type="number" id="monto-ingresos" name="monto" step="0.01" required class="monto-input" style="width: 100%;">
        </div>
      </div>

      <div style="grid-column: span 2; margin-top: 20px; display: flex; gap: 15px;">
        <button type="submit" class="submit-btn" style="flex: 1;">
          <i class="bi bi-save"></i> Registrar Ingreso
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Modal de Egresos -->
<div id="egresosModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2><i class="bi bi-credit-card"></i> Registro de Egresos</h2>
      <button class="modal-close" id="closeEgresosModal">Cerrar</button>
    </div>
    <form id="egresosForm">
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <!-- Columna 1 -->
        <div>
          <label for="fecha-egresos">Fecha</label>
          <input type="date" id="fecha-egresos" name="fecha" required style="width: 100%;">

          <label for="nro-dcto-egresos">N° Boleta/Factura</label>
          <input type="text" id="nro-dcto-egresos" name="nro_dcto" placeholder="Ingrese número de comprobante" required style="width: 100%;">

          <label for="categoria-egresos">Categoría de Egreso</label>
          <select id="categoria-egresos" name="categoria" required style="width: 100%;">
            <option value="">-- Selecciona una categoría --</option>
            <option value="energia_electrica">Energía Eléctrica ->(Gastos de Operación)</option>
            <option value="sueldos">Sueldos y Leyes Sociales ->(Gastos de Operación)</option>
            <option value="otras_cuentas">Otras Ctas. (Agua, Int. Cel.) ->(Gastos de Operación)</option>
            <option value="mantencion">Mantención y reparaciones Instalaciones ->(Gastos de Mantención)</option>
            <option value="insumos_oficina">Insumos y Materiales (Oficina) ->(Gastos de Administración)</option>
            <option value="materiales_red">Materiales e Insumos (Red) ->(Gastos de Mejoramiento)</option>
            <option value="viaticos">Viáticos / Seguros / Movilización ->(Otros Gastos)</option>
            <option value="trabajos_domicilio">Gastos por Trabajos en domicilio ->(Gastos de Mantención)</option>
            <option value="mejoramiento">Mejoramiento / Inversiones ->(Gastos de Mejoramiento)</option>
          </select>

          <label for="cuenta-origen">Cuenta Origen</label>
          <select id="cuenta-origen" name="cuenta_origen" required style="width: 100%;">
            <option value="">-- Selecciona una cuenta --</option>
            <option value="caja_general">Caja General</option>
            <option value="cuenta_corriente_1">Cuenta Corriente 1</option>
            <option value="cuenta_corriente_2">Cuenta Corriente 2</option>
            <option value="cuenta_ahorro">Cuenta de Ahorro</option>
          </select>
        </div>

        <!-- Columna 2 -->
        <div>
          <label for="proveedor">Razón Social Proveedor</label>
          <input type="text" id="razon_social" name="razon_social" placeholder="Razón Social" required style="width: 100%;">

          <label for="domicilio">R.U.T.</label>
          <input type="text" id="rut" name="rut_proveedor" placeholder="RUT del Proveedor" style="width: 100%;">

          <label for="descripcion-egresos">Descripción</label>
            <textarea id="descripcion-egresos" name="descripcion" required
                      style="width: 100%; padding: 14px; min-height: 100px; border: 1px solid #ddd; border-radius: 8px; resize: vertical;"></textarea>

          <label for="monto-egresos">Monto</label>
          <input type="number" id="monto-egresos" name="monto" step="0.01" required class="monto-input" style="width: 100%;">
        </div>
      </div>

      <div style="grid-column: span 2; margin-top: 20px; display: flex; gap: 15px;">
        <button type="submit" class="submit-btn" style="flex: 1;">
          <i class="bi bi-save"></i> Registrar Egreso
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let comprobanteCounter = parseInt(localStorage.getItem('comprobanteCounter')) || 1;

    // Funciones auxiliares
    function generarNumeroComprobante(tipo) {
      const prefijo = tipo === 'ingreso' ? 'I-' : 'E-';
      const numero = prefijo + comprobanteCounter.toString().padStart(4, '0');
      comprobanteCounter++;
      localStorage.setItem('comprobanteCounter', comprobanteCounter.toString());
      return numero;
    }

    function obtenerFechaActual() {
      return new Date().toISOString().split('T')[0];
    }

    function showNotification(message, type = 'success') {
      const notification = document.createElement('div');
      notification.className = `notification ${type}`;
      notification.textContent = message;
      document.body.appendChild(notification);
      
      notification.style.display = 'block';
      
      setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => {
          document.body.removeChild(notification);
        }, 200);
      }, 3000);
    }

    // Event listeners para botones principales
    document.getElementById('ingresosBtn').addEventListener('click', () => {
      document.getElementById('ingresosModal').classList.add('show');
      const nroDctoInput = document.getElementById('nro-dcto-ingresos');
      if (nroDctoInput) {
        nroDctoInput.value = generarNumeroComprobante('ingreso');
        nroDctoInput.readOnly = true;
      }
      const fechaInput = document.getElementById('fecha-ingresos');
      if (fechaInput) {
        fechaInput.value = obtenerFechaActual();
        fechaInput.readOnly = true;
      }
    });

    document.getElementById('egresosBtn').addEventListener('click', () => {
      document.getElementById('egresosModal').classList.add('show');
      const nroDctoInput = document.getElementById('nro-dcto-egresos');
      if (nroDctoInput) {
        nroDctoInput.value = generarNumeroComprobante('egreso');
        nroDctoInput.readOnly = true;
      }
      const fechaInput = document.getElementById('fecha-egresos');
      if (fechaInput) {
        fechaInput.value = obtenerFechaActual();
        fechaInput.readOnly = true;
      }
    });

    // Event listeners para cierre de modales
    document.getElementById('closeIngresosModal').addEventListener('click', () => {
      document.getElementById('ingresosModal').classList.remove('show');
      document.getElementById('ingresosForm').reset();
    });

    document.getElementById('closeEgresosModal').addEventListener('click', () => {
      document.getElementById('egresosModal').classList.remove('show');
      document.getElementById('egresosForm').reset();
    });

    // Event listeners para formularios
    document.getElementById('ingresosForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const data = Object.fromEntries(formData);
      
      // Validar datos
      if (!data.fecha || !data.nro_dcto || !data.categoria || !data.cuenta_destino || !data.descripcion || !data.monto) {
        showNotification('Por favor complete todos los campos requeridos', 'error');
        return;
      }

      if (parseFloat(data.monto) <= 0) {
        showNotification('El monto debe ser mayor a 0', 'error');
        return;
      }

      // Guardar en localStorage
      let movimientos = JSON.parse(localStorage.getItem('movimientos')) || [];
      const nuevoMovimiento = {
        id: Date.now(),
        tipo: 'ingreso',
        fecha: data.fecha,
        comprobante: data.nro_dcto,
        categoria: data.categoria,
        cuenta: data.cuenta_destino,
        descripcion: data.descripcion,
        monto: parseFloat(data.monto),
        timestamp: new Date().toISOString()
      };

      movimientos.push(nuevoMovimiento);
      localStorage.setItem('movimientos', JSON.stringify(movimientos));

      showNotification('Ingreso registrado exitosamente');
      document.getElementById('ingresosModal').classList.remove('show');
      this.reset();
    });

    document.getElementById('egresosForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const data = Object.fromEntries(formData);
      
      // Validar datos
      if (!data.fecha || !data.nro_dcto || !data.categoria || !data.cuenta_origen || !data.razon_social || !data.descripcion || !data.monto) {
        showNotification('Por favor complete todos los campos requeridos', 'error');
        return;
      }

      if (parseFloat(data.monto) <= 0) {
        showNotification('El monto debe ser mayor a 0', 'error');
        return;
      }

      // Guardar en localStorage
      let movimientos = JSON.parse(localStorage.getItem('movimientos')) || [];
      const nuevoMovimiento = {
        id: Date.now(),
        tipo: 'egreso',
        fecha: data.fecha,
        comprobante: data.nro_dcto,
        categoria: data.categoria,
        cuenta: data.cuenta_origen,
        proveedor: data.razon_social,
        rut_proveedor: data.rut_proveedor,
        descripcion: data.descripcion,
        monto: parseFloat(data.monto),
        timestamp: new Date().toISOString()
      };

      movimientos.push(nuevoMovimiento);
      localStorage.setItem('movimientos', JSON.stringify(movimientos));

      showNotification('Egreso registrado exitosamente');
      document.getElementById('egresosModal').classList.remove('show');
      this.reset();
    });

    // Cerrar modal al hacer clic fuera
    window.addEventListener('click', function(e) {
      const ingresosModal = document.getElementById('ingresosModal');
      const egresosModal = document.getElementById('egresosModal');
      
      if (e.target === ingresosModal) {
        ingresosModal.classList.remove('show');
      }
      if (e.target === egresosModal) {
        egresosModal.classList.remove('show');
      }
    });
  });
</script>
@endsection
