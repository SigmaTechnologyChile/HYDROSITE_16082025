{{-- 
    ARCHIVO ELIMINADO
    Este archivo ha sido reemplazado por panelcontrolrutas_simple.blade.php
    Fecha: 9 de agosto de 2025
    Motivo: Consolidación de vistas
--}}

    :root {

      --primary-color: #2c5282;

      --secondary-color: #4CAF50;

      --danger-color: #e53e3e;

      --light-bg: #f8f9fa;

    }



    * {

      box-sizing: border-box;

      margin: 0;

      padding: 0;

    }



    body {

      font-family: 'Poppins', sans-serif;

      background: linear-gradient(135deg, #f8f9fa, #eef2f7);

      padding: 20px;

      color: #333;

      min-height: 100vh;

    }



    .container {

      max-width: 1000px;

      margin: 0 auto;

      background-color: #fff;

      padding: 30px;

      border-radius: 12px;

      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);

    }



    h1 {

      color: var(--primary-color);

      text-align: center;

      font-size: 2.2rem;

      margin-bottom: 30px;

      position: relative;

    }



    h1::after {

      content: '';

      position: absolute;

      bottom: -10px;

      left: 50%;

      transform: translateX(-50%);

      width: 120px;

      height: 4px;

      background: linear-gradient(to right, var(--primary-color), var(--secondary-color));

      border-radius: 2px;

    }



    label {

      font-weight: 600;

      margin-bottom: 6px;

      display: block;

    }



    select, input {

      width: 100%;

      padding: 12px;

      border-radius: 8px;

      border: 1px solid #ccc;

      background-color: #f8fafc;

      font-size: 16px;

      margin-bottom: 15px;

      transition: border 0.3s ease;

    }



    select:focus, input:focus {

      border-color: var(--primary-color);

      outline: none;

      background-color: #fff;

      box-shadow: 0 0 0 3px rgba(44, 82, 130, 0.2);

    }



    .sector-selector {

      margin-bottom: 25px;

    }



    .client-list {

      border: 1px solid #ddd;

      border-radius: 8px;

      min-height: 200px;

      padding: 10px;

      background: #f9f9f9;

    }



    .client-item {

      background: white;

      padding: 12px;

      margin-bottom: 10px;

      border-radius: 8px;

      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);

      display: flex;

      justify-content: space-between;

      align-items: center;

    }



    .client-info {

      flex-grow: 1;

    }



    .service-number {

      font-weight: bold;

      color: #4a5568;

    }



    .client-actions {

      display: flex;

      gap: 8px;

    }



    .client-actions button {

      border: none;

      padding: 6px 10px;

      font-size: 14px;

      border-radius: 6px;

      cursor: pointer;

    }



    .move-btn {

      background-color: #edf2f7;

      color: #2d3748;

    }



    .move-btn:hover {

      background-color: #e2e8f0;

    }



    .btn-edit {

      background-color: var(--primary-color);

      color: white;

    }



    .btn-secondary {

      background-color: var(--danger-color);

      color: white;

    }



    .btn-primary {

      background: linear-gradient(120deg, var(--secondary-color), #388e3c);

      color: white;

      border: none;

      padding: 10px 20px;

      border-radius: 8px;

      cursor: pointer;

      font-weight: 600;

      font-size: 16px;

      transition: all 0.3s ease;

    }



    .btn-primary:hover {

      transform: translateY(-2px);

      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);

    }



    .buttons {

      display: flex;

      justify-content: flex-end;

      gap: 15px;

      margin-top: 20px;

    }



    /* MODAL */

    .modal {

      display: none;

      position: fixed;

      z-index: 999;

      left: 0;

      top: 0;

      width: 100%;

      height: 100%;

      background-color: rgba(0, 0, 0, 0.75);

      justify-content: center;

      align-items: center;

    }



    .modal-content {

      background-color: white;

      padding: 30px;

      border-radius: 12px;

      max-width: 550px;

      width: 90%;

      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);

      position: relative;

    }



    .modal-content h2 {

      color: var(--primary-color);

      margin-bottom: 20px;

      font-size: 1.6rem;

    }



    .close {

      position: absolute;

      right: 20px;

      top: 20px;

      font-size: 24px;

      color: #aaa;

      cursor: pointer;

      transition: color 0.3s;

    }



    .close:hover {

      color: black;

    }



    .modal-buttons {

      display: flex;

      justify-content: flex-end;

      gap: 10px;

      margin-top: 10px;

    }



    /* Nuevo modal para rutas */

    .modal-content-large {

      max-width: 90%;

      width: 90%;

      max-height: 90vh;

      overflow: auto;

    }



    .routes-header {

      display: flex;

      justify-content: space-between;

      align-items: center;

      margin-bottom: 20px;

    }



    .export-btn {

      background: linear-gradient(120deg, #2c5282, #4a6fa9);

      color: white;

      border: none;

      padding: 10px 20px;

      border-radius: 8px;

      cursor: pointer;

      font-weight: 600;

      font-size: 16px;

      transition: all 0.3s ease;

    }



    .export-btn:hover {

      background: linear-gradient(120deg, #4a6fa9, #2c5282);

      transform: translateY(-2px);

      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);

    }



    .routes-table {

      width: 100%;

      border-collapse: collapse;

      margin-top: 20px;

      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);

    }



    .routes-table th {

      background: linear-gradient(to right, var(--primary-color), #3a6ca8);

      color: white;

      text-align: left;

      padding: 15px;

      position: sticky;

      top: 0;

    }



    .routes-table td {

      padding: 12px 15px;

      border-bottom: 1px solid #eee;

    }



    .routes-table tr:nth-child(even) {

      background-color: #f8f9fa;

    }



    .routes-table tr:hover {

      background-color: #e9f7ff;

    }



    .sector-header {

      background-color: #2c5282;

      color: white;

      font-weight: bold;

      font-size: 1.1rem;

    }



    @media (max-width: 768px) {

      .client-item {

        flex-direction: column;

        align-items: flex-start;

      }



      .client-actions {

        margin-top: 10px;

      }



      .buttons {

        flex-direction: column;

        align-items: stretch;

      }



      .buttons button {

        width: 100%;

      }

    }

    /* Estilos para la tabla de miembros mejorada */
    .table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
      background: white;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .table th {
      background: linear-gradient(135deg, var(--primary-color), #3d72a4);
      color: white;
      padding: 15px 12px;
      text-align: left;
      font-weight: 600;
      border: none;
      font-size: 0.9rem;
    }

    .table th i {
      margin-right: 6px;
      color: rgba(255, 255, 255, 0.9);
    }

    .table td {
      padding: 12px;
      border-bottom: 1px solid #e9ecef;
      color: #495057;
      vertical-align: middle;
    }

    .table .member-row:hover {
      background-color: #f8f9fa;
      transform: translateY(-1px);
      transition: all 0.2s ease;
    }

    .service-badge {
      background: linear-gradient(135deg, var(--primary-color), #4169e1);
      color: white;
      padding: 6px 12px;
      border-radius: 15px;
      font-size: 0.9rem;
      font-weight: 600;
      display: inline-block;
      text-align: center;
      min-width: 80px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .spin {
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    /* Iconos en celdas */
    .table td i {
      margin-right: 6px;
      color: var(--primary-color);
    }

    /* Estilos específicos para información del miembro */
    .member-info {
      display: flex;
      flex-direction: column;
      gap: 2px;
    }

    .member-name {
      font-weight: 500;
      color: #333;
    }

    .member-info .text-muted {
      font-size: 0.8rem;
      color: #6c757d;
      font-style: italic;
    }

    .member-info i {
      color: var(--secondary-color);
      font-size: 1.1rem;
    }

      .routes-table {

        font-size: 14px;

      }



      .routes-table th,

      .routes-table td {

        padding: 8px 10px;

      }

    }

  </style>
  <div class="container">

    <h1>Configurar Rutas</h1>



    <div class="sector-selector">

      <label for="sector">Seleccionar Sector:</label>
      
      {{-- Debug temporal --}}
      @if(config('app.debug'))
        <small style="color: #666;">
          Debug: Locations count = {{ isset($locations) ? count($locations) : 'not set' }}
          @if(isset($locations) && count($locations) > 0)
            | First location: {{ $locations->first()->name ?? 'no name' }}
          @endif
        </small>
      @endif

      <select id="sector">

        <option value="">-- Seleccione un sector --</option>

        {{-- Debug específico para el @if --}}
        @if(config('app.debug'))
          <!-- Debug: isset = {{ isset($locations) ? 'true' : 'false' }}, count = {{ isset($locations) ? count($locations) : 'N/A' }}, type = {{ isset($locations) ? get_class($locations) : 'N/A' }} -->
        @endif

        {{-- Intentemos sin condiciones para debuggear --}}
        @forelse($locations as $location)
          <option value="{{ $location->id }}">{{ $location->name }}</option>
        @empty
          <option disabled>No hay sectores disponibles</option>
        @endforelse      </select>

    </div>



    <div id="client-container">
      <h3>Miembros en este sector</h3>
      <table id="clientes-sector-table" class="table">
        <thead>
          <tr>
            <th><i class="bi bi-geo-alt-fill"></i> Sector</th>
            <th><i class="bi bi-person-badge-fill"></i> ID de Servicio</th>
            <th><i class="bi bi-person-fill"></i> Nombre del Miembro</th>
          </tr>
        </thead>
        <tbody>
          @if(isset($clientes) && is_iterable($clientes))
            @foreach($clientes as $cliente)
              <tr>
                <td>{{ $cliente->sector_name ?? $cliente->location_name ?? '-' }}</td>
                <td>{{ $cliente->service_id ?? '-' }}</td>
                <td>{{ $cliente->member_name ?? $cliente->name ?? '-' }}</td>
              </tr>
            @endforeach
          @else
            <tr><td colspan="3" style="text-align:center;color:#888;">Seleccione un sector para ver los miembros</td></tr>
          @endif
        </tbody>
      </table>
      <div class="buttons">
        <button id="add-client-btn" class="btn-primary">Agregar Cliente</button>
        <button id="save-btn" class="btn-primary">Guardar Cambios</button>
        <button id="view-routes-btn" class="btn-primary">Ver Rutas</button>
      </div>
    </div>

  </div>



  <!-- Modal para clientes -->

  <div id="client-modal" class="modal">

    <div class="modal-content">

      <span class="close">&times;</span>

      <h2 id="modal-title">Agregar Cliente</h2>

      <form id="client-form" class="modal-form">

        <input type="hidden" id="client-id">

        <label for="client-name">Nombre del Cliente:</label>

        <input type="text" id="client-name" required>

        <label for="client-service">N° Servicio:</label>

        <input type="text" id="client-service" required>

        <label for="client-phone">Teléfono:</label>

        <input type="text" id="client-phone">

        <div class="modal-buttons">

          <button type="button" id="cancel-btn" class="btn-secondary">Cancelar</button>

          <button type="submit" id="save-client-btn" class="btn-primary">Guardar</button>

        </div>

      </form>

    </div>

  </div>



  <!-- Nuevo modal para ver rutas -->

  <div id="routes-modal" class="modal">

    <div class="modal-content modal-content_large">

      <span class="close">&times;</span>

      <div class="routes-header">

        <h2>Rutas Completas</h2>

        <button id="export-btn" class="export-btn">Exportar a Excel</button>

      </div>

      <div id="routes-table-container">

        <table class="routes-table">

          <thead>

            <tr>

              <th>Sector</th>

              <th>Nombre del Cliente</th>

              <th>N° Servicio</th>

              <th>Teléfono</th>

              <th>Orden</th>

            </tr>

          </thead>

          <tbody id="routes-table-body">

            <!-- Datos se llenarán dinámicamente -->

          </tbody>

        </table>

      </div>

    </div>

  </div>



  <script>

    // Esperar a que el DOM esté completamente cargado
    document.addEventListener('DOMContentLoaded', function() {

    // Datos iniciales

    const clientsData = {

      callejon: [

        { id: 1, name: "Juan Pérez", service: "SERV-001", phone: "555-1001", order: 1 },

        { id: 2, name: "María González", service: "SERV-002", phone: "555-1002", order: 2 },

        { id: 3, name: "Carlos López", service: "SERV-003", phone: "555-1003", order: 3 }

      ],

      ruta: [

        { id: 4, name: "Ana Martínez", service: "SERV-004", phone: "555-2001", order: 1 },

        { id: 5, name: "Pedro Sánchez", service: "SERV-005", phone: "555-2002", order: 2 },

        { id: 6, name: "Laura Ramírez", service: "SERV-006", phone: "555-2003", order: 3 }

      ],

      villa: [

        { id: 7, name: "José García", service: "SERV-007", phone: "555-3001", order: 1 },

        { id: 8, name: "Sofía Hernández", service: "SERV-008", phone: "555-3002", order: 2 },

        { id: 9, name: "Miguel Díaz", service: "SERV-009", phone: "555-3003", order: 3 }

      ]

    };



    let currentSector = '';

    let currentClientId = 0;

    let isEditing = false;

    // Log para confirmar que el script se está cargando
    console.log('Panel Control Rutas: Script iniciado');

    // Función de prueba simple
    window.prueba = function() {
        console.log('La función de prueba funciona!');
        return 'OK';
    };

    // Referencias a elementos del DOM

    const sectorSelect = document.getElementById('sector');

    const clientContainer = document.getElementById('client-container');

    const clientList = document.getElementById('client-list');

    const clientesTable = document.getElementById('clientes-sector-table');
    
    const clientesTbody = clientesTable ? clientesTable.querySelector('tbody') : null;

    console.log('=== ELEMENTOS DOM ===');
    console.log('sectorSelect:', sectorSelect);
    console.log('clientesTable:', clientesTable);
    console.log('clientesTbody:', clientesTbody);

    const addClientBtn = document.getElementById('add-client-btn');

    const saveBtn = document.getElementById('save-btn');

    const viewRoutesBtn = document.getElementById('view-routes-btn');

    const clientModal = document.getElementById('client-modal');

    const routesModal = document.getElementById('routes-modal');

    const modalTitle = document.getElementById('modal-title');

    const clientForm = document.getElementById('client-form');

    const clientIdInput = document.getElementById('client-id');

    const clientNameInput = document.getElementById('client-name');

    const clientServiceInput = document.getElementById('client-service');

    const clientPhoneInput = document.getElementById('client-phone');

    const cancelBtn = document.getElementById('cancel-btn');

    const closeBtns = document.querySelectorAll('.close');

    const routesTableBody = document.getElementById('routes-table-body');

    const exportBtn = document.getElementById('export-btn');



    // Event listeners - Usando una función directa
    document.getElementById('sector').onchange = function() {
      if (this.value) {
        cargarServicios(this.value);
      }
    };

    // Función simple para cargar servicios
    function cargarServicios(sectorId) {
      const tbody = document.querySelector('#clientes-sector-table tbody');
      if (!tbody) return;
      
      tbody.innerHTML = '<tr><td colspan="3">Cargando...</td></tr>';
      
      fetch('/ajax/clientes-por-sector/' + sectorId)
        .then(response => response.json())
        .then(servicios => {
          let html = '';
          servicios.forEach(servicio => {
            html += '<tr><td>' + (servicio.sector_name || '') + '</td><td><strong>' + servicio.service_id + '</strong></td><td>' + (servicio.member_name || 'Sin miembro') + '</td></tr>';
          });
          tbody.innerHTML = html || '<tr><td colspan="3">No hay servicios</td></tr>';
        })
        .catch(error => {
          tbody.innerHTML = '<tr><td colspan="3">Error: ' + error.message + '</td></tr>';
        });
    }

    if (addClientBtn) addClientBtn.addEventListener('click', () => openModal(false));

    saveBtn.addEventListener('click', saveChanges);

    viewRoutesBtn.addEventListener('click', viewRoutes);

    clientForm.addEventListener('submit', handleClientSubmit);

    cancelBtn.addEventListener('click', closeModal);

    exportBtn.addEventListener('click', exportToExcel);



    // Cerrar modales

    closeBtns.forEach(btn => {

      btn.addEventListener('click', function() {

        clientModal.style.display = 'none';

        routesModal.style.display = 'none';

      });

    });

    // Función de prueba del endpoint
    window.testEndpoint = async function(sectorId) {
      try {
        const url = `/ajax/clientes-por-sector/${sectorId}`;
        console.log('Testing URL:', url);
        const response = await fetch(url);
        const data = await response.json();
        console.log('Response data:', data);
        return data;
      } catch (error) {
        console.error('Test error:', error);
        return error;
      }
    };

    // Función para probar manualmente la carga
    window.testLoadSector = function(sectorId) {
      console.log('=== TEST MANUAL ===');
      if (sectorSelect) {
        sectorSelect.value = sectorId;
        console.log('Sector seleccionado manualmente:', sectorId);
        loadSectorClients();
      } else {
        console.error('sectorSelect no disponible');
      }
    };

    // Función para obtener todos los sectores disponibles
    window.getSectores = function() {
      if (sectorSelect) {
        const opciones = Array.from(sectorSelect.options).map(option => ({
          value: option.value,
          text: option.text
        }));
        console.log('Sectores disponibles:', opciones);
        return opciones;
      }
      return [];
    };

    // Función para cargar servicios del sector seleccionado
    async function loadSectorClients() {
      const sectorSelectEl = document.getElementById('sector');
      const tbodyEl = document.querySelector('#clientes-sector-table tbody');
      
      if (!sectorSelectEl || !tbodyEl) return;
      
      const sectorId = sectorSelectEl.value;
      if (!sectorId) return;
      
      // Mostrar loading
      tbodyEl.innerHTML = '<tr><td colspan="3" style="text-align:center;color:#007bff;"><i class="bi bi-hourglass-split"></i> Cargando servicios...</td></tr>';

      try {
        const response = await fetch(`/ajax/clientes-por-sector/${sectorId}`);
        if (!response.ok) throw new Error(`Error ${response.status}`);
        
        const servicios = await response.json();

        if (servicios.length > 0) {
          let html = '';
          servicios.forEach((servicio) => {
            html += `
              <tr>
                <td>${servicio.sector_name || 'Sin sector'}</td>
                <td><strong>${servicio.service_id}</strong></td>
                <td>${servicio.member_name || 'Sin miembro registrado'}</td>
              </tr>
            `;
          });
          tbodyEl.innerHTML = html;
        } else {
          tbodyEl.innerHTML = '<tr><td colspan="3" style="text-align:center;color:#6c757d;"><i class="bi bi-info-circle"></i> No hay servicios en este sector</td></tr>';
        }
      } catch (error) {
        tbodyEl.innerHTML = '<tr><td colspan="3" style="text-align:center;color:#dc3545;"><i class="bi bi-exclamation-triangle"></i> Error cargando servicios</td></tr>';
      }
    }



    // Mover cliente arriba/abajo

    function moveClient(id, dir) {

      const list = clientsData[currentSector];

      const idx = list.findIndex(c => c.id === id);

      if (dir === 'up' && idx > 0) {

        [list[idx].order, list[idx - 1].order] = [list[idx - 1].order, list[idx].order];

      } else if (dir === 'down' && idx < list.length - 1) {

        [list[idx].order, list[idx + 1].order] = [list[idx + 1].order, list[idx].order];

      }

      list.sort((a, b) => a.order - b.order);

      // loadSectorClients(); // Comentado porque ahora usamos la nueva funcionalidad

    }



    // Abrir modal para agregar/editar cliente

    function openModal(editing, id = null) {

      isEditing = editing;

      if (editing) {

        modalTitle.textContent = 'Editar Cliente';

        const client = clientsData[currentSector].find(c => c.id === id);

        clientIdInput.value = client.id;

        clientNameInput.value = client.name;

        clientServiceInput.value = client.service;

        clientPhoneInput.value = client.phone;

        currentClientId = client.id;

      } else {

        modalTitle.textContent = 'Agregar Cliente';

        clientForm.reset();

        currentClientId = 0;

      }

      clientModal.style.display = 'flex';

    }



    // Cerrar modal

    function closeModal() {

      clientModal.style.display = 'none';

    }



    // Manejar envío del formulario de cliente

    function handleClientSubmit(e) {

      e.preventDefault();

      const data = {

        id: isEditing ? currentClientId : Date.now(),

        name: clientNameInput.value,

        service: clientServiceInput.value,

        phone: clientPhoneInput.value,

        order: isEditing ? clientsData[currentSector].find(c => c.id === currentClientId).order : clientsData[currentSector].length + 1

      };

      if (isEditing) {

        const idx = clientsData[currentSector].findIndex(c => c.id === currentClientId);

        clientsData[currentSector][idx] = data;

      } else {

        clientsData[currentSector].push(data);

      }

      closeModal();

      loadSectorClients();

    }



    // Eliminar cliente

    function deleteClient(id) {

      if (confirm('¿Estás seguro de eliminar este cliente?')) {

        clientsData[currentSector] = clientsData[currentSector].filter(c => c.id !== id);

        clientsData[currentSector].forEach((c, i) => c.order = i + 1);

        loadSectorClients();

      }

    }



    // Guardar cambios

    function saveChanges() {

      // En una implementación real, aquí se enviarían los cambios al servidor

      alert('Cambios guardados correctamente');

    }



    // Ver todas las rutas

    function viewRoutes() {

      // Limpiar la tabla

      routesTableBody.innerHTML = '';



      // Obtener nombres de sectores

      const sectorNames = {

        callejon: 'Callejón La Copa',

        ruta: 'Ruta J40',

        villa: 'Villa Navidad'

      };



      // Llenar la tabla con todos los datos

      Object.entries(clientsData).forEach(([sectorKey, clients]) => {

        // Ordenar clientes por orden

        const sortedClients = [...clients].sort((a, b) => a.order - b.order);



        sortedClients.forEach((client, index) => {

          const row = document.createElement('tr');



          // Si es el primer cliente del sector, agregar fila de encabezado

          if (index === 0) {

            const headerRow = document.createElement('tr');

            headerRow.innerHTML = `

              <td colspan="5" class="sector-header">${sectorNames[sectorKey]}</td>

            `;

            routesTableBody.appendChild(headerRow);

          }



          row.innerHTML = `

            <td>${sectorNames[sectorKey]}</td>

            <td>${client.name}</td>

            <td>${client.service}</td>

            <td>${client.phone}</td>

            <td>${client.order}</td>

          `;

          routesTableBody.appendChild(row);

        });

      });



      // Mostrar el modal

      routesModal.style.display = 'flex';

    }



    // Exportar a Excel

    function exportToExcel() {

      // Crear contenido CSV

      let csvContent = "data:text/csv;charset=utf-8,"

        + "Sector,Nombre,Servicio,Teléfono,Orden\n";



      // Obtener nombres de sectores

      const sectorNames = {

        callejon: 'Callejón La Copa',

        ruta: 'Ruta J40',

        villa: 'Villa Navidad'

      };



      // Agregar todos los datos

      Object.entries(clientsData).forEach(([sectorKey, clients]) => {

        const sortedClients = [...clients].sort((a, b) => a.order - b.order);



        sortedClients.forEach(client => {

          csvContent += `${sectorNames[sectorKey]},${client.name},${client.service},${client.phone},${client.order}\n`;

        });

      });



      // Crear enlace de descarga

      const encodedUri = encodeURI(csvContent);

      const link = document.createElement("a");

      link.setAttribute("href", encodedUri);

      link.setAttribute("download", "rutas_clientes.csv");

      document.body.appendChild(link);



      // Descargar archivo

      link.click();

      document.body.removeChild(link);

    }



    link.click();

      document.body.removeChild(link);

    }

    // Cerrar modales al hacer clic fuera
    window.addEventListener('click', (e) => {
      if (e.target === clientModal) closeModal();
      if (e.target === routesModal) routesModal.style.display = 'none';
    });

    console.log('Use testEndpoint(sectorId) en la consola para probar el endpoint');
    
    // Test adicional para verificar que el script funciona
    console.log('Script cargado correctamente');
    console.log('testEndpoint disponible:', typeof window.testEndpoint);
    console.log('prueba disponible:', typeof window.prueba);
    
    // Mostrar mensaje por defecto al cargar la página
    if (clientesTbody) {
      clientesTbody.innerHTML = '<tr><td colspan="3" style="text-align:center;color:#6c757d;"><i class="bi bi-arrow-down-circle"></i> Seleccione un sector para ver los miembros</td></tr>';
    }

    }); // Cierre del DOMContentLoaded

  </script>

</body>

</html>
@endsection
