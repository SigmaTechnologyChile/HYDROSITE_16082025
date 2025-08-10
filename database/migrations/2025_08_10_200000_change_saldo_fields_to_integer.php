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
        Schema::table('configuracion_cuentas_iniciales', function (Blueprint $table) {
            // Cambiar saldo_inicial de decimal(15,2) a bigInteger (entero)
            $table->bigInteger('saldo_inicial')->change();
        });

        Schema::table('cuentas', function (Blueprint $table) {
            // Cambiar saldo_actual de decimal(15,2) a bigInteger (entero)
            $table->bigInteger('saldo_actual')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('configuracion_cuentas_iniciales', function (Blueprint $table) {
            // Revertir a decimal(15,2)
            $table->decimal('saldo_inicial', 15, 2)->change();
        });

        Schema::table('cuentas', function (Blueprint $table) {
            // Revertir a decimal(15,2)
            $table->decimal('saldo_actual', 15, 2)->default(0.00)->change();
        });
    }
};
