<?php
require_once 'vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "VERIFICANDO TABLAS EN LA BASE DE DATOS:\n";
echo "==========================================\n\n";

try {
    $tables = DB::select('SHOW TABLES');
    
    if (empty($tables)) {
        echo "❌ NO HAY TABLAS EN LA BASE DE DATOS\n";
    } else {
        echo "TABLAS EXISTENTES:\n";
        foreach ($tables as $table) {
            $tableName = reset($table);
            echo "✅ " . $tableName . "\n";
        }
    }
    
    echo "\n==========================================\n";
    echo "VERIFICANDO TABLAS CRÍTICAS:\n";
    
    $criticalTables = ['cuentas', 'movimientos', 'categorias', 'configuracion_cuentas_iniciales', 'migrations'];
    
    foreach ($criticalTables as $tableName) {
        if (Schema::hasTable($tableName)) {
            echo "✅ $tableName - EXISTE\n";
        } else {
            echo "❌ $tableName - NO EXISTE\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
