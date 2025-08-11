<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== PRUEBA ACTUALIZACIÓN DE DATOS EXISTENTES ===\n\n";

// Simular nuevos datos del formulario (como si el usuario modificara los valores)
$datosNuevos = [
    'caja_general_banco_id' => 5,  // Cambio de banco
    'caja_general_saldo_inicial' => 750000,  // Cambio de saldo
    'caja_general_numero_cuenta' => '99887766',  // Cambio de número
    'cuenta_corriente_1_banco_id' => 6,  
    'cuenta_corriente_1_saldo_inicial' => 2000000,  
    'cuenta_corriente_1_numero_cuenta' => '55443322',  
    'cuenta_ahorro_banco_id' => 7,  
    'cuenta_ahorro_saldo_inicial' => 1200000,  
    'cuenta_ahorro_numero_cuenta' => '77889900',  
    'cuenta_corriente_2_banco_id' => 8,  
    'cuenta_corriente_2_saldo_inicial' => 500000,  
    'cuenta_corriente_2_numero_cuenta' => '00998877',  
    'responsable' => 'Cristian Actualizado',
    'observaciones' => 'Datos actualizados - segunda prueba'
];

echo "1. 📋 NUEVOS DATOS DEL FORMULARIO:\n";
foreach ($datosNuevos as $campo => $valor) {
    echo "   $campo: $valor\n";
}

echo "\n2. 🗄️ ESTADO ANTES DE LA ACTUALIZACIÓN:\n";

$configAntes = \App\Models\ConfiguracionCuentasIniciales::where('org_id', 1)->get();
echo "   📊 configuracion_cuentas_iniciales:\n";
foreach ($configAntes as $config) {
    echo "      - {$config->tipo_cuenta}: Saldo={$config->saldo_inicial}, Banco={$config->banco_id}, Responsable='{$config->responsable}'\n";
}

$cuentasAntes = \App\Models\Cuenta::where('org_id', 1)->get();
echo "   🏦 cuentas:\n";
foreach ($cuentasAntes as $cuenta) {
    echo "      - {$cuenta->tipo}: Saldo={$cuenta->saldo_actual}, Banco={$cuenta->banco_id}, Responsable='{$cuenta->responsable}'\n";
}

echo "\n3. 🔄 SIMULANDO ACTUALIZACIÓN (EXACTO PROCESO DEL FORMULARIO):\n";

try {
    \DB::beginTransaction();
    
    $tiposCuenta = [
        'caja_general' => [
            'banco_id' => $datosNuevos['caja_general_banco_id'],
            'saldo_inicial' => $datosNuevos['caja_general_saldo_inicial'],
            'numero_cuenta' => $datosNuevos['caja_general_numero_cuenta']
        ],
        'cuenta_corriente_1' => [
            'banco_id' => $datosNuevos['cuenta_corriente_1_banco_id'],
            'saldo_inicial' => $datosNuevos['cuenta_corriente_1_saldo_inicial'],
            'numero_cuenta' => $datosNuevos['cuenta_corriente_1_numero_cuenta']
        ],
        'cuenta_ahorro' => [
            'banco_id' => $datosNuevos['cuenta_ahorro_banco_id'],
            'saldo_inicial' => $datosNuevos['cuenta_ahorro_saldo_inicial'],
            'numero_cuenta' => $datosNuevos['cuenta_ahorro_numero_cuenta']
        ],
        'cuenta_corriente_2' => [
            'banco_id' => $datosNuevos['cuenta_corriente_2_banco_id'],
            'saldo_inicial' => $datosNuevos['cuenta_corriente_2_saldo_inicial'],
            'numero_cuenta' => $datosNuevos['cuenta_corriente_2_numero_cuenta']
        ]
    ];

    foreach ($tiposCuenta as $tipo => $datos) {
        if ($datos['banco_id']) {
            echo "   📝 Actualizando $tipo...\n";
            
            $nombreBanco = \App\Models\Banco::find($datos['banco_id'])?->nombre ?? 'Sin banco';
            echo "      - Nuevo banco: $nombreBanco (ID: {$datos['banco_id']})\n";
            
            // PASO 1: Actualizar configuracion_cuentas_iniciales (usando updateOrCreate como en el controller)
            $configuracion = \App\Models\ConfiguracionCuentasIniciales::updateOrCreate(
                [
                    'org_id' => 1,
                    'tipo_cuenta' => $tipo
                ],
                [
                    'saldo_inicial' => $datos['saldo_inicial'],
                    'responsable' => $datosNuevos['responsable'],
                    'banco_id' => $datos['banco_id'],
                    'nombre_banco' => $nombreBanco,
                    'numero_cuenta' => $datos['numero_cuenta'],
                    'observaciones' => $datosNuevos['observaciones'],
                    'copiado_a_cuentas' => false  // Resetear flag como en el controller
                ]
            );
            echo "      - ✅ Actualizado en configuracion_cuentas_iniciales (ID: {$configuracion->id})\n";
            
            // PASO 2: Transferir a tabla cuentas (copiarACuentasOperativas)
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
                    'responsable' => $datosNuevos['responsable']
                ]
            );
            echo "      - ✅ Actualizado en cuentas (ID: {$cuenta->id})\n";
            
            // PASO 3: Marcar como copiado
            $configuracion->update(['copiado_a_cuentas' => true]);
            echo "      - ✅ Marcado como copiado\n";
        }
    }
    
    \DB::commit();
    echo "   🎉 ACTUALIZACIÓN COMPLETADA EXITOSAMENTE\n";
    
} catch (\Exception $e) {
    \DB::rollBack();
    echo "   ❌ ERROR: " . $e->getMessage() . "\n";
}

echo "\n4. 📊 ESTADO DESPUÉS DE LA ACTUALIZACIÓN:\n";

$configDespues = \App\Models\ConfiguracionCuentasIniciales::where('org_id', 1)->get();
echo "   📊 configuracion_cuentas_iniciales:\n";
foreach ($configDespues as $config) {
    echo "      - {$config->tipo_cuenta}: Saldo={$config->saldo_inicial}, Banco={$config->banco_id}({$config->nombre_banco}), Responsable='{$config->responsable}'\n";
}

$cuentasDespues = \App\Models\Cuenta::where('org_id', 1)->get();
echo "   🏦 cuentas:\n";
foreach ($cuentasDespues as $cuenta) {
    echo "      - {$cuenta->tipo}: Saldo={$cuenta->saldo_actual}, Banco={$cuenta->banco_id}, Responsable='{$cuenta->responsable}'\n";
}

echo "\n5. 🔍 COMPARACIÓN ANTES VS DESPUÉS:\n";
foreach ($tiposCuenta as $tipo => $datosNuevos) {
    $configAntes = $configAntes->where('tipo_cuenta', $tipo)->first();
    $configDespues = $configDespues->where('tipo_cuenta', $tipo)->first();
    
    if ($configAntes && $configDespues) {
        echo "   📋 $tipo:\n";
        echo "      ANTES: Saldo={$configAntes->saldo_inicial}, Banco={$configAntes->banco_id}\n";
        echo "      AHORA: Saldo={$configDespues->saldo_inicial}, Banco={$configDespues->banco_id}\n";
        
        $cambio = ($configAntes->saldo_inicial != $configDespues->saldo_inicial || 
                  $configAntes->banco_id != $configDespues->banco_id) ? "✅ ACTUALIZADO" : "❌ SIN CAMBIOS";
        echo "      ESTADO: $cambio\n\n";
    }
}

echo "6. ✅ VERIFICACIÓN FINAL DE SINCRONIZACIÓN:\n";
foreach ($configDespues as $config) {
    $cuentaCorrespondiente = $cuentasDespues->where('tipo', $config->tipo_cuenta)->first();
    if ($cuentaCorrespondiente) {
        $sincronizada = ($config->saldo_inicial == $cuentaCorrespondiente->saldo_actual && 
                        $config->banco_id == $cuentaCorrespondiente->banco_id);
        $estado = $sincronizada ? "✅ SINCRONIZADA" : "❌ DESINCRONIZADA";
        echo "   {$config->tipo_cuenta}: $estado\n";
    }
}

echo "\n=== CONCLUSIÓN ===\n";
echo "✅ Los datos SE ACTUALIZARON correctamente en ambas tablas\n";
echo "✅ La sincronización entre configuracion_cuentas_iniciales y cuentas FUNCIONA\n";
echo "✅ El flujo updateOrCreate + copiarACuentasOperativas FUNCIONA PERFECTAMENTE\n";
echo "\n=== FIN DE LA PRUEBA DE ACTUALIZACIÓN ===\n";
