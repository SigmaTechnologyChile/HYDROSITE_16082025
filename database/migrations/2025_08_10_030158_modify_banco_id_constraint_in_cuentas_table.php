<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cuentas', function (Blueprint $table) {
            // Eliminar la restricción de clave foránea
            $table->dropForeign('fk_cuentas_banco');
            
            // Cambiar la columna para permitir NULL
            $table->unsignedBigInteger('banco_id')->nullable()->change();
            
            // Recrear la restricción de clave foránea permitiendo NULL
            $table->foreign('banco_id', 'fk_cuentas_banco')->references('id')->on('bancos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cuentas', function (Blueprint $table) {
            // Eliminar la restricción de clave foránea
            $table->dropForeign('fk_cuentas_banco');
            
            // Cambiar la columna para NO permitir NULL
            $table->unsignedBigInteger('banco_id')->nullable(false)->change();
            
            // Recrear la restricción de clave foránea NO permitiendo NULL
            $table->foreign('banco_id', 'fk_cuentas_banco')->references('id')->on('bancos');
        });
    }
};
