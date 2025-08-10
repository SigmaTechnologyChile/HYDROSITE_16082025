<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ForeignKeyConstraintService;

class FKConstraintManagerCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'fk:manage 
                            {action : check|delete|script}
                            {table : Table name}
                            {id : Record ID}
                            {--method=cascade : Delete method (cascade|check_only|force)}
                            {--confirm : Skip confirmation prompt}';

    /**
     * The console command description.
     */
    protected $description = 'Manage Foreign Key Constraints - Solution for Issue #1451';

    private $fkService;

    public function __construct()
    {
        parent::__construct();
        $this->fkService = new ForeignKeyConstraintService();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');
        $table = $this->argument('table');
        $id = $this->argument('id');
        $method = $this->option('method');

        $this->info("FK Constraint Manager - Issue #1451");
        $this->info("=====================================");

        switch ($action) {
            case 'check':
                return $this->checkDependencies($table, $id);
            
            case 'delete':
                return $this->deleteRecord($table, $id, $method);
            
            case 'script':
                return $this->generateScript($table, $id, $method);
            
            default:
                $this->error("Acción no válida. Usar: check|delete|script");
                return 1;
        }
    }

    private function checkDependencies($table, $id)
    {
        $this->info("Verificando dependencias para $table ID $id...");
        
        try {
            $report = $this->fkService->getDependencyReport($table, $id);
            
            $this->line("");
            $this->info("📊 REPORTE DE DEPENDENCIAS");
            $this->line("Tabla: {$report['table']}");
            $this->line("ID: {$report['id']}");
            
            if ($report['can_delete_safely']) {
                $this->line("✅ <fg=green>Puede eliminarse de forma segura</fg=green>");
            } else {
                $this->line("⚠️  <fg=yellow>Tiene {$report['total_dependent_records']} dependencias</fg=yellow>");
                
                $this->line("");
                $this->info("📋 DEPENDENCIAS ENCONTRADAS:");
                
                $headers = ['Tabla', 'Columna', 'Registros', 'Ejemplos'];
                $rows = [];
                
                foreach ($report['dependencies'] as $dep) {
                    $rows[] = [
                        $dep['table'],
                        $dep['column'],
                        $dep['count'],
                        implode(', ', array_slice($dep['examples'], 0, 3))
                    ];
                }
                
                $this->table($headers, $rows);
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }

    private function deleteRecord($table, $id, $method)
    {
        $this->info("Preparando eliminación de $table ID $id (método: $method)...");
        
        try {
            // Verificar dependencias primero
            $report = $this->fkService->getDependencyReport($table, $id);
            
            if (!$report['can_delete_safely']) {
                $this->warn("⚠️  Este registro tiene {$report['total_dependent_records']} dependencias");
                
                foreach ($report['dependencies'] as $dep) {
                    $this->line("  - {$dep['count']} registros en {$dep['table']}");
                }
                
                $this->line("");
                $totalRecords = $report['total_dependent_records'] + 1;
                $this->warn("TOTAL DE REGISTROS QUE SE ELIMINARÁN: $totalRecords");
            }
            
            // Confirmar si no se pasó la opción --confirm
            if (!$this->option('confirm')) {
                if (!$this->confirm('¿Continuar con la eliminación?')) {
                    $this->info('Eliminación cancelada');
                    return 0;
                }
            }
            
            // Ejecutar eliminación
            $this->info("Ejecutando eliminación...");
            $result = $this->fkService->safeDelete($table, $id, $method);
            
            if ($result['success']) {
                $this->line("✅ <fg=green>{$result['message']}</fg=green>");
                $this->info("Registros afectados: {$result['affected_records']}");
            } else {
                $this->error("❌ {$result['message']}");
                return 1;
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }

    private function generateScript($table, $id, $method)
    {
        $this->info("Generando script SQL para $table ID $id (método: $method)...");
        
        try {
            $script = $this->fkService->generateDeleteScript($table, $id, $method);
            
            $this->line("");
            $this->info("📄 SCRIPT SQL GENERADO:");
            $this->line("========================");
            $this->line($script);
            
            // Opción para guardar en archivo
            if ($this->confirm('¿Guardar script en archivo?')) {
                $filename = "delete_script_{$table}_{$id}_" . date('Y-m-d_H-i-s') . ".sql";
                $filepath = storage_path("logs/$filename");
                
                file_put_contents($filepath, $script);
                $this->info("Script guardado en: $filepath");
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
}
