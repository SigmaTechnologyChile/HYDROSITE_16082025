<?php
// BACKUP - Controlador deshabilitado debido a eliminación de tabla movimientos
// Este archivo contiene el código original antes del mantenimiento del sistema

namespace App\Http\Controllers;

// use App\Models\Movimiento; // COMENTADO: tabla eliminada
use App\Models\Cuenta;
use App\Models\Categoria;
use Illuminate\Http\Request;

class MovimientoController extends Controller
{
    /**
     * NOTA: Este controlador está temporalmente deshabilitado 
     * debido al mantenimiento del sistema de movimientos.
     */
    
    private function maintenanceResponse()
    {
        return redirect()->back()->with('error', 'La funcionalidad de movimientos no está disponible temporalmente debido a mantenimiento del sistema.');
    }

    public function show($id)
    {
        return $this->maintenanceResponse();
    }

    public function edit($id)
    {
        return $this->maintenanceResponse();
    }

    public function index()
    {
        return $this->maintenanceResponse();
    }

    public function store(Request $request)
    {
        return $this->maintenanceResponse();
    }

    public function update(Request $request, $id)
    {
        return $this->maintenanceResponse();
    }

    public function destroy($id)
    {
        return $this->maintenanceResponse();
    }
}
