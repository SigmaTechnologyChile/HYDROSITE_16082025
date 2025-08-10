<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=hydrosite_db', 'cristianenriquecarvajal@gmail.com', 'Apolo24@24');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== SOLUCIÓN PARA FK CONSTRAINT ISSUE #1451 ===\n\n";
    
    // Función para verificar dependencias de un registro específico
    function verificarDependencias($pdo, $tabla, $id) {
        echo "Verificando dependencias para $tabla ID: $id\n";
        echo "================================================\n";
        
        $dependencias = [
            'orgs' => [
                'tier_config' => 'org_id',
                'users' => 'org_id',
                'cuentas' => 'org_id',
                'configuracion_cuentas_iniciales' => 'org_id'
            ],
            'cuentas' => [
                'auditoria_cuentas' => 'cuenta_id',
                'movimientos_origen' => 'cuenta_origen_id',
                'movimientos_destino' => 'cuenta_destino_id'
            ],
            'categorias' => [
                'movimientos' => 'categoria_id'
            ],
            'movimientos' => [
                'conciliaciones' => 'movimiento_id'
            ]
        ];
        
        if (!isset($dependencias[$tabla])) {
            echo "No hay dependencias conocidas para la tabla $tabla\n";
            return [];
        }
        
        $resultados = [];
        foreach ($dependencias[$tabla] as $tablaHija => $columnaFK) {
            try {
                // Simplificar: usar el nombre tal como está
                $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM movimientos WHERE $columnaFK = ?");
                if (strpos($tablaHija, 'movimientos') === false) {
                    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM $tablaHija WHERE $columnaFK = ?");
                }
                
                $stmt->execute([$id]);
                $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
                
                if ($count > 0) {
                    echo "⚠️  $tablaHija ($columnaFK): $count registros dependen de este\n";
                    $resultados[] = [
                        'tabla' => $tablaHija,
                        'columna' => $columnaFK,
                        'count' => $count
                    ];
                } else {
                    echo "✅ $tablaHija ($columnaFK): Sin dependencias\n";
                }
            } catch (Exception $e) {
                echo "❌ Error verificando $tablaHija: " . $e->getMessage() . "\n";
            }
        }
        
        return $resultados;
    }
    
    // Función para resolver conflictos de FK
    function resolverFK($pdo, $tabla, $id, $metodo = 'cascade') {
        echo "\n=== RESOLVIENDO FK CONSTRAINT PARA $tabla ID: $id ===\n";
        
        $dependencias = verificarDependencias($pdo, $tabla, $id);
        
        if (empty($dependencias)) {
            echo "✅ No hay dependencias. Puede eliminarse directamente.\n";
            return;
        }
        
        echo "\nMétodo seleccionado: $metodo\n";
        echo "Generando script de resolución...\n\n";
        
        switch ($metodo) {
            case 'cascade':
                echo "-- SCRIPT DE ELIMINACIÓN EN CASCADE\n";
                echo "-- EJECUTAR EN ORDEN PARA EVITAR FK CONSTRAINT\n\n";
                echo "SET FOREIGN_KEY_CHECKS = 0;\n\n";
                
                // Eliminar registros hijos primero
                foreach (array_reverse($dependencias) as $dep) {
                    echo "-- Eliminar registros hijos en {$dep['tabla']}\n";
                    echo "DELETE FROM {$dep['tabla']} WHERE {$dep['columna']} = $id;\n";
                    echo "-- Afectará {$dep['count']} registro(s)\n\n";
                }
                
                // Eliminar registro padre
                echo "-- Eliminar registro padre\n";
                echo "DELETE FROM $tabla WHERE id = $id;\n\n";
                echo "SET FOREIGN_KEY_CHECKS = 1;\n";
                break;
                
            case 'update_null':
                echo "-- SCRIPT PARA ESTABLECER FK EN NULL\n";
                echo "SET FOREIGN_KEY_CHECKS = 0;\n\n";
                
                foreach ($dependencias as $dep) {
                    echo "-- Establecer {$dep['columna']} en NULL para {$dep['tabla']}\n";
                    echo "UPDATE {$dep['tabla']} SET {$dep['columna']} = NULL WHERE {$dep['columna']} = $id;\n";
                    echo "-- Afectará {$dep['count']} registro(s)\n\n";
                }
                
                echo "-- Eliminar registro padre\n";
                echo "DELETE FROM $tabla WHERE id = $id;\n\n";
                echo "SET FOREIGN_KEY_CHECKS = 1;\n";
                break;
                
            case 'reassign':
                echo "-- SCRIPT PARA REASIGNAR A OTRO REGISTRO\n";
                echo "-- NOTA: Necesitarás especificar el nuevo ID de destino\n\n";
                echo "SET @nuevo_id = ?; -- Especificar el ID de destino\n\n";
                
                foreach ($dependencias as $dep) {
                    echo "-- Reasignar {$dep['columna']} en {$dep['tabla']}\n";
                    echo "UPDATE {$dep['tabla']} SET {$dep['columna']} = @nuevo_id WHERE {$dep['columna']} = $id;\n";
                    echo "-- Afectará {$dep['count']} registro(s)\n\n";
                }
                
                echo "-- Eliminar registro padre\n";
                echo "DELETE FROM $tabla WHERE id = $id;\n";
                break;
        }
    }
    
    // Menú interactivo
    echo "Selecciona una opción:\n";
    echo "1. Verificar dependencias de un registro específico\n";
    echo "2. Generar script de resolución CASCADE\n";
    echo "3. Generar script de resolución SET NULL\n";
    echo "4. Generar script de resolución REASSIGN\n";
    echo "5. Mostrar todas las FK activas\n";
    echo "6. Análisis completo del problema\n\n";
    
    // Por defecto, hacemos análisis completo
    $opcion = 6;
    
    switch ($opcion) {
        case 6:
            echo "=== ANÁLISIS COMPLETO DEL PROBLEMA FK CONSTRAINT ===\n\n";
            
            // Mostrar tablas con más dependencias
            echo "TABLAS CON FOREIGN KEYS (ordenadas por impacto):\n";
            echo "==================================================\n";
            
            $tablas_padre = ['orgs', 'cuentas', 'categorias', 'movimientos', 'planes', 'modulos', 'vistas'];
            
            foreach ($tablas_padre as $tabla) {
                try {
                    $stmt = $pdo->query("SELECT COUNT(*) as count FROM $tabla");
                    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
                    
                    if ($count > 0) {
                        echo "\n$tabla ($count registros):\n";
                        // Verificar algunas dependencias ejemplo
                        $stmt = $pdo->query("SELECT id FROM $tabla LIMIT 1");
                        $ejemplo = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($ejemplo) {
                            verificarDependencias($pdo, $tabla, $ejemplo['id']);
                        }
                    }
                } catch (Exception $e) {
                    echo "Error en $tabla: " . $e->getMessage() . "\n";
                }
            }
            
            echo "\n=== RECOMENDACIONES ESPECÍFICAS ===\n";
            echo "1. Para orgs: Eliminar primero users, cuentas, tier_config\n";
            echo "2. Para cuentas: Eliminar primero movimientos, auditoria_cuentas\n";
            echo "3. Para categorias: Eliminar primero movimientos\n";
            echo "4. Para movimientos: Eliminar primero conciliaciones\n\n";
            
            echo "COMANDOS SEGUROS PARA ELIMINACIÓN:\n";
            echo "===================================\n";
            echo "-- Deshabilitar FK temporalmente\n";
            echo "SET FOREIGN_KEY_CHECKS = 0;\n";
            echo "-- Realizar eliminaciones\n";
            echo "DELETE FROM tabla_hija WHERE fk_column = valor;\n";
            echo "DELETE FROM tabla_padre WHERE id = valor;\n";
            echo "-- Rehabilitar FK\n";
            echo "SET FOREIGN_KEY_CHECKS = 1;\n";
            break;
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
