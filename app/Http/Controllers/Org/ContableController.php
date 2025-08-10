<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\Cuenta;
use App\Models\Categoria;
use App\Models\Movimiento;
use App\Models\ConfiguracionInicial;
use App\Models\ConfiguracionCuentasIniciales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// ...código existente...

class ContableController extends Controller
{
    /**
     * Exporta los movimientos a Excel para la organización.
     */
    public function exportarExcel($id)
    {
        // Obtener movimientos filtrados por organización
        $movimientos = \App\Models\Movimiento::whereHas('cuentaOrigen', function($q) use ($id) {
            $q->where('org_id', $id);
        })->orderBy('fecha', 'desc')->get();

        // Crear el contenido CSV manualmente (puedes cambiar a Laravel Excel si está instalado)
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="movimientos_org_' . $id . '.csv"',
        ];

        $callback = function() use ($movimientos) {
            $handle = fopen('php://output', 'w');
            // Encabezados
            fputcsv($handle, ['Fecha', 'Descripción', 'Total Consumo', 'Cuotas Incorporación', 'Otros Ingresos', 'Giros']);
            foreach ($movimientos as $m) {
                fputcsv($handle, [
                    $m->fecha,
                    $m->descripcion,
                    $m->total_consumo,
                    $m->cuotas_incorporacion,
                    $m->otros_ingresos,
                    $m->giros,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function store(Request $request)
    {
        return $this->storeNuevoFlujo($request);
    }

    /**
     * NUEVO FLUJO - Configuración inicial con copia automática a tabla operativa
     */
    public function storeNuevoFlujo(Request $request)
    {
        try {
            \Log::info('=== NUEVO FLUJO - STORE INICIADO ===');
            \Log::info('Timestamp: ' . now());
            \Log::info('Data recibida:', $request->all());
            \Log::info('Headers:', $request->headers->all());
            \Log::info('Method: ' . $request->method());
            \Log::info('URL: ' . $request->url());
            
            // DEBUG específico del orgId
            $orgIdFromRequest = $request->input('orgId');
            \Log::info('DEBUG orgId raw: ' . $orgIdFromRequest);
            \Log::info('DEBUG orgId type: ' . gettype($orgIdFromRequest));
            \Log::info('DEBUG orgId isEmpty: ' . (empty($orgIdFromRequest) ? 'YES' : 'NO'));
            \Log::info('DEBUG orgId isNull: ' . (is_null($orgIdFromRequest) ? 'YES' : 'NO'));
            
            // Debugging de todos los campos importantes
            \Log::info('🔍 Todos los inputs:', [
                'orgId' => $request->input('orgId'),
                'saldo_caja_general' => $request->input('saldo_caja_general'),
                'responsable' => $request->input('responsable'),
                '_token' => $request->input('_token') ? 'PRESENTE' : 'AUSENTE'
            ]);
            
            // Debug específico del orgId
            \Log::info('orgId recibido:', [
                'value' => $request->input('orgId'),
                'type' => gettype($request->input('orgId')),
                'is_null' => is_null($request->input('orgId')),
                'is_empty' => empty($request->input('orgId'))
            ]);
            
            // Obtener orgId ANTES de validación (como en método antiguo)
            $orgId = $request->input('orgId');
            \Log::info('DEBUG - orgId obtenido ANTES de validación: ' . $orgId);
            
            // Validación básica (SIN validar orgId aquí, como en método antiguo)
            $request->validate([
                'saldo_caja_general' => 'required|numeric|min:0',
                'saldo_cta_corriente_1' => 'required|numeric|min:0',
                'saldo_cuenta_ahorro' => 'required|numeric|min:0',
                'responsable' => 'required|string|max:100',
                'banco_cta_corriente_1' => 'nullable|integer|exists:bancos,id',
                'banco_cuenta_ahorro' => 'nullable|integer|exists:bancos,id',
                'numero_cta_corriente_1' => 'nullable|string|max:50',
                'numero_cuenta_ahorro' => 'nullable|string|max:50',
            ]);
            
            // Validar orgId manualmente después (más control)
            if (!$orgId || !is_numeric($orgId)) {
                \Log::error('ERROR - orgId inválido: ' . $orgId);
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ID de organización inválido',
                    'debug' => ['orgId_received' => $orgId, 'type' => gettype($orgId)]
                ], 400);
            }
            
            $orgId = intval($orgId); // Convertir a entero
            \Log::info('DEBUG - orgId convertido: ' . $orgId);
            
            if ($orgId <= 0) {
                \Log::error('ERROR: orgId inválido después de conversión: ' . $orgId);
                return response()->json([
                    'success' => false,
                    'message' => 'ID de organización inválido'
                ], 400);
            }
            
            \Log::info("🏢 Procesando para organización: {$orgId}");

            // Array para rastrear cuentas procesadas
            $cuentasProcesadas = [];

            // 1. CAJA GENERAL
            $this->procesarConfiguracionInicial([
                'org_id' => $orgId,
                'tipo_cuenta' => 'caja_general',
                'saldo_inicial' => $request->saldo_caja_general,
                'responsable' => $request->responsable,
                'banco_id' => null, // Caja general no tiene banco
                'numero_cuenta' => null,
                'observaciones' => 'Caja general - Efectivo'
            ], $cuentasProcesadas);

            // 2. CUENTA CORRIENTE 1
            $this->procesarConfiguracionInicial([
                'org_id' => $orgId,
                'tipo_cuenta' => 'cuenta_corriente_1',
                'saldo_inicial' => $request->saldo_cta_corriente_1,
                'responsable' => $request->responsable,
                'banco_id' => $request->banco_cta_corriente_1,
                'numero_cuenta' => $request->numero_cta_corriente_1,
                'observaciones' => 'Cuenta corriente principal'
            ], $cuentasProcesadas);

            // 3. CUENTA AHORRO
            $this->procesarConfiguracionInicial([
                'org_id' => $orgId,
                'tipo_cuenta' => 'cuenta_ahorro',
                'saldo_inicial' => $request->saldo_cuenta_ahorro,
                'responsable' => $request->responsable,
                'banco_id' => $request->banco_cuenta_ahorro,
                'numero_cuenta' => $request->numero_cuenta_ahorro,
                'observaciones' => 'Cuenta de ahorro principal'
            ], $cuentasProcesadas);

            \Log::info('✅ PROCESO COMPLETADO');
            \Log::info('📊 Resumen:', [
                'total_cuentas' => count($cuentasProcesadas),
                'cuentas' => $cuentasProcesadas,
                'timestamp' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Configuración de cuentas guardada exitosamente',
                'data' => [
                    'total_cuentas' => count($cuentasProcesadas),
                    'cuentas_procesadas' => $cuentasProcesadas
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('❌ Errores de validación:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Errores de validación',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('❌ Error en store:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Procesa la configuración inicial de una cuenta y la copia a la tabla operativa
     */
    private function procesarConfiguracionInicial($datos, &$cuentasProcesadas)
    {
        \Log::info("🔄 Procesando: {$datos['tipo_cuenta']}");

        // Obtener nombre del banco si existe
        $nombreBanco = null;
        if ($datos['banco_id']) {
            $banco = \App\Models\Banco::find($datos['banco_id']);
            $nombreBanco = $banco ? $banco->nombre : null;
        }

        // 1. Guardar/actualizar en configuracion_cuentas_iniciales
        $configuracion = ConfiguracionCuentasIniciales::updateOrCreate(
            [
                'org_id' => $datos['org_id'],
                'tipo_cuenta' => $datos['tipo_cuenta']
            ],
            [
                'saldo_inicial' => $datos['saldo_inicial'],
                'responsable' => $datos['responsable'],
                'banco_id' => $datos['banco_id'],
                'nombre_banco' => $nombreBanco, // ← NUEVO CAMPO
                'numero_cuenta' => $datos['numero_cuenta'],
                'observaciones' => $datos['observaciones'],
                'copiado_a_cuentas' => false // Resetear flag
            ]
        );

        \Log::info("💾 Configuración inicial guardada ID: {$configuracion->id}");

        // 2. Copiar automáticamente a tabla operativa (cuentas)
        $this->copiarACuentasOperativas($configuracion);

        // 3. Marcar como copiado
        $configuracion->update(['copiado_a_cuentas' => true]);

        // 4. Agregar al tracking
        $cuentasProcesadas[] = [
            'tipo' => $datos['tipo_cuenta'],
            'saldo' => $datos['saldo_inicial'],
            'banco_id' => $datos['banco_id'],
            'numero_cuenta' => $datos['numero_cuenta'],
            'configuracion_id' => $configuracion->id
        ];

        \Log::info("✅ {$datos['tipo_cuenta']} procesada completamente");
    }

    /**
     * Copia la configuración inicial a la tabla operativa (cuentas)
     */
    private function copiarACuentasOperativas($configuracion)
    {
        $nombreCuenta = $this->generarNombreCuenta($configuracion->tipo_cuenta);
        
        // Obtener nombre del banco si existe
        $nombreBanco = null;
        if ($configuracion->banco_id) {
            $banco = \App\Models\Banco::find($configuracion->banco_id);
            $nombreBanco = $banco ? $banco->nombre : null;
        }
        
        $cuenta = Cuenta::updateOrCreate(
            [
                'org_id' => $configuracion->org_id,
                'tipo' => $configuracion->tipo_cuenta
            ],
            [
                'nombre' => $nombreCuenta,
                'saldo_actual' => $configuracion->saldo_inicial,
                'banco_id' => $configuracion->banco_id,
                'nombre_banco' => $nombreBanco, // ← NUEVO CAMPO
                'numero_cuenta' => $configuracion->numero_cuenta,
                'responsable' => $configuracion->responsable
            ]
        );

        \Log::info("🔗 Cuenta operativa actualizada ID: {$cuenta->id}, Nombre: {$nombreCuenta}, Banco: {$nombreBanco}");
        
        return $cuenta;
    }

    /**
     * Genera nombres descriptivos para las cuentas
     */
    private function generarNombreCuenta($tipoCuenta)
    {
        $nombres = [
            'caja_general' => 'Caja General',
            'cuenta_corriente_1' => 'Cuenta Corriente Principal',
            'cuenta_ahorro' => 'Cuenta de Ahorro Principal',
            'cuenta_corriente_2' => 'Cuenta Corriente Secundaria',
            'cuenta_corriente_3' => 'Cuenta Corriente Terciaria'
        ];

        return $nombres[$tipoCuenta] ?? ucfirst(str_replace('_', ' ', $tipoCuenta));
    }

    /**
     * MÉTODO ANTIGUO - mantenido como backup
     */
    public function storeAntiguo(Request $request)
    {
        try {
            // LOG: Test inicial
            \Log::info('=== MÉTODO STORE INICIADO ===');
            \Log::info('Timestamp: ' . now());
            \Log::info('Request method: ' . $request->method());
            \Log::info('Request headers: ', $request->headers->all());
            \Log::info('Request input count: ' . count($request->all()));
            
            // Helper function para limpiar valores undefined/null/empty
            $cleanValue = function($value) {
                if ($value === 'undefined' || $value === 'null' || $value === '' || $value === null) {
                    return null;
                }
                return $value;
            };
            
            $request->validate([
            'saldo_caja_general' => 'required|numeric',
            'banco_caja_general' => 'nullable|string',
            'numero_caja_general' => 'nullable|string',
            'responsable_caja_general' => 'nullable|string',
            'tipo_cuenta_caja_general' => 'nullable|string',
            'observaciones_caja_general' => 'nullable|string',

            'saldo_cta_corriente_1' => 'required|numeric',
            'banco_cta_corriente_1' => 'nullable|string',
            'numero_cta_corriente_1' => 'nullable|string',
            'responsable_cta_corriente_1' => 'nullable|string',
            'tipo_cuenta_cta_corriente_1' => 'nullable|string',
            'observaciones_cta_corriente_1' => 'nullable|string',

            'saldo_cuenta_ahorro' => 'required|numeric',
            'banco_cuenta_ahorro' => 'nullable|string',
            'numero_cuenta_ahorro' => 'nullable|string',
            'responsable_cuenta_ahorro' => 'nullable|string',
            'tipo_cuenta_cuenta_ahorro' => 'nullable|string',
            'observaciones_cuenta_ahorro' => 'nullable|string',
            
            // Validación para cuentas dinámicas
            'cuentas_dinamicas' => 'nullable|json',
        ]);

        $orgId = $request->input('orgId');
        
        // NUEVO: Manejar actualización solo de bancos
        if ($request->has('solo_bancos') && $request->solo_bancos === 'true') {
            \Log::info('🏦 MODO: Actualización solo de bancos');
            return $this->actualizarSoloBancos($request, $orgId);
        }
        
        // LOG: Debug de datos recibidos
        \Log::info('=== DATOS RECIBIDOS EN STORE ===');
        \Log::info('orgId: ' . $orgId);
        \Log::info('🔍 BANCOS RECIBIDOS:');
        \Log::info('  - banco_cta_corriente_1: ' . ($request->banco_cta_corriente_1 ?? 'NULL'));
        \Log::info('  - banco_cuenta_ahorro: ' . ($request->banco_cuenta_ahorro ?? 'NULL'));
        \Log::info('🔍 SALDOS RECIBIDOS:');
        \Log::info('  - saldo_caja_general: ' . ($request->saldo_caja_general ?? 'NULL'));
        \Log::info('  - saldo_cta_corriente_1: ' . ($request->saldo_cta_corriente_1 ?? 'NULL'));
        \Log::info('  - saldo_cuenta_ahorro: ' . ($request->saldo_cuenta_ahorro ?? 'NULL'));
        \Log::info('cuentas_dinamicas: ' . $request->input('cuentas_dinamicas'));
        \Log::info('Todos los parámetros:', $request->all());
        
        // DEBUG ADICIONAL: Escribir a archivo temporal
        $debugInfo = [
            'timestamp' => now(),
            'orgId' => $orgId,
            'cuentas_dinamicas_raw' => $request->input('cuentas_dinamicas'),
            'has_cuentas_dinamicas' => $request->has('cuentas_dinamicas'),
            'all_request' => $request->all()
        ];
        file_put_contents(storage_path('debug_cuentas.json'), json_encode($debugInfo, JSON_PRETTY_PRINT));
        
        $cuentasGuardadas = []; // Array para tracking de cuentas guardadas
        
        // Caja General
        $cuentaCajaGeneral = Cuenta::where('tipo', 'caja_general')->where('org_id', $orgId)->first();
        if (!$cuentaCajaGeneral) {
            // Crear la cuenta si no existe
            $cuentaCajaGeneral = Cuenta::create([
                'tipo' => 'caja_general',
                'nombre' => 'Caja General',
                'org_id' => $orgId,
                'saldo_actual' => $request->saldo_caja_general,
                'banco_id' => null, // Caja general no tiene banco
                'numero_cuenta' => null, // Caja general no tiene número de cuenta
            ]);
        } else {
            $cuentaCajaGeneral->saldo_actual = $request->saldo_caja_general;
            $cuentaCajaGeneral->save();
        }
        
        $configuracion = ConfiguracionInicial::updateOrCreate(
            [
                'org_id' => $orgId,
                'cuenta_id' => $cuentaCajaGeneral->id,
            ],
            [
                'saldo_inicial' => $request->saldo_caja_general,
                'responsable' => $cleanValue($request->responsable_caja_general),
                'banco' => 'Efectivo', // Mostrar "Efectivo" para caja general
                'numero_cuenta' => null,
                'tipo_cuenta' => $cleanValue($request->tipo_cuenta_caja_general),
                'observaciones' => $cleanValue($request->observaciones_caja_general),
            ]
        );
        \Log::info('✅ CAJA GENERAL GUARDADA - ID: ' . $configuracion->id . ', Saldo: ' . $request->saldo_caja_general);
        $cuentasGuardadas[] = ['tipo' => 'caja_general', 'saldo' => $request->saldo_caja_general];
        
        // Buscar banco por código para obtener el ID y nombre
        $bancoId = null;
        $bancoNombre = null;
        \Log::info('🔍 BANCO CORRIENTE 1 - Código recibido: ' . $request->banco_cta_corriente_1);
        if ($request->banco_cta_corriente_1) {
            // Buscar el banco por código para obtener el ID
            $banco = \App\Models\Banco::where('codigo', $request->banco_cta_corriente_1)->first();
            if (!$banco) {
                // Si no existe, crear el banco con código y nombre predeterminado
                $bancosEstaticos = [
                    'banco_estado' => 'Banco Estado',
                    'banco_chile' => 'Banco de Chile',
                    'banco_bci' => 'BCI',
                    'banco_santander' => 'Santander',
                    'banco_itau' => 'Itaú',
                    'banco_scotiabank' => 'Scotiabank',
                    'banco_bice' => 'BICE',
                    'banco_security' => 'Security',
                    'banco_falabella' => 'Falabella',
                ];
                
                if (isset($bancosEstaticos[$request->banco_cta_corriente_1])) {
                    $banco = \App\Models\Banco::create([
                        'codigo' => $request->banco_cta_corriente_1,
                        'nombre' => $bancosEstaticos[$request->banco_cta_corriente_1]
                    ]);
                }
            }
            
            if ($banco) {
                $bancoId = $banco->id;
                $bancoNombre = $banco->nombre;
                \Log::info('✅ BANCO ENCONTRADO - ID: ' . $bancoId . ', Nombre: ' . $bancoNombre);
            } else {
                \Log::warning('⚠️ BANCO NO ENCONTRADO para código: ' . $request->banco_cta_corriente_1);
            }
        } else {
            \Log::info('ℹ️ NO SE ENVIÓ CÓDIGO DE BANCO para cuenta corriente 1');
        }
        
        $cuentaCorriente1 = Cuenta::where('tipo', 'cuenta_corriente_1')->where('org_id', $orgId)->first();
        if (!$cuentaCorriente1) {
            // Crear la cuenta si no existe
            $cuentaCorriente1 = Cuenta::create([
                'tipo' => 'cuenta_corriente_1',
                'nombre' => 'Cuenta Corriente Principal',
                'org_id' => $orgId,
                'saldo_actual' => $request->saldo_cta_corriente_1,
                'banco_id' => $bancoId,
                'numero_cuenta' => $request->numero_cta_corriente_1,
            ]);
        } else {
            $cuentaCorriente1->saldo_actual = $request->saldo_cta_corriente_1;
            $cuentaCorriente1->banco_id = $bancoId;
            $cuentaCorriente1->numero_cuenta = $request->numero_cta_corriente_1;
            $cuentaCorriente1->save();
        }
        
        $configuracion = ConfiguracionInicial::updateOrCreate(
            [
                'org_id' => $orgId,
                'cuenta_id' => $cuentaCorriente1->id,
            ],
            [
                'saldo_inicial' => $request->saldo_cta_corriente_1,
                'responsable' => $cleanValue($request->responsable_cta_corriente_1),
                'banco' => $bancoNombre, // Guardar el nombre del banco en lugar del código
                'numero_cuenta' => $cleanValue($request->numero_cta_corriente_1),
                'tipo_cuenta' => $cleanValue($request->tipo_cuenta_cta_corriente_1),
                'observaciones' => $cleanValue($request->observaciones_cta_corriente_1),
            ]
        );
        \Log::info('💾 GUARDANDO EN configuracion_cuentas_iniciales - Banco: ' . ($bancoNombre ?? 'NULL') . ', Número cuenta: ' . $request->numero_cta_corriente_1);
        \Log::info('✅ CUENTA CORRIENTE 1 GUARDADA - ID: ' . $configuracion->id . ', Saldo: ' . $request->saldo_cta_corriente_1);
        $cuentasGuardadas[] = ['tipo' => 'cuenta_corriente_1', 'saldo' => $request->saldo_cta_corriente_1];
        
        // Cuenta de Ahorro
        $bancoIdAhorro = null;
        $bancoNombreAhorro = null;
        \Log::info('🔍 BANCO AHORRO - Código recibido: ' . $request->banco_cuenta_ahorro);
        if ($request->banco_cuenta_ahorro) {
            // Buscar el banco por código para obtener el ID
            $banco = \App\Models\Banco::where('codigo', $request->banco_cuenta_ahorro)->first();
            if (!$banco) {
                // Si no existe, crear el banco con código y nombre predeterminado
                $bancosEstaticos = [
                    'banco_estado' => 'Banco Estado',
                    'banco_chile' => 'Banco de Chile',
                    'banco_bci' => 'BCI',
                    'banco_santander' => 'Santander',
                    'banco_itau' => 'Itaú',
                    'banco_scotiabank' => 'Scotiabank',
                    'banco_bice' => 'BICE',
                    'banco_security' => 'Security',
                    'banco_falabella' => 'Falabella',
                ];
                
                if (isset($bancosEstaticos[$request->banco_cuenta_ahorro])) {
                    $banco = \App\Models\Banco::create([
                        'codigo' => $request->banco_cuenta_ahorro,
                        'nombre' => $bancosEstaticos[$request->banco_cuenta_ahorro]
                    ]);
                }
            }
            
            if ($banco) {
                $bancoIdAhorro = $banco->id;
                $bancoNombreAhorro = $banco->nombre;
                \Log::info('✅ BANCO AHORRO ENCONTRADO - ID: ' . $bancoIdAhorro . ', Nombre: ' . $bancoNombreAhorro);
            } else {
                \Log::warning('⚠️ BANCO AHORRO NO ENCONTRADO para código: ' . $request->banco_cuenta_ahorro);
            }
        } else {
            \Log::info('ℹ️ NO SE ENVIÓ CÓDIGO DE BANCO para cuenta de ahorro');
        }
        
        $cuentaAhorro = Cuenta::where('tipo', 'cuenta_ahorro')->where('org_id', $orgId)->first();
        if (!$cuentaAhorro) {
            // Crear la cuenta si no existe
            $cuentaAhorro = Cuenta::create([
                'tipo' => 'cuenta_ahorro',
                'nombre' => 'Cuenta de Ahorro Principal',
                'org_id' => $orgId,
                'saldo_actual' => $request->saldo_cuenta_ahorro,
                'banco_id' => $bancoIdAhorro,
                'numero_cuenta' => $request->numero_cuenta_ahorro,
            ]);
        } else {
            $cuentaAhorro->saldo_actual = $request->saldo_cuenta_ahorro;
            $cuentaAhorro->banco_id = $bancoIdAhorro;
            $cuentaAhorro->numero_cuenta = $request->numero_cuenta_ahorro;
            $cuentaAhorro->save();
        }
        
        $configuracion = ConfiguracionInicial::updateOrCreate(
            [
                'org_id' => $orgId,
                'cuenta_id' => $cuentaAhorro->id,
            ],
            [
                'saldo_inicial' => $request->saldo_cuenta_ahorro,
                'responsable' => $cleanValue($request->responsable_cuenta_ahorro),
                'banco' => $bancoNombreAhorro, // Guardar el nombre del banco en lugar del código
                'numero_cuenta' => $cleanValue($request->numero_cuenta_ahorro),
                'tipo_cuenta' => $cleanValue($request->tipo_cuenta_cuenta_ahorro),
                'observaciones' => $cleanValue($request->observaciones_cuenta_ahorro),
            ]
        );
        \Log::info('💾 GUARDANDO AHORRO EN configuracion_cuentas_iniciales - Banco: ' . ($bancoNombreAhorro ?? 'NULL') . ', Número cuenta: ' . $request->numero_cuenta_ahorro);
        \Log::info('✅ CUENTA AHORRO GUARDADA - ID: ' . $configuracion->id . ', Saldo: ' . $request->saldo_cuenta_ahorro);
        $cuentasGuardadas[] = ['tipo' => 'cuenta_ahorro', 'saldo' => $request->saldo_cuenta_ahorro];

        // NUEVO: Procesar cuentas dinámicas
        $cuentasDinamicasGuardadas = 0;
        $debugCuentasDinamicas = [];
        
        try {
            if ($request->has('cuentas_dinamicas')) {
                $cuentasDinamicasRaw = $request->input('cuentas_dinamicas');
                $debugCuentasDinamicas['raw_data'] = $cuentasDinamicasRaw;
                $debugCuentasDinamicas['is_empty'] = empty($cuentasDinamicasRaw);
                
                $cuentasDinamicas = json_decode($cuentasDinamicasRaw, true);
                $debugCuentasDinamicas['decoded_data'] = $cuentasDinamicas;
                $debugCuentasDinamicas['json_error'] = json_last_error();
                
                \Log::info('Cuentas dinámicas decodificadas:', $cuentasDinamicas);
                
                if (is_array($cuentasDinamicas) && count($cuentasDinamicas) > 0) {
                    $debugCuentasDinamicas['count'] = count($cuentasDinamicas);
                    
                    foreach ($cuentasDinamicas as $index => $cuentaDinamica) {
                        \Log::info('Procesando cuenta dinámica #' . ($index + 1) . ':', $cuentaDinamica);
                        
                        // Usar el tipo específico (ej: cuenta_corriente_2, cuenta_ahorro_3)
                        $tipoEspecifico = $cuentaDinamica['tipo'];
                        
                        // Limpiar valores undefined o null
                        $banco = $cuentaDinamica['banco'] ?? '';
                        if ($banco === 'undefined' || $banco === 'null') {
                            $banco = '';
                        }
                        
                        $numero = $cuentaDinamica['numero'] ?? '';
                        if ($numero === 'undefined' || $numero === 'null') {
                            $numero = '';
                        }
                        
                        $responsable = $cuentaDinamica['responsable'] ?? '';
                        if ($responsable === 'undefined' || $responsable === 'null') {
                            $responsable = '';
                        }
                        
                        $saldo = floatval($cuentaDinamica['saldo'] ?? 0);
                        
                // Buscar banco por código si se proporciona
                $bancoId = null;
                $bancoNombreDinamico = null;
                if (!empty($banco)) {
                    $bancoObj = \App\Models\Banco::where('codigo', $banco)->first();
                    if (!$bancoObj) {
                        // Si no existe, crear el banco con código y nombre predeterminado
                        $bancosEstaticos = [
                            'banco_estado' => 'Banco Estado',
                            'banco_chile' => 'Banco de Chile',
                            'banco_bci' => 'BCI',
                            'banco_santander' => 'Santander',
                            'banco_itau' => 'Itaú',
                            'banco_scotiabank' => 'Scotiabank',
                            'banco_bice' => 'BICE',
                            'banco_security' => 'Security',
                            'banco_falabella' => 'Falabella',
                        ];
                        
                        if (isset($bancosEstaticos[$banco])) {
                            $bancoObj = \App\Models\Banco::create([
                                'codigo' => $banco,
                                'nombre' => $bancosEstaticos[$banco]
                            ]);
                        }
                    }
                    
                    if ($bancoObj) {
                        $bancoId = $bancoObj->id;
                        $bancoNombreDinamico = $bancoObj->nombre;
                        \Log::info('📍 BANCO ENCONTRADO para cuenta dinámica: Código ' . $banco . ' = ID ' . $bancoId . ' = ' . $bancoNombreDinamico);
                    } else {
                        \Log::warning('⚠️ BANCO NO ENCONTRADO para cuenta dinámica: ' . $banco);
                    }
                }                        $nombreCuenta = !empty($banco) ? $banco : $tipoEspecifico;
                        
                        // Buscar si ya existe esta cuenta específica para esta organización
                        $cuenta = Cuenta::where([
                            'tipo' => $tipoEspecifico,
                            'org_id' => $orgId
                        ])->first();
                        
                        if (!$cuenta) {
                            // Crear nueva cuenta con tipo específico
                            $cuenta = Cuenta::create([
                                'tipo' => $tipoEspecifico,
                                'nombre' => $nombreCuenta,
                                'banco_id' => $bancoId, // Usar banco_id en lugar de banco
                                'numero_cuenta' => $numero,
                                'org_id' => $orgId,
                                'saldo_actual' => $saldo,
                            ]);
                            \Log::info('✅ NUEVA CUENTA DINÁMICA CREADA: ' . $tipoEspecifico . ' (ID: ' . $cuenta->id . ') con banco_id: ' . $bancoId);
                            $debugCuentasDinamicas['accounts_created'][] = $tipoEspecifico;
                        } else {
                            // Actualizar cuenta existente
                            $cuenta->update([
                                'banco_id' => $bancoId, // Usar banco_id en lugar de banco
                                'numero_cuenta' => $numero,
                                'saldo_actual' => $saldo,
                                'nombre' => $nombreCuenta,
                            ]);
                            \Log::info('✅ CUENTA DINÁMICA ACTUALIZADA: ' . $tipoEspecifico . ' (ID: ' . $cuenta->id . ')');
                            $debugCuentasDinamicas['accounts_updated'][] = $tipoEspecifico;
                        }
                        
                        // Verificar si ya tiene configuración inicial
                        $configuracionExistente = ConfiguracionInicial::where([
                            'org_id' => $orgId,
                            'cuenta_id' => $cuenta->id
                        ])->first();
                        
                        if ($configuracionExistente) {
                            \Log::info('Actualizando configuración inicial existente para: ' . $tipoEspecifico);
                            // Actualizar configuración existente
                            $configuracionExistente->update([
                                'saldo_inicial' => $saldo,
                                'responsable' => $cleanValue($responsable),
                                'banco' => $bancoNombreDinamico ?? null, // Guardar el nombre del banco en lugar del código
                                'numero_cuenta' => $cleanValue($numero),
                            ]);
                            $configuracionId = $configuracionExistente->id;
                            $debugCuentasDinamicas['configs_updated'][] = $tipoEspecifico;
                        } else {
                            // Crear nueva configuración inicial
                            $configuracion = ConfiguracionInicial::create([
                                'org_id' => $orgId,
                                'cuenta_id' => $cuenta->id,
                                'saldo_inicial' => $saldo,
                                'responsable' => $cleanValue($responsable),
                                'banco' => $bancoNombreDinamico ?? null, // Guardar el nombre del banco en lugar del código
                                'numero_cuenta' => $cleanValue($numero),
                                'tipo_cuenta' => null,
                                'observaciones' => 'Cuenta dinámica: ' . $tipoEspecifico,
                            ]);
                            $configuracionId = $configuracion->id;
                            \Log::info('✅ NUEVA CONFIGURACIÓN INICIAL CREADA para: ' . $tipoEspecifico);
                            $debugCuentasDinamicas['configs_created'][] = $tipoEspecifico;
                        }
                        
                        \Log::info('✅ CUENTA DINÁMICA COMPLETAMENTE GUARDADA: ' . $tipoEspecifico . ' - Config ID: ' . $configuracionId . ', Saldo: ' . $saldo);
                        $cuentasGuardadas[] = ['tipo' => $tipoEspecifico, 'saldo' => $saldo];
                        $cuentasDinamicasGuardadas++;
                    }
                } else {
                    $debugCuentasDinamicas['error'] = 'No es array válido o está vacío';
                    \Log::warning('cuentas_dinamicas no es un array válido o está vacío');
                }
            } else {
                $debugCuentasDinamicas['error'] = 'No se encontró el parámetro cuentas_dinamicas';
                \Log::info('No se encontraron cuentas dinámicas en la request');
            }
        } catch (\Exception $e) {
            $debugCuentasDinamicas['exception'] = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ];
            \Log::error('Error procesando cuentas dinámicas: ' . $e->getMessage());
        }
        
        // Escribir debug adicional
        file_put_contents(storage_path('debug_cuentas_dinamicas.json'), json_encode($debugCuentasDinamicas, JSON_PRETTY_PRINT));
        
        // LOG FINAL: Resumen de lo guardado
        \Log::info('=== RESUMEN FINAL DE GUARDADO ===');
        \Log::info('Total cuentas básicas guardadas: 3 (caja_general, cuenta_corriente_1, cuenta_ahorro)');
        \Log::info('Total cuentas dinámicas guardadas: ' . $cuentasDinamicasGuardadas);
        \Log::info('Todas las cuentas guardadas:', $cuentasGuardadas);
        \Log::info('=== FIN DE PROCESAMIENTO ===');

        // Verificar si es una petición AJAX
        if ($request->ajax()) {
            $totalCuentasGuardadas = count($cuentasGuardadas);
            $mensaje = "✅ Configuración guardada exitosamente: {$totalCuentasGuardadas} cuentas procesadas";
            if ($cuentasDinamicasGuardadas > 0) {
                $mensaje .= " (incluyendo {$cuentasDinamicasGuardadas} cuentas dinámicas)";
            }
            
            return response()->json([
                'success' => true,
                'message' => $mensaje,
                'debug_info' => [
                    'total_cuentas_guardadas' => $totalCuentasGuardadas,
                    'cuentas_dinamicas_guardadas' => $cuentasDinamicasGuardadas,
                    'cuentas_detalle' => $cuentasGuardadas,
                    'timestamp' => now()->format('Y-m-d H:i:s'),
                    'cuentas_dinamicas_debug' => $debugCuentasDinamicas,
                    'org_id' => $orgId
                ],
                'redirect' => route('cuentas_iniciales.show', ['id' => $request->orgId])
            ]);
        }

        $totalCuentasGuardadas = count($cuentasGuardadas);
        $mensaje = "Configuración guardada exitosamente: {$totalCuentasGuardadas} cuentas procesadas";
        if ($cuentasDinamicasGuardadas > 0) {
            $mensaje .= " (incluyendo {$cuentasDinamicasGuardadas} cuentas dinámicas)";
        }
        
        return redirect()->route('cuentas_iniciales.show', ['id' => $request->orgId])->with('success', $mensaje);
        
        } catch (\Exception $e) {
            \Log::error('❌ ERROR CRÍTICO EN STORE: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('Error line: ' . $e->getLine());
            \Log::error('Error file: ' . $e->getFile());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error interno del servidor: ' . $e->getMessage(),
                    'error_details' => [
                        'message' => $e->getMessage(),
                        'line' => $e->getLine(),
                        'file' => basename($e->getFile())
                    ]
                ], 500);
            }
            
            return redirect()->back()->withErrors(['error' => 'Error interno del servidor: ' . $e->getMessage()]);
        }
    }


    // Método index eliminado - funcionalidad movida a mostrarLibroCaja


    public function mostrarLibroCaja($id)
    {
        $movimientos = \App\Models\Movimiento::whereHas('cuentaOrigen', function($q) use ($id) {
            $q->where('org_id', $id);
        })
        ->orderBy('fecha', 'desc')
        ->get();
        $resumen = $this->getResumenSaldos($id);
        return view('orgs.contable.libro-caja', array_merge([
            'orgId' => $id,
            'movimientos' => $movimientos,
        ], $resumen));
    }

    /**
     * Centraliza la obtención de saldos iniciales y objetos de cuentas para el partial y las vistas
     */
    private function getResumenSaldos($orgId)
    {
        // Usar directamente la tabla de cuentas operativas (nuevo flujo)
        $cuentaCajaGeneral = Cuenta::where('org_id', $orgId)->where('tipo', 'caja_general')->first();
        $cuentaCorriente1 = Cuenta::where('org_id', $orgId)->where('tipo', 'cuenta_corriente_1')->first();
        $cuentaAhorro = Cuenta::where('org_id', $orgId)->where('tipo', 'cuenta_ahorro')->first();

        // Obtener configuraciones iniciales para saldos históricos
        $configCaja = ConfiguracionCuentasIniciales::where('org_id', $orgId)->where('tipo_cuenta', 'caja_general')->first();
        $configCorriente = ConfiguracionCuentasIniciales::where('org_id', $orgId)->where('tipo_cuenta', 'cuenta_corriente_1')->first();
        $configAhorro = ConfiguracionCuentasIniciales::where('org_id', $orgId)->where('tipo_cuenta', 'cuenta_ahorro')->first();

        // Saldos iniciales (desde configuración)
        $saldosIniciales = [
            'Caja General' => $configCaja->saldo_inicial ?? 0,
            'Cuenta Corriente 1' => $configCorriente->saldo_inicial ?? 0,
            'Cuenta de Ahorro' => $configAhorro->saldo_inicial ?? 0,
        ];

        // Saldos actuales (desde tabla operativa)
        $cuentaCajaGeneralActual = $cuentaCajaGeneral ? $cuentaCajaGeneral->saldo_actual : 0;
        $cuentaCorriente1Actual = $cuentaCorriente1 ? $cuentaCorriente1->saldo_actual : 0;
        $cuentaAhorroActual = $cuentaAhorro ? $cuentaAhorro->saldo_actual : 0;

        // Suma total inicial y actual
        $totalSaldoInicial = array_sum($saldosIniciales);
        $totalSaldoActual = $cuentaCajaGeneralActual + $cuentaCorriente1Actual + $cuentaAhorroActual;
        $saldosIniciales['Saldo Total'] = $totalSaldoInicial + $totalSaldoActual;

        // Totales de movimientos
        $movimientos = \App\Models\Movimiento::whereHas('cuentaOrigen', function($q) use ($orgId) {
            $q->where('org_id', $orgId);
        })->get();
        $totalIngresos = $movimientos->sum(function($m) {
            return ($m->total_consumo ?? 0) + ($m->cuotas_incorporacion ?? 0) + ($m->otros_ingresos ?? 0);
        });
        $totalEgresos = $movimientos->sum(function($m) {
            return ($m->giros ?? 0);
        });
        $saldoFinal = $saldosIniciales['Saldo Total'] + $totalIngresos - $totalEgresos;

        return [
            'saldosIniciales' => $saldosIniciales,
            'cuentaCajaGeneral' => $configCaja, // Configuración inicial para compatibilidad
            'cuentaCorriente1' => $configCorriente,
            'cuentaAhorro' => $configAhorro,
            'cuentaCajaGeneralOperativa' => $cuentaCajaGeneral, // Cuentas operativas
            'cuentaCorriente1Operativa' => $cuentaCorriente1,
            'cuentaAhorroOperativa' => $cuentaAhorro,
            'totalSaldoInicial' => $totalSaldoInicial,
            'totalSaldoActual' => $totalSaldoActual,
            'totalIngresos' => $totalIngresos,
            'totalEgresos' => $totalEgresos,
            'saldoFinal' => $saldoFinal,
        ];
    }

    /**
     * Muestra el balance general de la organización
     */
    public function balance($id)
    {
        $resumen = $this->getResumenSaldos($id);
        $movimientos = \App\Models\Movimiento::whereHas('cuentaOrigen', function($q) use ($id) {
            $q->where('org_id', $id);
        })->orderBy('fecha', 'desc')->get();

        return view('orgs.contable.balance', array_merge([
            'orgId' => $id,
            'movimientos' => $movimientos,
        ], $resumen));
    }

    /**
     * Muestra la conciliación bancaria
     */
    public function conciliacionBancaria($id)
    {
        $resumen = $this->getResumenSaldos($id);
        $movimientos = \App\Models\Movimiento::whereHas('cuentaOrigen', function($q) use ($id) {
            $q->where('org_id', $id);
        })->orderBy('fecha', 'desc')->get();

        return view('orgs.contable.conciliacion_bancaria', array_merge([
            'orgId' => $id,
            'movimientos' => $movimientos,
        ], $resumen));
    }

    /**
     * Muestra los movimientos contables
     */
    public function movimientos($id)
    {
        $movimientos = \App\Models\Movimiento::whereHas('cuentaOrigen', function($q) use ($id) {
            $q->where('org_id', $id);
        })->orderBy('fecha', 'desc')->get();
        $resumen = $this->getResumenSaldos($id);

        return view('orgs.contable.movimientos', array_merge([
            'orgId' => $id,
            'movimientos' => $movimientos,
        ], $resumen));
    }

    /**
     * Muestra el informe por rubro
     */
    public function informePorRubro($id)
    {
        $categorias = \App\Models\Categoria::with(['movimientos' => function($q) use ($id) {
            $q->whereHas('cuentaOrigen', function($query) use ($id) {
                $query->where('org_id', $id);
            });
        }])->get();
        $resumen = $this->getResumenSaldos($id);

        return view('orgs.contable.informe_por_rubro', array_merge([
            'orgId' => $id,
            'categorias' => $categorias,
        ], $resumen));
    }

    /**
     * Muestra el registro de ingresos y egresos
     */
    public function registroIngresosEgresos($id)
    {
        $movimientos = \App\Models\Movimiento::whereHas('cuentaOrigen', function($q) use ($id) {
            $q->where('org_id', $id);
        })->orderBy('fecha', 'desc')->get();
        $resumen = $this->getResumenSaldos($id);
        $categoriasIngresos = \App\Models\Categoria::where('tipo', 'ingreso')->orderBy('nombre')->get();
        $categoriasEgresos = \App\Models\Categoria::where('tipo', 'egreso')->orderBy('nombre')->get();
        
        // Obtener configuraciones iniciales con nombres de banco (usando nueva tabla)
        $configuracionesIniciales = ConfiguracionCuentasIniciales::where('org_id', $id)
            ->whereNotNull('banco_id')
            ->with('banco')
            ->orderBy('created_at')
            ->get();

        return view('orgs.contable.registro_ingresos_egresos', array_merge([
            'orgId' => $id,
            'movimientos' => $movimientos,
            'categoriasIngresos' => $categoriasIngresos,
            'categoriasEgresos' => $categoriasEgresos,
            'configuracionesIniciales' => $configuracionesIniciales,
        ], $resumen));
    }

    /**
     * Muestra las cuentas iniciales
     */
    public function cuentasIniciales($id)
    {
        // Usar las nuevas tablas del flujo
        $configuraciones = ConfiguracionCuentasIniciales::where('org_id', $id)->get();
        $cuentasIniciales = \App\Models\Cuenta::where('org_id', $id)->get();
        $bancos = \App\Models\Banco::orderBy('nombre')->get();
        $resumen = $this->getResumenSaldos($id);

        return view('orgs.contable.configuracion_cuentas_new', array_merge([
            'orgId' => $id,
            'cuentasIniciales' => $cuentasIniciales,
            'configuraciones' => $configuraciones,
            'bancos' => $bancos,
            'active' => 'cuentas-iniciales',
        ], $resumen));
    }

    /**
     * Muestra una cuenta inicial específica
     */
    public function mostrarCuentaInicial($id, $cuenta)
    {
        $configuracion = ConfiguracionCuentasIniciales::with('banco')->where('org_id', $id)->where('id', $cuenta)->first();
        $bancos = \App\Models\Banco::orderBy('nombre')->get();
        $resumen = $this->getResumenSaldos($id);

        return view('orgs.contable.configuracion_cuentas_new', array_merge([
            'orgId' => $id,
            'configuracion' => $configuracion,
            'bancos' => $bancos,
        ], $resumen));
    }

    /**
     * Muestra la configuración de cuentas
     */
    public function configuracionCuentas($id)
    {
        $configuraciones = ConfiguracionCuentasIniciales::with('banco')->where('org_id', $id)->get();
        $cuentas = \App\Models\Cuenta::where('org_id', $id)->get();
        $bancos = \App\Models\Banco::orderBy('nombre')->get();
        $resumen = $this->getResumenSaldos($id);

        return view('orgs.contable.configuracion_cuentas_new', array_merge([
            'orgId' => $id,
            'cuentas' => $cuentas,
            'configuraciones' => $configuraciones,
            'bancos' => $bancos,
        ], $resumen));
    }

    /**
     * Muestra el libro caja en formato tabular
     */
    public function libroCajaTabular($id)
    {
        // Obtener todas las cuentas de la organización
        $cuentas = \App\Models\Cuenta::where('org_id', $id)
            ->orderBy('nombre')
            ->get();
        
        // Mapear las cuentas a las columnas existentes de la vista
        // Para mantener compatibilidad con la estructura existente
        $columnasIngresos = [
            'Total Consumo' => $cuentas->where('nombre', 'LIKE', '%consumo%')->first(),
            'Cuotas Incorporación' => $cuentas->where('nombre', 'LIKE', '%incorporacion%')->first(),
            'Otros Ingresos' => $cuentas->where('nombre', 'LIKE', '%otros%')->where('tipo', 'LIKE', '%ingreso%')->first(),
            'Giros' => $cuentas->where('nombre', 'LIKE', '%giro%')->first(),
        ];
        
        $columnasEgresos = [
            'ENERGÍA ELÉCTRICA' => $cuentas->where('nombre', 'LIKE', '%energia%')->first(),
            'SUELDOS/LEYES SOCIALES' => $cuentas->where('nombre', 'LIKE', '%sueldo%')->first(),
            'OTROS GASTOS DE OPERACIÓN' => $cuentas->where('nombre', 'LIKE', '%operacion%')->first(),
            'GASTOS MANTENCION' => $cuentas->where('nombre', 'LIKE', '%mantencion%')->first(),
            'GASTOS ADMINISTRACION' => $cuentas->where('nombre', 'LIKE', '%administracion%')->first(),
            'GASTOS MEJORAMIENTO' => $cuentas->where('nombre', 'LIKE', '%mejoramiento%')->first(),
            'OTROS EGRESOS' => $cuentas->where('nombre', 'LIKE', '%otros%')->where('tipo', 'LIKE', '%egreso%')->first(),
            'DEPÓSITOS' => $cuentas->where('nombre', 'LIKE', '%deposito%')->first(),
        ];
        
        // Obtener movimientos para mostrar en la tabla
        $movimientos = \App\Models\Movimiento::with(['categoria', 'cuentaOrigen', 'cuentaDestino'])
            ->where(function($q) use ($id) {
                $q->whereHas('cuentaOrigen', function($sq) use ($id) {
                    $sq->where('org_id', $id);
                })->orWhereHas('cuentaDestino', function($sq) use ($id) {
                    $sq->where('org_id', $id);
                });
            })
            ->orderBy('fecha', 'desc')
            ->get()
            ->map(function($movimiento) use ($columnasIngresos, $columnasEgresos) {
                return $this->mapearMovimientoTabular($movimiento, $columnasIngresos, $columnasEgresos);
            });
        
        // Obtener saldos reales de las cuentas para el encabezado
        $cuentaCajaGeneral = $cuentas->where('tipo', 'caja_general')->first();
        $cuentaCorriente1 = $cuentas->where('tipo', 'cuenta_corriente')->first();
        $cuentaCorriente2 = $cuentas->where('tipo', 'cuenta_corriente')->skip(1)->first();
        $cuentaAhorro = $cuentas->where('tipo', 'cuenta_ahorro')->first();
        
        $saldoCajaGeneral = $cuentaCajaGeneral ? $cuentaCajaGeneral->saldo_actual : 0;
        $saldoCuentaCorriente1 = $cuentaCorriente1 ? $cuentaCorriente1->saldo_actual : 0;
        $saldoCuentaCorriente2 = $cuentaCorriente2 ? $cuentaCorriente2->saldo_actual : 0;
        $saldoCuentaAhorro = $cuentaAhorro ? $cuentaAhorro->saldo_actual : 0;
        $saldoTotal = $saldoCajaGeneral + $saldoCuentaCorriente1 + $saldoCuentaCorriente2 + $saldoCuentaAhorro;
        
        // Debug para verificar los valores
        \Log::info('Saldos obtenidos:', [
            'saldoCajaGeneral' => $saldoCajaGeneral,
            'saldoCuentaCorriente1' => $saldoCuentaCorriente1,
            'saldoCuentaCorriente2' => $saldoCuentaCorriente2,
            'saldoCuentaAhorro' => $saldoCuentaAhorro,
            'saldoTotal' => $saldoTotal,
            'totalCuentas' => $cuentas->count()
        ]);
        
        
        $resumen = $this->getResumenSaldos($id);

        $view = view('orgs.contable.libro_caja_tabular', array_merge([
            'orgId' => $id,
            'id' => $id,
            'movimientos' => $movimientos,
            'cuentas' => $cuentas,
            'columnasIngresos' => $columnasIngresos,
            'columnasEgresos' => $columnasEgresos,
            'mostrarLibroCaja' => true,
            'saldoCajaGeneral' => $saldoCajaGeneral,
            'saldoCuentaCorriente1' => $saldoCuentaCorriente1,
            'saldoCuentaCorriente2' => $saldoCuentaCorriente2,
            'saldoCuentaAhorro' => $saldoCuentaAhorro,
            'saldoTotal' => $saldoTotal,
        ], $resumen));
        
        // Inyectar script para actualizar saldos
        $content = $view->render();
        $script = "
        <script type='text/javascript'>
            document.addEventListener('DOMContentLoaded', function() {
                // Inyectar saldos reales desde la base de datos
                localStorage.setItem('saldoCajaGeneral', {$saldoCajaGeneral});
                localStorage.setItem('saldoCuentaCorriente1', {$saldoCuentaCorriente1});
                localStorage.setItem('saldoCuentaCorriente2', {$saldoCuentaCorriente2});
                localStorage.setItem('saldoCuentaAhorro', {$saldoCuentaAhorro});
                
                // Actualizar los campos inmediatamente después de que se cargue la página
                setTimeout(function() {
                    if (document.getElementById('saldoCajaGeneral')) {
                        document.getElementById('saldoCajaGeneral').value = formatCurrency({$saldoCajaGeneral});
                    }
                    if (document.getElementById('saldoCuentaCorriente1')) {
                        document.getElementById('saldoCuentaCorriente1').value = formatCurrency({$saldoCuentaCorriente1});
                    }
                    if (document.getElementById('saldoCuentaCorriente2')) {
                        document.getElementById('saldoCuentaCorriente2').value = formatCurrency({$saldoCuentaCorriente2});
                    }
                    if (document.getElementById('saldoCuentaAhorro')) {
                        document.getElementById('saldoCuentaAhorro').value = formatCurrency({$saldoCuentaAhorro});
                    }
                    if (document.getElementById('saldoTotal')) {
                        document.getElementById('saldoTotal').value = formatCurrency({$saldoTotal});
                    }
                }, 500);
            });
        </script>";
        
        // Inyectar el script antes del cierre del body
        $content = str_replace('</body>', $script . '</body>', $content);
        
        return response($content);
    }

    /**
     * API endpoint para obtener saldos actuales de las cuentas
     */
    public function obtenerSaldosActuales($id)
    {
        $cuentas = \App\Models\Cuenta::where('org_id', $id)->get();
        
        $cuentaCajaGeneral = $cuentas->where('tipo', 'caja_general')->first();
        $cuentaCorriente1 = $cuentas->where('tipo', 'cuenta_corriente')->first();
        $cuentaCorriente2 = $cuentas->where('tipo', 'cuenta_corriente')->skip(1)->first();
        $cuentaAhorro = $cuentas->where('tipo', 'cuenta_ahorro')->first();
        
        $saldos = [
            'saldoCajaGeneral' => $cuentaCajaGeneral ? $cuentaCajaGeneral->saldo_actual : 0,
            'saldoCuentaCorriente1' => $cuentaCorriente1 ? $cuentaCorriente1->saldo_actual : 0,
            'saldoCuentaCorriente2' => $cuentaCorriente2 ? $cuentaCorriente2->saldo_actual : 0,
            'saldoCuentaAhorro' => $cuentaAhorro ? $cuentaAhorro->saldo_actual : 0,
        ];
        
        $saldos['saldoTotal'] = array_sum($saldos);
        
        return response()->json($saldos);
    }

    /**
     * Mapea un movimiento a las columnas específicas del libro caja tabular
     */
    private function mapearMovimientoTabular($movimiento, $columnasIngresos = [], $columnasEgresos = [])
    {
        // Crear un objeto para retornar con la estructura de la tabla
        $item = (object) [
            'id' => $movimiento->id,
            'fecha' => $movimiento->fecha,
            'descripcion' => $movimiento->descripcion,
            'tipo' => $movimiento->tipo,
            'monto' => $movimiento->monto,
            // Columnas de ingresos
            'total_consumo' => 0,
            'cuotas_incorporacion' => 0,
            'otros_ingresos' => 0,
            'giros' => 0,
            // Columnas de egresos
            'energia_electrica' => 0,
            'sueldos_leyes' => 0,
            'otros_gastos_operacion' => 0,
            'gastos_mantencion' => 0,
            'gastos_administracion' => 0,
            'gastos_mejoramiento' => 0,
            'otros_egresos' => 0,
            'depositos' => 0,
        ];

        $monto = $movimiento->monto;

        // Mapear según el tipo y subtipo del movimiento
        if ($movimiento->subtipo === 'giro') {
            $item->giros = $monto;
        } elseif ($movimiento->subtipo === 'deposito') {
            $item->depositos = $monto;
        } elseif ($movimiento->tipo === 'ingreso') {
            // Mapear ingresos según la cuenta destino
            if ($movimiento->cuentaDestino) {
                $nombreCuenta = strtolower($movimiento->cuentaDestino->nombre);
                if (stripos($nombreCuenta, 'consumo') !== false) {
                    $item->total_consumo = $monto;
                } elseif (stripos($nombreCuenta, 'incorporacion') !== false) {
                    $item->cuotas_incorporacion = $monto;
                } else {
                    $item->otros_ingresos = $monto;
                }
            } else {
                $item->otros_ingresos = $monto;
            }
        } elseif ($movimiento->tipo === 'egreso') {
            // Mapear egresos según la categoría o cuenta origen
            if ($movimiento->categoria && $movimiento->categoria->grupo) {
                $grupo = $movimiento->categoria->grupo;
                switch ($grupo) {
                    case 'energia_electrica':
                        $item->energia_electrica = $monto;
                        break;
                    case 'sueldos_leyes':
                        $item->sueldos_leyes = $monto;
                        break;
                    case 'gastos_operacion':
                        $item->otros_gastos_operacion = $monto;
                        break;
                    case 'gastos_mantencion':
                        $item->gastos_mantencion = $monto;
                        break;
                    case 'gastos_administracion':
                        $item->gastos_administracion = $monto;
                        break;
                    case 'gastos_mejoramiento':
                        $item->gastos_mejoramiento = $monto;
                        break;
                    default:
                        $item->otros_egresos = $monto;
                        break;
                }
            } else {
                $item->otros_egresos = $monto;
            }
        }

        return $item;
    }

    /**
     * Muestra la vista de giros y depósitos
     */
    public function girosDepositos($id)
    {
        $movimientos = \App\Models\Movimiento::where(function($q) use ($id) {
            $q->whereHas('cuentaOrigen', function($sq) use ($id) {
                $sq->where('org_id', $id);
            })->orWhereHas('cuentaDestino', function($sq) use ($id) {
                $sq->where('org_id', $id);
            });
        })->where('tipo', 'transferencia')
          ->orderBy('fecha', 'desc')->get();
        
        $resumen = $this->getResumenSaldos($id);
        $configuracionesIniciales = ConfiguracionCuentasIniciales::where('org_id', $id)->get();

        return view('orgs.contable.giros_depositos', array_merge([
            'orgId' => $id,
            'movimientos' => $movimientos,
            'configuracionesIniciales' => $configuracionesIniciales,
        ], $resumen));
    }

    /**
     * Muestra la vista nice (dashboard personalizado)
     */
    public function nice($id)
    {
        $resumen = $this->getResumenSaldos($id);
        $movimientos = \App\Models\Movimiento::whereHas('cuentaOrigen', function($q) use ($id) {
            $q->where('org_id', $id);
        })->orderBy('fecha', 'desc')->limit(10)->get();
        $configuraciones = ConfiguracionCuentasIniciales::with('banco')->where('org_id', $id)->get();

        return view('orgs.contable.nice', array_merge([
            'orgId' => $id,
            'movimientos' => $movimientos,
            'configuraciones' => $configuraciones,
            'active' => 'dashboard',
        ], $resumen));
    }

    /**
     * Verifica el saldo disponible de una cuenta
     */
    public function verificarSaldoCuenta(Request $request)
    {
        try {
            $cuentaId = $request->input('cuenta_id');
            $montoEgreso = $request->input('monto', 0);
            
            // Buscar la cuenta
            $cuenta = \App\Models\Cuenta::find($cuentaId);
            
            if (!$cuenta) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cuenta no encontrada'
                ], 404);
            }

            $saldoActual = $cuenta->saldo_actual ?? 0;
            $saldoInsuficiente = $saldoActual < $montoEgreso;
            
            return response()->json([
                'success' => true,
                'saldo_actual' => $saldoActual,
                'monto_egreso' => $montoEgreso,
                'saldo_insuficiente' => $saldoInsuficiente,
                'saldo_resultante' => $saldoActual - $montoEgreso,
                'cuenta_nombre' => $cuenta->nombre
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar saldo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Procesa un registro de ingreso
     */
    public function procesarIngreso(Request $request)
    {
        try {
            $request->validate([
                'cuenta_destino' => 'required|exists:cuentas,id',
                'categoria' => 'required|exists:categorias,id',
                'monto' => 'required|numeric|min:0.01',
                'descripcion' => 'required|string|max:500',
                'fecha' => 'required|date',
                'numero_comprobante' => 'required|string|max:50'
            ]);

            \DB::beginTransaction();

            // Buscar la cuenta destino
            $cuentaDestino = \App\Models\Cuenta::find($request->cuenta_destino);
            if (!$cuentaDestino) {
                throw new \Exception('Cuenta destino no encontrada');
            }

            // Crear el movimiento de ingreso
            $movimiento = \App\Models\Movimiento::create([
                'org_id' => $request->org_id ?? auth()->user()->org_id,
                'tipo' => 'ingreso',
                'cuenta_destino_id' => $request->cuenta_destino,
                'categoria_id' => $request->categoria,
                'monto' => $request->monto,
                'descripcion' => $request->descripcion,
                'fecha' => $request->fecha,
                'nro_dcto' => $request->numero_comprobante,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Aplicar mapeo tabular automático
            $movimiento = $this->mapearMovimientoTabular($movimiento);
            $movimiento->save();

            // Actualizar saldo de la cuenta destino (SUMAR el ingreso)
            $cuentaDestino->saldo_actual += $request->monto;
            $cuentaDestino->save();

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ingreso registrado exitosamente',
                'data' => [
                    'movimiento_id' => $movimiento->id,
                    'nuevo_saldo' => $cuentaDestino->saldo_actual,
                    'cuenta_nombre' => $cuentaDestino->nombre
                ]
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar ingreso: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Procesa un registro de egreso
     */
    public function procesarEgreso(Request $request)
    {
        try {
            $request->validate([
                'cuenta_origen' => 'required|exists:cuentas,id',
                'categoria' => 'required|exists:categorias,id',
                'monto' => 'required|numeric|min:0.01',
                'descripcion' => 'required|string|max:500',
                'fecha' => 'required|date',
                'numero_comprobante' => 'required|string|max:50',
                'razon_social' => 'nullable|string|max:200',
                'rut_proveedor' => 'nullable|string|max:20'
            ]);

            \DB::beginTransaction();

            // Buscar la cuenta origen
            $cuentaOrigen = \App\Models\Cuenta::find($request->cuenta_origen);
            if (!$cuentaOrigen) {
                throw new \Exception('Cuenta origen no encontrada');
            }

            // Verificar saldo suficiente
            if ($cuentaOrigen->saldo_actual < $request->monto) {
                throw new \Exception("Saldo insuficiente. Saldo actual: $" . number_format($cuentaOrigen->saldo_actual, 2));
            }

            // Crear el movimiento de egreso
            $movimiento = \App\Models\Movimiento::create([
                'org_id' => $request->org_id ?? auth()->user()->org_id,
                'tipo' => 'egreso',
                'cuenta_origen_id' => $request->cuenta_origen,
                'categoria_id' => $request->categoria,
                'monto' => $request->monto,
                'descripcion' => $request->descripcion,
                'fecha' => $request->fecha,
                'nro_dcto' => $request->numero_comprobante,
                'proveedor' => $request->razon_social,
                'rut_proveedor' => $request->rut_proveedor,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Aplicar mapeo tabular automático
            $movimiento = $this->mapearMovimientoTabular($movimiento);
            $movimiento->save();

            // Actualizar saldo de la cuenta origen (RESTAR el egreso)
            $cuentaOrigen->saldo_actual -= $request->monto;
            $cuentaOrigen->save();

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Egreso registrado exitosamente',
                'data' => [
                    'movimiento_id' => $movimiento->id,
                    'nuevo_saldo' => $cuentaOrigen->saldo_actual,
                    'cuenta_nombre' => $cuentaOrigen->nombre
                ]
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar egreso: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Procesa un giro (transferencia saliente)
     */
    public function procesarGiro(Request $request)
    {
        try {
            $request->validate([
                'cuenta_origen' => 'required|exists:cuentas,id',
                'monto' => 'required|numeric|min:0.01',
                'descripcion' => 'required|string|max:500',
                'fecha' => 'required|date',
                'numero_comprobante' => 'required|string|max:50',
                'beneficiario' => 'nullable|string|max:200'
            ]);

            \DB::beginTransaction();

            // Buscar la cuenta origen
            $cuentaOrigen = \App\Models\Cuenta::find($request->cuenta_origen);
            if (!$cuentaOrigen) {
                throw new \Exception('Cuenta origen no encontrada');
            }

            // Verificar saldo suficiente
            if ($cuentaOrigen->saldo_actual < $request->monto) {
                throw new \Exception("Saldo insuficiente para el giro. Saldo actual: $" . number_format($cuentaOrigen->saldo_actual, 2));
            }

            // Crear el movimiento de giro (transferencia saliente)
            $movimiento = \App\Models\Movimiento::create([
                'org_id' => $request->org_id ?? auth()->user()->org_id,
                'tipo' => 'transferencia',
                'subtipo' => 'giro',
                'cuenta_origen_id' => $request->cuenta_origen,
                'cuenta_destino_id' => null, // Externa, no afecta cuentas internas
                'monto' => $request->monto,
                'descripcion' => $request->descripcion,
                'fecha' => $request->fecha,
                'nro_dcto' => $request->numero_comprobante,
                'proveedor' => $request->beneficiario,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Aplicar mapeo tabular automático (columna giros)
            $movimiento = $this->mapearMovimientoTabular($movimiento);
            $movimiento->save();

            // Actualizar saldo de la cuenta origen (RESTAR el giro)
            $cuentaOrigen->saldo_actual -= $request->monto;
            $cuentaOrigen->save();

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Giro procesado exitosamente',
                'data' => [
                    'movimiento_id' => $movimiento->id,
                    'nuevo_saldo' => $cuentaOrigen->saldo_actual,
                    'cuenta_nombre' => $cuentaOrigen->nombre
                ]
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar giro: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Procesa un depósito (transferencia entrante)
     */
    public function procesarDeposito(Request $request)
    {
        try {
            $request->validate([
                'cuenta_destino' => 'required|exists:cuentas,id',
                'monto' => 'required|numeric|min:0.01',
                'descripcion' => 'required|string|max:500',
                'fecha' => 'required|date',
                'numero_comprobante' => 'required|string|max:50',
                'origen' => 'nullable|string|max:200'
            ]);

            \DB::beginTransaction();

            // Buscar la cuenta destino
            $cuentaDestino = \App\Models\Cuenta::find($request->cuenta_destino);
            if (!$cuentaDestino) {
                throw new \Exception('Cuenta destino no encontrada');
            }

            // Crear el movimiento de depósito (transferencia entrante)
            $movimiento = \App\Models\Movimiento::create([
                'org_id' => $request->org_id ?? auth()->user()->org_id,
                'tipo' => 'transferencia',
                'subtipo' => 'deposito',
                'cuenta_origen_id' => null, // Externa, no afecta cuentas internas
                'cuenta_destino_id' => $request->cuenta_destino,
                'monto' => $request->monto,
                'descripcion' => $request->descripcion,
                'fecha' => $request->fecha,
                'nro_dcto' => $request->numero_comprobante,
                'proveedor' => $request->origen,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Aplicar mapeo tabular automático (columna depósitos)
            $movimiento = $this->mapearMovimientoTabular($movimiento);
            $movimiento->save();

            // Actualizar saldo de la cuenta destino (SUMAR el depósito)
            $cuentaDestino->saldo_actual += $request->monto;
            $cuentaDestino->save();

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Depósito procesado exitosamente',
                'data' => [
                    'movimiento_id' => $movimiento->id,
                    'nuevo_saldo' => $cuentaDestino->saldo_actual,
                    'cuenta_nombre' => $cuentaDestino->nombre
                ]
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar depósito: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Actualizar solo los bancos de las cuentas existentes
     */
    private function actualizarSoloBancos($request, $orgId)
    {
        try {
            \Log::info('🏦 Iniciando actualización solo de bancos para org: ' . $orgId);
            
            $bancosActualizados = 0;
            
            // Función auxiliar para limpiar valores
            $cleanValue = function($value) {
                if ($value === null || $value === 'undefined' || $value === '') {
                    return null;
                }
                return trim($value);
            };
            
            // Actualizar banco cuenta corriente 1
            if ($request->has('banco_cta_corriente_1') && $request->banco_cta_corriente_1) {
                $bancoCorr1 = $this->procesarBanco($request->banco_cta_corriente_1);
                
                $cuentaCorriente1 = Cuenta::where('tipo', 'cuenta_corriente_1')->where('org_id', $orgId)->first();
                if ($cuentaCorriente1) {
                    // Actualizar cuenta
                    $cuentaCorriente1->banco_id = $bancoCorr1['id'];
                    $cuentaCorriente1->save();
                    
                    // Actualizar configuracion_cuentas_iniciales (nueva tabla)
                    ConfiguracionCuentasIniciales::where('org_id', $orgId)
                        ->where('tipo', 'cuenta_corriente_1')
                        ->update(['banco_id' => $bancoCorr1['id']]);
                    
                    $bancosActualizados++;
                    \Log::info('✅ Banco actualizado para cuenta corriente 1: ' . $bancoCorr1['nombre']);
                }
            }
            
            // Actualizar banco cuenta ahorro
            if ($request->has('banco_cuenta_ahorro') && $request->banco_cuenta_ahorro) {
                $bancoAhorro = $this->procesarBanco($request->banco_cuenta_ahorro);
                
                $cuentaAhorro = Cuenta::where('tipo', 'cuenta_ahorro')->where('org_id', $orgId)->first();
                if ($cuentaAhorro) {
                    // Actualizar cuenta
                    $cuentaAhorro->banco_id = $bancoAhorro['id'];
                    $cuentaAhorro->save();
                    
                    // Actualizar configuracion_cuentas_iniciales (nueva tabla)
                    ConfiguracionCuentasIniciales::where('org_id', $orgId)
                        ->where('tipo', 'cuenta_ahorro')
                        ->update(['banco_id' => $bancoAhorro['id']]);
                    
                    $bancosActualizados++;
                    \Log::info('✅ Banco actualizado para cuenta ahorro: ' . $bancoAhorro['nombre']);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "✅ Bancos actualizados exitosamente: {$bancosActualizados} cuentas",
                'bancos_actualizados' => $bancosActualizados
            ]);
            
        } catch (\Exception $e) {
            \Log::error('❌ Error actualizando bancos: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error actualizando bancos: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Procesar información del banco (código y nombre)
     */
    private function procesarBanco($codigoBanco)
    {
        // Buscar el banco por código
        $banco = \App\Models\Banco::where('codigo', $codigoBanco)->first();
        
        if (!$banco) {
            // Si no existe, crear el banco
            $bancosEstaticos = [
                'banco_estado' => 'Banco Estado',
                'banco_chile' => 'Banco de Chile',
                'banco_bci' => 'BCI',
                'banco_santander' => 'Santander',
                'banco_itau' => 'Itaú',
                'banco_scotiabank' => 'Scotiabank',
                'banco_bice' => 'BICE',
                'banco_security' => 'Security',
                'banco_falabella' => 'Falabella',
            ];
            
            $nombreBanco = $bancosEstaticos[$codigoBanco] ?? $codigoBanco;
            
            $banco = \App\Models\Banco::create([
                'codigo' => $codigoBanco,
                'nombre' => $nombreBanco
            ]);
        }
        
        return [
            'id' => $banco->id,
            'nombre' => $banco->nombre,
            'codigo' => $banco->codigo
        ];
    }
}