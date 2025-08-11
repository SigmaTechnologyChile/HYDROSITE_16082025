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
        Schema::table('services', function (Blueprint $table) {
            $table->unsignedBigInteger('location_id')->nullable();
            $table->string('nombre')->nullable();
            $table->string('telefono')->nullable();
            $table->integer('order_by')->nullable();
            $table->string('numero')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['location_id', 'nombre', 'telefono', 'order_by', 'numero']);
        });
    }
};
