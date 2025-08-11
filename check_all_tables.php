<?php
require_once 'vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "VERIFICACIÓN COMPLETA DE LA BASE DE DATOS:\n";
echo "============================================\n\n";

try {
    // Obtener todas las tablas
    $tables = DB::select('SHOW TABLES');
    
    echo "TODAS LAS TABLAS EXISTENTES:\n";
    echo "----------------------------\n";
    
    $allTables = [];
    foreach ($tables as $table) {
        $tableName = reset($table);
        $allTables[] = $tableName;
        echo "✅ " . $tableName . "\n";
    }
    
    echo "\n" . count($allTables) . " tablas encontradas.\n\n";
    
    // Verificar tablas específicas del sistema contable
    $contableTables = [
        'cuentas',
        'movimientos', 
        'categorias',
        'configuracion_cuentas_iniciales',
        'org_cuentas_iniciales',
        'configuracion_inicial',
        'conciliaciones',
        'auditoria_cuentas',
        'pruebas'
    ];
    
    echo "VERIFICACIÓN DE TABLAS DEL SISTEMA CONTABLE:\n";
    echo "--------------------------------------------\n";
    
    foreach ($contableTables as $tableName) {
        if (in_array($tableName, $allTables)) {
            echo "✅ $tableName - EXISTE\n";
            
            // Obtener estructura de la tabla
            $columns = DB::select("DESCRIBE $tableName");
            echo "   Columnas: ";
            foreach ($columns as $col) {
                echo $col->Field . " ";
            }
            echo "\n";
        } else {
            echo "❌ $tableName - NO EXISTE\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
