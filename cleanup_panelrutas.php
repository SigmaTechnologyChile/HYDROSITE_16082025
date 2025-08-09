<?php
// Script para limpiar archivos blade no utilizados del panel de control de rutas

$archivosParaEliminar = [
    'c:\xampp\htdocs\hydrosite\public_html\resources\views\orgs\locations\panelcontrolrutas.blade.php',
    'c:\xampp\htdocs\hydrosite\public_html\resources\views\orgs\locations\panelcontrolrutas_limpio.blade.php',
    'c:\xampp\htdocs\hydrosite\public_html\app\Http\Controllers\Org\panelcontrolrutas.blade.php'
];

echo "🧹 Limpiando archivos blade no utilizados...\n\n";

foreach ($archivosParaEliminar as $archivo) {
    if (file_exists($archivo)) {
        if (unlink($archivo)) {
            echo "✅ Eliminado: " . basename($archivo) . "\n";
        } else {
            echo "❌ Error al eliminar: " . basename($archivo) . "\n";
        }
    } else {
        echo "ℹ️ No existe: " . basename($archivo) . "\n";
    }
}

echo "\n🎯 Archivo mantenido: panelcontrolrutas_simple.blade.php\n";
echo "📁 Ubicación: resources/views/orgs/locations/panelcontrolrutas_simple.blade.php\n";

// Verificar que el archivo principal existe
$archivoActivo = 'c:\xampp\htdocs\hydrosite\public_html\resources\views\orgs\locations\panelcontrolrutas_simple.blade.php';
if (file_exists($archivoActivo)) {
    echo "✅ Confirmado: El archivo principal está disponible\n";
    echo "📊 Tamaño: " . number_format(filesize($archivoActivo)) . " bytes\n";
} else {
    echo "⚠️ ADVERTENCIA: El archivo principal no se encuentra!\n";
}

echo "\n🚀 Limpieza completada!\n";
?>
