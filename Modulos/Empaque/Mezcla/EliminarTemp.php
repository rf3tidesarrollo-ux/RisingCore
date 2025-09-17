<?php
include_once '../../../Conexion/BD.php';
include_once "../../../Login/validar_sesion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $idTemp = intval($_POST['id']);

    // Validar que el ID sea positivo
    if ($idTemp > 0) {
        $stmt = $Con->prepare("DELETE FROM mezcla_lotes_temp WHERE id_mezcla_temp = ? AND usuario_id = ?");
        $stmt->bind_param("ii", $idTemp, $ID);
        if ($stmt->execute()) {
            echo "ok";
        } else {
            echo "error";
        }
        $stmt->close();
    } else {
        echo "id inválido";
    }
} else {
    echo "Solicitud no válida";
}
?>
