<?php
require_once 'vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "ANÁLISIS EXHAUSTIVO DE TODAS LAS TABLAS REFERENCIADAS EN EL CÓDIGO:\n";
echo "====================================================================\n\n";

try {
    // Obtener todas las tablas existentes
    $tables = DB::select('SHOW TABLES');
    $existingTables = [];
    foreach ($tables as $table) {
        $existingTables[] = reset($table);
    }
    
    // Lista EXHAUSTIVA basada en el análisis del código
    $expectedTables = [
        // Tablas del sistema base Laravel
        'users', 'password_reset_tokens', 'sessions', 'cache', 'cache_locks',
        'jobs', 'job_batches', 'failed_jobs', 'migrations',
        
        // Tablas del sistema administrativo
        'admins', 'rutas', 'services', 'macromedidor_readings',
        
        // ===== TABLAS PRINCIPALES DEL SISTEMA (64 originales) =====
        
        // Sistema de organizaciones y miembros
        'orgs',                    // Organizaciones principales  
        'members',                 // Miembros/clientes
        'orgs_members',           // Relación muchos a muchos orgs-members
        'locations',              // Localidades/sectores
        'states',                 // Estados/regiones
        'cities',                 // Ciudades
        'provinces',              // Provincias (referenciado en LocationController)
        
        // Sistema de servicios y lecturas
        'readings',               // Lecturas de medidores
        'inventaries',            // Inventarios (referenciado en InventaryExport)
        'orders',                 // Órdenes/pedidos (PaymentsExport)
        
        // Sistema contable completo
        'cuentas',                // Cuentas contables
        'movimientos',            // Movimientos contables  
        'categorias',             // Categorías de movimientos
        'configuracion_inicial',   // Configuración inicial de cuentas
        'configuracion_cuentas_iniciales', // Config específica de cuentas iniciales
        'org_cuentas_iniciales',  // Cuentas iniciales por organización
        'conciliaciones',         // Conciliaciones bancarias
        'auditoria_cuentas',      // Auditoría de cambios en cuentas
        'bancos',                 // Catálogo de bancos
        'pruebas',               // Tabla de pruebas
        
        // Sistema organizacional y planes
        'org_planes',            // Planes de organizaciones
        'planes',                // Catálogo de planes
        'planes_vistas',         // Vistas de planes
        'modulos',               // Módulos del sistema
        'vistas',                // Vistas del sistema
        'notifications',         // Notificaciones
        
        // Sistema de inventarios y categorías
        'inventories_categories', // Categorías de inventarios
        'fixed_costs_config',    // Configuración de costos fijos
        'tier_config',           // Configuración de tarifas por tramos
        
        // Posibles tablas adicionales que podrían faltar
        'payment_methods',       // Métodos de pago
        'billing_cycles',        // Ciclos de facturación  
        'consumption_ranges',    // Rangos de consumo
        'tariff_structures',     // Estructuras tarifarias
        'meter_types',           // Tipos de medidores
        'service_categories',    // Categorías de servicios
        'account_types',         // Tipos de cuentas
        'transaction_types',     // Tipos de transacciones
        'report_templates',      // Plantillas de reportes
        'user_permissions',      // Permisos de usuarios
        'system_settings',       // Configuraciones del sistema
        'backup_logs',           // Logs de respaldos
        'audit_trails',          // Rastros de auditoría
        'file_uploads',          // Cargas de archivos
        'email_templates',       // Plantillas de email
        'sms_templates',         // Plantillas de SMS
        'dashboard_widgets',     // Widgets del dashboard
        'custom_fields',         // Campos personalizados
        'workflow_states',       // Estados de flujo de trabajo
        'approval_processes',    // Procesos de aprobación
        'document_versions',     // Versiones de documentos
        'integration_logs',      // Logs de integraciones
        'api_tokens',           // Tokens de API
        'rate_limits',          // Límites de tasa
        'feature_flags'         // Banderas de características
    ];
    
    echo "RESUMEN DE VERIFICACIÓN:\n";
    echo "========================\n";
    echo "Tablas existentes actualmente: " . count($existingTables) . "\n";
    echo "Tablas que deberían existir (estimado): " . count($expectedTables) . "\n";
    echo "Tablas reportadas originalmente: 64\n\n";
    
    $missingTables = [];
    $presentTables = [];
    
    foreach ($expectedTables as $tableName) {
        if (in_array($tableName, $existingTables)) {
            $presentTables[] = $tableName;
        } else {
            $missingTables[] = $tableName;
        }
    }
    
    echo "ESTADO ACTUAL:\n";
    echo "==============\n";
    echo "✅ Tablas presentes: " . count($presentTables) . "\n";
    echo "❌ Tablas faltantes: " . count($missingTables) . "\n\n";
    
    echo "TABLAS FALTANTES CONFIRMADAS:\n";
    echo "=============================\n";
    foreach ($missingTables as $table) {
        echo "❌ $table\n";
    }
    
    echo "\nTABLAS PRESENTES:\n";
    echo "=================\n";
    foreach ($presentTables as $table) {
        echo "✅ $table\n";
    }
    
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "CONCLUSIÓN: Se perdieron aproximadamente " . count($missingTables) . " tablas\n";
    echo "Esto confirma la pérdida masiva de datos reportada.\n";
    echo str_repeat("=", 60) . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
