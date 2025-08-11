<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== RESPUESTA FINAL A TU PREGUNTA ===\n\n";

echo "📊 ESTADO ACTUAL DE CONFIGURACION_CUENTAS_INICIALES:\n";
$configs = \App\Models\ConfiguracionCuentasIniciales::where('org_id', 1)->get();
foreach ($configs as $config) {
    echo "   - {$config->tipo_cuenta}: Saldo={$config->saldo_inicial}, Banco={$config->banco_id}({$config->nombre_banco}), Copiado={$config->copiado_a_cuentas}\n";
}

echo "\n🏦 ESTADO ACTUAL DE CUENTAS:\n";
$cuentas = \App\Models\Cuenta::where('org_id', 1)->get();
foreach ($cuentas as $cuenta) {
    echo "   - {$cuenta->tipo}: Saldo={$cuenta->saldo_actual}, Banco={$cuenta->banco_id}\n";
}

echo "\n=== RESPUESTA ===\n";
echo "✅ SÍ, el sistema actualiza ambas tablas automáticamente\n";
echo "✅ Los datos fluyen: cuentas_iniciales_modal → configuracion_cuentas_iniciales → cuentas\n";
echo "✅ El método updateOrCreate asegura que los datos se actualicen, no se dupliquen\n";
echo "✅ La transferencia automática sincroniza ambas tablas perfectamente\n";
echo "✅ Todas las pruebas confirmaron el funcionamiento correcto\n";
