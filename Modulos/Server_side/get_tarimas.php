<?php
include_once '../../Conexion/BD.php';

$sede = $_GET['sede'] ?? '';

// Mapeo
$mapa_tipos = [
    'RF1' => 1,
    'RF2' => 2,
    'RF3' => 3
];

header('Content-Type: application/json');

if (isset($mapa_tipos[$sede])) {
    $sedes = $mapa_tipos[$sede];

    $stmt = $Con->prepare("SELECT t.id_tarima, t.nombre_tarima
                            FROM tipos_tarimas t
                            WHERE t.id_sede_t = ? ORDER BY t.id_tarima");
    $stmt->bind_param("i", $sedes);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $tarimas = [];

    while ($fila = $resultado->fetch_assoc()) {
        $tarimas[] = [
            'id' => $fila['id_tarima'],
            'tarima' => $fila['nombre_tarima']
        ];
    }

    echo json_encode(['status' => 'ok', 'tarimas' => $tarimas]);

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Tipo inválido']);
}

$Con->close();
?>
