<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== PRUEBA FLUJO COMPLETO CUENTAS INICIALES ===\n\n";

// Simular datos de prueba
$datosForm = [
    'caja_general_banco_id' => 1,
    'caja_general_saldo_inicial' => 500000,
    'caja_general_numero_cuenta' => '12345678',
    'cuenta_corriente_1_banco_id' => 2,
    'cuenta_corriente_1_saldo_inicial' => 1500000,
    'cuenta_corriente_1_numero_cuenta' => '87654321',
    'cuenta_ahorro_banco_id' => 3,
    'cuenta_ahorro_saldo_inicial' => 800000,
    'cuenta_ahorro_numero_cuenta' => '11223344',
    'cuenta_corriente_2_banco_id' => 4,
    'cuenta_corriente_2_saldo_inicial' => 300000,
    'cuenta_corriente_2_numero_cuenta' => '44332211',
    'responsable' => 'Cristian Test',
    'observaciones' => 'Prueba de flujo completo'
];

echo "1. 📋 DATOS DEL FORMULARIO:\n";
foreach ($datosForm as $campo => $valor) {
    echo "   $campo: $valor\n";
}

echo "\n2. 🗄️ ESTADO INICIAL DE LAS TABLAS:\n";

// Verificar estado inicial de configuracion_cuentas_iniciales
$configIniciales = \App\Models\ConfiguracionCuentasIniciales::where('org_id', 1)->get();
echo "   📊 configuracion_cuentas_iniciales: " . $configIniciales->count() . " registros\n";
foreach ($configIniciales as $config) {
    echo "      - {$config->tipo_cuenta}: Saldo={$config->saldo_inicial}, Banco={$config->banco_id}, Copiado={$config->copiado_a_cuentas}\n";
}

// Verificar estado inicial de cuentas
$cuentas = \App\Models\Cuenta::where('org_id', 1)->get();
echo "   🏦 cuentas: " . $cuentas->count() . " registros\n";
foreach ($cuentas as $cuenta) {
    echo "      - {$cuenta->tipo}: Saldo={$cuenta->saldo_actual}, Banco={$cuenta->banco_id}\n";
}

echo "\n3. 🔄 SIMULANDO GUARDADO DEL FORMULARIO:\n";

try {
    \DB::beginTransaction();
    
    // Simular el proceso del ContableController::guardarCuentasIniciales
    $tiposCuenta = [
        'caja_general' => [
            'banco_id' => $datosForm['caja_general_banco_id'],
            'saldo_inicial' => $datosForm['caja_general_saldo_inicial'],
            'numero_cuenta' => $datosForm['caja_general_numero_cuenta']
        ],
        'cuenta_corriente_1' => [
            'banco_id' => $datosForm['cuenta_corriente_1_banco_id'],
            'saldo_inicial' => $datosForm['cuenta_corriente_1_saldo_inicial'],
            'numero_cuenta' => $datosForm['cuenta_corriente_1_numero_cuenta']
        ],
        'cuenta_ahorro' => [
            'banco_id' => $datosForm['cuenta_ahorro_banco_id'],
            'saldo_inicial' => $datosForm['cuenta_ahorro_saldo_inicial'],
            'numero_cuenta' => $datosForm['cuenta_ahorro_numero_cuenta']
        ],
        'cuenta_corriente_2' => [
            'banco_id' => $datosForm['cuenta_corriente_2_banco_id'],
            'saldo_inicial' => $datosForm['cuenta_corriente_2_saldo_inicial'],
            'numero_cuenta' => $datosForm['cuenta_corriente_2_numero_cuenta']
        ]
    ];

    foreach ($tiposCuenta as $tipo => $datos) {
        if ($datos['banco_id']) {
            echo "   📝 Procesando $tipo...\n";
            
            // Obtener nombre del banco
            $nombreBanco = \App\Models\Banco::find($datos['banco_id'])?->nombre ?? 'Sin banco';
            echo "      - Banco encontrado: $nombreBanco\n";
            
            // Guardar en configuracion_cuentas_iniciales
            $configuracion = \App\Models\ConfiguracionCuentasIniciales::updateOrCreate(
                [
                    'org_id' => 1,
                    'tipo_cuenta' => $tipo
                ],
                [
                    'saldo_inicial' => $datos['saldo_inicial'],
                    'responsable' => $datosForm['responsable'],
                    'banco_id' => $datos['banco_id'],
                    'nombre_banco' => $nombreBanco,
                    'numero_cuenta' => $datos['numero_cuenta'],
                    'observaciones' => $datosForm['observaciones'],
                    'copiado_a_cuentas' => false
                ]
            );
            echo "      - ✅ Guardado en configuracion_cuentas_iniciales (ID: {$configuracion->id})\n";
            
            // Transferir a tabla cuentas (simulando copiarACuentasOperativas)
            $cuenta = \App\Models\Cuenta::updateOrCreate(
                [
                    'org_id' => 1,
                    'tipo' => $tipo
                ],
                [
                    'nombre' => ucfirst(str_replace('_', ' ', $tipo)),
                    'banco_id' => $datos['banco_id'],
                    'numero_cuenta' => $datos['numero_cuenta'],
                    'saldo_actual' => $datos['saldo_inicial'],
                    'responsable' => $datosForm['responsable']
                ]
            );
            echo "      - ✅ Transferido a cuentas (ID: {$cuenta->id})\n";
            
            // Marcar como copiado
            $configuracion->update(['copiado_a_cuentas' => true]);
            echo "      - ✅ Marcado como copiado\n";
        }
    }
    
    \DB::commit();
    echo "   🎉 TRANSACCIÓN COMPLETADA EXITOSAMENTE\n";
    
} catch (\Exception $e) {
    \DB::rollBack();
    echo "   ❌ ERROR: " . $e->getMessage() . "\n";
}

echo "\n4. 📊 ESTADO FINAL DE LAS TABLAS:\n";

// Verificar estado final de configuracion_cuentas_iniciales
$configFinales = \App\Models\ConfiguracionCuentasIniciales::where('org_id', 1)->get();
echo "   📊 configuracion_cuentas_iniciales: " . $configFinales->count() . " registros\n";
foreach ($configFinales as $config) {
    echo "      - {$config->tipo_cuenta}: Saldo={$config->saldo_inicial}, Banco={$config->banco_id}({$config->nombre_banco}), Copiado={$config->copiado_a_cuentas}\n";
}

// Verificar estado final de cuentas
$cuentasFinales = \App\Models\Cuenta::where('org_id', 1)->get();
echo "   🏦 cuentas: " . $cuentasFinales->count() . " registros\n";
foreach ($cuentasFinales as $cuenta) {
    echo "      - {$cuenta->tipo}: Saldo={$cuenta->saldo_actual}, Banco={$cuenta->banco_id}, Número={$cuenta->numero_cuenta}\n";
}

echo "\n5. ✅ VERIFICACIÓN DE SINCRONIZACIÓN:\n";
foreach ($configFinales as $config) {
    $cuentaCorrespondiente = $cuentasFinales->where('tipo', $config->tipo_cuenta)->first();
    if ($cuentaCorrespondiente) {
        $sincronizada = ($config->saldo_inicial == $cuentaCorrespondiente->saldo_actual && 
                        $config->banco_id == $cuentaCorrespondiente->banco_id);
        $estado = $sincronizada ? "✅ SINCRONIZADA" : "❌ DESINCRONIZADA";
        echo "   {$config->tipo_cuenta}: $estado\n";
    }
}

echo "\n=== FIN DE LA PRUEBA ===\n";
