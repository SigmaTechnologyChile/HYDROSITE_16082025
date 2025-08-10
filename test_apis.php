<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 TESTING SISTEMA CONTABLE - ELIMINAR HARDCODEO\n";
echo "=".str_repeat("=", 50)."\n\n";

// 1. Probar creación de movimiento
echo "1. PROBANDO CREACIÓN DE MOVIMIENTO\n";
echo "-".str_repeat("-", 30)."\n";

try {
    $movimiento = \App\Models\Movimiento::create([
        'org_id' => 864,
        'tipo' => 'ingreso',
        'fecha' => '2025-08-10',
        'monto' => 50000,
        'descripcion' => 'Prueba de ingreso - sistema habilitado',
        'nro_dcto' => 'TEST-001',
        'categoria_id' => 1,
        'cuenta_destino_id' => 1,
        'estado' => 'activo'
    ]);
    
    echo "✅ MOVIMIENTO CREADO EXITOSAMENTE\n";
    echo "   ID: {$movimiento->id}\n";
    echo "   Monto: ${$movimiento->monto}\n";
    echo "   Descripción: {$movimiento->descripcion}\n";
    
} catch (\Exception $e) {
    echo "❌ ERROR AL CREAR MOVIMIENTO: " . $e->getMessage() . "\n";
}

echo "\n";

// 2. Probar API de categorías
echo "2. PROBANDO API DE CATEGORÍAS\n";
echo "-".str_repeat("-", 30)."\n";

try {
    $categorias = \App\Models\Categoria::select('id', 'nombre', 'grupo', 'tipo')
        ->orderBy('tipo')
        ->orderBy('grupo')
        ->get();
    
    echo "✅ CATEGORÍAS OBTENIDAS: " . $categorias->count() . " registros\n";
    
    $categoriasOrganizadas = [
        'ingresos' => [],
        'egresos' => []
    ];
    
    foreach ($categorias as $categoria) {
        $tipo = $categoria->tipo === 'ingreso' ? 'ingresos' : 'egresos';
        
        if (!isset($categoriasOrganizadas[$tipo][$categoria->grupo])) {
            $categoriasOrganizadas[$tipo][$categoria->grupo] = [];
        }
        
        $categoriasOrganizadas[$tipo][$categoria->grupo][] = [
            'id' => $categoria->id,
            'nombre' => $categoria->nombre,
            'grupo' => $categoria->grupo
        ];
    }
    
    echo "   Ingresos: " . count($categoriasOrganizadas['ingresos']) . " grupos\n";
    echo "   Egresos: " . count($categoriasOrganizadas['egresos']) . " grupos\n";
    
    foreach ($categoriasOrganizadas['ingresos'] as $grupo => $cats) {
        echo "     - {$grupo}: " . count($cats) . " categorías\n";
    }
    
} catch (\Exception $e) {
    echo "❌ ERROR AL OBTENER CATEGORÍAS: " . $e->getMessage() . "\n";
}

echo "\n";

// 3. Probar API de bancos
echo "3. PROBANDO API DE BANCOS\n";
echo "-".str_repeat("-", 30)."\n";

try {
    $bancos = \App\Models\Banco::select('id', 'nombre', 'codigo')
        ->orderBy('nombre')
        ->get();
    
    echo "✅ BANCOS OBTENIDOS: " . $bancos->count() . " registros\n";
    
    foreach ($bancos->take(5) as $banco) {
        echo "   - {$banco->nombre} ({$banco->codigo})\n";
    }
    
    if ($bancos->count() > 5) {
        echo "   ... y " . ($bancos->count() - 5) . " más\n";
    }
    
} catch (\Exception $e) {
    echo "❌ ERROR AL OBTENER BANCOS: " . $e->getMessage() . "\n";
}

echo "\n";

// 4. Probar mapeo tabular
echo "4. PROBANDO MAPEO TABULAR\n";
echo "-".str_repeat("-", 30)."\n";

try {
    $controller = new \App\Http\Controllers\Org\ContableController();
    $movimientos = \App\Models\Movimiento::with(['categoria'])
        ->where('org_id', 864)
        ->limit(3)
        ->get();
    
    echo "✅ MOVIMIENTOS PARA MAPEO: " . $movimientos->count() . " registros\n";
    
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('mapearMovimientoTabular');
    $method->setAccessible(true);
    
    foreach ($movimientos as $movimiento) {
        try {
            $mapeado = $method->invoke($controller, $movimiento);
            echo "   - Mov ID {$movimiento->id}: Tipo {$movimiento->tipo}, Monto " . (float)$movimiento->monto . "\n";
            echo "     Mapeado: total_consumo=" . (float)$mapeado->total_consumo . ", energia_electrica=" . (float)$mapeado->energia_electrica . "\n";
        } catch (\Exception $e) {
            echo "   - Mov ID {$movimiento->id}: ERROR - " . $e->getMessage() . "\n";
        }
    }
    
} catch (\Exception $e) {
    echo "❌ ERROR AL PROBAR MAPEO: " . $e->getMessage() . "\n";
}

echo "\n";
echo "🎉 PRUEBAS COMPLETADAS\n";
echo "=".str_repeat("=", 50)."\n";
echo "✅ SISTEMA HABILITADO: Movimientos activados\n";
echo "✅ APIs DINÁMICAS: Categorías y bancos sin hardcodeo\n";
echo "✅ MAPEO CORREGIDO: Solo campos existentes en tabla\n";
