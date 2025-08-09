<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Route;

echo "=== RUTAS REGISTRADAS ===\n";

$routes = Route::getRoutes();
foreach ($routes as $route) {
    $uri = $route->uri();
    if (strpos($uri, 'clientes-por-sector') !== false) {
        echo "URI: " . $uri . "\n";
        echo "Methods: " . implode(', ', $route->methods()) . "\n";
        echo "Name: " . $route->getName() . "\n";
        echo "Action: " . $route->getActionName() . "\n";
        echo "---\n";
    }
}

echo "\n=== PROBANDO ACCESO DIRECTO ===\n";

// Intentar hacer una petición directa
try {
    $response = app()->handle(
        \Illuminate\Http\Request::create('/org/1/ajax/clientes-por-sector/1', 'GET')
    );
    
    echo "Status: " . $response->getStatusCode() . "\n";
    echo "Content: " . substr($response->getContent(), 0, 200) . "...\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
