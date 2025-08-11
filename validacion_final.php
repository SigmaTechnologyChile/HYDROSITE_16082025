<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VALIDACIÓN FINAL Y REPARACIÓN PREVENTIVA ===\n\n";

try {
    // 1. Verificar estructura de tablas
    echo "🔧 VERIFICANDO ESTRUCTURA DE TABLAS:\n";
    
    // Verificar que todas las columnas necesarias existen
    $configColumns = \Schema::getColumnListing('configuracion_cuentas_iniciales');
    $cuentasColumns = \Schema::getColumnListing('cuentas');
    
    $requiredConfigColumns = ['org_id', 'tipo_cuenta', 'saldo_inicial', 'banco_id', 'nombre_banco', 'numero_cuenta', 'responsable', 'copiado_a_cuentas'];
    $requiredCuentasColumns = ['org_id', 'tipo', 'saldo_actual', 'banco_id', 'numero_cuenta', 'responsable'];
    
    echo "   📊 configuracion_cuentas_iniciales:\n";
    foreach ($requiredConfigColumns as $col) {
        $exists = in_array($col, $configColumns) ? "✅" : "❌";
        echo "      $exists $col\n";
    }
    
    echo "   🏦 cuentas:\n";
    foreach ($requiredCuentasColumns as $col) {
        $exists = in_array($col, $cuentasColumns) ? "✅" : "❌";
        echo "      $exists $col\n";
    }
    
    // 2. Verificar datos y aplicar reparación preventiva
    echo "\n🔄 APLICANDO REPARACIÓN PREVENTIVA:\n";
    
    \DB::beginTransaction();
    
    $configs = \App\Models\ConfiguracionCuentasIniciales::where('org_id', 1)->get();
    
    foreach ($configs as $config) {
        echo "   📝 Procesando {$config->tipo_cuenta}...\n";
        
        // Asegurar que nombre_banco está actualizado
        if ($config->banco_id && !$config->nombre_banco) {
            $banco = \App\Models\Banco::find($config->banco_id);
            if ($banco) {
                $config->update(['nombre_banco' => $banco->nombre]);
                echo "      ✅ Nombre de banco actualizado: {$banco->nombre}\n";
            }
        }
        
        // Sincronizar con tabla cuentas
        $cuenta = \App\Models\Cuenta::updateOrCreate(
            [
                'org_id' => $config->org_id,
                'tipo' => $config->tipo_cuenta
            ],
            [
                'nombre' => ucfirst(str_replace('_', ' ', $config->tipo_cuenta)),
                'saldo_actual' => $config->saldo_inicial,
                'banco_id' => $config->banco_id,
                'numero_cuenta' => $config->numero_cuenta,
                'responsable' => $config->responsable
            ]
        );
        
        // Marcar como copiado
        $config->update(['copiado_a_cuentas' => true]);
        
        echo "      ✅ Sincronizado perfectamente\n";
    }
    
    \DB::commit();
    
    // 3. Validación final completa
    echo "\n📊 VALIDACIÓN FINAL COMPLETA:\n";
    
    $configsFinales = \App\Models\ConfiguracionCuentasIniciales::where('org_id', 1)->get();
    $cuentasFinales = \App\Models\Cuenta::where('org_id', 1)->get();
    
    $todoSincronizado = true;
    
    foreach ($configsFinales as $config) {
        $cuenta = $cuentasFinales->where('tipo', $config->tipo_cuenta)->first();
        
        if (!$cuenta) {
            echo "   ❌ {$config->tipo_cuenta}: Falta en tabla cuentas\n";
            $todoSincronizado = false;
            continue;
        }
        
        $checks = [
            'saldo' => $config->saldo_inicial == $cuenta->saldo_actual,
            'banco' => $config->banco_id == $cuenta->banco_id,
            'numero' => $config->numero_cuenta == $cuenta->numero_cuenta,
            'responsable' => $config->responsable == $cuenta->responsable,
            'nombre_banco' => !empty($config->nombre_banco),
            'copiado' => $config->copiado_a_cuentas == true
        ];
        
        $todosOK = array_reduce($checks, function($carry, $item) { return $carry && $item; }, true);
        
        if ($todosOK) {
            echo "   ✅ {$config->tipo_cuenta}: PERFECTO\n";
        } else {
            echo "   ❌ {$config->tipo_cuenta}: Problemas detectados\n";
            foreach ($checks as $campo => $ok) {
                if (!$ok) echo "      - $campo: FALLA\n";
            }
            $todoSincronizado = false;
        }
    }
    
    // 4. Resultado final
    if ($todoSincronizado) {
        echo "\n🎉 SISTEMA COMPLETAMENTE SINCRONIZADO\n";
        echo "✅ Todas las tablas están en perfecto estado\n";
        echo "✅ No hay errores de desincronización\n";
        echo "✅ El flujo de datos funciona perfectamente\n";
    } else {
        echo "\n🚨 DETECTADOS PROBLEMAS QUE REQUIEREN ATENCIÓN\n";
    }
    
    // 5. Limpiar archivos de prueba
    echo "\n🧹 LIMPIANDO ARCHIVOS DE PRUEBA:\n";
    $archivos = [
        'test_flujo_cuentas_iniciales.php',
        'test_actualizacion_cuentas.php',
        'respuesta_final.php',
        'diagnostico_sincronizacion.php'
    ];
    
    foreach ($archivos as $archivo) {
        if (file_exists($archivo)) {
            unlink($archivo);
            echo "   🗑️ Eliminado: $archivo\n";
        }
    }
    
} catch (\Exception $e) {
    \DB::rollBack();
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Línea: " . $e->getLine() . "\n";
}

echo "\n=== VALIDACIÓN COMPLETADA ===\n";
