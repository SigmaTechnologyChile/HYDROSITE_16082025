{{-- 
  Estilos modernos para el módulo contable
  Basado en el design system del index y aplicado consistentemente
--}}

<style>
  /* ================================
     VARIABLES CSS DESIGN SYSTEM
     ================================ */
  :root {
    --primary-color: #2c5282;
    --secondary-color: #4CAF50;
    --danger-color: #e53e3e;
    --success-color: #48bb78;
    --warning-color: #dd6b20;
    --info-color: #4299e1;
    --purple-color: #805ad5;
    --orange-color: #dd6b20;
    --light-bg: #f8f9fa;
    --text-primary: #2d3748;
    --text-secondary: #4a5568;
    --text-muted: #718096;
    --border-color: #e2e8f0;
    --hover-border: #cbd5e0;
    --focus-border: #4299e1;
    
    /* Sombras modernas */
    --shadow-xs: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    
    /* Radius modernos */
    --radius-xs: 0.125rem;
    --radius-sm: 0.25rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-xl: 1rem;
    --radius-2xl: 1.5rem;
    --radius-full: 9999px;
    
    /* Espaciado */
    --spacing-xs: 0.25rem;
    --spacing-sm: 0.5rem;
    --spacing-md: 1rem;
    --spacing-lg: 1.5rem;
    --spacing-xl: 2rem;
    --spacing-2xl: 3rem;
  }

  /* ================================
     RESET Y BASE
     ================================ */
  * {
    box-sizing: border-box;
  }

  /* ================================
     CONTENEDORES PRINCIPALES
     ================================ */
  .contable-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: var(--spacing-lg);
  }

  .contable-section {
    background: white;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-lg);
    margin-bottom: var(--spacing-xl);
    overflow: hidden;
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
  }

  .contable-section:hover {
    box-shadow: var(--shadow-xl);
    transform: translateY(-2px);
  }

  /* ================================
     HEADERS Y TÍTULOS
     ================================ */
  .contable-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, #4299e1 100%);
    color: white;
    padding: var(--spacing-xl) var(--spacing-xl);
    position: relative;
    overflow: hidden;
  }

  .contable-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
    pointer-events: none;
  }

  .contable-title {
    font-size: 1.875rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    position: relative;
    z-index: 1;
  }

  .contable-title i {
    font-size: 2.25rem;
    opacity: 0.95;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
  }

  .contable-subtitle {
    font-size: 1rem;
    opacity: 0.9;
    margin: var(--spacing-sm) 0 0 0;
    position: relative;
    z-index: 1;
  }

  /* ================================
     CONTENIDO DE SECCIONES
     ================================ */
  .contable-body {
    padding: var(--spacing-2xl);
    background: #fafbfc;
  }

  /* ================================
     ALERTAS Y NOTIFICACIONES
     ================================ */
  .contable-alert {
    display: flex;
    align-items: flex-start;
    gap: var(--spacing-md);
    padding: var(--spacing-lg) var(--spacing-xl);
    border-radius: var(--radius-lg);
    margin-bottom: var(--spacing-xl);
    box-shadow: var(--shadow-sm);
    font-weight: 500;
    line-height: 1.5;
  }

  .contable-alert i {
    font-size: 1.25rem;
    margin-top: 2px;
    flex-shrink: 0;
  }

  .contable-alert p {
    margin: 0;
  }

  .contable-alert-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    border: 1px solid #b8dabd;
    border-left: 4px solid var(--success-color);
    color: #155724;
  }

  .contable-alert-success i {
    color: var(--success-color);
  }

  .contable-alert-warning {
    background: linear-gradient(135deg, #fff3cd 0%, #fef3cd 100%);
    border: 1px solid #f4c430;
    border-left: 4px solid var(--warning-color);
    color: #8b4513;
  }

  .contable-alert-warning i {
    color: var(--warning-color);
  }

  .contable-alert-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    border: 1px solid #f1b0b7;
    border-left: 4px solid var(--danger-color);
    color: #721c24;
  }

  .contable-alert-danger i {
    color: var(--danger-color);
  }

  .contable-alert-info {
    background: linear-gradient(135deg, #cce7ff 0%, #b8dcff 100%);
    border: 1px solid #a3d2ff;
    border-left: 4px solid var(--info-color);
    color: #004085;
  }

  .contable-alert-info i {
    color: var(--info-color);
  }

  /* ================================
     FORMULARIOS MODERNOS
     ================================ */
  .contable-form-section {
    background: white;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
    margin-bottom: var(--spacing-xl);
    overflow: hidden;
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
  }

  .contable-form-section:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
  }

  .contable-form-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    padding: var(--spacing-lg) var(--spacing-xl);
    border-bottom: 1px solid var(--border-color);
  }

  .contable-form-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
  }

  .contable-form-header h3 i {
    color: var(--primary-color);
    font-size: 1.375rem;
  }

  .contable-form-body {
    padding: var(--spacing-xl);
  }

  .contable-form-grid {
    display: grid;
    gap: var(--spacing-lg);
  }

  .contable-form-grid-2 {
    grid-template-columns: repeat(2, 1fr);
  }

  .contable-form-grid-3 {
    grid-template-columns: repeat(3, 1fr);
  }

  .contable-form-grid-4 {
    grid-template-columns: repeat(4, 1fr);
  }

  @media (max-width: 1024px) {
    .contable-form-grid-3,
    .contable-form-grid-4 {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (max-width: 768px) {
    .contable-form-grid-2,
    .contable-form-grid-3,
    .contable-form-grid-4 {
      grid-template-columns: 1fr;
    }
  }

  .contable-form-group {
    margin-bottom: var(--spacing-lg);
  }

  .contable-form-label {
    display: block;
    margin-bottom: var(--spacing-sm);
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.95rem;
  }

  .contable-form-label .required {
    color: var(--danger-color);
    margin-left: var(--spacing-xs);
  }

  .contable-form-input {
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

  .contable-form-input:focus {
    border-color: var(--primary-color);
    background-color: #fff;
    box-shadow: 0 0 0 4px rgba(44, 82, 130, 0.15);
    outline: none;
    transform: translateY(-1px);
  }

  .contable-form-input:hover {
    background-color: #fff;
    border-color: var(--hover-border);
  }

  .contable-form-input:disabled {
    background-color: #f1f5f9;
    color: #64748b;
    cursor: not-allowed;
  }

  .contable-form-select {
    cursor: pointer;
  }

  .contable-form-textarea {
    resize: vertical;
    min-height: 100px;
  }

  /* ================================
     BOTONES MODERNOS
     ================================ */
  .contable-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-sm);
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
    font-family: inherit;
  }

  .contable-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none !important;
  }

  .contable-btn-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, #4299e1 100%);
    color: white;
    box-shadow: var(--shadow-md);
  }

  .contable-btn-primary:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
  }

  .contable-btn-success {
    background: linear-gradient(135deg, var(--secondary-color) 0%, #388e3c 100%);
    color: white;
    box-shadow: var(--shadow-md);
  }

  .contable-btn-success:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(76, 175, 80, 0.4);
  }

  .contable-btn-danger {
    background: linear-gradient(135deg, var(--danger-color) 0%, #c53030 100%);
    color: white;
    box-shadow: var(--shadow-md);
  }

  .contable-btn-danger:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(229, 62, 62, 0.4);
  }

  .contable-btn-warning {
    background: linear-gradient(135deg, var(--warning-color) 0%, #c05621 100%);
    color: white;
    box-shadow: var(--shadow-md);
  }

  .contable-btn-warning:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(221, 107, 32, 0.4);
  }

  .contable-btn-outline {
    background: transparent;
    color: var(--primary-color);
    border: 2px solid var(--primary-color);
  }

  .contable-btn-outline:hover:not(:disabled) {
    background: var(--primary-color);
    color: white;
    transform: translateY(-2px);
  }

  .contable-btn-sm {
    padding: 12px 20px;
    font-size: 14px;
  }

  .contable-btn-lg {
    padding: 20px 36px;
    font-size: 18px;
  }

  .contable-btn-full {
    width: 100%;
  }

  /* Efecto de brillo en botones */
  .contable-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    transition: left 0.6s;
  }

  .contable-btn:hover::before {
    left: 100%;
  }

  /* ================================
     TABLAS MODERNAS
     ================================ */
  .contable-table-wrapper {
    background: white;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    border: 1px solid var(--border-color);
  }

  .contable-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.95rem;
  }

  .contable-table-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, #4299e1 100%);
    color: white;
  }

  .contable-table-header th {
    padding: 18px 20px;
    text-align: left;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .contable-table tbody tr {
    border-bottom: 1px solid var(--border-color);
    transition: all 0.2s ease;
  }

  .contable-table tbody tr:hover {
    background-color: #f8fafc;
  }

  .contable-table tbody tr:last-child {
    border-bottom: none;
  }

  .contable-table td {
    padding: 16px 20px;
    color: var(--text-primary);
  }

  .contable-table-striped tbody tr:nth-child(even) {
    background-color: #f8fafc;
  }

  .contable-table-striped tbody tr:nth-child(even):hover {
    background-color: #e2e8f0;
  }

  /* ================================
     CARDS Y TARJETAS
     ================================ */
  .contable-card {
    background: white;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border-color);
    overflow: hidden;
    transition: all 0.3s ease;
  }

  .contable-card:hover {
    box-shadow: var(--shadow-xl);
    transform: translateY(-2px);
  }

  .contable-card-header {
    padding: var(--spacing-lg) var(--spacing-xl);
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-bottom: 1px solid var(--border-color);
  }

  .contable-card-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
  }

  .contable-card-subtitle {
    font-size: 0.9rem;
    color: var(--text-secondary);
    margin: var(--spacing-xs) 0 0 0;
  }

  .contable-card-body {
    padding: var(--spacing-xl);
  }

  .contable-card-footer {
    padding: var(--spacing-lg) var(--spacing-xl);
    background: #f8fafc;
    border-top: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  /* ================================
     MODALES
     ================================ */
  .contable-modal-overlay {
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

  .contable-modal-overlay.show {
    display: flex !important;
  }

  .contable-modal {
    background: white;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-2xl);
    width: 100%;
    max-width: 1100px;
    max-height: 90vh;
    overflow-y: auto;
    animation: slideUp 0.4s ease-out;
    position: relative;
    margin: auto;
    border: 1px solid var(--border-color);
  }

  .contable-modal-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, #4299e1 100%);
    color: white;
    padding: 24px 32px;
    border-bottom: 1px solid var(--border-color);
    position: relative;
    overflow: hidden;
  }

  .contable-modal-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
    pointer-events: none;
  }

  .contable-modal-title {
    font-size: 1.875rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    position: relative;
    z-index: 1;
  }

  .contable-modal-body {
    padding: var(--spacing-2xl);
    background: #fafbfc;
  }

  .contable-modal-footer {
    padding: var(--spacing-xl) var(--spacing-2xl);
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-top: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: var(--spacing-md);
  }

  /* ================================
     BREADCRUMBS
     ================================ */
  .contable-breadcrumb {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing-xl);
    padding: var(--spacing-md) 0;
  }

  .contable-breadcrumb-item {
    color: var(--text-secondary);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s ease;
  }

  .contable-breadcrumb-item:hover {
    color: var(--primary-color);
  }

  .contable-breadcrumb-item.active {
    color: var(--text-primary);
    font-weight: 600;
  }

  .contable-breadcrumb-separator {
    color: var(--text-muted);
    margin: 0 var(--spacing-xs);
  }

  /* ================================
     BADGES Y ETIQUETAS
     ================================ */
  .contable-badge {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-xs);
    padding: 6px 12px;
    border-radius: var(--radius-full);
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
  }

  .contable-badge-primary {
    background: rgba(44, 82, 130, 0.1);
    color: var(--primary-color);
  }

  .contable-badge-success {
    background: rgba(72, 187, 120, 0.1);
    color: var(--success-color);
  }

  .contable-badge-danger {
    background: rgba(229, 62, 62, 0.1);
    color: var(--danger-color);
  }

  .contable-badge-warning {
    background: rgba(221, 107, 32, 0.1);
    color: var(--warning-color);
  }

  .contable-badge-info {
    background: rgba(66, 153, 225, 0.1);
    color: var(--info-color);
  }

  /* ================================
     UTILIDADES RESPONSIVAS
     ================================ */
  @media (max-width: 1024px) {
    .contable-container {
      padding: var(--spacing-md);
    }
    
    .contable-body,
    .contable-modal-body {
      padding: var(--spacing-xl);
    }
  }

  @media (max-width: 768px) {
    .contable-container {
      padding: var(--spacing-sm);
    }
    
    .contable-title {
      font-size: 1.5rem;
    }
    
    .contable-body,
    .contable-modal-body {
      padding: var(--spacing-lg);
    }
    
    .contable-header {
      padding: var(--spacing-lg);
    }
    
    .contable-btn {
      width: 100%;
      justify-content: center;
    }
    
    .contable-modal {
      width: 98%;
      max-width: none;
      min-width: unset;
      resize: none;
      max-height: 95vh;
      margin: 0;
    }
  }

  @media (max-width: 480px) {
    .contable-modal {
      width: 99%;
      border-radius: var(--radius-md);
    }
    
    .contable-modal-header {
      padding: 16px 20px;
      border-radius: var(--radius-md) var(--radius-md) 0 0;
    }
    
    .contable-title {
      font-size: 1.25rem;
    }
    
    .contable-body,
    .contable-modal-body {
      padding: var(--spacing-md);
    }
  }

  /* ================================
     ANIMACIONES
     ================================ */
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

  @keyframes slideInLeft {
    from { 
      opacity: 0; 
      transform: translateX(-30px); 
    }
    to { 
      opacity: 1; 
      transform: translateX(0); 
    }
  }

  @keyframes slideInRight {
    from { 
      opacity: 0; 
      transform: translateX(30px); 
    }
    to { 
      opacity: 1; 
      transform: translateX(0); 
    }
  }

  /* ================================
     CLASES DE UTILIDAD
     ================================ */
  .contable-text-center { text-align: center; }
  .contable-text-left { text-align: left; }
  .contable-text-right { text-align: right; }
  
  .contable-font-bold { font-weight: 700; }
  .contable-font-semibold { font-weight: 600; }
  .contable-font-medium { font-weight: 500; }
  
  .contable-text-primary { color: var(--text-primary); }
  .contable-text-secondary { color: var(--text-secondary); }
  .contable-text-muted { color: var(--text-muted); }
  
  .contable-mb-0 { margin-bottom: 0; }
  .contable-mb-sm { margin-bottom: var(--spacing-sm); }
  .contable-mb-md { margin-bottom: var(--spacing-md); }
  .contable-mb-lg { margin-bottom: var(--spacing-lg); }
  .contable-mb-xl { margin-bottom: var(--spacing-xl); }
  
  .contable-mt-0 { margin-top: 0; }
  .contable-mt-sm { margin-top: var(--spacing-sm); }
  .contable-mt-md { margin-top: var(--spacing-md); }
  .contable-mt-lg { margin-top: var(--spacing-lg); }
  .contable-mt-xl { margin-top: var(--spacing-xl); }
  
  .contable-hidden { display: none; }
  .contable-block { display: block; }
  .contable-flex { display: flex; }
  .contable-grid { display: grid; }
  
  .contable-items-center { align-items: center; }
  .contable-justify-center { justify-content: center; }
  .contable-justify-between { justify-content: space-between; }
  
  .contable-w-full { width: 100%; }
  .contable-h-full { height: 100%; }
  
  .contable-cursor-pointer { cursor: pointer; }
  .contable-cursor-not-allowed { cursor: not-allowed; }
  
  .contable-select-none { user-select: none; }
  
  .contable-transition { transition: all 0.3s ease; }
  
  .contable-overflow-hidden { overflow: hidden; }
  .contable-overflow-auto { overflow: auto; }
  
  .contable-relative { position: relative; }
  .contable-absolute { position: absolute; }
  .contable-fixed { position: fixed; }
  
  .contable-z-10 { z-index: 10; }
  .contable-z-20 { z-index: 20; }
  .contable-z-30 { z-index: 30; }
  .contable-z-40 { z-index: 40; }
  .contable-z-50 { z-index: 50; }

  /* ================================
     ESTADOS DE CARGA
     ================================ */
  .contable-loading {
    position: relative;
    pointer-events: none;
  }

  .contable-loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 100;
  }

  .contable-spinner {
    width: 24px;
    height: 24px;
    border: 2px solid var(--border-color);
    border-top: 2px solid var(--primary-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
</style>
