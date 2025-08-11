@extends('layouts.nice')

@section('title', 'Configuración de Cuentas Iniciales')

{{-- Incluir estilos modernos del módulo contable --}}
@include('orgs.contable.partials.contable-styles')

@section('content')

<div class="balance-section">
  <!-- Header -->
  <div class="section-header">
    <div>
      <h1>
        <i class="bi bi-gear-fill" style="color: var(--info-color);"></i>
        Configuración de Cuentas Iniciales
      </h1>
      <p>Gestiona los saldos iniciales de todas las cuentas contables</p>
    </div>
    <div>
      <button type="button" onclick="openCuentasInicialesModal()" class="btn-config-primary">
        <i class="bi bi-gear"></i>
        Configurar Cuentas
      </button>
    </div>
  </div>

  <!-- Información actual de cuentas -->
  @if($configuraciones && $configuraciones->count() > 0)
  <div class="config-card">
    <h3>
      <i class="bi bi-info-circle"></i>
      Cuentas Configuradas Actualmente
    </h3>
    
    <div class="cuentas-grid">
      @foreach($configuraciones as $config)
      <div class="cuenta-item">
        <div class="cuenta-header">
          <h4>
            @if($config->tipo == 'caja_general')
              <i class="bi bi-cash-stack"></i> Caja General
            @elseif($config->tipo == 'cuenta_corriente_1')
              <i class="bi bi-credit-card"></i> Cuenta Corriente Principal
            @elseif($config->tipo == 'cuenta_ahorro')
              <i class="bi bi-piggy-bank"></i> Cuenta de Ahorro
            @else
              <i class="bi bi-bank"></i> {{ ucfirst(str_replace('_', ' ', $config->tipo)) }}
            @endif
          </h4>
        </div>
        <div class="cuenta-details">
          <p><strong>Saldo:</strong> ${{ number_format($config->saldo_actual) }}</p>
          @if($config->banco)
            <p><strong>Banco:</strong> {{ $config->banco }}</p>
          @endif
          @if($config->numero_cuenta)
            <p><strong>Número:</strong> {{ $config->numero_cuenta }}</p>
          @endif
          @if($config->responsable)
            <p><strong>Responsable:</strong> {{ $config->responsable }}</p>
          @endif
        </div>
      </div>
      @endforeach
    </div>
  </div>
  @else
  <div class="config-card text-center">
    <div class="empty-state">
      <i class="bi bi-exclamation-triangle" style="font-size: 4rem; color: var(--warning-color); margin-bottom: 1rem;"></i>
      <h3>No hay cuentas configuradas</h3>
      <p>Configura las cuentas iniciales para comenzar a usar el sistema contable.</p>
      <button type="button" onclick="openCuentasInicialesModal()" class="btn-config-primary">
        <i class="bi bi-plus-circle"></i>
        Configurar Primera Vez
      </button>
    </div>
  </div>
  @endif
</div>

<!-- Incluir el modal -->
@include('orgs.contable.cuentas_iniciales_modal')

<style>
/* Variables y estilos base */
:root {
  --primary-color: #2c5282;
  --secondary-color: #4CAF50;
  --danger-color: #e53e3e;
  --success-color: #48bb78;
  --warning-color: #dd6b20;
  --info-color: #3182ce;
  --light-bg: #f8f9fa;
  --dark-text: #2d3748;
  --border-color: #e2e8f0;
  --shadow-card: 0 4px 12px rgba(0, 0, 0, 0.08);
  --shadow-hover: 0 8px 20px rgba(0, 0, 0, 0.12);
}

.balance-section {
  padding: 20px;
  max-width: 1400px;
  margin: 0 auto;
  background: var(--light-bg);
}

.section-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 30px;
  background: white;
  padding: 25px;
  border-radius: 15px;
  box-shadow: var(--shadow-card);
}

.section-header h1 {
  color: var(--primary-color);
  font-size: 2.5rem;
  margin: 0;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 15px;
}

.section-header p {
  color: #718096;
  font-size: 1.1rem;
  margin: 0;
  margin-top: 5px;
}

.config-card {
  background: white;
  border-radius: 15px;
  padding: 30px;
  box-shadow: var(--shadow-card);
  margin-bottom: 30px;
}

.config-card h3 {
  color: var(--primary-color);
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.cuentas-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
}

.cuenta-item {
  background: #f8fafc;
  border: 2px solid var(--border-color);
  border-radius: 12px;
  padding: 20px;
  transition: all 0.3s ease;
}

.cuenta-item:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-hover);
  border-color: var(--primary-color);
}

.cuenta-header h4 {
  color: var(--primary-color);
  margin: 0 0 15px 0;
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 1.1rem;
}

.cuenta-details p {
  margin: 8px 0;
  color: var(--dark-text);
}

.empty-state {
  padding: 40px 20px;
}

.empty-state h3 {
  color: var(--dark-text);
  margin-bottom: 10px;
}

.empty-state p {
  color: #718096;
  margin-bottom: 30px;
  font-size: 1.1rem;
}

.btn-config-primary {
  background: linear-gradient(135deg, var(--secondary-color) 0%, #388e3c 100%);
  color: white;
  border: none;
  border-radius: 12px;
  padding: 15px 25px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  text-decoration: none;
  box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
}

.btn-config-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 18px rgba(76, 175, 80, 0.4);
  background: linear-gradient(135deg, #388e3c 0%, #2e7d32 100%);
}

.text-center {
  text-align: center;
}
</style>

<script>
// Función global para abrir el modal (será definida por el modal incluido)
window.addEventListener('DOMContentLoaded', function() {
  console.log('✅ Vista de configuración de cuentas iniciales cargada');
});
</script>

@endsection
