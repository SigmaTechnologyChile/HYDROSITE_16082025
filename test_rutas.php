<?php
// Script de prueba para verificar la tabla rutas
require_once 'vendor/autoload.php';

// Cargar Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Probar conexión a base de datos
try {
    $pdo = new PDO('mysql:host=localhost;dbname=hydrosite_db', 'root', '');
    echo "✅ Conexión a base de datos exitosa\n";
    
    // Verificar si tabla rutas existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'rutas'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Tabla 'rutas' existe\n";
        
        // Verificar estructura de la tabla
        $stmt = $pdo->query("DESCRIBE rutas");
        echo "📋 Estructura de la tabla rutas:\n";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "   - {$row['Field']} ({$row['Type']})\n";
        }
        
        // Contar registros
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM rutas");
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        echo "📊 Total de registros en rutas: {$total}\n";
        
    } else {
        echo "❌ Tabla 'rutas' no existe\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n🔗 Para probar el sistema, navega a:\n";
echo "http://localhost/hydrosite/public_html/public/org/[ID_ORG]/panel-control-rutas\n";
echo "\nDonde [ID_ORG] es el ID de una organización válida.\n";
?>
