<?php

// Test script para verificar la consulta de miembros por sector
require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

$app = require_once 'bootstrap/app.php';

use App\Models\Service;
use App\Models\Member;
use App\Models\Location;

// Parámetros de prueba - ajusta estos valores según tu base de datos
$orgId = 864;
$sectorId = 5;

echo "Probando consulta para orgId: {$orgId}, sectorId: {$sectorId}\n\n";

try {
    // Verificar si hay servicios con esos parámetros
    $serviciosCount = Service::where('org_id', $orgId)
        ->where('locality_id', $sectorId)
        ->count();
    
    echo "Total de servicios en org {$orgId} y sector {$sectorId}: {$serviciosCount}\n\n";
    
    // Ejecutar la consulta completa
    $servicios = Service::join('members', 'services.member_id', '=', 'members.id')
        ->join('locations', 'services.locality_id', '=', 'locations.id')
        ->where('services.locality_id', $sectorId)
        ->where('services.org_id', $orgId)
        ->select(
            'services.id as service_id',
            'services.nro as numero_servicio',
            'locations.name as sector',
            'members.rut',
            'members.first_name',
            'members.last_name'
        )
        ->orderBy('members.first_name')
        ->orderBy('members.last_name')
        ->get();

    echo "Servicios encontrados: " . $servicios->count() . "\n\n";

    foreach ($servicios as $servicio) {
        echo "ID: {$servicio->service_id}, ";
        echo "Servicio #: {$servicio->numero_servicio}, ";
        echo "Sector: {$servicio->sector}, ";
        echo "RUT: {$servicio->rut}, ";
        echo "Nombre: {$servicio->first_name} {$servicio->last_name}\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
