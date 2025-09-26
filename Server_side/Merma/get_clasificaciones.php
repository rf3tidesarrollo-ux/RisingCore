<?php
include_once '../../../Conexion/BD.php';

$tipo = $_GET['tipo'] ?? '';

// Mapeo de códigos a nombres
$mapa_tipos = [
    'PRODUCCIÓN-NACIONAL' => 'PRODUCCIÓN-NACIONAL',
    'EMPAQUE-NACIONAL' => 'EMPAQUE-NACIONAL',
    'EMPAQUE-MERMA' => 'EMPAQUE-MERMA',
    'PRODUCCIÓN-MERMA' => 'PRODUCCIÓN-MERMA'
];

if (isset($mapa_tipos[$tipo])) {
    $tipo_nombre = $mapa_tipos[$tipo];

    $stmt = $Con->prepare("SELECT id_merma, motivo FROM clasificacion_merma WHERE tipo_merma = ?");
    $stmt->bind_param("s", $tipo_nombre);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        echo '<option value="0">Seleccione la clasificación:</option>';
        while ($fila = $resultado->fetch_assoc()) {
            echo '<option value="'.$fila['id_merma'].'">'.htmlspecialchars($fila['motivo']).'</option>';
        }
    } else {
        echo '<option value="0">No hay clasificaciones disponibles</option>';
    }

    $stmt->close();
} else {
    echo '<option value="0">Tipo inválido</option>';
}

$Con->close();
?>
