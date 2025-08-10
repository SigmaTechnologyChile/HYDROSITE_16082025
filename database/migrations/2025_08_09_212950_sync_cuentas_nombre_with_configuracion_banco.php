<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sincronizar la columna nombre de cuentas con la columna banco de configuracion_cuentas_iniciales
        DB::statement("
            UPDATE cuentas 
            SET nombre = (
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
        
        // También sincronizar el campo banco en la tabla cuentas si existe
        DB::statement("
            UPDATE cuentas 
            SET banco = nombre
            WHERE banco IS NULL OR banco != nombre
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No revertir automáticamente ya que podría causar pérdida de datos
        // Si necesitas revertir, hazlo manualmente
    }
};
