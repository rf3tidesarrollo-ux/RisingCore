<?php
include_once '../../../Conexion/BD.php';

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

    $stmt = $Con->prepare("SELECT id_ruta, ruta
                            FROM rh_rutas
                            WHERE id_sede_r = ? ORDER BY ruta");
    $stmt->bind_param("i", $sedes);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $rutas = [];

    while ($fila = $resultado->fetch_assoc()) {
        $rutas[] = [
            'id' => $fila['id_ruta'],
            'ruta' => $fila['ruta']
        ];
    }

    echo json_encode(['status' => 'ok', 'rutas' => $rutas]);

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Tipo inválido']);
}

$Con->close();
?>
