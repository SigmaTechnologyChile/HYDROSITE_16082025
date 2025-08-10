<?php

namespace App\Http\Controllers;

use App\Services\ForeignKeyConstraintService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador para manejar eliminaciones con Foreign Key Constraints
 * Solución para Issue #1451
 */
class FKConstraintController extends Controller
{
    private $fkService;

    public function __construct()
    {
        $this->fkService = new ForeignKeyConstraintService();
    }

    /**
     * Verificar dependencias antes de eliminar
     */
    public function checkDependencies(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'table' => 'required|string',
            'id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $report = $this->fkService->getDependencyReport(
                $request->table,
                $request->id
            );

            return response()->json([
                'success' => true,
                'data' => $report
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar dependencias',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar registro con manejo de FK constraints
     */
    public function safeDelete(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'table' => 'required|string',
            'id' => 'required|integer',
            'method' => 'sometimes|string|in:cascade,check_only,force'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $method = $request->input('method', 'cascade');

        try {
            $result = $this->fkService->safeDelete(
                $request->table,
                $request->id,
                $method
            );

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar registro',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generar script SQL para eliminación manual
     */
    public function generateScript(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'table' => 'required|string',
            'id' => 'required|integer',
            'method' => 'sometimes|string|in:cascade,force'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $script = $this->fkService->generateDeleteScript(
                $request->table,
                $request->id,
                $request->input('method', 'cascade')
            );

            return response()->json([
                'success' => true,
                'script' => $script
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar script',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Vista para gestionar FK constraints
     */
    public function index()
    {
        return view('admin.fk-constraints', [
            'title' => 'Gestión de Foreign Key Constraints'
        ]);
    }

    /**
     * Endpoint para uso desde JavaScript
     */
    public function handleDelete(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'table' => 'required|string',
            'id' => 'required|integer',
            'confirm' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Primero verificar dependencias
            $report = $this->fkService->getDependencyReport(
                $request->table,
                $request->id
            );

            // Si no hay confirmación y hay dependencias, pedir confirmación
            if (!$request->input('confirm', false) && !$report['can_delete_safely']) {
                return response()->json([
                    'success' => false,
                    'requires_confirmation' => true,
                    'message' => 'Este registro tiene dependencias. ¿Eliminar en cascada?',
                    'dependency_report' => $report
                ]);
            }

            // Proceder con eliminación
            $method = $report['can_delete_safely'] ? 'check_only' : 'cascade';
            $result = $this->fkService->safeDelete(
                $request->table,
                $request->id,
                $method
            );

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar eliminación',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
