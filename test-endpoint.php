<?php
// Archivo de prueba simple para el endpoint
echo "<h3>Prueba del endpoint clientes-por-sector</h3>";

// Probar con diferentes IDs de sector
$sectores = [1, 2, 3, 4, 5];

foreach ($sectores as $sectorId) {
    echo "<h4>Sector ID: $sectorId</h4>";
    
    $url = "http://localhost:8000/ajax/clientes-por-sector/$sectorId";
    echo "<p><strong>URL:</strong> $url</p>";
    
    // Usar cURL para hacer la petición
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    echo "<p><strong>HTTP Code:</strong> $httpCode</p>";
    
    if ($error) {
        echo "<p style='color: red;'><strong>Error:</strong> $error</p>";
    } else {
        echo "<p><strong>Response:</strong></p>";
        echo "<pre style='background: #f5f5f5; padding: 10px; border-radius: 5px;'>";
        if ($httpCode == 200) {
            $data = json_decode($response, true);
            if ($data !== null) {
                echo htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT));
            } else {
                echo htmlspecialchars($response);
            }
        } else {
            echo htmlspecialchars($response);
        }
        echo "</pre>";
    }
    
    echo "<hr>";
}
?>
