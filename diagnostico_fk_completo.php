<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=hydrosite_db', 'cristianenriquecarvajal@gmail.com', 'Apolo24@24');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== ANÁLISIS COMPLETO DE FOREIGN KEYS ===\n\n";
    
    // 1. Obtener todas las foreign keys
    $stmt = $pdo->query("
        SELECT 
            TABLE_NAME,
            COLUMN_NAME,
            CONSTRAINT_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE REFERENCED_TABLE_SCHEMA = 'hydrosite_db'
        AND REFERENCED_TABLE_NAME IS NOT NULL
        ORDER BY TABLE_NAME, COLUMN_NAME
    ");
    
    echo "Foreign Keys actuales:\n";
    echo "========================================\n";
    $foreignKeys = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['TABLE_NAME'] . " -> " . $row['COLUMN_NAME'] . " -> " . 
             $row['REFERENCED_TABLE_NAME'] . "." . $row['REFERENCED_COLUMN_NAME'] . "\n";
        $foreignKeys[] = $row;
    }
    
    echo "\n=== CONTEO DE REGISTROS ===\n";
    $tables = ['orgs', 'users', 'cuentas', 'movimientos', 'configuracion_cuentas_iniciales', 'categorias'];
    
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
            $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            echo "$table: $count registros\n";
        } catch (Exception $e) {
            echo "$table: Tabla no existe\n";
        }
    }
    
    echo "\n=== ANÁLISIS DE DEPENDENCIAS ===\n";
    
    // Verificar qué registros están causando problemas de FK
    if (!empty($foreignKeys)) {
        foreach ($foreignKeys as $fk) {
            echo "\nAnalizando: {$fk['TABLE_NAME']}.{$fk['COLUMN_NAME']} -> {$fk['REFERENCED_TABLE_NAME']}.{$fk['REFERENCED_COLUMN_NAME']}\n";
            
            try {
                // Buscar registros huérfanos
                $stmt = $pdo->query("
                    SELECT COUNT(*) as orphans
                    FROM {$fk['TABLE_NAME']} t1
                    LEFT JOIN {$fk['REFERENCED_TABLE_NAME']} t2 ON t1.{$fk['COLUMN_NAME']} = t2.{$fk['REFERENCED_COLUMN_NAME']}
                    WHERE t1.{$fk['COLUMN_NAME']} IS NOT NULL AND t2.{$fk['REFERENCED_COLUMN_NAME']} IS NULL
                ");
                $orphans = $stmt->fetch(PDO::FETCH_ASSOC)['orphans'];
                
                if ($orphans > 0) {
                    echo "  ⚠️  PROBLEMA: $orphans registros huérfanos encontrados\n";
                    
                    // Mostrar algunos ejemplos
                    $stmt = $pdo->query("
                        SELECT t1.{$fk['COLUMN_NAME']} as valor_faltante, COUNT(*) as cantidad
                        FROM {$fk['TABLE_NAME']} t1
                        LEFT JOIN {$fk['REFERENCED_TABLE_NAME']} t2 ON t1.{$fk['COLUMN_NAME']} = t2.{$fk['REFERENCED_COLUMN_NAME']}
                        WHERE t1.{$fk['COLUMN_NAME']} IS NOT NULL AND t2.{$fk['REFERENCED_COLUMN_NAME']} IS NULL
                        GROUP BY t1.{$fk['COLUMN_NAME']}
                        LIMIT 5
                    ");
                    
                    echo "  Valores problemáticos:\n";
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "    - {$row['valor_faltante']} ({$row['cantidad']} veces)\n";
                    }
                } else {
                    echo "  ✅ Sin problemas de integridad\n";
                }
            } catch (Exception $e) {
                echo "  ❌ Error al verificar: " . $e->getMessage() . "\n";
            }
        }
    }
    
    echo "\n=== RECOMENDACIONES PARA RESOLVER FK CONSTRAINTS ===\n";
    echo "1. Para eliminar registros padre con dependencias:\n";
    echo "   - Opción A: Eliminar primero los registros hijos\n";
    echo "   - Opción B: Usar CASCADE en la FK\n";
    echo "   - Opción C: Deshabilitar temporalmente las FK\n\n";
    
    echo "2. Para registros huérfanos:\n";
    echo "   - Crear los registros padre faltantes\n";
    echo "   - O eliminar los registros huérfanos\n\n";
    
    echo "3. Comandos útiles:\n";
    echo "   SET FOREIGN_KEY_CHECKS = 0; -- Deshabilitar FK\n";
    echo "   SET FOREIGN_KEY_CHECKS = 1; -- Habilitar FK\n";
    
} catch (Exception $e) {
    echo "Error de conexión: " . $e->getMessage() . "\n";
}
?>
