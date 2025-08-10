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
            $table->string('nombre_banco', 100)->nullable()->after('banco_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('configuracion_cuentas_iniciales', function (Blueprint $table) {
            $table->dropColumn('nombre_banco');
        });
    }
};
