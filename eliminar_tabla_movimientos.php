<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=hydrosite_db', 'cristianenriquecarvajal@gmail.com', 'Apolo24@24');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== ELIMINACIÓN DE TABLA MOVIMIENTOS ===\n\n";
    
    // 1. Verificar si la tabla existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'movimientos'");
    $tableExists = $stmt->rowCount() > 0;
    
    if (!$tableExists) {
        echo "❌ La tabla 'movimientos' no existe.\n";
        exit;
    }
    
    echo "✅ La tabla 'movimientos' existe.\n\n";
    
    // 2. Verificar datos en la tabla
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM movimientos");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    echo "📊 Registros en movimientos: $count\n\n";
    
    // 3. Verificar foreign keys que apuntan A movimientos
    echo "🔍 Verificando foreign keys que apuntan a movimientos...\n";
    $stmt = $pdo->query("
        SELECT 
            TABLE_NAME,
            COLUMN_NAME,
            CONSTRAINT_NAME
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE REFERENCED_TABLE_SCHEMA = 'hydrosite_db'
        AND REFERENCED_TABLE_NAME = 'movimientos'
    ");
    
    $dependentFKs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($dependentFKs)) {
        echo "⚠️  ATENCIÓN: Hay foreign keys que apuntan a movimientos:\n";
        foreach ($dependentFKs as $fk) {
            echo "   - {$fk['TABLE_NAME']}.{$fk['COLUMN_NAME']} (constraint: {$fk['CONSTRAINT_NAME']})\n";
        }
        echo "\n";
    } else {
        echo "✅ No hay foreign keys externas que apunten a movimientos.\n\n";
    }
    
    // 4. Verificar foreign keys DESDE movimientos hacia otras tablas
    echo "🔍 Verificando foreign keys desde movimientos hacia otras tablas...\n";
    $stmt = $pdo->query("
        SELECT 
            COLUMN_NAME,
            CONSTRAINT_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE TABLE_SCHEMA = 'hydrosite_db'
        AND TABLE_NAME = 'movimientos'
        AND REFERENCED_TABLE_NAME IS NOT NULL
    ");
    
    $outgoingFKs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($outgoingFKs)) {
        echo "📋 Foreign keys desde movimientos:\n";
        foreach ($outgoingFKs as $fk) {
            echo "   - {$fk['COLUMN_NAME']} -> {$fk['REFERENCED_TABLE_NAME']}.{$fk['REFERENCED_COLUMN_NAME']}\n";
        }
        echo "\n";
    }
    
    // 5. Generar script de eliminación
    echo "=== SCRIPT DE ELIMINACIÓN ===\n\n";
    
    echo "-- Script para eliminar tabla movimientos\n";
    echo "-- Generado: " . date('Y-m-d H:i:s') . "\n\n";
    
    if (!empty($dependentFKs)) {
        echo "-- PASO 1: Eliminar foreign keys que apuntan a movimientos\n";
        foreach ($dependentFKs as $fk) {
            echo "ALTER TABLE {$fk['TABLE_NAME']} DROP FOREIGN KEY {$fk['CONSTRAINT_NAME']};\n";
        }
        echo "\n";
    }
    
    echo "-- PASO 2: Eliminar la tabla movimientos\n";
    echo "DROP TABLE IF EXISTS movimientos;\n\n";
    
    // 6. Opción de ejecución automática
    echo "=== OPCIONES DE EJECUCIÓN ===\n\n";
    echo "1. EJECUTAR AUTOMÁTICAMENTE (descomenta la línea de abajo):\n";
    echo "// \$ejecutar = true;\n\n";
    
    echo "2. EJECUTAR MANUALMENTE:\n";
    echo "   Copia y pega el script SQL de arriba en tu gestor de base de datos.\n\n";
    
    // Descomenta esta línea para ejecutar automáticamente
    $ejecutar = true;
    
    if (isset($ejecutar) && $ejecutar === true) {
        echo "🚀 EJECUTANDO ELIMINACIÓN AUTOMÁTICA...\n\n";
        
        try {
            $pdo->beginTransaction();
            
            // Eliminar foreign keys dependientes
            foreach ($dependentFKs as $fk) {
                echo "Eliminando FK: {$fk['CONSTRAINT_NAME']}\n";
                $pdo->exec("ALTER TABLE {$fk['TABLE_NAME']} DROP FOREIGN KEY {$fk['CONSTRAINT_NAME']}");
            }
            
            // Eliminar tabla
            echo "Eliminando tabla movimientos...\n";
            $pdo->exec("DROP TABLE IF EXISTS movimientos");
            
            $pdo->commit();
            echo "\n✅ ¡Tabla movimientos eliminada exitosamente!\n";
            
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "\n❌ Error durante la eliminación: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n=== INFORMACIÓN ADICIONAL ===\n";
    echo "- Si eliminas la tabla movimientos, perderás todos los datos de transacciones.\n";
    echo "- Asegúrate de hacer un backup antes de proceder.\n";
    echo "- Las foreign keys se eliminarán automáticamente si existen.\n";
    
} catch (Exception $e) {
    echo "Error de conexión: " . $e->getMessage() . "\n";
}
?>
