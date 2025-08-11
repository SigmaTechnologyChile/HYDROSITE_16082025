<?php

require_once 'vendor/autoload.php';

// Configurar Laravel
$app = require_once 'bootstrap/app.php';
$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);
$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);
$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Crear request falso
$request = Illuminate\Http\Request::create('/test', 'GET');
$response = $kernel->handle($request);

// Test de categorías
echo "=== PRUEBA DE CATEGORÍAS ===\n\n";

try {
    // Test de categorías de ingreso
    echo "📈 CATEGORÍAS DE INGRESO:\n";
    $categoriasIngresos = \App\Models\Categoria::where('tipo', 'ingreso')->orderBy('nombre')->get();
    echo "Total: " . $categoriasIngresos->count() . " categorías\n";
    foreach ($categoriasIngresos as $categoria) {
        echo "- ID: {$categoria->id} | Nombre: {$categoria->nombre} | Grupo: {$categoria->grupo}\n";
    }
    
    echo "\n📉 CATEGORÍAS DE EGRESO:\n";
    $categoriasEgresos = \App\Models\Categoria::where('tipo', 'egreso')->orderBy('nombre')->get();
    echo "Total: " . $categoriasEgresos->count() . " categorías\n";
    foreach ($categoriasEgresos as $categoria) {
        echo "- ID: {$categoria->id} | Nombre: {$categoria->nombre} | Grupo: {$categoria->grupo}\n";
    }
    
    echo "\n✅ TEST COMPLETADO EXITOSAMENTE\n";
    echo "Las categorías están disponibles para el controlador ContableController\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

$kernel->terminate($request, $response);
