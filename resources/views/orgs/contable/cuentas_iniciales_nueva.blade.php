@extends('layouts.nice', ['active' => 'cuentas_iniciales'])

@section('title', 'Configuración de Cuentas Iniciales')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
    <!-- Header Principal con Diseño Moderno -->
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 shadow-2xl">
        <!-- Patrón de fondo decorativo -->
        <div class="absolute inset-0 bg-black opacity-10 bg-[url('data:image/svg+xml,%3Csvg width="20" height="20" xmlns="http://www.w3.org/2000/svg"%3E%3Cdefs%3E%3Cpattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse"%3E%3Cpath d="M 20 0 L 0 0 0 20" fill="none" stroke="%23ffffff" stroke-width="0.5" opacity="0.3"/%3E%3C/pattern%3E%3C/defs%3E%3Crect width="100%25" height="100%25" fill="url(%23grid)"/%3E%3C/svg%3E')]"></div>
        
        <div class="relative max-w-7xl mx-auto px-6 py-12">
            <!-- Breadcrumb Moderno -->
            <nav class="flex items-center space-x-3 text-white/80 text-sm mb-8">
                <a href="{{ route('orgs.dashboard', ['id' => $orgId]) }}" 
                   class="flex items-center hover:text-white transition-colors duration-200 bg-white/10 px-3 py-1 rounded-full backdrop-blur-sm">
                    <i class="bi bi-house-door mr-2"></i>Inicio
                </a>
                <i class="bi bi-chevron-right text-white/60"></i>
                <span class="bg-white/10 px-3 py-1 rounded-full backdrop-blur-sm">Contable</span>
                <i class="bi bi-chevron-right text-white/60"></i>
                <span class="text-white font-medium bg-white/20 px-3 py-1 rounded-full backdrop-blur-sm">Cuentas Iniciales</span>
            </nav>

            <!-- Título Principal -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="mb-6 lg:mb-0">
                    <div class="flex items-center mb-4">
                        <div class="bg-white/20 p-4 rounded-2xl mr-4 backdrop-blur-sm shadow-lg">
                            <i class="bi bi-bank text-3xl text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-4xl lg:text-5xl font-bold text-white mb-2">
                                Configuración de Cuentas
                            </h1>
                            <p class="text-xl text-white/90">Configure los saldos iniciales y datos bancarios</p>
                        </div>
                    </div>
                </div>
                
                <!-- Card de Estado -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 shadow-xl">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-white/80 text-sm font-medium">Estado del Sistema</span>
                        <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse shadow-lg"></div>
                    </div>
                    <div class="text-white text-2xl font-bold mb-1">Operativo</div>
                    <div class="text-white/80 text-sm">Conexión estable</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="max-w-7xl mx-auto px-6 py-12">
        <!-- Grid de Cards Principales -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 mb-12">
            <!-- Card Resumen Total -->
            <div class="lg:col-span-2 bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-500 hover:-translate-y-1">
                <div class="bg-gradient-to-r from-emerald-500 to-teal-600 p-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-white mb-2">Resumen General</h2>
                            <p class="text-emerald-100">Estado actual de las cuentas</p>
                        </div>
                        <div class="bg-white/20 p-4 rounded-2xl backdrop-blur-sm">
                            <i class="bi bi-cash-stack text-4xl text-white"></i>
                        </div>
                    </div>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl">
                            <div class="text-3xl font-bold text-gray-800 mb-2">$0</div>
                            <div class="text-gray-600 font-medium">Total en Efectivo</div>
                            <div class="text-xs text-gray-500 mt-1">Caja General</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl">
                            <div class="text-3xl font-bold text-gray-800 mb-2">$0</div>
                            <div class="text-gray-600 font-medium">Total Bancario</div>
                            <div class="text-xs text-gray-500 mt-1">Cuentas Corrientes</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Acciones Rápidas -->
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-500 hover:-translate-y-1">
                <div class="bg-gradient-to-r from-orange-500 to-red-500 p-6">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <i class="bi bi-lightning-charge mr-2"></i>
                        Acciones Rápidas
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <button class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <i class="bi bi-plus-circle mr-2"></i>
                        Nueva Cuenta
                    </button>
                    <button class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <i class="bi bi-upload mr-2"></i>
                        Importar
                    </button>
                    <button class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <i class="bi bi-gear mr-2"></i>
                        Configurar
                    </button>
                </div>
            </div>

            <!-- Card Estadísticas -->
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-500 hover:-translate-y-1">
                <div class="bg-gradient-to-r from-violet-500 to-purple-600 p-6">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <i class="bi bi-bar-chart mr-2"></i>
                        Estadísticas
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Cuentas Activas</span>
                        <span class="font-bold text-gray-800">0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Último Movimiento</span>
                        <span class="font-bold text-gray-800">-</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Balance Total</span>
                        <span class="font-bold text-green-600">$0</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario Principal de Configuración -->
        <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
            <!-- Header del Formulario -->
            <div class="bg-gradient-to-r from-slate-800 to-gray-900 p-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-2 flex items-center">
                            <i class="bi bi-bank mr-4 text-4xl"></i>
                            Configuración de Cuentas Iniciales
                        </h2>
                        <p class="text-gray-300 text-lg">Establezca los saldos iniciales y datos bancarios de su organización</p>
                    </div>
                    <div class="hidden lg:block bg-white/10 backdrop-blur-sm rounded-2xl p-4">
                        <i class="bi bi-shield-check text-4xl text-green-400"></i>
                    </div>
                </div>
            </div>

            <!-- Navegación por Tabs -->
            <div class="bg-gray-50 border-b border-gray-200">
                <nav class="max-w-full overflow-x-auto">
                    <div class="flex min-w-max px-8">
                        <button class="tab-btn active flex items-center py-6 px-6 border-b-3 border-indigo-500 font-semibold text-indigo-600 transition-all duration-300">
                            <i class="bi bi-cash-stack mr-3 text-xl"></i>
                            <span>Caja General</span>
                        </button>
                        <button class="tab-btn flex items-center py-6 px-6 border-b-3 border-transparent font-semibold text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-all duration-300">
                            <i class="bi bi-credit-card mr-3 text-xl"></i>
                            <span>Cuenta Corriente 1</span>
                        </button>
                        <button class="tab-btn flex items-center py-6 px-6 border-b-3 border-transparent font-semibold text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-all duration-300">
                            <i class="bi bi-credit-card-2-front mr-3 text-xl"></i>
                            <span>Cuenta Corriente 2</span>
                        </button>
                        <button class="tab-btn flex items-center py-6 px-6 border-b-3 border-transparent font-semibold text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-all duration-300">
                            <i class="bi bi-piggy-bank mr-3 text-xl"></i>
                            <span>Cuenta de Ahorro</span>
                        </button>
                    </div>
                </nav>
            </div>

            <!-- Contenido de Tabs -->
            <div class="p-8">
                <!-- Tab Content: Caja General -->
                <div class="tab-content active" id="tab-caja-general">
                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                        <!-- Formulario -->
                        <div class="xl:col-span-2 space-y-6">
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-8 border border-blue-100 shadow-lg">
                                <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                                    <div class="bg-blue-500 p-3 rounded-xl mr-4">
                                        <i class="bi bi-wallet2 text-white text-xl"></i>
                                    </div>
                                    Configuración de Caja General
                                </h3>
                                
                                <form class="space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-3">
                                                <i class="bi bi-currency-dollar mr-2 text-green-600"></i>
                                                Saldo Inicial
                                            </label>
                                            <div class="relative">
                                                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-bold text-lg">$</span>
                                                <input type="number" 
                                                       class="w-full pl-12 pr-4 py-4 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 bg-white shadow-sm hover:shadow-md text-lg font-semibold" 
                                                       placeholder="0.00"
                                                       step="0.01">
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-3">
                                                <i class="bi bi-person-badge mr-2 text-purple-600"></i>
                                                Responsable
                                            </label>
                                            <input type="text" 
                                                   class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 bg-white shadow-sm hover:shadow-md text-lg" 
                                                   placeholder="Nombre del responsable">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-3">
                                            <i class="bi bi-card-text mr-2 text-orange-600"></i>
                                            Observaciones
                                        </label>
                                        <textarea rows="4" 
                                                  class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 resize-none bg-white shadow-sm hover:shadow-md" 
                                                  placeholder="Observaciones o comentarios adicionales..."></textarea>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- Botones de Acción -->
                            <div class="flex flex-col sm:flex-row gap-4">
                                <button class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl">
                                    <i class="bi bi-check-circle mr-3"></i>
                                    Guardar Configuración
                                </button>
                                <button class="px-8 py-4 border-2 border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-300 shadow-lg hover:shadow-xl">
                                    <i class="bi bi-arrow-clockwise mr-3"></i>
                                    Restablecer
                                </button>
                            </div>
                        </div>

                        <!-- Panel de Vista Previa -->
                        <div class="space-y-6">
                            <!-- Vista Previa -->
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200 shadow-lg">
                                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                    <div class="bg-green-500 p-2 rounded-lg mr-3">
                                        <i class="bi bi-eye text-white"></i>
                                    </div>
                                    Vista Previa
                                </h3>
                                
                                <div class="bg-white rounded-xl p-6 shadow-md border border-green-100">
                                    <div class="flex items-center justify-between mb-4">
                                        <span class="font-bold text-gray-700">Caja General</span>
                                        <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-bold">
                                            <i class="bi bi-check-circle mr-1"></i>
                                            Activa
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold text-gray-800 mb-2">$0.00</div>
                                    <div class="text-sm text-gray-600">Saldo inicial configurado</div>
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <div class="text-xs text-gray-500">Responsable:</div>
                                        <div class="font-medium text-gray-700">Sin asignar</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Información Importante -->
                            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-6 border border-amber-200 shadow-lg">
                                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                    <div class="bg-amber-500 p-2 rounded-lg mr-3">
                                        <i class="bi bi-info-circle text-white"></i>
                                    </div>
                                    Información Importante
                                </h3>
                                <ul class="space-y-3 text-sm text-gray-700">
                                    <li class="flex items-start">
                                        <i class="bi bi-check-circle-fill text-green-500 mr-3 mt-0.5 flex-shrink-0"></i>
                                        <span>Los saldos iniciales se establecen una sola vez al configurar el sistema</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="bi bi-check-circle-fill text-green-500 mr-3 mt-0.5 flex-shrink-0"></i>
                                        <span>Estos valores servirán como base para todos los movimientos contables</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="bi bi-check-circle-fill text-green-500 mr-3 mt-0.5 flex-shrink-0"></i>
                                        <span>Solo los administradores pueden modificar esta configuración</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="bi bi-check-circle-fill text-green-500 mr-3 mt-0.5 flex-shrink-0"></i>
                                        <span>Recomendamos verificar todos los datos antes de confirmar</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Más contenido de tabs se agregaría aquí... -->
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos personalizados para la interfaz moderna */
.tab-btn.active {
    @apply border-indigo-500 text-indigo-600;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

/* Animaciones suaves */
.hover\:scale-105:hover {
    transform: scale(1.05);
}

/* Efectos de gradiente */
.bg-gradient-to-br {
    background-image: linear-gradient(to bottom right, var(--tw-gradient-stops));
}

/* Sombras personalizadas */
.shadow-2xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Transiciones suaves para todos los elementos interactivos */
button, input, textarea, select {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Efectos de hover para las cards */
.hover\:-translate-y-1:hover {
    transform: translateY(-0.25rem);
}

/* Backdrop blur para efectos de cristal */
.backdrop-blur-sm {
    backdrop-filter: blur(4px);
}

.backdrop-blur-md {
    backdrop-filter: blur(12px);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Funcionalidad de navegación por tabs
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach((button, index) => {
        button.addEventListener('click', function() {
            // Remover clases activas de todos los tabs
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-indigo-500', 'text-indigo-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            
            tabContents.forEach(content => {
                content.classList.remove('active');
            });
            
            // Activar el tab clickeado
            this.classList.add('active', 'border-indigo-500', 'text-indigo-600');
            this.classList.remove('border-transparent', 'text-gray-500');
            
            // Mostrar el contenido correspondiente
            if (tabContents[index]) {
                tabContents[index].classList.add('active');
            }
        });
    });
    
    // Efectos de hover mejorados para inputs
    const inputs = document.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-2px)';
            this.parentElement.style.transition = 'all 0.3s ease';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });
    });
    
    // Animación de entrada suave para las cards
    const cards = document.querySelectorAll('.bg-white');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Mensaje de bienvenida
    console.log('🎨 Vista moderna de Cuentas Iniciales cargada exitosamente!');
    console.log('✨ Interfaz completamente nueva y elegante lista para usar');
});
</script>
@endsection
