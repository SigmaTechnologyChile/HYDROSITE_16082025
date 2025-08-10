<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "Estructura de tabla configuracion_cuentas_iniciales:\n";
    echo "=====================================\n";
    
    $columns = DB::select('DESCRIBE configuracion_cuentas_iniciales');
    
    foreach($columns as $column) {
        echo $column->Field . ' - ' . $column->Type . ' - Null: ' . $column->Null . ' - Default: ' . $column->Default . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
