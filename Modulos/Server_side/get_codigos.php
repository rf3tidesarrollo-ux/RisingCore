<?php
include_once '../../Conexion/BD.php';

$tipo = $_GET['tipo'] ?? '';

// Mapeo
$mapa_tipos = [
    'RF1' => 1,
    'RF2' => 2,
    'RF3' => 3
];

header('Content-Type: application/json');

if (isset($mapa_tipos[$tipo])) {
    $codigo = $mapa_tipos[$tipo];

    $mes_actual = date('n');
    $año_actual = date('Y');

    $año_inicio = ($mes_actual >= 1 && $mes_actual <= 7) ? $año_actual - 1 : $año_actual;
    $año_fin = $año_inicio + 1;
    $ciclo = $año_inicio . '-' . $año_fin;

    $stmt = $Con->prepare("SELECT tv.id_variedad, tv.codigo
                            FROM tipo_variaciones tv
                            JOIN invernaderos m ON tv.id_modulo_v = m.id_invernadero
                            JOIN ciclos c ON tv.id_ciclo_v = c.id_ciclo
                            WHERE m.id_sede_i = ? AND c.ciclo = ? ORDER BY tv.codigo ASC");
    $stmt->bind_param("is", $codigo, $ciclo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $variedades = [];

    while ($fila = $resultado->fetch_assoc()) {
        $variedades[] = [
            'id' => $fila['id_variedad'],
            'codigo' => $fila['codigo']
        ];
    }

    echo json_encode(['status' => 'ok', 'variedades' => $variedades]);

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Tipo inválido']);
}

$Con->close();
?>
