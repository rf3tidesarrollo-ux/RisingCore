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

    $stmt = $Con->prepare("SELECT d.id_destino, d.folio_d
                            FROM destinos_embarque d
                            WHERE d.id_sede_d = ? ORDER BY d.folio_d");
    $stmt->bind_param("i", $sedes);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $destinos = [];

    while ($fila = $resultado->fetch_assoc()) {
        $destinos[] = [
            'id' => $fila['id_destino'],
            'destino' => $fila['folio_d']
        ];
    }

    echo json_encode(['status' => 'ok', 'destinos' => $destinos]);

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Tipo inválido']);
}

$Con->close();
?>
