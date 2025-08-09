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
        Schema::create('rutas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('service_id');
            $table->integer('orden')->default(1);
            $table->timestamps();

            // Índices para mejorar el rendimiento
            $table->index(['org_id', 'location_id']);
            $table->index(['location_id', 'orden']);
            $table->unique(['org_id', 'location_id', 'service_id'], 'rutas_unique_service');

            // Claves foráneas (opcional, dependiendo de tu esquema)
            // $table->foreign('org_id')->references('id')->on('orgs')->onDelete('cascade');
            // $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            // $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rutas');
    }
};
