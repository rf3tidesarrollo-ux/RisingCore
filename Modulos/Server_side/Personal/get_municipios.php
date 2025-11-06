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

    $stmt = $Con->prepare("SELECT id_ubicacion, municipio
                            FROM rh_ubicaciones
                            WHERE id_sede_u = ? ORDER BY municipio");
    $stmt->bind_param("i", $sedes);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $municipios = [];

    while ($fila = $resultado->fetch_assoc()) {
        $municipios[] = [
            'id' => $fila['id_ubicacion'],
            'municipio' => $fila['municipio']
        ];
    }

    echo json_encode(['status' => 'ok', 'municipios' => $municipios]);

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Tipo inválido']);
}

$Con->close();
?>
