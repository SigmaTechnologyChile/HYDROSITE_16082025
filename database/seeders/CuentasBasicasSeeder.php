<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CuentasBasicasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Este seeder NO crea cuentas automáticamente para organizaciones.
     * Solo define los tipos de cuenta que estarán disponibles en el sistema.
     * Las cuentas se crearán cuando cada organización configure sus cuentas iniciales.
     */
    public function run(): void
    {
        // Este seeder puede estar vacío por ahora
        // Las cuentas se crearán dinámicamente cuando cada organización 
        // use el formulario de configuración inicial
        
        echo "✅ CuentasBasicasSeeder ejecutado. Las cuentas se crearán dinámicamente.\n";
    }
}
