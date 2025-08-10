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
        Schema::table('movimientos', function (Blueprint $table) {
            // Columnas de Ingresos
            $table->decimal('total_consumo', 15, 2)->default(0)->after('transferencia_id');
            $table->decimal('cuotas_incorporacion', 15, 2)->default(0)->after('total_consumo');
            $table->decimal('otros_ingresos', 15, 2)->default(0)->after('cuotas_incorporacion');
            $table->decimal('giros', 15, 2)->default(0)->after('otros_ingresos');
            
            // Columnas de Egresos
            $table->decimal('energia_electrica', 15, 2)->default(0)->after('giros');
            $table->decimal('sueldos_leyes', 15, 2)->default(0)->after('energia_electrica');
            $table->decimal('otros_gastos_operacion', 15, 2)->default(0)->after('sueldos_leyes');
            $table->decimal('gastos_mantencion', 15, 2)->default(0)->after('otros_gastos_operacion');
            $table->decimal('gastos_administracion', 15, 2)->default(0)->after('gastos_mantencion');
            $table->decimal('gastos_mejoramiento', 15, 2)->default(0)->after('gastos_administracion');
            $table->decimal('otros_egresos', 15, 2)->default(0)->after('gastos_mejoramiento');
            $table->decimal('depositos', 15, 2)->default(0)->after('otros_egresos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimientos', function (Blueprint $table) {
            $table->dropColumn([
                'total_consumo',
                'cuotas_incorporacion', 
                'otros_ingresos',
                'giros',
                'energia_electrica',
                'sueldos_leyes',
                'otros_gastos_operacion',
                'gastos_mantencion',
                'gastos_administracion',
                'gastos_mejoramiento',
                'otros_egresos',
                'depositos'
            ]);
        });
    }
};
