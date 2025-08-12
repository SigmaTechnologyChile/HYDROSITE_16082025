@extends('layouts.nice', ['active'=>'orgs.locations.panelcontrolrutas', 'title'=>'Panel Control Rutas'])

@section('content')
<script>
    // URL base para AJAX
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
            <h5><i class="bi bi-table"></i> Servicios del Sector</h5>
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

<style>
.card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-radius: 12px;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px 12px 0 0 !important;
    border: none;
}

.table th {
    border-top: none;
}

.service-id {
    background: #e3f2fd;
    color: #1976d2;
    padding: 4px 8px;
    border-radius: 6px;
    font-weight: bold;
    font-family: monospace;
}

.loading {
    color: #2196f3;
}

.error {
    color: #f44336;
}

.success {
    color: #4caf50;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.spinning {
    animation: spin 1s linear infinite;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Panel Control Rutas - Iniciado');
    
    const sectorSelect = document.getElementById('sector');
    const tbody = document.querySelector('#servicios-table tbody');
    
    // Verificar que los elementos existen
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
        console.log('=== INICIANDO CARGA DE SERVICIOS ===');
        console.log('Sector ID:', sectorId);
        console.log('URL que se va a llamar:', `/ajax/clientes-por-sector/${sectorId}`);
        
        // Mostrar loading
        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center loading py-4">
                    <i class="bi bi-arrow-clockwise spinning fs-2"></i><br>
                    Cargando servicios del sector...
                </td>
            </tr>
        `;
        
        // Hacer petición AJAX
        const ajaxUrl = `${window.baseUrl}/org/${window.orgId}/ajax/clientes-por-sector/${sectorId}`;
        console.log('URL AJAX:', ajaxUrl);
        
        fetch(ajaxUrl)
            .then(response => {
                console.log('=== RESPUESTA RECIBIDA ===');
                console.log('Status:', response.status);
                console.log('OK:', response.ok);
                console.log('Headers:', response.headers);
                
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('=== DATOS PROCESADOS ===');
                console.log('Data completa:', data);
                if (data.success && data.data) {
                    console.log('Cantidad de servicios:', data.data.length);
                    mostrarServicios(data.data);
                } else {
                    console.log('No hay datos o error:', data.message);
                    mostrarError(data.message || 'No se pudieron cargar los servicios');
                }
            })
            .catch(error => {
                console.error('=== ERROR EN PETICIÓN ===');
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
        servicios.forEach(servicio => {
            html += `
                <tr>
                    <td><span class="badge bg-primary">Sector Seleccionado</span></td>
                    <td><strong>${servicio.service_id}</strong></td>
                    <td colspan="2" class="text-muted">Service ID: ${servicio.service_id}</td>
                </tr>
            `;
        });
        
        tbody.innerHTML = html;
        
        // Actualizar contador en el header
        const cardHeader = document.querySelector('.card:last-child .card-header h5');
        if (cardHeader) {
            cardHeader.innerHTML = `<i class="bi bi-table"></i> Services IDs del Sector (${servicios.length} encontrados)`;
        }
    }
        });
        
        tbody.innerHTML = html;
        console.log(`Mostrados ${servicios.length} servicios`);
    }
    
    // Función para mostrar error
    function mostrarError(mensaje) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center error py-4">
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
    
    // Funciones globales para testing
    window.testSector = function(sectorId) {
        sectorSelect.value = sectorId;
        cargarServicios(sectorId);
    };
    
    window.testEndpoint = function(sectorId) {
        return fetch(`${window.baseUrl}/org/${window.orgId}/ajax/clientes-por-sector/${sectorId}`)
            .then(response => response.json())
            .then(data => {
                console.log('Test data:', data);
                return data;
            });
    };
    
    // Función para probar el flujo completo paso a paso
    window.testFlujo = function(locationId) {
        console.log('=== INICIANDO FLUJO DE PRUEBA ===');
        console.log('PASO 1: Usuario selecciona sector → Location ID:', locationId);
        
        fetch(`${window.baseUrl}/org/${window.orgId}/ajax/clientes-por-sector/${locationId}`)
            .then(response => {
                console.log('PASO 2-4: Respuesta del servidor:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('=== DATOS RECIBIDOS ===');
                console.log('Success:', data.success);
                console.log('Total:', data.total);
                console.log('Data:', data.data);
                
                if (data.success && data.data && data.data.length > 0) {
                    console.log('=== ANÁLISIS DE RESULTADOS ===');
                    data.data.forEach((servicio, index) => {
                        console.log(`Servicio ${index + 1}:`, {
                            service_id: servicio.service_id
                        });
                    });
                } else {
                    console.log('No se encontraron servicios o hubo un error');
                }
            })
            .catch(error => {
                console.error('Error en el flujo:', error);
            });
    };
    
    console.log('Panel Control Rutas - Listo');
    console.log('Funciones disponibles: testSector(id), testEndpoint(id), testFlujo(id)');
});
</script>
@endsection
