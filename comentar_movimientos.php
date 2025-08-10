<?php
/**
 * Script para comentar referencias al modelo Movimiento eliminado
 * Evita errores después de eliminar la tabla movimientos
 */

$filesToProcess = [
    'C:\xampp\htdocs\hydrosite\public_html\app\Http\Controllers\Org\ContableController.php',
    'C:\xampp\htdocs\hydrosite\public_html\app\Http\Controllers\MovimientoController.php',
    'C:\xampp\htdocs\hydrosite\public_html\app\Http\Controllers\ConciliacionController.php'
];

function commentMovimientoReferences($filePath) {
    if (!file_exists($filePath)) {
        echo "❌ Archivo no encontrado: $filePath\n";
        return false;
    }

    $content = file_get_contents($filePath);
    $originalContent = $content;
    
    // Comentar use statements
    $content = preg_replace('/^use App\\\\Models\\\\Movimiento;$/m', '// use App\\Models\\Movimiento; // COMENTADO: tabla eliminada', $content);
    
    // Comentar referencias a \App\Models\Movimiento::
    $content = preg_replace('/(\s*)(\$movimientos?\s*=\s*\\\\App\\\\Models\\\\Movimiento::.*?;)/s', '$1// $2 // COMENTADO: tabla eliminada', $content);
    
    // Comentar otras referencias
    $content = preg_replace('/(\s*)(.*\\\\App\\\\Models\\\\Movimiento::.*?;)/s', '$1// $2 // COMENTADO: tabla eliminada', $content);
    
    if ($content !== $originalContent) {
        file_put_contents($filePath, $content);
        echo "✅ Procesado: " . basename($filePath) . "\n";
        return true;
    } else {
        echo "ℹ️  Sin cambios: " . basename($filePath) . "\n";
        return false;
    }
}

echo "=== COMENTANDO REFERENCIAS A MODELO MOVIMIENTO ===\n\n";

foreach ($filesToProcess as $file) {
    commentMovimientoReferences($file);
}

echo "\n=== PROCESO COMPLETADO ===\n";
echo "Se han comentado las referencias al modelo Movimiento eliminado.\n";
echo "Los controladores ahora deberían funcionar sin errores.\n";
?>
