
<style>
  /* Variables CSS modernas basadas en el index */
  :root {
    --primary-color: #2c5282;
    --secondary-color: #4CAF50;
    --danger-color: #e53e3e;
    --success-color: #48bb78;
    --warning-color: #dd6b20;
    --purple-color: #805ad5;
    --orange-color: #dd6b20;
    --light-bg: #f8f9fa;
    --text-primary: #2d3748;
    --border-color: #e2e8f0;
    --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    --radius-sm: 0.25rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-xl: 1rem;
  }

  /* Reset y base */
  * {
    box-sizing: border-box;
  }

  /* Prevenimos problemas de viewport en móviles */
  body.modal-open {
    overflow: hidden;
    position: fixed;
    width: 100%;
  }

  /* Overlay del modal - DISEÑO MEJORADO */
  #cuentasInicialesModal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(8px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    padding: 1rem;
    animation: fadeIn 0.3s ease-out;
    overflow-y: auto;
  }

  /* Estado visible del modal */
  #cuentasInicialesModal.show {
    display: flex !important;
  }

  /* Animaciones mejoradas */
  @keyframes fadeIn {
    from { 
      opacity: 0; 
      backdrop-filter: blur(0px);
    }
    to { 
      opacity: 1; 
      backdrop-filter: blur(8px);
    }
  }

  @keyframes slideUp {
    from { 
      opacity: 0; 
      transform: translateY(30px) scale(0.95); 
    }
    to { 
      opacity: 1; 
      transform: translateY(0) scale(1); 
    }
  }

  /* Contenedor principal del modal - DISEÑO MEJORADO */
  .modal-content {
    background: white;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-xl);
    width: 100%;
    max-width: 1100px;
    max-height: 90vh;
    overflow-y: auto;
    animation: slideUp 0.4s ease-out;
    position: relative;
    margin: auto;
    border: 1px solid var(--border-color);
  }

  /* Redimensionable solo en pantallas grandes */
  @media (min-width: 768px) {
    .modal-content {
      resize: both;
      min-width: 700px;
      min-height: 500px;
    }
  }

  /* Header del modal - DISEÑO MEJORADO */
  .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px 32px;
    border-bottom: 1px solid var(--border-color);
    background: linear-gradient(135deg, var(--primary-color) 0%, #4299e1 100%);
    border-radius: var(--radius-xl) var(--radius-xl) 0 0;
    color: white;
    position: relative;
    overflow: hidden;
  }

  .modal-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
    pointer-events: none;
  }

  .modal-header h2 {
    font-size: 1.875rem;
    font-weight: 700;
    color: white;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.875rem;
    max-width: 75%;
    position: relative;
    z-index: 1;
  }

  .modal-header h2 i {
    font-size: 2.25rem;
    opacity: 0.95;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
  }

  .modal-close {
    background: var(--danger-color);
    color: white;
    border: none;
    border-radius: var(--radius-md);
    padding: 10px 18px;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    position: relative;
    z-index: 1;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
  }

  .modal-close:hover {
    background: #c53030;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(197, 48, 48, 0.3);
  }

  /* Contenido del modal - DISEÑO MEJORADO */
  .modal-body {
    padding: 32px;
    background: #fafbfc;
  }

  /* Alerta de advertencia - DISEÑO MEJORADO */
  .alert-warning {
    background: linear-gradient(135deg, #fff3cd 0%, #fef3cd 100%);
    border: 1px solid #f4c430;
    border-left: 4px solid var(--warning-color);
    border-radius: var(--radius-lg);
    padding: 20px 24px;
    margin-bottom: 28px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    box-shadow: var(--shadow-sm);
  }

  .alert-warning i {
    color: var(--warning-color);
    font-size: 1.25rem;
    margin-top: 2px;
    flex-shrink: 0;
  }

  .alert-warning p {
    margin: 0;
    color: #8b4513;
    font-weight: 500;
    line-height: 1.5;
  }

  /* Secciones del formulario - DISEÑO MEJORADO */
  .form-section {
    background: white;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
    margin-bottom: 24px;
    overflow: hidden;
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
  }

  .form-section:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
  }

  .form-section-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    padding: 20px 28px;
    border-bottom: 1px solid var(--border-color);
  }

  .form-section-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .form-section-header h3 i {
    color: var(--primary-color);
    font-size: 1.375rem;
  }

  .form-section-body {
    padding: 28px;
  }

  /* Grid del formulario */
  .form-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
  }

  @media (max-width: 768px) {
    .form-grid {
      grid-template-columns: 1fr;
    }
  }

  /* Grupos de formulario - estilo del index */
  .form-group {
    margin-bottom: 20px;
  }

  .form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.95rem;
  }

  .form-group .required {
    color: var(--danger-color);
    margin-left: 0.25rem;
  }

  /* Inputs modernos - DISEÑO MEJORADO */
  .form-input {
    width: 100%;
    padding: 16px 18px;
    border: 2px solid var(--border-color);
    border-radius: var(--radius-lg);
    font-size: 16px;
    color: var(--text-primary);
    background-color: #f8fafc;
    transition: all 0.3s ease;
    font-weight: 500;
  }

  .form-input:focus {
    border-color: var(--primary-color);
    background-color: #fff;
    box-shadow: 0 0 0 4px rgba(44, 82, 130, 0.15);
    outline: none;
    transform: translateY(-1px);
  }

  .form-input:hover {
    background-color: #fff;
    border-color: #cbd5e0;
  }

  /* Select mejorado - sin flechas personalizadas */
  .form-select {
    cursor: pointer;
  }

  /* Botones modernos - DISEÑO MEJORADO */
  .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.625rem;
    padding: 16px 28px;
    border: none;
    border-radius: var(--radius-lg);
    font-weight: 700;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    position: relative;
    overflow: hidden;
  }
    text-decoration: none;
  }

  .btn-primary {
    background: linear-gradient(135deg, var(--secondary-color) 0%, #388e3c 100%);
    color: white;
    width: 100%;
    box-shadow: 0 4px 8px rgba(76, 175, 80, 0.2);
    font-size: 18px;
    padding: 18px 32px;
  }

  .btn-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    transition: left 0.6s;
  }

  .btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(76, 175, 80, 0.4);
  }

  .btn-primary:hover::before {
    left: 100%;
  }

  .btn-primary:active {
    transform: translateY(-1px);
  }

  /* Footer del modal - DISEÑO MEJORADO */
  .modal-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    padding: 24px 32px;
    border-top: 1px solid var(--border-color);
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-radius: 0 0 var(--radius-xl) var(--radius-xl);
  }

  /* Estados de loading y disabled */
  .btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none !important;
  }

  .form-input:disabled {
    background-color: #f1f5f9;
    color: #64748b;
    cursor: not-allowed;
  }

  /* Responsivo mejorado */
  @media (max-width: 1024px) {
    .modal-content {
      max-width: 95%;
      margin: 1rem auto;
    }
  }

  @media (max-width: 768px) {
    #cuentasInicialesModal {
      padding: 0.25rem;
      align-items: flex-start;
      padding-top: 2rem;
    }

    .modal-content {
      width: 98%;
      max-width: none;
      min-width: unset;
      resize: none;
      max-height: 95vh;
      margin: 0;
    }

    .modal-header {
      padding: 15px 20px;
      margin-bottom: 15px;
    }

    .modal-header h2 {
      font-size: 1.25rem;
    }

    .modal-body {
      padding: 15px 20px;
    }

    .form-section-body {
      padding: 15px;
    }

    .form-grid {
      grid-template-columns: 1fr !important;
      gap: 15px;
    }

    .modal-footer {
      padding: 15px 20px;
    }

    .btn {
      width: 100%;
      justify-content: center;
      padding: 12px 20px;
    }
  }

  @media (max-width: 480px) {
    #cuentasInicialesModal {
      padding: 0.125rem;
      align-items: flex-start;
      padding-top: 1rem;
    }

    .modal-content {
      width: 99%;
      border-radius: var(--radius-md);
    }

    .modal-header {
      padding: 12px 15px;
      border-radius: var(--radius-md) var(--radius-md) 0 0;
    }

    .modal-header h2 {
      font-size: 1.1rem;
    }

    .modal-body {
      padding: 12px 15px;
    }

    .form-section-body {
      padding: 12px;
    }

    .form-input {
      padding: 12px;
      font-size: 16px; /* Evita zoom en iOS */
    }

    .modal-footer {
      padding: 12px 15px;
      border-radius: 0 0 var(--radius-md) var(--radius-md);
    }
  }

  /* Indicador de redimensionamiento */
  .resize-handle {
    position: absolute;
    right: 5px;
    bottom: 5px;
    width: 20px;
    height: 20px;
    cursor: se-resize;
    background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%232c5282'%3E%3Cpath d='M22 22H10V20H20V10H22V22Z'/%3E%3C/svg%3E") no-repeat center;
    opacity: 0.5;
    transition: opacity 0.2s;
  }

  @media (min-width: 768px) {
    .resize-handle {
      display: block;
    }
  }

  @media (max-width: 767px) {
    .resize-handle {
      display: none;
    }
  }
</style>

<div id="cuentasInicialesModal" class="modal">
  <div class="modal-content">
    <!-- Header -->
    <div class="modal-header">
      <h2>
        <i class="bi bi-bank2"></i>
        Configuración de Cuentas Iniciales
      </h2>
      <button class="modal-close" id="closeCuentasInicialesModal">
        <i class="bi bi-x-lg"></i>
        Cerrar
      </button>
    </div>

    <!-- Body -->
    <div class="modal-body">
      <!-- Alerta de advertencia -->
      <div class="alert-warning">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <p><strong>Importante:</strong> Esta configuración solo se puede realizar una vez. Asegúrese de que todos los datos sean correctos antes de guardar.</p>
      </div>

      <form id="cuentasInicialesForm">
        <!-- Caja General -->
        <div class="form-section">
          <div class="form-section-header">
            <h3>
              <i class="bi bi-cash-stack"></i>
              Caja General
            </h3>
          </div>
          <div class="form-section-body">
            <div class="form-grid three-cols">
              <div class="form-group">
                <label for="saldo-caja-general">
                  Saldo Inicial
                  <span class="required">*</span>
                </label>
                <input 
                  type="number" 
                  id="saldo-caja-general" 
                  name="saldo_caja_general" 
                  class="form-input"
                  step="0.01" 
                  min="0" 
                  placeholder="0.00"
                  required
                >
              </div>
              <div class="form-group">
                <label for="banco-caja-general">Banco</label>
                <select id="banco-caja-general" name="banco_caja_general" class="form-input form-select">
                  <option value="">Seleccionar banco</option>
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
                <input 
                  type="text" 
                  id="numero-caja-general" 
                  name="numero_caja_general"
                  class="form-input"
                  placeholder="Ej: 12345678-9"
                >
              </div>
            </div>
          </div>
        </div>

        <!-- Cuenta Corriente 1 -->
        <div class="form-section">
          <div class="form-section-header">
            <h3>
              <i class="bi bi-credit-card"></i>
              Cuenta Corriente 1
            </h3>
          </div>
          <div class="form-section-body">
            <div class="form-grid three-cols">
              <div class="form-group">
                <label for="saldo-cta-corriente-1">
                  Saldo Inicial
                  <span class="required">*</span>
                </label>
                <input 
                  type="number" 
                  id="saldo-cta-corriente-1" 
                  name="saldo_cta_corriente_1" 
                  class="form-input"
                  step="0.01" 
                  min="0" 
                  placeholder="0.00"
                  required
                >
              </div>
              <div class="form-group">
                <label for="banco-cta-corriente-1">Banco</label>
                <select id="banco-cta-corriente-1" name="banco_cta_corriente_1" class="form-input form-select">
                  <option value="">Seleccionar banco</option>
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
                <input 
                  type="text" 
                  id="numero-cta-corriente-1" 
                  name="numero_cta_corriente_1"
                  class="form-input"
                  placeholder="Ej: 12345678-9"
                >
              </div>
            </div>
          </div>
        </div>

        <!-- Cuenta Corriente 2 -->
        <div class="form-section">
          <div class="form-section-header">
            <h3>
              <i class="bi bi-credit-card-2-front"></i>
              Cuenta Corriente 2
            </h3>
          </div>
          <div class="form-section-body">
            <div class="form-grid three-cols">
              <div class="form-group">
                <label for="saldo-cta-corriente-2">
                  Saldo Inicial
                  <span class="required">*</span>
                </label>
                <input 
                  type="number" 
                  id="saldo-cta-corriente-2" 
                  name="saldo_cta_corriente_2" 
                  class="form-input"
                  step="0.01" 
                  min="0" 
                  placeholder="0.00"
                  required
                >
              </div>
              <div class="form-group">
                <label for="banco-cta-corriente-2">Banco</label>
                <select id="banco-cta-corriente-2" name="banco_cta_corriente_2" class="form-input form-select">
                  <option value="">Seleccionar banco</option>
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
                <input 
                  type="text" 
                  id="numero-cta-corriente-2" 
                  name="numero_cta_corriente_2"
                  class="form-input"
                  placeholder="Ej: 12345678-9"
                >
              </div>
            </div>
          </div>
        </div>

        <!-- Cuenta de Ahorro -->
        <div class="form-section">
          <div class="form-section-header">
            <h3>
              <i class="bi bi-piggy-bank"></i>
              Cuenta de Ahorro
            </h3>
          </div>
          <div class="form-section-body">
            <div class="form-grid three-cols">
              <div class="form-group">
                <label for="saldo-cuenta-ahorro">
                  Saldo Inicial
                  <span class="required">*</span>
                </label>
                <input 
                  type="number" 
                  id="saldo-cuenta-ahorro" 
                  name="saldo_cuenta_ahorro" 
                  class="form-input"
                  step="0.01" 
                  min="0" 
                  placeholder="0.00"
                  required
                >
              </div>
              <div class="form-group">
                <label for="banco-cuenta-ahorro">Banco</label>
                <select id="banco-cuenta-ahorro" name="banco_cuenta_ahorro" class="form-input form-select">
                  <option value="">Seleccionar banco</option>
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
                <input 
                  type="text" 
                  id="numero-cuenta-ahorro" 
                  name="numero_cuenta_ahorro"
                  class="form-input"
                  placeholder="Ej: 12345678-9"
                >
              </div>
            </div>
          </div>
        </div>

        <!-- Responsable -->
        <div class="form-section">
          <div class="form-section-header">
            <h3>
              <i class="bi bi-person-check"></i>
              Información del Responsable
            </h3>
          </div>
          <div class="form-section-body">
            <div class="form-group">
              <label for="responsable">
                Nombre del Responsable
                <span class="required">*</span>
              </label>
              <input 
                type="text" 
                id="responsable" 
                name="responsable" 
                class="form-input"
                placeholder="Ingrese el nombre completo del responsable"
                required
              >
            </div>
          </div>
        </div>
      </form>
    </div>

    <!-- Footer -->
    <div class="modal-footer">
      <button type="submit" form="cuentasInicialesForm" class="btn btn-primary">
        <i class="bi bi-check-circle"></i>
        Guardar Configuración
      </button>
    </div>

    <!-- Indicador de redimensionamiento -->
    <div class="resize-handle"></div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const modal = document.getElementById('cuentasInicialesModal');
  const closeBtn = document.getElementById('closeCuentasInicialesModal');
  
  // Función para abrir modal - MEJORADA
  function openModal() {
    modal.classList.add('show');
    document.body.classList.add('modal-open');
    
    // Animación de entrada suave
    requestAnimationFrame(() => {
      modal.style.opacity = '1';
    });
    
    // Evitar scroll en móviles
    if (window.innerWidth <= 768) {
      const scrollY = window.scrollY;
      document.body.style.position = 'fixed';
      document.body.style.top = `-${scrollY}px`;
      document.body.style.width = '100%';
      document.body.dataset.scrollY = scrollY;
    }
  }
  
  // Función para cerrar modal - MEJORADA
  function closeModal() {
    // Animación de salida
    modal.style.opacity = '0';
    
    setTimeout(() => {
      modal.classList.remove('show');
      document.body.classList.remove('modal-open');
      
      // Restaurar scroll en móviles
      if (window.innerWidth <= 768) {
        const scrollY = document.body.dataset.scrollY || '0';
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.style.width = '';
        window.scrollTo(0, parseInt(scrollY));
        delete document.body.dataset.scrollY;
      }
    }, 300);
  }
  
  // Hacer las funciones globales para que puedan ser llamadas desde otros elementos
  window.openCuentasInicialesModal = openModal;
  window.closeCuentasInicialesModal = closeModal;
  
  // Event listeners
  if (closeBtn) {
    closeBtn.addEventListener('click', closeModal);
  }
  
  // Cerrar al hacer clic fuera del modal (solo en desktop)
  modal.addEventListener('click', function(e) {
    if (e.target === modal && window.innerWidth > 768) {
      closeModal();
    }
  });
  
  // Cerrar con tecla ESC
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && modal.classList.contains('show')) {
      closeModal();
    }
  });
  
  // Mejorar experiencia de redimensionado
  window.addEventListener('resize', function() {
    if (window.innerWidth <= 768 && modal.classList.contains('show')) {
      const modalContent = modal.querySelector('.modal-content');
      modalContent.style.maxHeight = '95vh';
    }
  });
  
  // Agregar efecto de hover a los campos de formulario
  const formInputs = modal.querySelectorAll('.form-input');
  formInputs.forEach(input => {
    input.addEventListener('focus', function() {
      this.parentElement.style.transform = 'translateY(-2px)';
    });
    
    input.addEventListener('blur', function() {
      this.parentElement.style.transform = 'translateY(0)';
    });
  });
  
  // Inicializar modal según el parámetro de URL
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get('modal') === 'cuentas_iniciales') {
    openModal();
  }
  
  console.log('🎉 Modal de Cuentas Iniciales inicializado correctamente!');
});
</script>
