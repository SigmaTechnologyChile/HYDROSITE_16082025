<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== DIAGNÓSTICO DE DATOS ===\n\n";

// 1. Verificar locations disponibles
echo "1. LOCATIONS DISPONIBLES:\n";
$locations = DB::table('locations')->select('id', 'name', 'org_id')->limit(10)->get();
foreach($locations as $loc) {
    echo "- ID: {$loc->id}, Nombre: {$loc->name}, Org: {$loc->org_id}\n";
}

// 2. Verificar services totales
$totalServices = DB::table('services')->count();
echo "\n2. TOTAL SERVICES: {$totalServices}\n";

// 3. Verificar services con locality_id
echo "\n3. SERVICES CON LOCALITY_ID:\n";
$servicesConLocalityId = DB::table('services')
    ->select('id', 'locality_id', 'rut')
    ->whereNotNull('locality_id')
    ->limit(10)
    ->get();

foreach($servicesConLocalityId as $service) {
    echo "- Service ID: {$service->id}, locality_id: {$service->locality_id}, RUT: {$service->rut}\n";
}

// 4. Verificar si hay algún service con locality_id = 1
$servicesEnLocation1 = DB::table('services')->where('locality_id', 1)->count();
echo "\n4. SERVICES EN LOCALITY_ID = 1: {$servicesEnLocation1}\n";

// 5. Ver qué locality_ids únicos existen en services
echo "\n5. LOCALITY_IDS ÚNICOS EN SERVICES:\n";
$localityIds = DB::table('services')
    ->select('locality_id')
    ->whereNotNull('locality_id')
    ->groupBy('locality_id')
    ->get();

foreach($localityIds as $lid) {
    $count = DB::table('services')->where('locality_id', $lid->locality_id)->count();
    echo "- locality_id: {$lid->locality_id} ({$count} services)\n";
}

// 6. Verificar con el org_id correcto (864)
echo "\n6. LOCATIONS DEL ORG 864:\n";
$locationsOrg864 = DB::table('locations')->where('org_id', 864)->select('id', 'name')->get();
foreach($locationsOrg864 as $loc) {
    $servicesCount = DB::table('services')->where('locality_id', $loc->id)->count();
    echo "- Location ID: {$loc->id}, Nombre: {$loc->name}, Services: {$servicesCount}\n";
}

echo "\n=== FIN DIAGNÓSTICO ===\n";
?>
