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

    $stmt = $Con->prepare("SELECT c.id_caja, c.tipo_caja
                            FROM tipos_cajas c
                            WHERE c.id_sede_c = ? ORDER BY c.tipo_caja");
    $stmt->bind_param("i", $sedes);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $cajas = [];

    while ($fila = $resultado->fetch_assoc()) {
        $cajas[] = [
            'id' => $fila['id_caja'],
            'caja' => $fila['tipo_caja']
        ];
    }

    echo json_encode(['status' => 'ok', 'cajas' => $cajas]);

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Tipo inválido']);
}

$Con->close();
?>
