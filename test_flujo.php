<?php
require 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== PRUEBA DEL FLUJO ===\n\n";

// PASO 1: Verificar locations disponibles
echo "PASO 1: Verificando locations disponibles:\n";
$locations = DB::table('locations')->select('id', 'name')->limit(5)->get();
foreach($locations as $loc) {
    echo "- ID: {$loc->id} - Nombre: {$loc->name}\n";
}

if ($locations->count() > 0) {
    $locationId = $locations->first()->id;
    echo "\nUsando location_id: {$locationId}\n\n";
    
    // PASO 2: Buscar servicios en ese sector
    echo "PASO 2: Buscando servicios en locality_id = {$locationId}:\n";
    $servicios = DB::table('services')
        ->where('locality_id', $locationId)
        ->select('id', 'rut', 'sector', 'telefono')
        ->limit(3)
        ->get();
    
    echo "Servicios encontrados: " . $servicios->count() . "\n";
    foreach($servicios as $servicio) {
        echo "- Service ID: {$servicio->id}, RUT: {$servicio->rut}, Sector: {$servicio->sector}\n";
    }
    
    if ($servicios->count() > 0) {
        echo "\nPASO 3: Haciendo JOIN con members:\n";
        $serviciosCompletos = DB::table('services')
            ->join('members', 'services.rut', '=', 'members.rut')
            ->join('locations', 'services.locality_id', '=', 'locations.id')
            ->where('services.locality_id', $locationId)
            ->select(
                'locations.name as sector_name',
                'services.id as service_id',
                'services.rut',
                'members.first_name',
                'members.last_name'
            )
            ->limit(3)
            ->get();
        
        echo "Servicios con datos completos: " . $serviciosCompletos->count() . "\n";
        foreach($serviciosCompletos as $sc) {
            echo "- Service: {$sc->service_id}, Member: {$sc->first_name} {$sc->last_name}, RUT: {$sc->rut}\n";
        }
    } else {
        echo "No hay servicios en este sector.\n";
    }
} else {
    echo "No hay locations disponibles.\n";
}

echo "\n=== FIN PRUEBA ===\n";
