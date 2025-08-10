<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔄 Iniciando sincronización de cuentas...\n\n";

try {
    // Mostrar estado antes
    echo "📊 Estado ANTES de la sincronización:\n";
    echo "=====================================\n";
    
    $cuentas = DB::table('cuentas')->get(['id', 'nombre', 'banco']);
    echo "Tabla CUENTAS:\n";
    foreach($cuentas as $cuenta) {
        echo "  ID: {$cuenta->id} | Nombre: '{$cuenta->nombre}' | Banco: '{$cuenta->banco}'\n";
    }
    
    $configs = DB::table('configuracion_cuentas_iniciales')->get(['cuenta_id', 'banco']);
    echo "\nTabla CONFIGURACION_CUENTAS_INICIALES:\n";
    foreach($configs as $config) {
        echo "  Cuenta ID: {$config->cuenta_id} | Banco Config: '{$config->banco}'\n";
    }
    
    echo "\n🔧 Ejecutando sincronización...\n";
    
    // Ejecutar la sincronización
    $updated = DB::statement("
        UPDATE cuentas 
        SET nombre = (
            SELECT banco 
            FROM configuracion_cuentas_iniciales 
            WHERE configuracion_cuentas_iniciales.cuenta_id = cuentas.id 
            AND configuracion_cuentas_iniciales.banco IS NOT NULL
            LIMIT 1
        ),
        banco = (
            SELECT banco 
            FROM configuracion_cuentas_iniciales 
            WHERE configuracion_cuentas_iniciales.cuenta_id = cuentas.id 
            AND configuracion_cuentas_iniciales.banco IS NOT NULL
            LIMIT 1
        )
        WHERE EXISTS (
            SELECT 1 
            FROM configuracion_cuentas_iniciales 
            WHERE configuracion_cuentas_iniciales.cuenta_id = cuentas.id 
            AND configuracion_cuentas_iniciales.banco IS NOT NULL
        )
    ");
    
    echo "✅ Sincronización SQL ejecutada exitosamente\n\n";
    
    // Mostrar estado después
    echo "📊 Estado DESPUÉS de la sincronización:\n";
    echo "======================================\n";
    
    $cuentas = DB::table('cuentas')->get(['id', 'nombre', 'banco']);
    echo "Tabla CUENTAS:\n";
    foreach($cuentas as $cuenta) {
        echo "  ID: {$cuenta->id} | Nombre: '{$cuenta->nombre}' | Banco: '{$cuenta->banco}'\n";
    }
    
    echo "\n🎉 Sincronización completada exitosamente!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
