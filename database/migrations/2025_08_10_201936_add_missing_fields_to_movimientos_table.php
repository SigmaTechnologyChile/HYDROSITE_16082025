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
            // Agregar campos que faltan para giros/depósitos
            if (!Schema::hasColumn('movimientos', 'subtipo')) {
                $table->enum('subtipo', ['giro', 'deposito', 'transferencia_interna', 'transferencia_externa', 'ajuste', 'correccion'])->nullable()->after('tipo');
            }
            
            // Agregar campos adicionales para información de proveedores/beneficiarios
            if (!Schema::hasColumn('movimientos', 'proveedor')) {
                $table->string('proveedor', 200)->nullable()->after('nro_dcto');
            }
            
            if (!Schema::hasColumn('movimientos', 'rut_proveedor')) {
                $table->string('rut_proveedor', 20)->nullable()->after('proveedor');
            }
            
            // Agregar campos adicionales que están en el código pero no en la tabla
            if (!Schema::hasColumn('movimientos', 'otros_ingresos')) {
                $table->decimal('otros_ingresos', 15, 2)->nullable()->default(0.00)->after('cuotas_incorporacion');
            }
            
            if (!Schema::hasColumn('movimientos', 'sueldos_leyes')) {
                $table->decimal('sueldos_leyes', 15, 2)->nullable()->default(0.00)->after('energia_electrica');
            }
            
            if (!Schema::hasColumn('movimientos', 'otros_gastos_operacion')) {
                $table->decimal('otros_gastos_operacion', 15, 2)->nullable()->default(0.00)->after('sueldos_leyes');
            }
            
            if (!Schema::hasColumn('movimientos', 'gastos_mantencion')) {
                $table->decimal('gastos_mantencion', 15, 2)->nullable()->default(0.00)->after('otros_gastos_operacion');
            }
            
            if (!Schema::hasColumn('movimientos', 'gastos_administracion')) {
                $table->decimal('gastos_administracion', 15, 2)->nullable()->default(0.00)->after('gastos_mantencion');
            }
            
            if (!Schema::hasColumn('movimientos', 'gastos_mejoramiento')) {
                $table->decimal('gastos_mejoramiento', 15, 2)->nullable()->default(0.00)->after('gastos_administracion');
            }
            
            if (!Schema::hasColumn('movimientos', 'otros_egresos')) {
                $table->decimal('otros_egresos', 15, 2)->nullable()->default(0.00)->after('gastos_mejoramiento');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimientos', function (Blueprint $table) {
            // Revertir los cambios
            if (Schema::hasColumn('movimientos', 'subtipo')) {
                $table->dropColumn('subtipo');
            }
            if (Schema::hasColumn('movimientos', 'proveedor')) {
                $table->dropColumn('proveedor');
            }
            if (Schema::hasColumn('movimientos', 'rut_proveedor')) {
                $table->dropColumn('rut_proveedor');
            }
            if (Schema::hasColumn('movimientos', 'otros_ingresos')) {
                $table->dropColumn('otros_ingresos');
            }
            if (Schema::hasColumn('movimientos', 'sueldos_leyes')) {
                $table->dropColumn('sueldos_leyes');
            }
            if (Schema::hasColumn('movimientos', 'otros_gastos_operacion')) {
                $table->dropColumn('otros_gastos_operacion');
            }
            if (Schema::hasColumn('movimientos', 'gastos_mantencion')) {
                $table->dropColumn('gastos_mantencion');
            }
            if (Schema::hasColumn('movimientos', 'gastos_administracion')) {
                $table->dropColumn('gastos_administracion');
            }
            if (Schema::hasColumn('movimientos', 'gastos_mejoramiento')) {
                $table->dropColumn('gastos_mejoramiento');
            }
            if (Schema::hasColumn('movimientos', 'otros_egresos')) {
                $table->dropColumn('otros_egresos');
            }
        });
    }
};
