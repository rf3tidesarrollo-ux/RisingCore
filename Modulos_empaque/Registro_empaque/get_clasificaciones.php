<?php
include_once '../../Conexion/BD.php';

if (isset($_POST['tipo'])) {
    $tipo = $_POST['tipo'];

    $stmt = $Con->prepare("SELECT id_clasificacion, motivo FROM tipos_clasificacion WHERE tipo_merma = ?");
    $stmt->bind_param("s", $tipo);
    $stmt->execute();
    $result = $stmt->get_result();

    $options = '<option value="">Seleccione la clasificación</option>';
    while ($row = $result->fetch_assoc()) {
        $options .= '<option value="' . $row['id_clasificacion'] . '">' . $row['motivo'] . '</option>';
    }

    echo $options;
}
?>
