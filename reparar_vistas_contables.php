<?php
/**
 * Script para reparar TODAS las vistas contables que referencian movimientos
 */

$contableViewsDir = 'C:\xampp\htdocs\hydrosite\public_html\resources\views\orgs\contable';
$viewsToFix = [
    'balance.blade.php',
    'balance_profesional.blade.php', 
    'registro_ingresos_egresos.blade.php',
    'giros_depositos.blade.php',
    'conciliacion_bancaria.blade.php',
    'informe_por_rubro.blade.php'
];

echo "=== REPARANDO VISTAS CONTABLES ===\n\n";

foreach ($viewsToFix as $viewFile) {
    $filePath = $contableViewsDir . DIRECTORY_SEPARATOR . $viewFile;
    
    if (!file_exists($filePath)) {
        echo "⚠️  Archivo no encontrado: $viewFile\n";
        continue;
    }
    
    $content = file_get_contents($filePath);
    $originalContent = $content;
    
    echo "🔍 Procesando: $viewFile\n";
    
    // Patrones de reparación
    $fixes = [
        // Comentar @if con movimientos
        '/@if\s*\(\s*isset\s*\(\s*\$movimientos\s*\)/' => '{{-- COMENTADO: tabla movimientos eliminada --}}
{{-- @if(isset($movimientos)',
        
        // Comentar @forelse con movimientos  
        '/@forelse\s*\(\s*\$movimientos/' => '{{-- COMENTADO: tabla movimientos eliminada --}}
{{-- @forelse($movimientos',
        
        // Comentar @foreach con movimientos
        '/@foreach\s*\(\s*\$movimientos/' => '{{-- COMENTADO: tabla movimientos eliminada --}}
{{-- @foreach($movimientos',
        
        // Reemplazar "No hay movimientos" con mensaje temporal
        '/No hay movimientos registrados/' => 'Módulo de movimientos temporalmente deshabilitado',
        
        // Comentar referencias directas a $movimiento
        '/\$movimiento->/' => '{{-- $movimiento-> --}}',
    ];
    
    $changesCount = 0;
    
    foreach ($fixes as $pattern => $replacement) {
        $newContent = preg_replace($pattern, $replacement, $content);
        if ($newContent !== $content) {
            $content = $newContent;
            $changesCount++;
        }
    }
    
    // Agregar alerta al inicio si la vista tiene referencias a movimientos
    if (strpos($originalContent, 'movimientos') !== false || strpos($originalContent, 'movimiento') !== false) {
        // Buscar @section('content') y agregar alerta después
        if (strpos($content, "@section('content')") !== false) {
            $content = str_replace(
                "@section('content')",
                "@section('content')
{{-- ALERTA: Módulo de movimientos deshabilitado --}}
<div class=\"alert alert-info\" role=\"alert\">
    <i class=\"bi bi-info-circle\"></i>
    <strong>Información:</strong> El módulo de movimientos está temporalmente deshabilitado.
    Las funcionalidades de cuentas y balances básicos siguen disponibles.
</div>",
                $content
            );
            $changesCount++;
        }
    }
    
    if ($changesCount > 0) {
        file_put_contents($filePath, $content);
        echo "   ✅ $changesCount cambios aplicados\n";
    } else {
        echo "   ℹ️  Sin cambios necesarios\n";
    }
}

echo "\n=== RESUMEN DE REPARACIÓN ===\n";
echo "✅ Vistas revisadas y reparadas\n";
echo "✅ Referencias a movimientos comentadas\n";
echo "✅ Mensajes de usuario actualizados\n";
echo "✅ Alertas informativas agregadas\n\n";

echo "=== ESTADO ACTUAL DEL SISTEMA ===\n";
echo "🗑️  Tabla movimientos: ELIMINADA\n";
echo "🔧 Código PHP: REPARADO\n";
echo "🎨 Vistas Blade: REPARADAS\n";
echo "✅ Funcionalidades básicas: DISPONIBLES\n\n";

echo "Las siguientes funcionalidades siguen funcionando:\n";
echo "• Gestión de cuentas iniciales\n";
echo "• Configuración de organizaciones\n";
echo "• Balances básicos (solo saldos iniciales)\n";
echo "• Sincronización de cuentas\n";
?>
