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

    $stmt = $Con->prepare("SELECT h.id_thorario, h.tipo_h
                            FROM rh_tipos_horarios h
                            WHERE h.id_sede_h = ? ORDER BY h.id_thorario");
    $stmt->bind_param("i", $sedes);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $horarios = [];

    while ($fila = $resultado->fetch_assoc()) {
        $horarios[] = [
            'id' => $fila['id_thorario'],
            'horario' => $fila['tipo_h']
        ];
    }

    echo json_encode(['status' => 'ok', 'horarios' => $horarios]);

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Tipo inválido']);
}

$Con->close();
?>
