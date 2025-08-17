<style>
    .service-members {
        color: #444;
        font-size: 13px;
        margin-left: 10px;
        font-style: italic;
    }
</style>
@extends('layouts.nice', ['active'=>'orgs.locations.panelcontrolrutas', 'title'=>'Panel Control Rutas'])

@section('content')
<style>
    .service-row {
        background: #fff;
        border: 1px solid #e3f2fd;
        border-radius: 6px;
        padding: 8px 12px;
        margin: 4px 0;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 1px 3px rgba(0    // Event listener para botón Editar Ruta
    editarRutaBtn.addEventListener('click', function() {
        console.log('Estado actual:', {
            sectorData: currentSectorData,
            serviciosCount: currentServiciosData.length,
            serviciosData: currentServiciosData
        });
        
        if (currentServiciosData.length > 0) {
            abrirModalOrdenamiento();
        } else {
            alert('No hay servicios disponibles para ordenar en este sector');
        }
    }););
        transition: all 0.2s ease;
    }
    
    .service-row:hover {
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        transform: translateX(2px);
        background: #f8f9ff;
    }
    
    .service-id {
        background: #2196f3;
        color: white;
        border-radius: 15px;
        padding: 4px 10px;
        font-size: 12px;
        font-weight: bold;
        min-width: 45px;
        text-align: center;
        flex-shrink: 0;
    }
    
    .service-name {
        font-weight: 600;
        color: #333;
        flex: 1;
        min-width: 0;
    }
    
    .service-rut {
        color: #666;
        font-size: 14px;
        font-family: monospace;
        flex-shrink: 0;
    }
    
    .sector-badge {
        background: #28a745;
        color: white;
        border-radius: 12px;
        padding: 3px 8px;
        font-size: 11px;
        flex-shrink: 0;
    }
    
    .servicios-container {
        min-height: 120px;
        max-height: 500px;
        overflow-y: auto;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        background-color: #f8f9fa;
    }
    
    .loading-text {
        color: #6c757d;
        font-style: italic;
    }
    
    .error-text {
        color: #dc3545;
        font-weight: 500;
    }
    
    .success-text {
        color: #28a745;
        font-weight: 500;
    }

    .service-count {
        background: #f0f8ff;
        border: 1px solid #2196f3;
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 15px;
        text-align: center;
    }

    /* Estilos del Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }

    .modal.show {
        display: block;
    }

    .modal-content {
        background-color: #fefefe;
        margin: 2% auto;
        padding: 20px;
        border: none;
        border-radius: 10px;
        width: 90%;
        max-width: 800px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #eee;
    }

    .close {
        color: #aaa;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        line-height: 1;
    }

    .close:hover {
        color: #000;
    }

    /* Estilos para items ordenables */
    .sortable-item {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 12px;
        margin: 8px 0;
        display: flex;
        align-items: center;
        gap: 15px;
        cursor: move;
        transition: all 0.2s ease;
        position: relative;
    }

    .sortable-item:hover {
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        transform: translateY(-1px);
    }

    .sortable-item.dragging {
        opacity: 0.5;
        transform: rotate(5deg);
    }

    .drag-handle {
        color: #666;
        font-size: 18px;
        cursor: move;
        margin-right: 10px;
    }

    .order-number {
        background: #ff6b35;
        color: white;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
        flex-shrink: 0;
    }

    .service-info {
        flex: 1;
        min-width: 0;
    }

    .service-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 3px;
    }

    .service-subtitle {
        color: #666;
        font-size: 13px;
    }

    .order-controls {
        display: flex;
        flex-direction: column;
        gap: 2px;
        flex-shrink: 0;
    }

    .order-btn {
        background: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 6px 10px;
        font-size: 14px;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.2s;
        min-width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .order-btn:hover {
        background: #0056b3;
    }

    .order-btn:disabled {
        background: #ccc;
        cursor: not-allowed;
    }

    .modal-footer {
        margin-top: 20px;
        padding-top: 15px;
        border-top: 2px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn-save-order {
        background: #28a745;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-save-order:hover {
        background: #218838;
    }
</style>

<div class="container-fluid">
    <div class="pagetitle">
        <h1>Panel Control Rutas</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('orgs.dashboard', $org->id) }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Panel Control Rutas</li>
            </ol>
        </nav>
    </div>

    <!-- Selector de Sector -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><i class="bi bi-geo-alt-fill"></i> Seleccionar Sector</h5>
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
                <div class="col-md-3 d-flex align-items-end">
                    <button id="editarRutaBtn" class="btn btn-danger w-100" disabled>
                        <i class="bi bi-pencil-square"></i> Editar Ruta
                    </button>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button id="imprimirBtn" class="btn btn-primary w-100">
                        <i class="bi bi-printer"></i> Imprimir
                    </button>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('imprimirBtn').addEventListener('click', function() {
        window.print();
    });
});
</script>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="text-muted">
                        <small>Total sectores: {{ isset($locations) ? count($locations) : 0 }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Servicios del Sector -->
    <div class="card">
        <div class="card-header">
            <h5><i class="bi bi-people-fill"></i> Servicios del Sector</h5>
        </div>
        <div class="card-body">
            <div id="servicios-container" class="servicios-container">
                <p class="text-muted mb-0">Seleccione un sector para ver los servicios disponibles</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Orden de Ruta -->
<div id="editarRutaModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="bi bi-list-ol"></i> Organizar Orden de Ruta</h2>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                <strong>Instrucciones:</strong> Arrastra los servicios para cambiar su orden de prioridad o usa los botones de orden. 
                El orden #1 será el primero en la ruta.
            </div>
            <div id="sortable-container">
                <!-- Los servicios se cargarán aquí dinámicamente -->
            </div>
        </div>
        <div class="modal-footer">
            <div>
                <button id="resetOrderBtn" class="btn btn-secondary">
                    <i class="bi bi-arrow-clockwise"></i> Resetear Orden
                </button>
            </div>
            <div>
                <button id="cancelOrderBtn" class="btn btn-outline-secondary me-2">Cancelar</button>
                <button id="saveOrderBtn" class="btn-save-order">
                    <i class="bi bi-check-lg"></i> Guardar Orden
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== PANEL CONTROL RUTAS INICIADO ===');
    
    const sectorSelect = document.getElementById('sector');
    const serviciosContainer = document.getElementById('servicios-container');
    const editarRutaBtn = document.getElementById('editarRutaBtn');
    const modal = document.getElementById('editarRutaModal');
    const closeBtn = modal.querySelector('.close');
    const cancelBtn = document.getElementById('cancelOrderBtn');
    const saveBtn = document.getElementById('saveOrderBtn');
    const resetBtn = document.getElementById('resetOrderBtn');
    const sortableContainer = document.getElementById('sortable-container');
    
    let currentServiciosData = [];
    let currentSectorData = { id: null, name: '' };
    
    if (!sectorSelect || !serviciosContainer || !editarRutaBtn) {
        console.error('Elementos no encontrados');
        return;
    }
    
    console.log('Elementos encontrados correctamente');
    
    // Event listener para cambio de sector
    sectorSelect.addEventListener('change', function() {
        // Primero limpiar todo el estado anterior
        reinicializarSistema();
        
        cargarServicios();
        
        // Habilitar/deshabilitar botón Editar Ruta
        if (this.value) {
            editarRutaBtn.disabled = false;
            currentSectorData.id = this.value;
            currentSectorData.name = this.options[this.selectedIndex].textContent;
        } else {
            editarRutaBtn.disabled = true;
            currentSectorData = { id: null, name: '' };
            currentServiciosData = [];
        }
    });
    
    // Función para reinicializar completamente el sistema
    function reinicializarSistema() {
        // Cerrar modal si está abierto
        if (modal.classList.contains('show')) {
            cerrarModal();
        }
        
        // Limpiar variables globales
        currentServiciosData = [];
        draggedItem = null;
        
        // Limpiar contenedores
        serviciosContainer.innerHTML = '';
        sortableContainer.innerHTML = '';
        
        console.log('Sistema reinicializado para nuevo sector');
    }
    
    // Event listener para botón Editar Ruta
    editarRutaBtn.addEventListener('click', function() {
        if (currentServiciosData.length > 0) {
            abrirModalOrdenamiento();
        } else {
            alert('No hay servicios cargados para organizar');
        }
    });
    
    // Event listeners para cerrar modal
    closeBtn.addEventListener('click', cerrarModal);
    cancelBtn.addEventListener('click', cerrarModal);
    
    // Event listener para guardar orden
    saveBtn.addEventListener('click', guardarOrden);
    
    // Event listener para resetear orden
    resetBtn.addEventListener('click', resetearOrden);
    
    // Cerrar modal al hacer clic fuera
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            cerrarModal();
        }
    });
    
    // Función para abrir modal de ordenamiento
    function abrirModalOrdenamiento() {
        // Verificar que tenemos datos válidos
        if (!currentServiciosData || currentServiciosData.length === 0) {
            alert('No hay servicios disponibles para ordenar');
            return;
        }
        
        if (!currentSectorData.id) {
            alert('No hay sector seleccionado');
            return;
        }
        
        // Limpiar modal antes de abrir
        limpiarModal();
        
        // Mostrar modal
        modal.classList.add('show');
        modal.querySelector('.modal-header h2').innerHTML = 
            `<i class="bi bi-list-ol"></i> Organizar Orden de Ruta - ${currentSectorData.name}`;
        
        // Generar lista ordenable
        generarListaOrdenable();
        
        console.log('Modal abierto para sector:', currentSectorData.name);
    }
    
    // Función para cerrar modal
    function cerrarModal() {
        modal.classList.remove('show');
        
        // Limpiar completamente el modal
        limpiarModal();
    }
    
    // Función para limpiar modal completamente
    function limpiarModal() {
        // Limpiar container de elementos ordenables
        sortableContainer.innerHTML = '';
        
        // Resetear variable global de servicios draggeados
        draggedItem = null;
        
        // Restaurar botón de guardar a su estado original
        const saveBtn = document.querySelector('.btn-save-order');
        if (saveBtn) {
            saveBtn.innerHTML = '<i class="bi bi-check2-circle"></i> Guardar Orden';
            saveBtn.style.background = '#007bff';
            saveBtn.disabled = false;
        }
        
        // Limpiar cualquier clase de arrastre residual
        document.querySelectorAll('.dragging').forEach(el => {
            el.classList.remove('dragging');
        });
        
        console.log('Modal limpiado completamente');
    }
    
    // Función para generar lista ordenable
    function generarListaOrdenable() {
        sortableContainer.innerHTML = '';
        
        currentServiciosData.forEach((servicio, index) => {
            const item = document.createElement('div');
            item.className = 'sortable-item';
            item.dataset.serviceId = servicio.numero;
            item.innerHTML = `
                <div class="drag-handle">
                    <i class="bi bi-grip-vertical"></i>
                </div>
                <div class="order-number">${index + 1}</div>
                <div class="service-info">
                    <div class="service-title">ID: ${servicio.numero} - ${servicio.full_name || 'Sin nombre'}</div>
                    <div class="service-subtitle">RUT: ${servicio.rut || 'No disponible'}</div>
                </div>
                <div class="order-controls">
                    <button class="order-btn" onclick="moverArriba(${index})" ${index === 0 ? 'disabled' : ''}>↑</button>
                    <button class="order-btn" onclick="moverAbajo(${index})" ${index === currentServiciosData.length - 1 ? 'disabled' : ''}>↓</button>
                </div>
            `;
            sortableContainer.appendChild(item);
        });
        
        // Hacer elementos arrastrables
        habilitarDragAndDrop();
    }
    
    // Función para habilitar drag and drop
    function habilitarDragAndDrop() {
        const items = sortableContainer.querySelectorAll('.sortable-item');
        
        items.forEach(item => {
            // Primero remover event listeners existentes para evitar duplicados
            item.removeEventListener('dragstart', handleDragStart);
            item.removeEventListener('dragover', handleDragOver);
            item.removeEventListener('drop', handleDrop);
            item.removeEventListener('dragend', handleDragEnd);
            
            // Luego agregar los nuevos event listeners
            item.addEventListener('dragstart', handleDragStart);
            item.addEventListener('dragover', handleDragOver);
            item.addEventListener('drop', handleDrop);
            item.addEventListener('dragend', handleDragEnd);
            item.draggable = true;
        });
        
        console.log(`Drag & Drop habilitado para ${items.length} elementos`);
    }
    
    // Funciones de drag and drop
    let draggedItem = null;
    
    function handleDragStart(e) {
        draggedItem = this;
        this.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
    }
    
    function handleDragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        
        const afterElement = getDragAfterElement(sortableContainer, e.clientY);
        if (afterElement == null) {
            sortableContainer.appendChild(draggedItem);
        } else {
            sortableContainer.insertBefore(draggedItem, afterElement);
        }
    }
    
    function handleDrop(e) {
        e.preventDefault();
        actualizarOrdenDespuesDeDrag();
    }
    
    function handleDragEnd(e) {
        this.classList.remove('dragging');
        draggedItem = null;
    }
    
    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.sortable-item:not(.dragging)')];
        
        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }
    
    // Función para actualizar orden después de drag
    function actualizarOrdenDespuesDeDrag() {
        const items = sortableContainer.querySelectorAll('.sortable-item');
        const newOrder = [];
        
        items.forEach(item => {
            const serviceId = item.dataset.serviceId;
            const servicioData = currentServiciosData.find(s => s.numero == serviceId);
            if (servicioData) {
                newOrder.push(servicioData);
            }
        });
        
        currentServiciosData = newOrder;
        generarListaOrdenable();
    }
    
    // Funciones para mover arriba/abajo
    window.moverArriba = function(index) {
        if (index > 0) {
            [currentServiciosData[index], currentServiciosData[index - 1]] = 
            [currentServiciosData[index - 1], currentServiciosData[index]];
            generarListaOrdenable();
        }
    }
    
    window.moverAbajo = function(index) {
        if (index < currentServiciosData.length - 1) {
            [currentServiciosData[index], currentServiciosData[index + 1]] = 
            [currentServiciosData[index + 1], currentServiciosData[index]];
            generarListaOrdenable();
        }
    }
    
    // Función para resetear orden
    function resetearOrden() {
        // Ordenar por ID de servicio (orden original)
        currentServiciosData.sort((a, b) => parseInt(a.numero) - parseInt(b.numero));
        generarListaOrdenable();
    }
    
    // Función para guardar orden
    async function guardarOrden() {
        const ordenFinal = currentServiciosData.map((servicio, index) => ({
            service_id: servicio.numero,
            orden: index + 1
        }));
        
        console.log('Orden final a guardar:', ordenFinal);
        
        try {
            // Mostrar loading en el botón
            const saveBtn = document.querySelector('.btn-save-order');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="bi bi-hourglass"></i> Guardando...';
            saveBtn.disabled = true;
            
            const response = await fetch(`/org/{{ $org->id }}/sectores/${currentSectorData.id}/guardar-orden`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    servicios: ordenFinal
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Mostrar éxito
                saveBtn.innerHTML = '<i class="bi bi-check-circle"></i> ¡Guardado!';
                saveBtn.style.background = '#28a745';
                
                console.log('Orden guardado exitosamente:', result);
                
                // Cerrar modal después de un momento
                setTimeout(() => {
                    cerrarModal();
                    // Recargar servicios para mostrar el nuevo orden
                    cargarServicios();
                }, 1500);
                
            } else {
                throw new Error(result.message || 'Error al guardar');
            }
            
        } catch (error) {
            console.error('Error al guardar orden:', error);
            
            // Restaurar botón y mostrar error
            const saveBtn = document.querySelector('.btn-save-order');
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
            
            alert(`Error al guardar el orden: ${error.message}`);
        }
    }
    
    // Función para cargar servicios del sector seleccionado
    async function cargarServicios() {
        const sectorId = sectorSelect.value;
        
        if (!sectorId) {
            serviciosContainer.innerHTML = '<p class="text-muted mb-0">Seleccione un sector para ver los servicios disponibles</p>';
            currentServiciosData = [];
            return;
        }
        
        // Mostrar loading
        serviciosContainer.innerHTML = '<p class="loading-text mb-0">🔄 Cargando servicios...</p>';
        
        try {
            console.log(`Cargando servicios para sector: ${sectorId}`);
            
            const response = await fetch(`/org/{{ $org->id }}/sectores/${sectorId}/servicios-con-orden`);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const servicios = await response.json();
            
            console.log(`Servicios obtenidos:`, servicios);
            
            // Almacenar datos para el modal
            currentServiciosData = servicios;
            
            if (servicios.length === 0) {
                serviciosContainer.innerHTML = '<p class="error-text mb-0">❌ No hay servicios registrados en este sector</p>';
                return;
            }
            
            // Obtener el nombre del sector seleccionado
            const sectorName = sectorSelect.options[sectorSelect.selectedIndex].textContent;
            
            // Mostrar servicios en filas con miembros
            let html = `
                <div class="service-count">
                    <strong>✅ ${servicios.length} servicio(s)</strong> encontrado(s) en <strong>${sectorName}</strong>
                </div>
            `;

            servicios.forEach(servicio => {
                html += `
                    <div class="service-row">
                        <span class="service-id">${servicio.numero}</span>
                        <span class="service-name">${servicio.full_name || 'Sin nombre'}</span>
                        <span class="service-rut">${servicio.rut || 'Sin RUT'}</span>
                        <span class="sector-badge">${sectorName}</span>
                        <span class="service-members">
                            ${servicio.members && servicio.members.length > 0
                                ? '<strong>Miembros:</strong> ' + servicio.members.map(m => m.name).join(', ')
                                : '<em>Sin miembros</em>'}
                        </span>
                    </div>
                `;
            });

            serviciosContainer.innerHTML = html;
            
        } catch (error) {
            console.error('Error al cargar servicios:', error);
            serviciosContainer.innerHTML = `<p class="error-text mb-0">❌ Error al cargar servicios: ${error.message}</p>`;
            currentServiciosData = [];
        }
    }
    
    // Funciones de diagnóstico para testing
    window.testSector = function(sectorId) {
        sectorSelect.value = sectorId;
        cargarServicios();
    };
    
    window.testTodosSectores = function() {
        const options = sectorSelect.querySelectorAll('option');
        
        console.log('=== PROBANDO TODOS LOS SECTORES ===');
        console.log('Sectores disponibles:', options.length - 1); // -1 para excluir el placeholder
        
        options.forEach(function(option, index) {
            const sectorId = option.value;
            const sectorName = option.textContent;
            
            // Solo probar si tiene un valor válido
            if (sectorId && sectorId !== '') {
                setTimeout(function() {
                    console.log(`\n--- Probando Sector ${index}: ${sectorName} (ID: ${sectorId}) ---`);
                    testSector(sectorId);
                }, index * 1000); // 1 segundo entre pruebas
            }
        });
    };
    
    console.log('Panel Control Rutas - Listo');
    console.log('Funciones disponibles: testSector(id), testTodosSectores()');
});
</script>
@endsection
