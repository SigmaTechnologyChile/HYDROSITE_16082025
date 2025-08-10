<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DIAGNÓSTICO DE TABLAS PARA FOREIGN KEYS ===\n\n";

// Verificar qué tablas existen
$tables = \DB::select('SHOW TABLES');
echo "📋 TABLAS EXISTENTES:\n";
foreach ($tables as $table) {
    $tableName = array_values((array)$table)[0];
    echo "   - $tableName\n";
}

echo "\n🔍 VERIFICANDO NOMBRES ESPECÍFICOS:\n";

// Verificar nombres específicos que podrían causar el error
$checkTables = ['orgs', 'organizations', 'organisaciones', 'organizaciones', 'categorias', 'categories', 'cuentas', 'accounts', 'users', 'usuarios'];

foreach ($checkTables as $tableName) {
    try {
        $result = \DB::select("SHOW TABLES LIKE '$tableName'");
        if (count($result) > 0) {
            echo "   ✅ $tableName - EXISTE\n";
            
            // Mostrar estructura de la tabla
            $columns = \DB::select("DESCRIBE $tableName");
            echo "      Columnas PK: ";
            foreach ($columns as $col) {
                if ($col->Key === 'PRI') {
                    echo "{$col->Field} (PRIMARY), ";
                }
            }
            echo "\n";
        } else {
            echo "   ❌ $tableName - NO EXISTE\n";
        }
    } catch (Exception $e) {
        echo "   ❌ $tableName - ERROR: " . $e->getMessage() . "\n";
    }
}

echo "\n🔧 GENERANDO SCRIPT CORREGIDO:\n";

// Verificar las tablas que realmente existen
$existingTables = [];
foreach (['orgs', 'organizaciones', 'categorias', 'cuentas', 'users'] as $table) {
    $result = \DB::select("SHOW TABLES LIKE '$table'");
    if (count($result) > 0) {
        $existingTables[$table] = true;
        echo "   ✅ $table confirmada\n";
    }
}

echo "\n📝 SCRIPT SQL CORREGIDO:\n";
echo "-- ================================================================\n";
echo "-- FOREIGN KEYS CORREGIDAS SEGÚN TABLAS EXISTENTES\n";
echo "-- ================================================================\n\n";

// Generar script corregido
if (isset($existingTables['organizaciones'])) {
    echo "ALTER TABLE movimientos ADD CONSTRAINT fk_movimientos_org FOREIGN KEY (org_id) REFERENCES organizaciones(id) ON DELETE CASCADE;\n";
} elseif (isset($existingTables['orgs'])) {
    echo "ALTER TABLE movimientos ADD CONSTRAINT fk_movimientos_org FOREIGN KEY (org_id) REFERENCES orgs(id) ON DELETE CASCADE;\n";
} else {
    echo "-- ❌ Tabla de organizaciones no encontrada\n";
}

if (isset($existingTables['categorias'])) {
    echo "ALTER TABLE movimientos ADD CONSTRAINT fk_movimientos_categoria FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL;\n";
} else {
    echo "-- ❌ Tabla categorias no encontrada\n";
}

if (isset($existingTables['cuentas'])) {
    echo "ALTER TABLE movimientos ADD CONSTRAINT fk_movimientos_cuenta_origen FOREIGN KEY (cuenta_origen_id) REFERENCES cuentas(id) ON DELETE SET NULL;\n";
    echo "ALTER TABLE movimientos ADD CONSTRAINT fk_movimientos_cuenta_destino FOREIGN KEY (cuenta_destino_id) REFERENCES cuentas(id) ON DELETE SET NULL;\n";
} else {
    echo "-- ❌ Tabla cuentas no encontrada\n";
}

if (isset($existingTables['users'])) {
    echo "ALTER TABLE movimientos ADD CONSTRAINT fk_movimientos_usuario FOREIGN KEY (usuario_id) REFERENCES users(id) ON DELETE SET NULL;\n";
} else {
    echo "-- ❌ Tabla users no encontrada\n";
}

echo "\n-- ================================================================\n";
