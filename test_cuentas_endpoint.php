<?php
// Test script para verificar el endpoint de cuentas iniciales
// Ejecutar desde: http://localhost/hydrosite/public_html/test_cuentas_endpoint.php

// Headers para debug
header('Content-Type: text/html; charset=utf-8');
echo "<h1>🧪 Test del Endpoint de Cuentas Iniciales</h1>";

// Datos de prueba
$testData = [
    'orgId' => 864,
    '_token' => 'test-token',
    'saldo_caja_general' => 1000,
    'saldo_cta_corriente_1' => 5000,
    'banco_cta_corriente_1' => '1', // ID de banco válido
    'numero_cta_corriente_1' => '123456789',
    'saldo_cuenta_ahorro' => 3000,
    'banco_cuenta_ahorro' => '2', // ID de banco válido
    'numero_cuenta_ahorro' => '987654321',
    'responsable_caja_general' => 'Juan Pérez',
    'responsable_cta_corriente_1' => 'Juan Pérez',
    'responsable_cuenta_ahorro' => 'Juan Pérez'
];

echo "<h2>📤 Datos que se enviarían:</h2>";
echo "<pre>" . print_r($testData, true) . "</pre>";

// Verificar ruta
$url = "http://localhost/hydrosite/public_html/public/org/864/cuentas-iniciales";
echo "<h2>🌐 URL del endpoint:</h2>";
echo "<code>$url</code>";

// Verificar controlador
$controllerPath = __DIR__ . '/app/Http/Controllers/Org/ContableController.php';
echo "<h2>📁 Archivo del controlador:</h2>";
if (file_exists($controllerPath)) {
    echo "✅ Existe: $controllerPath";
} else {
    echo "❌ No existe: $controllerPath";
}

// Verificar base de datos (si tienes acceso)
echo "<h2>🗄️ Verificar tabla configuracion_cuentas_iniciales:</h2>";
try {
    $pdo = new PDO('mysql:host=localhost;dbname=hydrosite_db', 'root', '');
    $stmt = $pdo->query("DESCRIBE configuracion_cuentas_iniciales");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "✅ Tabla existe. Columnas:<br>";
    foreach ($columns as $column) {
        echo "- {$column['Field']} ({$column['Type']})<br>";
    }
} catch (PDOException $e) {
    echo "❌ Error conectando a BD: " . $e->getMessage();
}

echo "<h2>🔧 Instrucciones:</h2>";
echo "<ol>";
echo "<li>Abre las herramientas de desarrollador (F12)</li>";
echo "<li>Ve a la pestaña 'Console'</li>";
echo "<li>Llena el formulario y haz clic en 'Guardar Cuentas Iniciales'</li>";
echo "<li>Observa los mensajes de debug en la consola</li>";
echo "<li>Si hay errores, cópialos y compártelos</li>";
echo "</ol>";
?>
