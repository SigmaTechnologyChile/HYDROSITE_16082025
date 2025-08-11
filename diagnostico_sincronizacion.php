<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DIAGNÓSTICO DE DESINCRONIZACIÓN ===\n\n";

try {
    // 1. Verificar estado actual de ambas tablas
    echo "📊 VERIFICANDO ESTADO ACTUAL:\n";
    
    $configs = \App\Models\ConfiguracionCuentasIniciales::where('org_id', 1)->get();
    $cuentas = \App\Models\Cuenta::where('org_id', 1)->get();
    
    echo "   configuracion_cuentas_iniciales: " . $configs->count() . " registros\n";
    echo "   cuentas: " . $cuentas->count() . " registros\n\n";
    
    // 2. Detectar desincronizaciones
    echo "🔍 DETECTANDO DESINCRONIZACIONES:\n";
    $errores = [];
    
    foreach ($configs as $config) {
        $cuentaCorrespondiente = $cuentas->where('tipo', $config->tipo_cuenta)->first();
        
        if (!$cuentaCorrespondiente) {
            $errores[] = "❌ Cuenta {$config->tipo_cuenta} existe en configuracion pero NO en cuentas";
            continue;
        }
        
        // Verificar sincronización de datos
        $desincronizada = false;
        $detalles = [];
        
        if ($config->saldo_inicial != $cuentaCorrespondiente->saldo_actual) {
            $desincronizada = true;
            $detalles[] = "Saldo: config={$config->saldo_inicial} vs cuenta={$cuentaCorrespondiente->saldo_actual}";
        }
        
        if ($config->banco_id != $cuentaCorrespondiente->banco_id) {
            $desincronizada = true;
            $detalles[] = "Banco: config={$config->banco_id} vs cuenta={$cuentaCorrespondiente->banco_id}";
        }
        
        if ($config->numero_cuenta != $cuentaCorrespondiente->numero_cuenta) {
            $desincronizada = true;
            $detalles[] = "Número: config='{$config->numero_cuenta}' vs cuenta='{$cuentaCorrespondiente->numero_cuenta}'";
        }
        
        if ($config->responsable != $cuentaCorrespondiente->responsable) {
            $desincronizada = true;
            $detalles[] = "Responsable: config='{$config->responsable}' vs cuenta='{$cuentaCorrespondiente->responsable}'";
        }
        
        if ($desincronizada) {
            $errores[] = "❌ Desincronización en {$config->tipo_cuenta}: " . implode(', ', $detalles);
        } else {
            echo "   ✅ {$config->tipo_cuenta}: SINCRONIZADA\n";
        }
    }
    
    // 3. Verificar cuentas huérfanas
    foreach ($cuentas as $cuenta) {
        $configCorrespondiente = $configs->where('tipo_cuenta', $cuenta->tipo)->first();
        if (!$configCorrespondiente) {
            $errores[] = "❌ Cuenta {$cuenta->tipo} existe en cuentas pero NO en configuracion";
        }
    }
    
    if (empty($errores)) {
        echo "\n🎉 NO SE DETECTARON DESINCRONIZACIONES\n";
    } else {
        echo "\n🚨 ERRORES DETECTADOS:\n";
        foreach ($errores as $error) {
            echo "   $error\n";
        }
        
        // 4. REPARAR AUTOMÁTICAMENTE
        echo "\n🔧 INICIANDO REPARACIÓN AUTOMÁTICA:\n";
        
        \DB::beginTransaction();
        
        foreach ($configs as $config) {
            echo "   📝 Reparando {$config->tipo_cuenta}...\n";
            
            // Obtener nombre del banco
            $nombreBanco = \App\Models\Banco::find($config->banco_id)?->nombre ?? 'Sin banco';
            
            // Actualizar/crear cuenta correspondiente
            $cuenta = \App\Models\Cuenta::updateOrCreate(
                [
                    'org_id' => $config->org_id,
                    'tipo' => $config->tipo_cuenta
                ],
                [
                    'nombre' => ucfirst(str_replace('_', ' ', $config->tipo_cuenta)),
                    'banco_id' => $config->banco_id,
                    'numero_cuenta' => $config->numero_cuenta,
                    'saldo_actual' => $config->saldo_inicial,
                    'responsable' => $config->responsable
                ]
            );
            
            // Actualizar configuración con nombre de banco
            $config->update([
                'nombre_banco' => $nombreBanco,
                'copiado_a_cuentas' => true
            ]);
            
            echo "      ✅ Reparado: {$config->tipo_cuenta}\n";
        }
        
        \DB::commit();
        echo "   🎉 REPARACIÓN COMPLETADA\n";
    }
    
    // 5. Verificación final
    echo "\n📊 VERIFICACIÓN FINAL:\n";
    
    $configsFinales = \App\Models\ConfiguracionCuentasIniciales::where('org_id', 1)->get();
    $cuentasFinales = \App\Models\Cuenta::where('org_id', 1)->get();
    
    foreach ($configsFinales as $config) {
        $cuenta = $cuentasFinales->where('tipo', $config->tipo_cuenta)->first();
        if ($cuenta) {
            $sincronizada = (
                $config->saldo_inicial == $cuenta->saldo_actual && 
                $config->banco_id == $cuenta->banco_id &&
                $config->numero_cuenta == $cuenta->numero_cuenta &&
                $config->responsable == $cuenta->responsable
            );
            $estado = $sincronizada ? "✅ SINCRONIZADA" : "❌ AÚN DESINCRONIZADA";
            echo "   {$config->tipo_cuenta}: $estado\n";
        }
    }
    
} catch (\Exception $e) {
    \DB::rollBack();
    echo "❌ ERROR DURANTE DIAGNÓSTICO: " . $e->getMessage() . "\n";
    echo "Línea: " . $e->getLine() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
}

echo "\n=== FIN DEL DIAGNÓSTICO ===\n";
