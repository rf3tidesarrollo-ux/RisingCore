<?php
include_once '../../Conexion/BD.php';

$tipo = $_GET['tipo'] ?? '';

// Mapeo de códigos a nombres
$mapa_tipos = [
    'RF1' => '1',
    'RF2' => '2',
    'RF3' => '3'
];

// Elimina este echo que puede romper el HTML
// echo $mapa_tipos[$tipo];  

if (isset($mapa_tipos[$tipo])) {
    $codigo = $mapa_tipos[$tipo];

    $mes_actual = date('n');   // 'n' devuelve el mes sin ceros (1 a 12)
    $año_actual = date('Y');

    if ($mes_actual >= 1 && $mes_actual <= 7) {
        $año_inicio = $año_actual - 1;
        $año_fin = $año_actual;
    } else {
        $año_inicio = $año_actual;
        $año_fin = $año_actual + 1;
    }

    $ciclo = $año_inicio . '-' . $año_fin;

    $stmt = $Con->prepare("SELECT tv.id_variedad, tv.codigo
                            FROM tipo_variaciones tv
                            JOIN invernaderos m ON tv.id_modulo_v = m.id_invernadero
                            JOIN ciclos c ON tv.id_ciclo_v = c.id_ciclo
                            WHERE m.id_sede_i = ? AND c.ciclo = ? ORDER BY tv.codigo ASC");
    $stmt->bind_param("is", $codigo, $ciclo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        echo '<option value="0">Seleccione la variedad:</option>';
        while ($fila = $resultado->fetch_assoc()) {
            echo '<option value="'.$fila['id_variedad'].'">'.htmlspecialchars($fila['codigo']).'</option>';
        }
    } else {
        echo '<option value="0">No hay variedades disponibles</option>';
    }

    $stmt->close();
} else {
    echo '<option value="0">Tipo inválido</option>';
}

$Con->close();
?>
