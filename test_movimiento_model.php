<?php

// Script de prueba para verificar que el modelo Movimiento funciona
require_once 'vendor/autoload.php';

use App\Models\Movimiento;

// Cargar la configuración de Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "🔍 Probando modelo Movimiento...\n";
    
    // Contar movimientos
    $totalMovimientos = Movimiento::count();
    echo "✅ Total movimientos en BD: $totalMovimientos\n";
    
    // Obtener movimientos recientes
    $movimientosRecientes = Movimiento::orderBy('created_at', 'desc')->limit(3)->get();
    echo "✅ Movimientos recientes:\n";
    
    foreach($movimientosRecientes as $mov) {
        echo "   - ID: {$mov->id}, Fecha: {$mov->fecha}, Tipo: {$mov->tipo}, Monto: $" . number_format($mov->monto, 2) . "\n";
    }
    
    // Probar relaciones
    $movimiento = Movimiento::first();
    if($movimiento) {
        echo "✅ Probando relaciones:\n";
        echo "   - Organización ID: {$movimiento->org_id}\n";
        echo "   - Categoría: {$movimiento->categoria}\n";
    }
    
    echo "\n🎉 ¡Modelo Movimiento funcionando correctamente!\n";
    
} catch(Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Línea: " . $e->getLine() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
}
