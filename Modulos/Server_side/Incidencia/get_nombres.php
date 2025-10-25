<?php
include_once '../../../Conexion/BD.php';

$sede = $_GET['sede'] ?? '';
$dep = $_GET['dep'] ?? '';

// Mapeo
$mapa_tipos = [
    'RF1' => 1,
    'RF2' => 2,
    'RF3' => 3
];

header('Content-Type: application/json');

if (isset($mapa_tipos[$sede]) && isset($dep)) {
    $sedes = $mapa_tipos[$sede];

    $stmt = $Con->prepare("SELECT badge, CONCAT(nombre, ' ', apellido_p, ' ', apellido_m) AS NC FROM rh_personal
                            WHERE id_sede_pl = ? AND id_depto_pl = ? AND status_pl = 1 ORDER BY NC");
    $stmt->bind_param("ii", $sedes, $dep);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $nombres = [];

    while ($fila = $resultado->fetch_assoc()) {
        $nombres[] = [
            'id' => $fila['badge'],
            'nombre' => $fila['NC']
        ];
    }

    echo json_encode(['status' => 'ok', 'nombres' => $nombres]);

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Tipo inválido']);
}

$Con->close();
?>
