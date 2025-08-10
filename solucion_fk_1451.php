<?php
/**
 * SOLUCIÓN COMPLETA PARA ISSUE #1451
 * Foreign Key Constraint - No puede deletear línea padre
 * 
 * Este script proporciona múltiples métodos para resolver conflictos de FK
 */

class FKConstraintResolver {
    private $pdo;
    
    public function __construct() {
        $this->pdo = new PDO('mysql:host=127.0.0.1;dbname=hydrosite_db', 'cristianenriquecarvajal@gmail.com', 'Apolo24@24');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    /**
     * Resolver eliminación segura de registro con dependencias
     */
    public function eliminarConDependencias($tabla, $id, $metodo = 'cascade') {
        echo "=== ELIMINACIÓN SEGURA: $tabla ID $id ===\n";
        echo "Método: $metodo\n\n";
        
        try {
            $this->pdo->beginTransaction();
            
            switch ($metodo) {
                case 'cascade':
                    $this->eliminarCascade($tabla, $id);
                    break;
                case 'safe':
                    $this->eliminarSeguro($tabla, $id);
                    break;
                case 'force':
                    $this->eliminarForzado($tabla, $id);
                    break;
            }
            
            $this->pdo->commit();
            echo "✅ Eliminación completada exitosamente\n";
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            echo "❌ Error en eliminación: " . $e->getMessage() . "\n";
            return false;
        }
        
        return true;
    }
    
    /**
     * Método CASCADE - Elimina registros hijos primero
     */
    private function eliminarCascade($tabla, $id) {
        echo "Ejecutando eliminación en CASCADE...\n";
        
        $dependencias = $this->obtenerDependencias($tabla, $id);
        
        // Eliminar en orden inverso (hijos primero)
        foreach (array_reverse($dependencias) as $dep) {
            $sql = "DELETE FROM {$dep['tabla']} WHERE {$dep['columna']} = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            $affected = $stmt->rowCount();
            echo "- Eliminados $affected registros de {$dep['tabla']}\n";
        }
        
        // Eliminar registro padre
        $sql = "DELETE FROM $tabla WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        echo "- Eliminado registro padre de $tabla\n";
    }
    
    /**
     * Método SEGURO - Verifica antes de eliminar
     */
    private function eliminarSeguro($tabla, $id) {
        echo "Ejecutando eliminación segura...\n";
        
        $dependencias = $this->obtenerDependencias($tabla, $id);
        
        if (!empty($dependencias)) {
            throw new Exception("No se puede eliminar: existen " . count($dependencias) . " dependencias");
        }
        
        $sql = "DELETE FROM $tabla WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        echo "- Eliminado registro de $tabla (sin dependencias)\n";
    }
    
    /**
     * Método FORZADO - Deshabilita FK temporalmente
     */
    private function eliminarForzado($tabla, $id) {
        echo "Ejecutando eliminación forzada...\n";
        
        // Deshabilitar FK
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        
        $dependencias = $this->obtenerDependencias($tabla, $id);
        
        // Eliminar registros hijos
        foreach ($dependencias as $dep) {
            $sql = "DELETE FROM {$dep['tabla']} WHERE {$dep['columna']} = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            $affected = $stmt->rowCount();
            echo "- Eliminados $affected registros de {$dep['tabla']}\n";
        }
        
        // Eliminar registro padre
        $sql = "DELETE FROM $tabla WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        echo "- Eliminado registro padre de $tabla\n";
        
        // Rehabilitar FK
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    }
    
    /**
     * Obtener todas las dependencias de un registro
     */
    private function obtenerDependencias($tabla, $id) {
        $mapaDependencias = [
            'orgs' => [
                ['tabla' => 'tier_config', 'columna' => 'org_id'],
                ['tabla' => 'users', 'columna' => 'org_id'],
                ['tabla' => 'cuentas', 'columna' => 'org_id'],
                ['tabla' => 'configuracion_cuentas_iniciales', 'columna' => 'org_id']
            ],
            'cuentas' => [
                ['tabla' => 'auditoria_cuentas', 'columna' => 'cuenta_id'],
                ['tabla' => 'movimientos', 'columna' => 'cuenta_origen_id'],
                ['tabla' => 'movimientos', 'columna' => 'cuenta_destino_id']
            ],
            'categorias' => [
                ['tabla' => 'movimientos', 'columna' => 'categoria_id']
            ],
            'movimientos' => [
                ['tabla' => 'conciliaciones', 'columna' => 'movimiento_id']
            ]
        ];
        
        if (!isset($mapaDependencias[$tabla])) {
            return [];
        }
        
        $dependenciasEncontradas = [];
        
        foreach ($mapaDependencias[$tabla] as $dep) {
            try {
                $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM {$dep['tabla']} WHERE {$dep['columna']} = ?");
                $stmt->execute([$id]);
                $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
                
                if ($count > 0) {
                    $dependenciasEncontradas[] = [
                        'tabla' => $dep['tabla'],
                        'columna' => $dep['columna'],
                        'count' => $count
                    ];
                }
            } catch (Exception $e) {
                // Ignorar errores de tablas que no existen
            }
        }
        
        return $dependenciasEncontradas;
    }
    
    /**
     * Generar script SQL para eliminación manual
     */
    public function generarScriptEliminacion($tabla, $id, $metodo = 'cascade') {
        echo "=== SCRIPT SQL PARA ELIMINACIÓN MANUAL ===\n";
        echo "-- Tabla: $tabla, ID: $id, Método: $metodo\n\n";
        
        $dependencias = $this->obtenerDependencias($tabla, $id);
        
        if ($metodo === 'force') {
            echo "SET FOREIGN_KEY_CHECKS = 0;\n\n";
        }
        
        echo "START TRANSACTION;\n\n";
        
        foreach ($dependencias as $dep) {
            echo "-- Eliminar {$dep['count']} registros de {$dep['tabla']}\n";
            echo "DELETE FROM {$dep['tabla']} WHERE {$dep['columna']} = $id;\n\n";
        }
        
        echo "-- Eliminar registro padre\n";
        echo "DELETE FROM $tabla WHERE id = $id;\n\n";
        
        echo "COMMIT;\n\n";
        
        if ($metodo === 'force') {
            echo "SET FOREIGN_KEY_CHECKS = 1;\n";
        }
    }
    
    /**
     * Verificar qué registros se verían afectados
     */
    public function previsualizarEliminacion($tabla, $id) {
        echo "=== PREVISUALIZACIÓN DE ELIMINACIÓN ===\n";
        echo "Registro principal: $tabla ID $id\n\n";
        
        $dependencias = $this->obtenerDependencias($tabla, $id);
        
        if (empty($dependencias)) {
            echo "✅ Sin dependencias - Eliminación segura\n";
            return;
        }
        
        echo "⚠️  ADVERTENCIA: Se eliminarán también:\n";
        $totalAfectados = 1; // El registro principal
        
        foreach ($dependencias as $dep) {
            echo "- {$dep['count']} registros en {$dep['tabla']}\n";
            $totalAfectados += $dep['count'];
            
            // Mostrar algunos ejemplos
            try {
                $stmt = $this->pdo->prepare("SELECT id FROM {$dep['tabla']} WHERE {$dep['columna']} = ? LIMIT 3");
                $stmt->execute([$id]);
                $ejemplos = $stmt->fetchAll(PDO::FETCH_COLUMN);
                echo "  Ejemplos: " . implode(', ', $ejemplos) . "\n";
            } catch (Exception $e) {
                // Ignorar errores
            }
        }
        
        echo "\nTOTAL DE REGISTROS AFECTADOS: $totalAfectados\n";
    }
}

// Uso del script
try {
    $resolver = new FKConstraintResolver();
    
    // Ejemplo de uso - puedes modificar estos valores
    $tabla = 'orgs';
    $id = 1;
    
    echo "=== RESOLVEDOR DE FK CONSTRAINT - ISSUE #1451 ===\n\n";
    
    // 1. Previsualizar qué se eliminará
    $resolver->previsualizarEliminacion($tabla, $id);
    
    echo "\n" . str_repeat("=", 50) . "\n\n";
    
    // 2. Generar script manual
    $resolver->generarScriptEliminacion($tabla, $id, 'cascade');
    
    echo "\n" . str_repeat("=", 50) . "\n\n";
    
    // 3. Para ejecutar eliminación automática, descomenta:
    // $resolver->eliminarConDependencias($tabla, $id, 'cascade');
    
    echo "INSTRUCCIONES:\n";
    echo "1. Revisa la previsualización\n";
    echo "2. Usa el script SQL generado\n";
    echo "3. O descomenta la línea para ejecución automática\n\n";
    
    echo "MÉTODOS DISPONIBLES:\n";
    echo "- 'cascade': Elimina registros hijos primero\n";
    echo "- 'safe': Solo elimina si no hay dependencias\n";
    echo "- 'force': Deshabilita FK temporalmente\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
