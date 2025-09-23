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

if (isset($sede)) {
    $sede = $mapa_tipos[$sede];

    $stmt = $Con->prepare("SELECT l.id_linea, l.linea
                            FROM empaque_lineas l
                            WHERE l.id_sede_l = ? ORDER BY l.linea ASC");
    $stmt->bind_param("i", $sede);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $lineas = [];

    while ($fila = $resultado->fetch_assoc()) {
        $lineas[] = [
            'id' => $fila['id_linea'],
            'linea' => $fila['linea']
        ];
    }

    echo json_encode(['status' => 'ok', 'lineas' => $lineas]);

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Tipo inválido']);
}

$Con->close();
?>
