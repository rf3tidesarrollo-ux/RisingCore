<?php
include_once '../../Conexion/BD.php';

$tipo = $_GET['tipo'] ?? '';

// Mapeo de códigos a nombres
$mapa_tipos = [
    'A' => '1',
    'B' => '2',
    'C' => '3'
];

if (isset($mapa_tipos[$tipo])) {
    $nave = $mapa_tipos[$tipo];

    $stmt = $Con->prepare("SELECT id_invernadero, invernadero FROM invernaderos WHERE id_sede_i = ?");
    $stmt->bind_param("i", $nave);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        echo '<option value="0">Seleccione la nave:</option>';
        while ($fila = $resultado->fetch_assoc()) {
            echo '<option value="'.$fila['id_invernadero'].'">'.htmlspecialchars($fila['invernadero']).'</option>';
        }
    } else {
        echo '<option value="0">No hay naves disponibles</option>';
    }

    $stmt->close();
} else {
    echo '<option value="0">Tipo inválido</option>';
}

$Con->close();
?>