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
        // ==========================================
        // RECUPERACIÓN DE TABLAS CRÍTICAS DEL SISTEMA
        // Tablas perdidas: 46 de 67 originales
        // ==========================================

        // 1. TABLA ORGS - Organizaciones principales
        Schema::create('orgs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('rut', 20)->unique();
            $table->string('razon_social', 255);
            $table->string('logo', 255)->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('state', 100)->nullable();
            $table->string('commune', 100)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. TABLA STATES - Estados/Regiones
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 10)->nullable();
            $table->timestamps();
        });

        // 3. TABLA CITIES - Ciudades
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->unsignedBigInteger('state_id');
            $table->unsignedBigInteger('province_id')->nullable();
            $table->timestamps();
        });

        // 4. TABLA PROVINCES - Provincias
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->unsignedBigInteger('state_id');
            $table->timestamps();
        });

        // 5. TABLA LOCATIONS - Localidades/Sectores
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('org_id')->nullable();
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });

        // 6. TABLA MEMBERS - Miembros/Clientes
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('rut', 20)->unique();
            $table->string('full_name', 255);
            $table->string('email', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('address', 255)->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamps();
        });

        // 7. TABLA ORGS_MEMBERS - Relación Organizaciones-Miembros
        Schema::create('orgs_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id');
            $table->unsignedBigInteger('member_id');
            $table->enum('role', ['admin', 'operator', 'viewer'])->default('viewer');
            $table->date('joined_at')->nullable();
            $table->timestamps();
            $table->unique(['org_id', 'member_id']);
        });

        // 8. TABLA BANCOS - Catálogo de Bancos
        Schema::create('bancos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('codigo', 10)->nullable();
            $table->string('swift', 20)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // 9. TABLA CONFIGURACION_CUENTAS_INICIALES - Configuración de Cuentas Iniciales
        Schema::create('configuracion_cuentas_iniciales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id');
            $table->unsignedBigInteger('cuenta_id');
            $table->decimal('saldo_inicial', 15, 2);
            $table->string('responsable', 100);
            $table->string('banco', 100)->nullable();
            $table->string('numero_cuenta', 50)->nullable();
            $table->string('tipo_cuenta', 50)->nullable();
            $table->string('observaciones', 255)->nullable();
            $table->string('nombre_banco', 100)->nullable();
            $table->timestamps();
            $table->unique(['org_id', 'cuenta_id']);
        });

        // 10. TABLA READINGS - Lecturas de Medidores
        Schema::create('readings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('org_id');
            $table->integer('previous_reading')->default(0);
            $table->integer('current_reading');
            $table->integer('consumption')->default(0);
            $table->decimal('amount', 10, 2)->default(0);
            $table->date('reading_date');
            $table->date('billing_period_start');
            $table->date('billing_period_end');
            $table->enum('status', ['pending', 'billed', 'paid'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('readings');
        Schema::dropIfExists('configuracion_cuentas_iniciales');
        Schema::dropIfExists('bancos');
        Schema::dropIfExists('orgs_members');
        Schema::dropIfExists('members');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('provinces');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('states');
        Schema::dropIfExists('orgs');
    }
};
