<?php
require_once 'vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "VERIFICACIÓN EXHAUSTIVA DE TODAS LAS TABLAS REQUERIDAS:\n";
echo "=======================================================\n\n";

try {
    // Obtener todas las tablas existentes
    $tables = DB::select('SHOW TABLES');
    $existingTables = [];
    foreach ($tables as $table) {
        $existingTables[] = reset($table);
    }
    
    // Lista completa de tablas que deberían existir según modelos y migraciones
    $expectedTables = [
        // Tablas del sistema base
        'users', 'password_reset_tokens', 'sessions', 'cache', 'cache_locks',
        'jobs', 'job_batches', 'failed_jobs', 'migrations',
        
        // Tablas del sistema administrativo
        'admins', 'rutas', 'services', 'macromedidor_readings',
        
        // Tablas del sistema contable
        'cuentas', 'movimientos', 'categorias', 'configuracion_inicial',
        'configuracion_cuentas_iniciales', 'org_cuentas_iniciales',
        'conciliaciones', 'auditoria_cuentas', 'pruebas',
        
        // Tablas del sistema organizacional
        'bancos', 'orgs_members', 'org_planes', 'planes', 'planes_vistas',
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
            echo "✅ $tableName - EXISTE\n";
        } else {
            $missingTables[] = $tableName;
            echo "❌ $tableName - FALTA\n";
        }
    }
    
    echo "\n=======================================================\n";
    echo "RESUMEN FINAL:\n";
    echo "✅ Tablas presentes: " . count($presentTables) . "\n";
    echo "❌ Tablas faltantes: " . count($missingTables) . "\n\n";
    
    if (!empty($missingTables)) {
        echo "TABLAS QUE FALTAN:\n";
        echo "------------------\n";
        foreach ($missingTables as $table) {
            echo "• $table\n";
        }
    }
    
    // Verificar también tablas extra que existen pero no están en la lista
    $extraTables = array_diff($existingTables, $expectedTables);
    if (!empty($extraTables)) {
        echo "\nTABLAS ADICIONALES ENCONTRADAS:\n";
        echo "-------------------------------\n";
        foreach ($extraTables as $table) {
            echo "• $table\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
