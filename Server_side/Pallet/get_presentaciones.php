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

    $stmt = $Con->prepare("SELECT p.id_presentacion_p, p.presentacion
                            FROM presentaciones_pallet p
                            WHERE p.sede_id = ? ORDER BY p.presentacion ASC");
    $stmt->bind_param("i", $sedes);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $presentaciones = [];

    while ($fila = $resultado->fetch_assoc()) {
        $presentaciones[] = [
            'id' => $fila['id_presentacion_p'],
            'presentacion' => $fila['presentacion']
        ];
    }

    echo json_encode(['status' => 'ok', 'presentaciones' => $presentaciones]);

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Tipo inválido']);
}

$Con->close();
?>
