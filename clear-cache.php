<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "Limpiando cachés...\n";

// Limpiar cache de rutas
Artisan::call('route:clear');
echo "Route cache cleared\n";

// Limpiar cache de configuración
Artisan::call('config:clear');
echo "Config cache cleared\n";

// Limpiar cache de vistas
Artisan::call('view:clear');
echo "View cache cleared\n";

echo "Listo!\n";
?>
