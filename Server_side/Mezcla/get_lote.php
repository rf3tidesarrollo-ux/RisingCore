<?php
include_once '../../../Conexion/BD.php';

$sede = $_GET['sede'] ?? '';
$variedad = $_GET['variedad'] ?? '';

// Mapeo
$mapa_tipos = [
    'RF1' => 1,
    'RF2' => 2,
    'RF3' => 3
];

header('Content-Type: application/json');

if (isset($sede) && isset($variedad)) {
    $sede = $mapa_tipos[$sede];

    $mes_actual = date('n');
    $año_actual = date('Y');

    $año_inicio = ($mes_actual >= 1 && $mes_actual <= 7) ? $año_actual - 1 : $año_actual;
    $año_fin = $año_inicio + 1;
    $ciclo = $año_inicio . '-' . $año_fin;

    $stmt = $Con->prepare("SELECT re.id_registro_r, re.no_serie_r
                            FROM registro_empaque re
                            JOIN tipo_variaciones tv ON re.id_codigo_r = tv.id_variedad
                            JOIN invernaderos m ON tv.id_modulo_v = m.id_invernadero
                            JOIN ciclos c ON tv.id_ciclo_v = c.id_ciclo
                            WHERE m.id_sede_i = ? AND tv.id_nombre_v = ? AND c.ciclo = ? AND activo_r = 1  AND cajas_dis > 0 ORDER BY re.no_serie_r ASC");
    $stmt->bind_param("iis", $sede, $variedad, $ciclo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $variedades = [];

    while ($fila = $resultado->fetch_assoc()) {
        $variedades[] = [
            'id' => $fila['id_registro_r'],
            'lote' => $fila['no_serie_r']
        ];
    }

    echo json_encode(['status' => 'ok', 'variedades' => $variedades]);

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Tipo inválido']);
}

$Con->close();
?>
