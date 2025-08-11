<?php
require_once 'vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "VERIFICACIÓN COMPLETA DE TODAS LAS TABLAS REQUERIDAS:\n";
echo "=======================================================\n\n";

try {
    // Obtener todas las tablas existentes
    $tables = DB::select('SHOW TABLES');
    $existingTables = [];
    foreach ($tables as $table) {
        $existingTables[] = reset($table);
    }
    
    // Lista COMPLETA de tablas que deberían existir según modelos y migraciones
    $expectedTables = [
        // Tablas del sistema base
        'users', 'password_reset_tokens', 'sessions', 'cache', 'cache_locks',
        'jobs', 'job_batches', 'failed_jobs', 'migrations',
        
        // Tablas del sistema administrativo
        'admins', 'rutas', 'services', 'macromedidor_readings',
        
        // Tablas principales del sistema (mencionadas por el usuario)
        'members',           // Modelo Member (sin $table definido)
        'orgs',             // Modelo Org (sin $table definido)  
        'locations',        // Modelo Location (sin $table definido)
        'states',           // Referenciado en Location
        'cities',           // Referenciado en Location y Member exports
        
        // Tablas del sistema contable
        'cuentas', 'movimientos', 'categorias', 'configuracion_inicial',
        'configuracion_cuentas_iniciales', 'org_cuentas_iniciales',
        'conciliaciones', 'auditoria_cuentas', 'pruebas', 'bancos',
        
        // Tablas del sistema organizacional
        'orgs_members', 'org_planes', 'planes', 'planes_vistas',
        'modulos', 'vistas', 'notifications',
        
        // Tablas de inventario y configuración
        'inventories_categories', 'fixed_costs_config', 'tier_config'
    ];
    
    echo "RESUMEN DE VERIFICACIÓN:\n";
    echo "========================\n";
    echo "Tablas existentes: " . count($existingTables) . "\n";
    echo "Tablas esperadas: " . count($expectedTables) . "\n\n";
    
    $missingTables = [];
    $presentTables = [];
    
    foreach ($expectedTables as $tableName) {
        if (in_array($tableName, $existingTables)) {
            $presentTables[] = $tableName;
            echo "✅ $tableName\n";
        } else {
            $missingTables[] = $tableName;
            echo "❌ $tableName - FALTA\n";
        }
    }
    
    echo "\n=======================================================\n";
    echo "RESUMEN FINAL:\n";
    echo "✅ Tablas presentes: " . count($presentTables) . " / " . count($expectedTables) . "\n";
    echo "❌ Tablas faltantes: " . count($missingTables) . "\n\n";
    
    if (!empty($missingTables)) {
        echo "TABLAS QUE FALTAN:\n";
        echo "------------------\n";
        foreach ($missingTables as $table) {
            echo "• $table\n";
        }
        
        echo "\nPRIORIDAD DE RECREACIÓN:\n";
        echo "------------------------\n";
        echo "1. CRÍTICAS (sistema base):\n";
        foreach (['members', 'orgs', 'locations', 'states', 'cities'] as $critical) {
            if (in_array($critical, $missingTables)) {
                echo "   • $critical\n";
            }
        }
        
        echo "2. IMPORTANTES (sistema contable):\n";
        foreach (['bancos', 'configuracion_cuentas_iniciales'] as $important) {
            if (in_array($important, $missingTables)) {
                echo "   • $important\n";
            }
        }
        
        echo "3. COMPLEMENTARIAS:\n";
        foreach ($missingTables as $table) {
            if (!in_array($table, ['members', 'orgs', 'locations', 'states', 'cities', 'bancos', 'configuracion_cuentas_iniciales'])) {
                echo "   • $table\n";
            }
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
