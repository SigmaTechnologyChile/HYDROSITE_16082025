<?php

// Script más específico para corregir $active en nice.blade.php
$layoutFile = 'resources/views/layouts/nice.blade.php';

if (file_exists($layoutFile)) {
    $lines = file($layoutFile);
    $modified = false;
    
    for ($i = 0; $i < count($lines); $i++) {
        $line = $lines[$i];
        
        // Si la línea contiene $active == pero NO contiene isset($active)
        if (strpos($line, '$active ==') !== false && strpos($line, 'isset($active)') === false) {
            // Reemplazar $active == con (isset($active) && $active ==
            $newLine = preg_replace('/\$active\s*(==)/', '(isset($active) && $active $1', $line);
            
            // Agregar paréntesis de cierre después del operador ternario
            $newLine = preg_replace('/(\?\s*[\'"][^\'"]*[\'"])\s*:\s*([\'"][^\'"]*[\'"])\s*\}\}/', '$1) : $2 }}', $newLine);
            
            if ($newLine !== $line) {
                $lines[$i] = $newLine;
                $modified = true;
                echo "Línea " . ($i + 1) . " corregida\n";
            }
        }
    }
    
    if ($modified) {
        file_put_contents($layoutFile, implode('', $lines));
        echo "✅ Archivo actualizado con éxito\n";
    } else {
        echo "ℹ️ No se encontraron líneas para corregir\n";
    }
} else {
    echo "❌ No se encontró el archivo $layoutFile\n";
}

echo "Script completado.\n";
?>
