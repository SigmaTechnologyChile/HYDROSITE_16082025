<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "Modificando tabla configuracion_cuentas_iniciales...\n";
    echo "=============================================\n";
    
    // Hacer nullable los campos que pueden estar vacíos
    echo "1. Modificando campo numero_cuenta para permitir NULL...\n";
    DB::statement("ALTER TABLE configuracion_cuentas_iniciales MODIFY numero_cuenta VARCHAR(50) NULL");
    
    echo "2. Modificando campo responsable para permitir NULL...\n";
    DB::statement("ALTER TABLE configuracion_cuentas_iniciales MODIFY responsable VARCHAR(100) NULL");
    
    echo "✅ Modificaciones completadas exitosamente!\n";
    echo "Los campos numero_cuenta y responsable ahora permiten NULL.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
