<?php
// Script para comparar lecturas entre la base de datos y los valores esperados
// Ejecuta este archivo desde la terminal de VS Code: php comparar_lecturas.php

$host = 'localhost';
$user = 'root'; // Cambia por tu usuario
$pass = '';
$db   = 'hydrosite'; // Cambia por el nombre de tu base

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$esperados = [
    104 => [
        'nro_servicio' => '100',
        'rut' => '6546512-4',
        'full_name' => 'DAVIS GONZÁLEZ ROBERTO GUILLERMO',
        'period' => '2025-08',
        'previous_reading' => 0,
        'current_reading' => 10,
        'cm3' => 10,
        'total' => 0
    ],
    105 => [
        'nro_servicio' => '100216',
        'rut' => '11370744-5',
        'full_name' => 'RIVERA HORMAZABAL FRESIA GUADALUPE',
        'period' => '2025-08',
        'previous_reading' => 0,
        'current_reading' => 13,
        'cm3' => 13,
        'total' => 9500
    ],
    107 => [
        'nro_servicio' => '101',
        'rut' => '7425928-6',
        'full_name' => 'PEREZ MORALES PEDRO',
        'period' => '2025-08',
        'previous_reading' => 0,
        'current_reading' => 12,
        'cm3' => 12,
        'total' => 9000
    ],
    108 => [
        'nro_servicio' => '1017',
        'rut' => '6424377-2',
        'full_name' => 'LUIS ARTURO MIRANDA ROMERO',
        'period' => '2025-08',
        'previous_reading' => 0,
        'current_reading' => 15,
        'cm3' => 15,
        'total' => 10500
    ]
];

$sql = "
SELECT
    r.id,
    s.nro AS nro_servicio,
    m.rut,
    m.full_name,
    r.period,
    r.previous_reading,
    r.current_reading,
    r.cm3,
    r.total
FROM readings r
JOIN services s ON r.service_id = s.id
JOIN members m ON s.member_id = m.id
WHERE r.id IN (104, 105, 107, 108)
ORDER BY r.id ASC
";
$res = $conn->query($sql);

if (!$res) {
    die("Error en la consulta: " . $conn->error);
}

// Mostrar tabla comparativa
$campos = ['nro_servicio','rut','full_name','period','previous_reading','current_reading','cm3','total'];
echo "\n| id | campo | esperado | bd | ok? |\n";
echo str_repeat('-', 70) . "\n";
while ($row = $res->fetch_assoc()) {
    $id = $row['id'];
    foreach ($campos as $campo) {
        $valorEsperado = $esperados[$id][$campo];
        $valorBD = $row[$campo];
        $ok = ($valorEsperado == $valorBD) ? '✔️' : '❌';
        printf("| %3d | %-15s | %-20s | %-20s | %s |\n", $id, $campo, $valorEsperado, $valorBD, $ok);
    }
}
$conn->close();
?>
