<?php



namespace App\Http\Controllers\Org;



use App\Http\Controllers\Controller;

use App\Models\Location;

use App\Models\Member;

use App\Models\Service;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;



class OperatorController extends Controller

{

    public function index($orgId)

    {

        // Consulta corregida (usa el nombre correcto de la columna)
//
        $locations = Location::where('org_id', $orgId) // Ajusta este nombre

                            ->orderBy('name')

                            ->get();



        return view('orgs.operator.index', [

      'orgId' => $orgId,

            'locations' => $locations

        ]);

    }

    public function getMembersBySector($orgId, $sectorId)
    {
        try {
            Log::info("=== INICIO getMembersBySector ===");
            Log::info("Buscando servicios para orgId: {$orgId}, sectorId: {$sectorId}");
            Log::info("Request URL: " . request()->url());
            Log::info("Request method: " . request()->method());
            
            // Verificar que los parámetros sean numéricos
            if (!is_numeric($orgId) || !is_numeric($sectorId)) {
                Log::error("Parámetros no numéricos: orgId={$orgId}, sectorId={$sectorId}");
                return response()->json(['error' => 'Parámetros inválidos'], 400);
            }
            
            // Verificar que la organización existe
            $orgExists = DB::table('orgs')->where('id', $orgId)->exists();
            Log::info("Organización existe: " . ($orgExists ? 'SI' : 'NO'));
            
            // Verificar que el sector existe
            $locationExists = Location::where('id', $sectorId)->exists();
            Log::info("Sector existe: " . ($locationExists ? 'SI' : 'NO'));
            
            // Obtener servicios con información del miembro y sector
            Log::info("Ejecutando consulta SQL para locality_id: {$sectorId} y org_id: {$orgId}");
            
            $servicios = Service::join('members', 'services.member_id', '=', 'members.id')
                ->join('locations', 'services.locality_id', '=', 'locations.id')
                ->where('services.locality_id', $sectorId)
                ->where('services.org_id', $orgId)
                ->select(
                    'services.id as service_id',
                    'services.nro as numero_servicio',
                    'services.locality_id',
                    'locations.name as sector',
                    'members.rut',
                    'members.first_name',
                    'members.last_name'
                )
                ->orderBy('services.nro')
                ->orderBy('members.first_name')
                ->orderBy('members.last_name')
                ->get();
            
            Log::info("SQL Query ejecutada: " . Service::join('members', 'services.member_id', '=', 'members.id')
                ->join('locations', 'services.locality_id', '=', 'locations.id')
                ->where('services.locality_id', $sectorId)
                ->where('services.org_id', $orgId)
                ->toSql());
            Log::info("Parámetros de consulta: [locality_id: {$sectorId}, org_id: {$orgId}]");

            Log::info("Query SQL ejecutada exitosamente");
            Log::info("Servicios encontrados: " . $servicios->count());
            
            if ($servicios->count() > 0) {
                Log::info("Primer servicio encontrado: " . json_encode($servicios->first()));
                Log::info("Lista completa de servicios:");
                foreach ($servicios as $index => $servicio) {
                    Log::info("Servicio {$index}: ID={$servicio->service_id}, Número={$servicio->numero_servicio}, Miembro={$servicio->first_name} {$servicio->last_name}, RUT={$servicio->rut}");
                }
            } else {
                Log::warning("No se encontraron servicios para locality_id: {$sectorId} y org_id: {$orgId}");
                
                // Debug adicional: verificar si existen servicios sin filtros
                $totalServices = Service::where('org_id', $orgId)->count();
                Log::info("Total de servicios en la organización: {$totalServices}");
                
                $servicesInSector = Service::where('locality_id', $sectorId)->count();
                Log::info("Total de servicios con locality_id {$sectorId}: {$servicesInSector}");
            }

            $result = $servicios->map(function($servicio) {
                return [
                    'numero' => $servicio->numero_servicio,
                    'rut' => $servicio->rut,
                    'full_name' => trim($servicio->first_name . ' ' . $servicio->last_name),
                    'service_id' => $servicio->service_id,
                    'locality_id' => $servicio->locality_id,
                    'sector' => $servicio->sector,
                    'first_name' => $servicio->first_name,
                    'last_name' => $servicio->last_name
                ];
            });

            Log::info("=== RESULTADO FINAL ===");
            Log::info("Cantidad de servicios para retornar: " . $result->count());
            foreach ($result as $index => $servicio) {
                Log::info("Resultado {$index}: {$servicio['display_text']}");
            }
            Log::info("=== FIN getMembersBySector ===");

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error("=== ERROR en getMembersBySector ===");
            Log::error("Mensaje: " . $e->getMessage());
            Log::error("Archivo: " . $e->getFile() . ':' . $e->getLine());
            Log::error("Stack trace: " . $e->getTraceAsString());
            Log::error("=== FIN ERROR ===");
            return response()->json(['error' => 'Error al cargar miembros: ' . $e->getMessage()], 500);
        }
    }

    public function getServicesBySector($sectorId)
    {
        try {
            Log::info("=== INICIO getServicesBySector ===");
            Log::info("Buscando servicios para sectorId: {$sectorId}");
            
            // Verificar que el parámetro sea numérico
            if (!is_numeric($sectorId)) {
                Log::error("Parámetro no numérico: sectorId={$sectorId}");
                return response()->json(['error' => 'Parámetro inválido'], 400);
            }
            
            // Obtener servicios con información del miembro y sector
            $servicios = Service::join('members', 'services.member_id', '=', 'members.id')
                ->join('locations', 'services.locality_id', '=', 'locations.id')
                ->where('services.locality_id', $sectorId)
                ->select(
                    'services.id as service_id',
                    'services.nro as numero',
                    'members.rut',
                    'members.first_name',
                    'members.last_name'
                )
                ->orderBy('services.nro')
                ->orderBy('members.first_name')
                ->orderBy('members.last_name')
                ->get();

            Log::info("Servicios encontrados: " . $servicios->count());

            $result = $servicios->map(function($servicio) {
                return [
                    'numero' => $servicio->numero,
                    'rut' => $servicio->rut,
                    'full_name' => trim($servicio->first_name . ' ' . $servicio->last_name)
                ];
            });

            Log::info("=== FIN getServicesBySector ===");
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error("=== ERROR en getServicesBySector ===");
            Log::error("Mensaje: " . $e->getMessage());
            Log::error("=== FIN ERROR ===");
            return response()->json(['error' => 'Error al cargar servicios: ' . $e->getMessage()], 500);
        }
    }

}
