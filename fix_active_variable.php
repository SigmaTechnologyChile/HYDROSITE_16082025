<?php

// Script para corregir todas las referencias a $active en nice.blade.php
$layoutFile = 'resources/views/layouts/nice.blade.php';

if (file_exists($layoutFile)) {
    $content = file_get_contents($layoutFile);
    
    // Patrón para encontrar todas las referencias a $active sin isset()
    // Excepto la línea que ya tiene isset($active) && 
    $pattern = '/\{\{\s*\$active\s*(==|===)\s*([\'"][^\'"]+[\'"])\s*\?\s*([\'"][^\'"]*[\'"])\s*:\s*([\'"][^\'"]*[\'"])\s*\}\}/';
    
    // Reemplazo para agregar isset($active) &&
    $replacement = '{{ (isset($active) && $active $1 $2) ? $3 : $4 }}';
    
    $newContent = preg_replace($pattern, $replacement, $content);
    
    // También corregir casos donde hay espacios adicionales
    $pattern2 = '/\{\{\s*\(isset\(\$active\)\s*&&\s*\$active\s*(==|===)\s*([\'"][^\'"]+[\'"])\)\s*\?\s*([\'"][^\'"]*[\'"])\s*:\s*([\'"][^\'"]*[\'"])\s*\}\}/';
    
    // Verificar si se hicieron cambios
    $changes = substr_count($content, '$active ==') - substr_count($newContent, '$active ==');
    
    if ($changes > 0) {
        file_put_contents($layoutFile, $newContent);
        echo "✅ Se corrigieron $changes referencias a \$active en $layoutFile\n";
    } else {
        echo "ℹ️ No se encontraron cambios necesarios en $layoutFile\n";
    }
} else {
    echo "❌ No se encontró el archivo $layoutFile\n";
}

echo "Script completado.\n";
?>
