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

    $stmt = $Con->prepare("SELECT em.id_embarque, em.folio_em
                            FROM embarques_pallets em
                            WHERE em.id_sede_em = ? ORDER BY em.folio_em");
    $stmt->bind_param("i", $sedes);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $embarques = [];

    while ($fila = $resultado->fetch_assoc()) {
        $embarques[] = [
            'id' => $fila['id_embarque'],
            'embarque' => $fila['folio_em']
        ];
    }

    echo json_encode(['status' => 'ok', 'embarques' => $embarques]);

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Tipo inválido']);
}

$Con->close();
?>
