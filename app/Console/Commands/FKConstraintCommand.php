<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ForeignKeyConstraintService;

class FKConstraintCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'fk:constraint 
                            {action : check, delete, script, or report}
                            {table : Table name}
                            {id : Record ID}
                            {--method=cascade : Deletion method (cascade, check_only, force)}
                            {--confirm : Confirm deletion without prompt}';

    /**
     * The console command description.
     */
    protected $description = 'Manage Foreign Key Constraints - Solution for Issue #1451';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');
        $table = $this->argument('table');
        $id = $this->argument('id');
        $method = $this->option('method');
        $confirm = $this->option('confirm');

        $fkService = new ForeignKeyConstraintService();

        $this->info("FK Constraint Manager - Issue #1451");
        $this->info("Action: $action | Table: $table | ID: $id");
        $this->line('');

        try {
            switch ($action) {
                case 'check':
                    $this->handleCheck($fkService, $table, $id);
                    break;
                
                case 'delete':
                    $this->handleDelete($fkService, $table, $id, $method, $confirm);
                    break;
                
                case 'script':
                    $this->handleScript($fkService, $table, $id, $method);
                    break;
                
                case 'report':
                    $this->handleReport($fkService, $table, $id);
                    break;
                
                default:
                    $this->error("Acción no válida. Use: check, delete, script, o report");
                    return 1;
            }

        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Handle check action
     */
    private function handleCheck($fkService, $table, $id)
    {
        $this->info("Verificando dependencias...");
        
        $report = $fkService->getDependencyReport($table, $id);
        
        $this->table(['Tabla', 'ID', 'Puede eliminarse', 'Registros dependientes'], [
            [$report['table'], $report['id'], $report['can_delete_safely'] ? '✅ Sí' : '❌ No', $report['total_dependent_records']]
        ]);

        if (!empty($report['dependencies'])) {
            $this->line('');
            $this->warn('Dependencias encontradas:');
            
            $dependencyData = [];
            foreach ($report['dependencies'] as $dep) {
                $dependencyData[] = [
                    $dep['table'],
                    $dep['column'],
                    $dep['count'],
                    implode(', ', array_slice($dep['examples'], 0, 3))
                ];
            }
            
            $this->table(['Tabla', 'Columna', 'Cantidad', 'Ejemplos'], $dependencyData);
        }
    }

    /**
     * Handle delete action
     */
    private function handleDelete($fkService, $table, $id, $method, $confirm)
    {
        // Verificar dependencias primero
        $report = $fkService->getDependencyReport($table, $id);
        
        if (!$report['can_delete_safely']) {
            $this->warn("¡ATENCIÓN! Este registro tiene {$report['total_dependent_records']} dependencias.");
            $this->line("Se eliminarán registros adicionales:");
            
            foreach ($report['dependencies'] as $dep) {
                $this->line("  - {$dep['count']} registros en {$dep['table']}");
            }
            $this->line('');
        }

        if (!$confirm && !$this->confirm("¿Proceder con la eliminación usando método '$method'?")) {
            $this->info('Operación cancelada.');
            return;
        }

        $this->info("Eliminando registro...");
        
        $result = $fkService->safeDelete($table, $id, $method);
        
        if ($result['success']) {
            $this->info("✅ Registro eliminado exitosamente.");
            $this->line("Registros afectados: {$result['affected_records']}");
        } else {
            $this->error("❌ Error en eliminación: {$result['message']}");
        }
    }

    /**
     * Handle script action
     */
    private function handleScript($fkService, $table, $id, $method)
    {
        $this->info("Generando script SQL...");
        
        $script = $fkService->generateDeleteScript($table, $id, $method);
        
        $this->line('');
        $this->line('=== SCRIPT SQL GENERADO ===');
        $this->line($script);
        $this->line('=== FIN DEL SCRIPT ===');
        $this->line('');
        
        if ($this->confirm('¿Guardar script en archivo?')) {
            $filename = "delete_{$table}_{$id}_" . date('Y-m-d_H-i-s') . ".sql";
            file_put_contents(storage_path("app/{$filename}"), $script);
            $this->info("Script guardado en: storage/app/{$filename}");
        }
    }

    /**
     * Handle report action
     */
    private function handleReport($fkService, $table, $id)
    {
        $this->info("Generando reporte completo...");
        
        $report = $fkService->getDependencyReport($table, $id);
        
        $this->line('');
        $this->line('=== REPORTE DE DEPENDENCIAS ===');
        $this->line("Tabla: {$report['table']}");
        $this->line("ID: {$report['id']}");
        $this->line("Puede eliminarse seguro: " . ($report['can_delete_safely'] ? 'SÍ' : 'NO'));
        $this->line("Total registros dependientes: {$report['total_dependent_records']}");
        
        if (!empty($report['dependencies'])) {
            $this->line('');
            $this->line('DEPENDENCIAS DETALLADAS:');
            
            foreach ($report['dependencies'] as $dep) {
                $this->line("  {$dep['table']} ({$dep['column']}):");
                $this->line("    - Cantidad: {$dep['count']}");
                $this->line("    - Ejemplos: " . implode(', ', $dep['examples']));
            }
        }
        
        $this->line('');
        $this->line('MÉTODOS DE ELIMINACIÓN RECOMENDADOS:');
        
        if ($report['can_delete_safely']) {
            $this->line("  ✅ check_only - Eliminación directa segura");
        } else {
            $this->line("  ⚠️  cascade - Eliminar dependencias primero");
            $this->line("  ⚠️  force - Deshabilitar FK temporalmente");
        }
        
        $this->line('');
        $this->line('COMANDOS DISPONIBLES:');
        $this->line("  Eliminar: php artisan fk:constraint delete {$table} {$id} --method=cascade");
        $this->line("  Script:   php artisan fk:constraint script {$table} {$id} --method=cascade");
    }
}
