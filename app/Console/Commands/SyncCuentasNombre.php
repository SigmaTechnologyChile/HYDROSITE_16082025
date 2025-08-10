<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncCuentasNombre extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:cuentas-nombre';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza la columna nombre de cuentas con la columna banco de configuracion_cuentas_iniciales';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando sincronización de nombres de cuentas...');

        try {
            // Contar registros antes
            $cuentasAntes = DB::table('cuentas')->count();
            $this->info("Total de cuentas encontradas: {$cuentasAntes}");

            // Sincronizar nombres de cuentas con bancos de configuración
            $updated = DB::statement("
                UPDATE cuentas 
                SET nombre = (
                    SELECT banco 
                    FROM configuracion_cuentas_iniciales 
                    WHERE configuracion_cuentas_iniciales.cuenta_id = cuentas.id 
                    AND configuracion_cuentas_iniciales.banco IS NOT NULL
                    LIMIT 1
                ),
                banco = (
                    SELECT banco 
                    FROM configuracion_cuentas_iniciales 
                    WHERE configuracion_cuentas_iniciales.cuenta_id = cuentas.id 
                    AND configuracion_cuentas_iniciales.banco IS NOT NULL
                    LIMIT 1
                )
                WHERE EXISTS (
                    SELECT 1 
                    FROM configuracion_cuentas_iniciales 
                    WHERE configuracion_cuentas_iniciales.cuenta_id = cuentas.id 
                    AND configuracion_cuentas_iniciales.banco IS NOT NULL
                )
            ");

            // Mostrar resultados
            $cuentas = DB::table('cuentas')->get(['id', 'nombre', 'banco']);
            $this->info("\nEstado actual de las cuentas:");
            foreach ($cuentas as $cuenta) {
                $this->line("ID: {$cuenta->id} | Nombre: {$cuenta->nombre} | Banco: {$cuenta->banco}");
            }

            $configuraciones = DB::table('configuracion_cuentas_iniciales')->get(['cuenta_id', 'banco']);
            $this->info("\nConfiguraciones encontradas:");
            foreach ($configuraciones as $config) {
                $this->line("Cuenta ID: {$config->cuenta_id} | Banco Config: {$config->banco}");
            }

            $this->info("\n✅ Sincronización completada exitosamente!");
            
        } catch (\Exception $e) {
            $this->error("❌ Error durante la sincronización: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
