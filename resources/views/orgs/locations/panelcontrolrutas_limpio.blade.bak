{{-- 
    ARCHIVO ELIMINADO
    Este archivo ha sido reemplazado por panelcontrolrutas_simple.blade.php
    Fecha: 9 de agosto de 2025
    Motivo: Consolidación de vistas
--}}
    window.baseUrl = '{{ url("/") }}';
    window.orgId = {{ $org->id }};
</script>

<div class="container mt-4">
    <h2><i class="bi bi-geo-alt-fill"></i> Panel Control de Rutas</h2>
    <p class="text-muted">Seleccione un sector para ver todos los servicios registrados</p>

    <!-- Selector de Sector -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><i class="bi bi-funnel-fill"></i> Seleccionar Sector</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label for="sector" class="form-label">Sector:</label>
                    <select id="sector" class="form-select">
                        <option value="">-- Seleccione un sector --</option>
                        @if(isset($locations) && count($locations) > 0)
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="text-muted">
                        <small>Total sectores disponibles: {{ isset($locations) ? count($locations) : 0 }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Servicios -->
    <div class="card">
        <div class="card-header">
            <h5><i class="bi bi-table"></i> Services IDs del Sector</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="servicios-table" class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th><i class="bi bi-geo-alt"></i> Sector</th>
                            <th><i class="bi bi-hash"></i> Service ID</th>
                            <th colspan="2"><i class="bi bi-info-circle"></i> Información</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="bi bi-arrow-down-circle fs-2"></i><br>
                                Seleccione un sector para ver los servicios
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== PANEL CONTROL RUTAS INICIADO ===');
    
    const sectorSelect = document.getElementById('sector');
    const tbody = document.querySelector('#servicios-table tbody');
    
    if (!sectorSelect || !tbody) {
        console.error('Elementos no encontrados');
        return;
    }
    
    console.log('Elementos encontrados correctamente');
    
    // Event listener para cambio de sector
    sectorSelect.addEventListener('change', function() {
        const sectorId = this.value;
        console.log('Sector seleccionado:', sectorId);
        
        if (sectorId) {
            cargarServicios(sectorId);
        } else {
            mostrarMensajeInicial();
        }
    });
    
    // Función para cargar servicios
    function cargarServicios(sectorId) {
        console.log('=== CARGANDO SERVICIOS ===');
        console.log('Sector ID:', sectorId);
        
        // Mostrar loading
        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center py-4">
                    <i class="bi bi-arrow-clockwise"></i> Cargando servicios...
                </td>
            </tr>
        `;
        
        // Construir URL
        const ajaxUrl = window.baseUrl + '/org/' + window.orgId + '/ajax/clientes-por-sector/' + sectorId;
        console.log('URL AJAX:', ajaxUrl);
        
        // Hacer petición AJAX
        fetch(ajaxUrl)
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Error HTTP: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Datos recibidos:', data);
                console.log('Success:', data.success);
                console.log('Total encontrados:', data.total);
                console.log('Array de datos:', data.data);
                
                if (data.success && data.data) {
                    if (data.data.length > 0) {
                        console.log('Mostrando', data.data.length, 'servicios');
                        mostrarServicios(data.data);
                    } else {
                        console.log('No hay servicios en este sector');
                        mostrarSinDatos('No hay servicios registrados en este sector');
                    }
                } else {
                    console.log('Error en respuesta:', data.message);
                    mostrarError(data.message || 'No se pudieron cargar los servicios');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarError(error.message);
            });
    }
    
    // Función para mostrar servicios
    function mostrarServicios(servicios) {
        if (!Array.isArray(servicios) || servicios.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-2"></i><br>
                        No hay servicios registrados en este sector
                    </td>
                </tr>
            `;
            return;
        }
        
        let html = '';
        servicios.forEach(function(servicio) {
            html += `
                <tr>
                    <td><span class="badge bg-primary">Sector Seleccionado</span></td>
                    <td><strong>${servicio.service_id}</strong></td>
                    <td colspan="2" class="text-muted">Service ID: ${servicio.service_id}</td>
                </tr>
            `;
        });
        
        tbody.innerHTML = html;
        
        // Actualizar contador
        const cardHeader = document.querySelector('.card:last-child .card-header h5');
        if (cardHeader) {
            cardHeader.innerHTML = '<i class="bi bi-table"></i> Services IDs del Sector (' + servicios.length + ' encontrados)';
        }
    }
    
    // Función para mostrar cuando no hay datos
    function mostrarSinDatos(mensaje) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center text-info py-4">
                    <i class="bi bi-info-circle fs-2"></i><br>
                    ${mensaje}<br>
                    <small class="text-muted">Pruebe con otro sector</small>
                </td>
            </tr>
        `;
    }
    
    // Función para mostrar errores
    function mostrarError(mensaje) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center text-danger py-4">
                    <i class="bi bi-exclamation-triangle fs-2"></i><br>
                    Error: ${mensaje}
                </td>
            </tr>
        `;
    }
    
    // Función para mostrar mensaje inicial
    function mostrarMensajeInicial() {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center text-muted py-4">
                    <i class="bi bi-arrow-down-circle fs-2"></i><br>
                    Seleccione un sector para ver los servicios
                </td>
            </tr>
        `;
    }
    
    // Funciones de test globales
    window.testSector = function(sectorId) {
        sectorSelect.value = sectorId;
        cargarServicios(sectorId);
    };
    
    window.testEndpoint = function(sectorId) {
        const url = window.baseUrl + '/org/' + window.orgId + '/ajax/clientes-por-sector/' + sectorId;
        console.log('Testing URL:', url);
        return fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log('Test data:', data);
                return data;
            });
    };
    
    // Función para probar todos los sectores disponibles
    window.testTodosSectores = function() {
        const selectElement = document.getElementById('sector');
        const options = selectElement.querySelectorAll('option');
        
        console.log('=== PROBANDO TODOS LOS SECTORES ===');
        console.log('Sectores disponibles:', options.length);
        
        options.forEach(function(option, index) {
            const sectorId = option.value;
            const sectorName = option.textContent;
            
            // Solo probar si tiene un valor válido
            if (sectorId && sectorId !== '' && sectorId !== 'Seleccione un sector') {
                setTimeout(function() {
                    console.log(`\n--- Probando Sector ${index + 1}: ${sectorName} (ID: ${sectorId}) ---`);
                    testEndpoint(sectorId).then(function(data) {
                        if (data.success && data.total > 0) {
                            console.log(`✅ SECTOR ${sectorName}: ${data.total} servicios encontrados`);
                        } else {
                            console.log(`❌ SECTOR ${sectorName}: Sin servicios`);
                        }
                    });
                }, index * 500); // Delay para no saturar
            }
        });
    };
    
    console.log('Panel Control Rutas - Listo');
    console.log('Funciones disponibles: testSector(id), testEndpoint(id), testTodosSectores()');
});
</script>
@endsection
