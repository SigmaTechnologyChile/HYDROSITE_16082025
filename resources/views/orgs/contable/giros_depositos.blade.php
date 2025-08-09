@extends('layouts.nice', ['active' => 'giros-depositos', 'title' => 'Giros y Depósitos'])

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
        <span class="contable-breadcrumb-item active">Giros y Depósitos</span>
    </nav>

    <!-- Sección principal -->
    <div class="contable-section">
        <div class="contable-header">
            <div>
                <h1 class="contable-title">
                    <i class="bi bi-bank2"></i>
                    Giros y Depósitos
                </h1>
                <p class="contable-subtitle">Movimientos entre cuentas bancarias y caja general</p>
            </div>
        </div>

        <div class="contable-body">
            <!-- Formularios de Giros y Depósitos -->
            <div class="contable-form-grid contable-form-grid-2 contable-mb-xl">
                <!-- Sección Giros -->
                <div class="contable-card">
                    <div class="contable-card-header">
                        <h3 class="contable-card-title">
                            <i class="bi bi-send"></i> GIROS
                        </h3>
                        <p class="contable-text-secondary">Desde Cta. Cte./ Cta. Ahorro → Caja General</p>
                    </div>
                    <div class="contable-card-body">
                        <form id="girosForm">
                            <div class="contable-form-grid contable-form-grid-1">
                                <div class="contable-form-group">
                                    <label for="fecha-giro" class="contable-form-label">Fecha</label>
                                    <input type="date" id="fecha-giro" name="fecha" required class="contable-form-control">
                                </div>
                                <div class="contable-form-group">
                                    <label for="monto-giro" class="contable-form-label">Monto</label>
                                    <input type="number" id="monto-giro" name="monto" step="0.01" required class="contable-form-control">
                                </div>
                                <div class="contable-form-group">
                                    <label for="cuenta-giro" class="contable-form-label">Cuenta Origen</label>
                                    <select id="cuenta-giro" name="cuenta" required class="contable-form-control">
                                        <option value="">-- Seleccione cuenta --</option>
                                        <option value="cuenta_corriente_1">Cuenta Corriente 1</option>
                                        <option value="cuenta_corriente_2">Cuenta Corriente 2</option>
                                        <option value="cuenta_ahorro">Cuenta de Ahorro</option>
                                    </select>
                                </div>
                                <div class="contable-form-group">
                                    <label for="detalle-giro" class="contable-form-label">Detalle</label>
                                    <input type="text" id="detalle-giro" name="detalle" placeholder="Detalle del giro" required class="contable-form-control">
                                </div>
                                <div class="contable-form-group">
                                    <label for="nro-dcto-giro" class="contable-form-label">N° Comprobante</label>
                                    <input type="text" id="nro-dcto-giro" name="nro_dcto" required class="contable-form-control" readonly>
                                </div>
                            </div>

                            <div class="contable-mt-lg contable-text-center">
                                <button type="submit" class="contable-btn contable-btn-primary">
                                    <i class="bi bi-save"></i> Registrar Giro
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sección Depósitos -->
                <div class="contable-card">
                    <div class="contable-card-header">
                        <h3 class="contable-card-title">
                            <i class="bi bi-bank"></i> DEPÓSITOS
                        </h3>
                        <p class="contable-text-secondary">Desde Caja General → Cta. Cte./ Cta. Ahorro</p>
                    </div>
                    <div class="contable-card-body">
                        <form id="depositosForm">
                            <div class="contable-form-grid contable-form-grid-1">
                                <div class="contable-form-group">
                                    <label for="fecha-deposito" class="contable-form-label">Fecha</label>
                                    <input type="date" id="fecha-deposito" name="fecha" required class="contable-form-control">
                                </div>
                                <div class="contable-form-group">
                                    <label for="monto-deposito" class="contable-form-label">Monto</label>
                                    <input type="number" id="monto-deposito" name="monto" step="0.01" required class="contable-form-control">
                                </div>
                                <div class="contable-form-group">
                                    <label for="cuenta-deposito" class="contable-form-label">Cuenta Destino</label>
                                    <select id="cuenta-deposito" name="cuenta" required class="contable-form-control">
                                        <option value="">-- Seleccione cuenta --</option>
                                        <option value="cuenta_corriente_1">Cuenta Corriente 1</option>
                                        <option value="cuenta_corriente_2">Cuenta Corriente 2</option>
                                        <option value="cuenta_ahorro">Cuenta de Ahorro</option>
                                    </select>
                                </div>
                                <div class="contable-form-group">
                                    <label for="detalle-deposito" class="contable-form-label">Detalle</label>
                                    <input type="text" id="detalle-deposito" name="detalle" placeholder="Detalle del depósito" required class="contable-form-control">
                                </div>
                                <div class="contable-form-group">
                                    <label for="nro-dcto-deposito" class="contable-form-label">N° Comprobante</label>
                                    <input type="text" id="nro-dcto-deposito" name="nro_dcto" required class="contable-form-control" readonly>
                                </div>
                            </div>

                            <div class="contable-mt-lg contable-text-center">
                                <button type="submit" class="contable-btn contable-btn-success">
                                    <i class="bi bi-save"></i> Registrar Depósito
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Historial de movimientos -->
            <div class="contable-card contable-mt-xl">
                <div class="contable-card-header">
                    <h3 class="contable-card-title">
                        <i class="bi bi-clock-history"></i>
                        Historial de Movimientos
                    </h3>
                </div>
                <div class="contable-card-body">
                    <div class="contable-table-container">
                        <table class="contable-table">
                            <thead>
                                <tr>
                                    <th>
                                        <i class="bi bi-calendar3"></i>
                                        Fecha
                                    </th>
                                    <th>
                                        <i class="bi bi-arrow-left-right"></i>
                                        Tipo
                                    </th>
                                    <th>
                                        <i class="bi bi-bank"></i>
                                        Cuenta
                                    </th>
                                    <th>
                                        <i class="bi bi-currency-dollar"></i>
                                        Monto
                                    </th>
                                    <th>
                                        <i class="bi bi-file-text"></i>
                                        Detalle
                                    </th>
                                    <th>
                                        <i class="bi bi-receipt"></i>
                                        Comprobante
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="historialMovimientos">
                                <tr>
                                    <td colspan="6" class="contable-text-center contable-text-secondary" style="padding: var(--spacing-2xl);">
                                        <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: var(--spacing-md); opacity: 0.5;"></i>
                                        No hay movimientos registrados
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos adicionales para notificaciones y efectos específicos */
    .notification {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;
        min-width: 320px;
        max-width: 90vw;
        padding: var(--spacing-lg) var(--spacing-xl);
        background: var(--gray-800);
        color: white;
        border-radius: var(--border-radius-md);
        font-size: 1.2rem;
        text-align: center;
        box-shadow: var(--shadow-lg);
        display: none;
        opacity: 0.97;
        transition: opacity 0.2s;
    }
    .notification.success { 
        background: var(--success-color); 
        color: white; 
    }
    .notification.error { 
        background: var(--danger-color); 
        color: white; 
    }

    /* Efectos de animación para los botones de formulario */
    .contable-btn[type="submit"]:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let comprobanteCounter = parseInt(localStorage.getItem('comprobanteCounter')) || 1;

    // Funciones auxiliares
    function generarNumeroComprobante(tipo) {
        const prefijo = tipo === 'giro' ? 'G-' : 'D-';
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

    // Inicializar campos automáticos
    function inicializarCampos() {
        // Giros
        const fechaGiro = document.getElementById('fecha-giro');
        const nroDctoGiro = document.getElementById('nro-dcto-giro');
        if (fechaGiro) {
            fechaGiro.value = obtenerFechaActual();
            fechaGiro.readOnly = true;
        }
        if (nroDctoGiro) {
            nroDctoGiro.value = generarNumeroComprobante('giro');
        }

        // Depósitos
        const fechaDeposito = document.getElementById('fecha-deposito');
        const nroDctoDeposito = document.getElementById('nro-dcto-deposito');
        if (fechaDeposito) {
            fechaDeposito.value = obtenerFechaActual();
            fechaDeposito.readOnly = true;
        }
        if (nroDctoDeposito) {
            nroDctoDeposito.value = generarNumeroComprobante('deposito');
        }
    }

    // Event listeners para formularios
    document.getElementById('girosForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        
        // Validar datos
        if (!data.fecha || !data.monto || !data.cuenta || !data.detalle || !data.nro_dcto) {
            showNotification('Por favor complete todos los campos requeridos', 'error');
            return;
        }

        if (parseFloat(data.monto) <= 0) {
            showNotification('El monto debe ser mayor a 0', 'error');
            return;
        }

        // Guardar en localStorage
        let movimientos = JSON.parse(localStorage.getItem('girosDepositos')) || [];
        const nuevoGiro = {
            id: Date.now(),
            tipo: 'giro',
            fecha: data.fecha,
            comprobante: data.nro_dcto,
            cuenta_origen: data.cuenta,
            cuenta_destino: 'caja_general',
            detalle: data.detalle,
            monto: parseFloat(data.monto),
            timestamp: new Date().toISOString()
        };

        movimientos.push(nuevoGiro);
        localStorage.setItem('girosDepositos', JSON.stringify(movimientos));

        showNotification('Giro registrado exitosamente');
        this.reset();
        inicializarCampos();
    });

    document.getElementById('depositosForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        
        // Validar datos
        if (!data.fecha || !data.monto || !data.cuenta || !data.detalle || !data.nro_dcto) {
            showNotification('Por favor complete todos los campos requeridos', 'error');
            return;
        }

        if (parseFloat(data.monto) <= 0) {
            showNotification('El monto debe ser mayor a 0', 'error');
            return;
        }

        // Guardar en localStorage
        let movimientos = JSON.parse(localStorage.getItem('girosDepositos')) || [];
        const nuevoDeposito = {
            id: Date.now(),
            tipo: 'deposito',
            fecha: data.fecha,
            comprobante: data.nro_dcto,
            cuenta_origen: 'caja_general',
            cuenta_destino: data.cuenta,
            detalle: data.detalle,
            monto: parseFloat(data.monto),
            timestamp: new Date().toISOString()
        };

        movimientos.push(nuevoDeposito);
        localStorage.setItem('girosDepositos', JSON.stringify(movimientos));

        showNotification('Depósito registrado exitosamente');
        this.reset();
        inicializarCampos();
    });

    // Inicializar al cargar la página
    inicializarCampos();
});
</script>
@endsection
