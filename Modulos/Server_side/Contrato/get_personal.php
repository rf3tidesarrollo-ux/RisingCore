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

    $stmt = $Con->prepare("SELECT p.id_personal, CONCAT(p.nombre, ' ', p.apellido_p, ' ', p.apellido_m) AS NC
                            FROM rh_personal p WHERE p.id_sede_pl = ? AND p.id_depto_pl = ? AND p.status_pl = 1
                            AND EXISTS (
                                SELECT 1 
                                FROM rh_check c 
                                WHERE c.badge = p.badge
                                LIMIT 1
                            )
                            AND EXISTS (
                                SELECT 1 
                                FROM rh_personal_completo pc 
                                WHERE p.id_personal = pc.id_registro_p
                                LIMIT 1
                            )
                        ORDER BY NC");
    $stmt->bind_param("ii", $sedes, $dep);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $nombres = [];

    while ($fila = $resultado->fetch_assoc()) {
        $nombres[] = [
            'id' => $fila['id_personal'],
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
