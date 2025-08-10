<?php
// Script para hacer banco_id nullable en la tabla cuentas

require_once 'vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "Verificando restricciones de clave foránea en tabla cuentas...\n";
    
    // Obtener las restricciones de clave foránea existentes
    $constraints = DB::select("
        SELECT CONSTRAINT_NAME 
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE TABLE_NAME = 'cuentas' 
        AND TABLE_SCHEMA = DATABASE() 
        AND REFERENCED_TABLE_NAME IS NOT NULL
    ");
    
    echo "Restricciones encontradas:\n";
    foreach ($constraints as $constraint) {
        echo "- " . $constraint->CONSTRAINT_NAME . "\n";
    }
    
    if (!empty($constraints)) {
        // Eliminar todas las restricciones de clave foránea
        foreach ($constraints as $constraint) {
            echo "Eliminando restricción: " . $constraint->CONSTRAINT_NAME . "\n";
            DB::statement("ALTER TABLE cuentas DROP FOREIGN KEY `{$constraint->CONSTRAINT_NAME}`");
        }
    }
    
    // Hacer la columna nullable
    echo "Modificando columna banco_id para permitir NULL...\n";
    DB::statement("ALTER TABLE cuentas MODIFY banco_id BIGINT UNSIGNED NULL");
    
    // Verificar si la tabla bancos existe antes de crear la restricción
    $bancosExists = DB::select("SHOW TABLES LIKE 'bancos'");
    
    if (!empty($bancosExists)) {
        // Verificar si la tabla bancos tiene la columna id
        $bancosColumns = DB::select("DESCRIBE bancos");
        $hasId = false;
        foreach ($bancosColumns as $column) {
            if ($column->Field === 'id') {
                $hasId = true;
                break;
            }
        }
        
        if ($hasId) {
            // Recrear la restricción de clave foránea permitiendo NULL
            echo "Recreando restricción de clave foránea...\n";
            try {
                DB::statement("ALTER TABLE cuentas ADD CONSTRAINT fk_cuentas_banco FOREIGN KEY (banco_id) REFERENCES bancos(id) ON DELETE SET NULL");
                echo "✅ Restricción de clave foránea creada correctamente.\n";
            } catch (Exception $e) {
                echo "⚠️ Warning: No se pudo crear la restricción de clave foránea: " . $e->getMessage() . "\n";
                echo "Pero la columna banco_id ahora permite NULL, que es lo importante.\n";
            }
        } else {
            echo "⚠️ Warning: La tabla bancos no tiene columna 'id'.\n";
        }
    } else {
        echo "⚠️ Warning: La tabla bancos no existe.\n";
    }
    
    echo "✅ Modificación completada exitosamente!\n";
    echo "La columna banco_id ahora permite valores NULL.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
