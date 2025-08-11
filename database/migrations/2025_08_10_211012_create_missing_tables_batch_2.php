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
        // RECUPERACIÓN DE TABLAS ADICIONALES DEL SISTEMA
        // Segundo lote: Tablas de inventarios, planes y configuraciones
        // ==========================================

        // 1. TABLA INVENTARIES - Inventarios
        Schema::create('inventaries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('code', 50)->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('price', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('org_id');
            $table->timestamps();
        });

        // 2. TABLA INVENTORIES_CATEGORIES - Categorías de Inventarios
        Schema::create('inventories_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('description', 255)->nullable();
            $table->unsignedBigInteger('org_id');
            $table->timestamps();
        });

        // 3. TABLA ORDERS - Órdenes/Pedidos
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('dni', 20);
            $table->decimal('amount', 10, 2);
            $table->date('order_date');
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->unsignedBigInteger('org_id');
            $table->timestamps();
        });

        // 4. TABLA ORG_PLANES - Planes de Organizaciones
        Schema::create('org_planes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id');
            $table->unsignedBigInteger('plan_id');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // 5. TABLA PLANES - Catálogo de Planes
        Schema::create('planes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('max_users')->nullable();
            $table->integer('max_locations')->nullable();
            $table->json('features')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // 6. TABLA PLANES_VISTAS - Vistas de Planes
        Schema::create('planes_vistas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('vista_id');
            $table->boolean('access')->default(true);
            $table->timestamps();
        });

        // 7. TABLA MODULOS - Módulos del Sistema
        Schema::create('modulos', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->text('description')->nullable();
            $table->string('icon', 50)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // 8. TABLA VISTAS - Vistas del Sistema
        Schema::create('vistas', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('route', 100);
            $table->unsignedBigInteger('modulo_id');
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // 9. TABLA NOTIFICATIONS - Notificaciones
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type', 100);
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        // 10. TABLA FIXED_COSTS_CONFIG - Configuración de Costos Fijos
        Schema::create('fixed_costs_config', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id');
            $table->decimal('base_cost', 10, 2)->default(0);
            $table->decimal('maintenance_cost', 10, 2)->default(0);
            $table->decimal('admin_cost', 10, 2)->default(0);
            $table->json('additional_costs')->nullable();
            $table->timestamps();
        });

        // 11. TABLA TIER_CONFIG - Configuración de Tarifas por Tramos
        Schema::create('tier_config', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id');
            $table->integer('tier_min');
            $table->integer('tier_max');
            $table->decimal('price_per_unit', 10, 2);
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tier_config');
        Schema::dropIfExists('fixed_costs_config');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('vistas');
        Schema::dropIfExists('modulos');
        Schema::dropIfExists('planes_vistas');
        Schema::dropIfExists('planes');
        Schema::dropIfExists('org_planes');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('inventories_categories');
        Schema::dropIfExists('inventaries');
    }
};
