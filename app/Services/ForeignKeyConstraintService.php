<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Servicio para resolver problemas de Foreign Key Constraints
 * Solución para Issue #1451
 */
class ForeignKeyConstraintService
{
    /**
     * Mapa de dependencias para cada tabla
     */
    private $dependencyMap = [
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

    /**
     * Eliminar registro de forma segura con manejo de dependencias
     */
    public function safeDelete($table, $id, $method = 'cascade')
    {
        try {
            DB::beginTransaction();

            $dependencies = $this->getDependencies($table, $id);
            
            Log::info("FK Constraint Service: Eliminando $table ID $id", [
                'method' => $method,
                'dependencies' => count($dependencies)
            ]);

            switch ($method) {
                case 'cascade':
                    $this->deleteCascade($table, $id, $dependencies);
                    break;
                    
                case 'check_only':
                    $this->checkDependencies($table, $id, $dependencies);
                    break;
                    
                case 'force':
                    $this->deleteForced($table, $id, $dependencies);
                    break;
                    
                default:
                    throw new Exception("Método no válido: $method");
            }

            DB::commit();
            
            Log::info("FK Constraint Service: Eliminación exitosa", [
                'table' => $table,
                'id' => $id,
                'method' => $method
            ]);

            return [
                'success' => true,
                'message' => "Registro eliminado exitosamente",
                'affected_records' => count($dependencies) + 1
            ];

        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error("FK Constraint Service: Error en eliminación", [
                'table' => $table,
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => "Error al eliminar: " . $e->getMessage(),
                'dependencies' => $this->getDependencies($table, $id)
            ];
        }
    }

    /**
     * Verificar dependencias sin eliminar
     */
    public function checkDependencies($table, $id, $dependencies = null)
    {
        if ($dependencies === null) {
            $dependencies = $this->getDependencies($table, $id);
        }

        if (!empty($dependencies)) {
            $message = "No se puede eliminar el registro. Dependencias encontradas:\n";
            foreach ($dependencies as $dep) {
                $message .= "- {$dep['count']} registros en {$dep['table']}\n";
            }
            throw new Exception($message);
        }

        return true;
    }

    /**
     * Eliminar en cascada (hijos primero)
     */
    private function deleteCascade($table, $id, $dependencies)
    {
        // Eliminar dependencias en orden reverso
        foreach (array_reverse($dependencies) as $dep) {
            $realTable = $this->getRealTableName($dep['table']);
            $affected = DB::table($realTable)
                ->where($dep['column'], $id)
                ->delete();
                
            Log::info("FK Constraint: Eliminados $affected registros de $realTable");
        }

        // Eliminar registro principal
        $affected = DB::table($table)->where('id', $id)->delete();
        Log::info("FK Constraint: Eliminado registro principal de $table");
    }

    /**
     * Eliminar forzado (deshabilitar FK temporalmente)
     */
    private function deleteForced($table, $id, $dependencies)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        try {
            // Eliminar dependencias
            foreach ($dependencies as $dep) {
                $realTable = $this->getRealTableName($dep['table']);
                DB::table($realTable)->where($dep['column'], $id)->delete();
            }

            // Eliminar registro principal
            DB::table($table)->where('id', $id)->delete();

        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }

    /**
     * Obtener dependencias de un registro
     */
    private function getDependencies($table, $id)
    {
        if (!isset($this->dependencyMap[$table])) {
            return [];
        }

        $dependencies = [];

        foreach ($this->dependencyMap[$table] as $dependentTable => $foreignKey) {
            try {
                $realTable = $this->getRealTableName($dependentTable);
                
                $count = DB::table($realTable)
                    ->where($foreignKey, $id)
                    ->count();

                if ($count > 0) {
                    $dependencies[] = [
                        'table' => $dependentTable,
                        'real_table' => $realTable,
                        'column' => $foreignKey,
                        'count' => $count
                    ];
                }
            } catch (Exception $e) {
                Log::warning("Error verificando dependencia $dependentTable: " . $e->getMessage());
            }
        }

        return $dependencies;
    }

    /**
     * Obtener nombre real de tabla (para casos como movimientos_origen -> movimientos)
     */
    private function getRealTableName($tableName)
    {
        if (strpos($tableName, 'movimientos_') === 0) {
            return 'movimientos';
        }
        
        return $tableName;
    }

    /**
     * Generar reporte de dependencias
     */
    public function getDependencyReport($table, $id)
    {
        $dependencies = $this->getDependencies($table, $id);
        
        $report = [
            'table' => $table,
            'id' => $id,
            'can_delete_safely' => empty($dependencies),
            'total_dependent_records' => array_sum(array_column($dependencies, 'count')),
            'dependencies' => []
        ];

        foreach ($dependencies as $dep) {
            $examples = DB::table($dep['real_table'])
                ->select('id')
                ->where($dep['column'], $id)
                ->limit(5)
                ->pluck('id')
                ->toArray();

            $report['dependencies'][] = [
                'table' => $dep['table'],
                'column' => $dep['column'],
                'count' => $dep['count'],
                'examples' => $examples
            ];
        }

        return $report;
    }

    /**
     * Generar script SQL para eliminación manual
     */
    public function generateDeleteScript($table, $id, $method = 'cascade')
    {
        $dependencies = $this->getDependencies($table, $id);
        
        $script = "-- Script de eliminación para $table ID $id\n";
        $script .= "-- Método: $method\n";
        $script .= "-- Generado: " . now()->format('Y-m-d H:i:s') . "\n\n";

        if ($method === 'force') {
            $script .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";
        }

        $script .= "START TRANSACTION;\n\n";

        foreach ($dependencies as $dep) {
            $script .= "-- Eliminar {$dep['count']} registros de {$dep['real_table']}\n";
            $script .= "DELETE FROM {$dep['real_table']} WHERE {$dep['column']} = $id;\n\n";
        }

        $script .= "-- Eliminar registro principal\n";
        $script .= "DELETE FROM $table WHERE id = $id;\n\n";
        $script .= "COMMIT;\n\n";

        if ($method === 'force') {
            $script .= "SET FOREIGN_KEY_CHECKS = 1;\n";
        }

        return $script;
    }
}
