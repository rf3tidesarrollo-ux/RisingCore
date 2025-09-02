<?php
include_once '../../Conexion/BD.php';

$tipo = $_GET['tipo'] ?? '';

// Mapeo de códigos a nombres
$mapa_tipos = [
    'RF1' => '1',
    'RF2' => '2',
    'RF3' => '3'
];
echo $mapa_tipos[$tipo];
if (isset($mapa_tipos[$tipo])) {
    $codigo = $mapa_tipos[$tipo];

    $stmt = $Con->prepare("SELECT tv.id_variedad, tv.codigo
                            FROM tipo_variaciones tv
                            JOIN invernaderos m ON tv.id_modulo_v = m.id_invernadero
                            WHERE m.id_sede_i = ?");
    $stmt->bind_param("i", $codigo);
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