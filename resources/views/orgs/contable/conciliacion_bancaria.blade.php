@extends('layouts.nice', ['active' => 'conciliacion-bancaria', 'title' => 'Conciliación Bancaria'])

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
        <span class="contable-breadcrumb-item active">Conciliación Bancaria</span>
    </nav>

    <!-- Sección principal -->
    <div class="contable-section">
        <div class="contable-header">
            <div>
                <h1 class="contable-title">
                    <i class="bi bi-bank"></i>
                    Conciliación Bancaria
                </h1>
                <p class="contable-subtitle">Comparación de movimientos registrados con extractos bancarios</p>
            </div>
            <div class="contable-header-actions">
                <button onclick="cargarExtracto()" class="contable-btn contable-btn-primary">
                    <i class="bi bi-upload"></i> Cargar Extracto
                </button>
                <button onclick="conciliarAutomatico()" class="contable-btn contable-btn-success">
                    <i class="bi bi-check-circle"></i> Conciliación Automática
                </button>
            </div>
        </div>

        <div class="contable-body">
            <!-- Estado de conciliación modernizado -->
            <div class="contable-card contable-mb-xl">
                <div class="contable-card-header">
                    <h3 class="contable-card-title">
                        <i class="bi bi-check-circle-fill" style="color: var(--success-color);"></i>
                        Estado de Conciliación
                    </h3>
                </div>
                <div class="contable-card-body">
                    <div class="contable-form-grid contable-form-grid-2">
                        <div class="contable-status-item">
                            <div class="contable-flex contable-justify-between contable-align-center contable-mb-sm">
                                <span class="contable-text-sm contable-text-medium">Movimientos Conciliados</span>
                                <span id="conciliadosPercent" class="contable-badge contable-badge-success">75%</span>
                            </div>
                            <div class="contable-progress">
                                <div id="conciliadosBar" class="contable-progress-bar contable-progress-success" style="width: 75%"></div>
                            </div>
                        </div>
                        <div class="contable-status-item">
                            <div class="contable-flex contable-justify-between contable-align-center contable-mb-sm">
                                <span class="contable-text-sm contable-text-medium">Movimientos Pendientes</span>
                                <span id="pendientesPercent" class="contable-badge contable-badge-warning">25%</span>
                            </div>
                            <div class="contable-progress">
                                <div id="pendientesBar" class="contable-progress-bar contable-progress-warning" style="width: 25%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                        </button>
                        <button onclick="exportarConciliacion()" class="btn btn-info">
                            <i class="bi bi-download"></i> Exportar Reporte
                        </button>
                    </div>

                    <!-- Grid de conciliación -->
                    <div class="conciliacion-grid">
                        <!-- Movimientos registrados -->
                        <div class="conciliacion-card">
                            <h3><i class="bi bi-journal-check text-primary"></i> Movimientos Registrados</h3>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Descripción</th>
                                            <th>Cuenta</th>
                                            <th>Monto</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaConciliacion">
                                        <!-- Los movimientos se generarán dinámicamente -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Extracto bancario -->
                        <div class="conciliacion-card">
                            <h3><i class="bi bi-bank text-info"></i> Extracto Bancario</h3>
                            <div class="upload-area" id="uploadArea">
                                <div class="upload-content">
                                    <i class="bi bi-cloud-upload upload-icon"></i>
                                    <p>Arrastra el archivo del extracto bancario aquí</p>
                                    <p class="text-muted">o haz clic para seleccionar archivo</p>
                                    <input type="file" id="extractoFile" accept=".csv,.xlsx,.pdf" style="display: none;">
                                </div>
                            </div>
                            
                            <div class="table-responsive" id="tablaExtractoContainer" style="display: none;">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Descripción</th>
                                            <th>Monto</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaExtracto">
                                        <!-- Los movimientos del extracto se generarán aquí -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Diferencias y ajustes -->
                    <div class="diferencias-section">
                        <h3><i class="bi bi-exclamation-triangle text-warning"></i> Diferencias Detectadas</h3>
                        <div class="diferencias-grid">
                            <div class="diferencia-card">
                                <h4>Movimientos no conciliados</h4>
                                <ul id="movimientosNoConciliados" class="diferencias-list">
                                    <!-- Se llenarán automáticamente -->
                                </ul>
                            </div>
                            <div class="diferencia-card">
                                <h4>Ajustes sugeridos</h4>
                                <ul id="ajustesSugeridos" class="diferencias-list">
                                    <!-- Se llenarán automáticamente -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal para conciliación manual -->
<div class="modal fade" id="conciliacionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Conciliación Manual</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="conciliacionForm">
                    <div class="mb-3">
                        <label for="movimientoRegistrado" class="form-label">Movimiento Registrado</label>
                        <select id="movimientoRegistrado" class="form-select" required>
                            <option value="">Seleccionar movimiento...</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="movimientoExtracto" class="form-label">Movimiento del Extracto</label>
                        <select id="movimientoExtracto" class="form-select" required>
                            <option value="">Seleccionar movimiento...</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea id="observaciones" class="form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarConciliacion()">Conciliar</button>
            </div>
        </div>
    </div>
</div>

<style>
    .conciliacion-status-card {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        margin: 20px 0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .conciliacion-status-card h3 {
        margin: 0 0 15px 0;
        color: #333;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .status-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .status-item {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .status-label {
        font-weight: 600;
        color: #333;
    }

    .status-percent {
        font-weight: bold;
        color: #666;
        text-align: right;
    }

    .action-buttons {
        margin: 20px 0;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .conciliacion-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin: 30px 0;
    }

    .conciliacion-card {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .conciliacion-card h3 {
        margin: 0 0 15px 0;
        color: #333;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .upload-area {
        border: 2px dashed #ddd;
        border-radius: 10px;
        padding: 40px;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.3s ease;
    }

    .upload-area:hover {
        border-color: #007bff;
    }

    .upload-area.dragover {
        border-color: #007bff;
        background-color: rgba(0, 123, 255, 0.1);
    }

    .upload-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .upload-icon {
        font-size: 3rem;
        color: #6c757d;
    }

    .diferencias-section {
        margin: 30px 0;
    }

    .diferencias-section h3 {
        margin: 0 0 20px 0;
        color: #333;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .diferencias-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .diferencia-card {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .diferencia-card h4 {
        margin: 0 0 15px 0;
        color: #333;
        font-size: 1.1rem;
    }

    .diferencias-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .diferencias-list li {
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .diferencias-list li:last-child {
        border-bottom: none;
    }

    .badge-conciliado {
        background-color: #28a745;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.8rem;
    }

    .badge-pendiente {
        background-color: #ffc107;
        color: #212529;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.8rem;
    }

    @media (max-width: 768px) {
        .conciliacion-grid {
            grid-template-columns: 1fr;
        }
        
        .diferencias-grid {
            grid-template-columns: 1fr;
        }
        
        .action-buttons {
            flex-direction: column;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarMovimientosConciliacion();
    configurarUploadArea();
});

function cargarMovimientosConciliacion() {
    // Obtener movimientos desde localStorage
    const movimientos = JSON.parse(localStorage.getItem('movimientos')) || [];
    const girosDepositos = JSON.parse(localStorage.getItem('girosDepositos')) || [];
    
    // Combinar todos los movimientos bancarios
    const movimientosBancarios = [
        ...movimientos.filter(m => m.cuenta && m.cuenta !== 'caja_general'),
        ...girosDepositos
    ];
    
    const tbody = document.getElementById('tablaConciliacion');
    tbody.innerHTML = '';
    
    movimientosBancarios.forEach((mov, index) => {
        const estado = Math.random() > 0.3 ? 'conciliado' : 'pendiente';
        const badgeClass = estado === 'conciliado' ? 'badge-conciliado' : 'badge-pendiente';
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${mov.fecha || 'N/A'}</td>
            <td>${mov.descripcion || mov.detalle || 'Sin descripción'}</td>
            <td>${formatearCuenta(mov.cuenta_origen || mov.cuenta_destino || mov.cuenta)}</td>
            <td class="${mov.tipo === 'ingreso' || mov.tipo === 'deposito' ? 'text-success' : 'text-danger'}">
                ${formatCurrency(mov.monto)}
            </td>
            <td><span class="${badgeClass}">${estado.toUpperCase()}</span></td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="abrirConciliacionModal(${index})">
                    <i class="bi bi-check-circle"></i>
                </button>
                <button class="btn btn-sm btn-outline-info" onclick="verDetalle(${index})">
                    <i class="bi bi-eye"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
    
    actualizarEstadisticasConciliacion();
}

function configurarUploadArea() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('extractoFile');
    
    uploadArea.addEventListener('click', () => {
        fileInput.click();
    });
    
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });
    
    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });
    
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            procesarExtracto(files[0]);
        }
    });
    
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            procesarExtracto(e.target.files[0]);
        }
    });
}

function procesarExtracto(file) {
    // Simular procesamiento del extracto
    showNotification('Procesando extracto bancario...', 'info');
    
    setTimeout(() => {
        // Datos simulados del extracto
        const movimientosExtracto = [
            { fecha: '2024-01-15', descripcion: 'Transferencia recibida', monto: 150000, conciliado: false },
            { fecha: '2024-01-16', descripcion: 'Pago servicios', monto: -45000, conciliado: false },
            { fecha: '2024-01-17', descripcion: 'Depósito efectivo', monto: 80000, conciliado: false },
            { fecha: '2024-01-18', descripcion: 'Comisión bancaria', monto: -3500, conciliado: false }
        ];
        
        mostrarExtracto(movimientosExtracto);
        showNotification('Extracto bancario cargado exitosamente', 'success');
    }, 2000);
}

function mostrarExtracto(movimientos) {
    document.getElementById('uploadArea').style.display = 'none';
    document.getElementById('tablaExtractoContainer').style.display = 'block';
    
    const tbody = document.getElementById('tablaExtracto');
    tbody.innerHTML = '';
    
    movimientos.forEach((mov, index) => {
        const estado = mov.conciliado ? 'conciliado' : 'pendiente';
        const badgeClass = estado === 'conciliado' ? 'badge-conciliado' : 'badge-pendiente';
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${mov.fecha}</td>
            <td>${mov.descripcion}</td>
            <td class="${mov.monto > 0 ? 'text-success' : 'text-danger'}">
                ${formatCurrency(Math.abs(mov.monto))}
            </td>
            <td><span class="${badgeClass}">${estado.toUpperCase()}</span></td>
            <td>
                <button class="btn btn-sm btn-outline-success" onclick="conciliarMovimiento('extracto', ${index})">
                    <i class="bi bi-check"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function cargarExtracto() {
    document.getElementById('extractoFile').click();
}

function conciliarAutomatico() {
    showNotification('Iniciando conciliación automática...', 'info');
    
    setTimeout(() => {
        // Simular conciliación automática
        const conciliados = Math.floor(Math.random() * 5) + 3;
        showNotification(`Se conciliaron automáticamente ${conciliados} movimientos`, 'success');
        cargarMovimientosConciliacion();
    }, 3000);
}

function exportarConciliacion() {
    showNotification('Exportando reporte de conciliación...', 'info');
    
    setTimeout(() => {
        showNotification('Reporte exportado exitosamente', 'success');
    }, 1500);
}

function abrirConciliacionModal(index) {
    const modal = new bootstrap.Modal(document.getElementById('conciliacionModal'));
    modal.show();
}

function guardarConciliacion() {
    showNotification('Conciliación guardada exitosamente', 'success');
    const modal = bootstrap.Modal.getInstance(document.getElementById('conciliacionModal'));
    modal.hide();
    cargarMovimientosConciliacion();
}

function conciliarMovimiento(tipo, index) {
    showNotification('Movimiento conciliado exitosamente', 'success');
    cargarMovimientosConciliacion();
}

function verDetalle(index) {
    showNotification('Mostrando detalles del movimiento', 'info');
}

function actualizarEstadisticasConciliacion() {
    // Calcular estadísticas simuladas
    const totalMovimientos = document.querySelectorAll('#tablaConciliacion tr').length;
    const conciliados = document.querySelectorAll('.badge-conciliado').length;
    const pendientes = totalMovimientos - conciliados;
    
    const porcentajeConciliados = totalMovimientos > 0 ? Math.round((conciliados / totalMovimientos) * 100) : 0;
    const porcentajePendientes = 100 - porcentajeConciliados;
    
    document.getElementById('conciliadosBar').style.width = porcentajeConciliados + '%';
    document.getElementById('pendientesBar').style.width = porcentajePendientes + '%';
    document.getElementById('conciliadosPercent').textContent = porcentajeConciliados + '%';
    document.getElementById('pendientesPercent').textContent = porcentajePendientes + '%';
}

function formatearCuenta(cuenta) {
    const cuentas = {
        'cuenta_corriente_1': 'Cuenta Corriente 1',
        'cuenta_corriente_2': 'Cuenta Corriente 2',
        'cuenta_ahorro': 'Cuenta de Ahorro',
        'caja_general': 'Caja General'
    };
    
    return cuentas[cuenta] || cuenta;
}

function formatCurrency(value) {
    const num = parseFloat(value) || 0;
    return num.toLocaleString('es-CL', { style: 'currency', currency: 'CLP', minimumFractionDigits: 0 });
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show`;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}
</script>
@endsection
