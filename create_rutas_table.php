<?php
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Configurar Eloquent sin Laravel
$capsule = new Capsule;

$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'hydrosite_db',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

try {
    // Verificar si la tabla ya existe
    $pdo = $capsule->getConnection()->getPdo();
    $stmt = $pdo->query("SHOW TABLES LIKE 'rutas'");
    
    if ($stmt->rowCount() > 0) {
        echo "La tabla 'rutas' ya existe.\n";
    } else {
        // Crear la tabla
        $sql = "
        CREATE TABLE rutas (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            org_id BIGINT UNSIGNED NOT NULL,
            location_id BIGINT UNSIGNED NOT NULL,
            service_id BIGINT UNSIGNED NOT NULL,
            orden INT NOT NULL DEFAULT 1,
            created_at TIMESTAMP NULL DEFAULT NULL,
            updated_at TIMESTAMP NULL DEFAULT NULL,
            INDEX idx_org_location (org_id, location_id),
            INDEX idx_location_orden (location_id, orden),
            UNIQUE KEY rutas_unique_service (org_id, location_id, service_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        $pdo->exec($sql);
        echo "Tabla 'rutas' creada exitosamente.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
