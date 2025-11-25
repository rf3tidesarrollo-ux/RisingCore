<?php
include_once '../../../Conexion/BD.php';
include_once "../../../Login/validar_sesion.php";

if (isset($ID)) {

    // Validar que el ID sea positivo
    $stmt = $Con->prepare("DELETE FROM cp_requisicion_temp WHERE id_usuario_t = ?");
    $stmt->bind_param("i", $ID);
    if ($stmt->execute()) {
        echo "ok";
    } else {
        echo "error";
    }
    $stmt->close();
    
} else {
    echo "Solicitud no válida";
}
?>
