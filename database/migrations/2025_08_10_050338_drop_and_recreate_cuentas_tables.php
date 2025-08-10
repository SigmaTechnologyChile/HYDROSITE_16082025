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
        // Deshabilitar verificaciones de foreign key temporalmente
        Schema::disableForeignKeyConstraints();
        
        // 1. ELIMINAR TABLAS EXISTENTES (en orden correcto para evitar problemas de FK)
        Schema::dropIfExists('configuracion_cuentas_iniciales');
        Schema::dropIfExists('cuentas');
        
        // 2. RECREAR TABLA CUENTAS (tabla operativa del sistema)
        Schema::create('cuentas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id');
            $table->string('nombre', 100);
            $table->enum('tipo', ['caja_general', 'cuenta_corriente_1', 'cuenta_ahorro', 'cuenta_corriente_2', 'cuenta_corriente_3']);
            $table->decimal('saldo_actual', 15, 2)->default(0.00);
            $table->unsignedInteger('banco_id')->nullable(); // Sin FK por ahora
            $table->string('numero_cuenta', 50)->nullable();
            $table->string('responsable', 100)->nullable();
            $table->timestamps();
            
            // Solo constraint único por ahora
            $table->unique(['org_id', 'tipo']); // Una cuenta de cada tipo por organización
            
            // Índices para mejor performance
            $table->index(['org_id', 'tipo']);
            $table->index('banco_id');
        });
        
        // 3. RECREAR TABLA CONFIGURACION_CUENTAS_INICIALES (solo para setup inicial)
        Schema::create('configuracion_cuentas_iniciales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id');
            $table->enum('tipo_cuenta', ['caja_general', 'cuenta_corriente_1', 'cuenta_ahorro', 'cuenta_corriente_2', 'cuenta_corriente_3']);
            $table->decimal('saldo_inicial', 15, 2);
            $table->string('responsable', 100);
            $table->unsignedInteger('banco_id')->nullable(); // Sin FK por ahora
            $table->string('numero_cuenta', 50)->nullable();
            $table->text('observaciones')->nullable();
            $table->boolean('copiado_a_cuentas')->default(false); // Flag para saber si ya se copió
            $table->timestamps();
            
            // Solo constraint único por ahora
            $table->unique(['org_id', 'tipo_cuenta']); // Una configuración inicial por tipo por org
            
            // Índices
            $table->index(['org_id', 'tipo_cuenta']);
            $table->index('banco_id');
            $table->index('copiado_a_cuentas');
        });
        
        // Rehabilitar verificaciones de foreign key
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar en orden inverso
        Schema::dropIfExists('configuracion_cuentas_iniciales');
        Schema::dropIfExists('cuentas');
    }
};
