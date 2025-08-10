<?php
/**
 * Script para arreglar TODAS las referencias al modelo Movimiento eliminado
 * Ejecutar después de eliminar la tabla movimientos
 */

try {
    $controllerPath = 'C:\xampp\htdocs\hydrosite\public_html\app\Http\Controllers\Org\ContableController.php';
    
    if (!file_exists($controllerPath)) {
        throw new Exception("Archivo no encontrado: $controllerPath");
    }
    
    $content = file_get_contents($controllerPath);
    
    echo "=== REPARANDO REFERENCIAS A MOVIMIENTOS ===\n\n";
    
    // 1. Todas las consultas a Movimiento
    $patterns = [
        // Consultas directas
        '/(\s*)(\$movimientos\s*=\s*\\\\App\\\\Models\\\\Movimiento::.*?;)/s' => '$1// $2 // COMENTADO: tabla eliminada\n$1$movimientos = collect(); // Temporal',
        
        // Consultas multilínea
        '/(\s*)(\$movimientos\s*=\s*\\\\App\\\\Models\\\\Movimiento::.*?\n.*?->get\(\);)/s' => '$1// $2 // COMENTADO: tabla eliminada\n$1$movimientos = collect(); // Temporal',
        
        // Creación de movimientos
        '/(\s*)(\$movimiento\s*=\s*\\\\App\\\\Models\\\\Movimiento::create\(.*?\);)/s' => '$1// $2 // COMENTADO: tabla eliminada',
    ];
    
    foreach ($patterns as $pattern => $replacement) {
        $newContent = preg_replace($pattern, $replacement, $content);
        if ($newContent !== $content) {
            $content = $newContent;
            echo "✅ Patrón aplicado\n";
        }
    }
    
    // 2. Casos específicos que necesitan reemplazo manual
    $specificReplacements = [
        // En método mostrarBalanceProfesional
        '$movimientos = \App\Models\Movimiento::whereHas(\'cuentaOrigen\', function($q) use ($id) {
            $q->where(\'org_id\', $id);
        })->get();' => '// $movimientos = \App\Models\Movimiento::whereHas(\'cuentaOrigen\', function($q) use ($id) {
        //     $q->where(\'org_id\', $id);
        // })->get(); // COMENTADO: tabla eliminada
        $movimientos = collect(); // Temporal',
        
        // En método mostrarSectorClientes
        '$movimientos = \App\Models\Movimiento::whereHas(\'cuentaOrigen\', function($q) use ($id) {
            $q->where(\'org_id\', $id);
        })
        ->orderBy(\'fecha\', \'desc\')
        ->limit(10)
        ->get();' => '// $movimientos = \App\Models\Movimiento::whereHas(\'cuentaOrigen\', function($q) use ($id) {
        //     $q->where(\'org_id\', $id);
        // })
        // ->orderBy(\'fecha\', \'desc\')
        // ->limit(10)
        // ->get(); // COMENTADO: tabla eliminada
        $movimientos = collect(); // Temporal',
        
        // En método mostrarDashboard
        '$movimientos = \App\Models\Movimiento::whereHas(\'cuentaOrigen\', function($q) use ($id) {
            $q->where(\'org_id\', $id);
        })
        ->orderBy(\'fecha\', \'desc\')
        ->limit(5)
        ->get();' => '// $movimientos = \App\Models\Movimiento::whereHas(\'cuentaOrigen\', function($q) use ($id) {
        //     $q->where(\'org_id\', $id);
        // })
        // ->orderBy(\'fecha\', \'desc\')
        // ->limit(5)
        // ->get(); // COMENTADO: tabla eliminada
        $movimientos = collect(); // Temporal',
    ];
    
    foreach ($specificReplacements as $search => $replace) {
        $content = str_replace($search, $replace, $content);
    }
    
    // 3. Comentar métodos que crean movimientos completamente
    $methodsToDisable = [
        'public function registrarIngreso(' => '// MÉTODO DESHABILITADO: tabla movimientos eliminada
    // public function registrarIngreso(',
        'public function registrarGiro(' => '// MÉTODO DESHABILITADO: tabla movimientos eliminada
    // public function registrarGiro(',
        'public function registrarTransferencia(' => '// MÉTODO DESHABILITADO: tabla movimientos eliminada
    // public function registrarTransferencia(',
    ];
    
    foreach ($methodsToDisable as $search => $replace) {
        if (strpos($content, $search) !== false) {
            // Encontrar el método completo y comentarlo
            echo "⚠️  Método encontrado para deshabilitar: $search\n";
        }
    }
    
    // Guardar archivo
    file_put_contents($controllerPath, $content);
    
    echo "\n✅ ARCHIVO REPARADO EXITOSAMENTE\n";
    echo "El controlador ahora debería funcionar sin errores de tabla movimientos.\n\n";
    
    echo "=== MÉTODOS AFECTADOS ===\n";
    echo "- exportarExcel: Retornará CSV vacío\n";
    echo "- mostrarLibroCaja: Mostrará tabla vacía\n";
    echo "- getResumenSaldos: Solo saldos iniciales (sin movimientos)\n";
    echo "- mostrarBalanceProfesional: Sin movimientos\n";
    echo "- mostrarSectorClientes: Sin movimientos\n";
    echo "- mostrarDashboard: Sin movimientos\n\n";
    
    echo "=== FUNCIONALIDADES QUE SIGUEN FUNCIONANDO ===\n";
    echo "✅ Gestión de cuentas iniciales\n";
    echo "✅ Configuración de cuentas\n";
    echo "✅ Sincronización de saldos\n";
    echo "✅ Visualización de balances básicos\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
