<?php
// Test simple para verificar datos
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== DIAGNÓSTICO ===\n";

// 1. Verificar si hay locations
$locations = DB::table('locations')->select('id', 'name')->limit(5)->get();
echo "Locations encontradas: " . $locations->count() . "\n";
foreach($locations as $loc) {
    echo "- ID: {$loc->id}, Nombre: {$loc->name}\n";
}

// 2. Verificar si hay services
$totalServices = DB::table('services')->count();
echo "\nTotal services: {$totalServices}\n";

// 3. Verificar si hay services con locality_id
$servicesConLocalityId = DB::table('services')->whereNotNull('locality_id')->count();
echo "Services con locality_id: {$servicesConLocalityId}\n";

// 4. Ver ejemplos de locality_id en services
$ejemplos = DB::table('services')->select('id', 'locality_id')->whereNotNull('locality_id')->limit(5)->get();
echo "\nEjemplos de services con locality_id:\n";
foreach($ejemplos as $ej) {
    echo "- Service ID: {$ej->id}, locality_id: {$ej->locality_id}\n";
}

// 5. Verificar si algún location.id coincide con services.locality_id
if ($locations->count() > 0) {
    $locationId = $locations->first()->id;
    $serviciosEnEseLocation = DB::table('services')->where('locality_id', $locationId)->count();
    echo "\nServices en location_id {$locationId}: {$serviciosEnEseLocation}\n";
}
?>
