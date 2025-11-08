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

    $stmt = $Con->prepare("SELECT c.id_carro, c.folio_carro
                            FROM tipos_carros c
                            WHERE c.id_sede_c = ? ORDER BY c.id_carro");
    $stmt->bind_param("i", $sedes);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $carros = [];

    while ($fila = $resultado->fetch_assoc()) {
        $carros[] = [
            'id' => $fila['id_carro'],
            'traila' => $fila['folio_carro']
        ];
    }

    echo json_encode(['status' => 'ok', 'carros' => $carros]);

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Tipo inválido']);
}

$Con->close();
?>
