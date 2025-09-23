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

    $stmt = $Con->prepare("SELECT m.id_mezcla, m.folio_m
                            FROM mezclas m
                            WHERE m.id_sede_m = ? AND activo_m = 1  AND estado_m = 0 ORDER BY m.folio_m ASC");
    $stmt->bind_param("i", $sede);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $mezclas = [];

    while ($fila = $resultado->fetch_assoc()) {
        $mezclas[] = [
            'id' => $fila['id_mezcla'],
            'mezcla' => $fila['folio_m']
        ];
    }

    echo json_encode(['status' => 'ok', 'mezclas' => $mezclas]);

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Tipo inválido']);
}

$Con->close();
?>
