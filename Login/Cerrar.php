<?php
    ob_start();
        session_start();
        include_once 'Session.php';
        include_once 'Conexion.php';
        include_once '../Conexion/BD.php';

        $User = $_SESSION['ID'];

        $stmt = $Con->prepare("UPDATE usuarios SET estado = 0 WHERE id_usuario = ?");
        $stmt->bind_param("i",$User);
        if ($stmt->execute()) {
            $UserSession = new UserSession();
            $UserSession->closeSession();
            
            header("location: ../index.php");
        } else{
            alert("Error al cerrar sesión. Por favor, inténtalo de nuevo.");
        }
        $stmt->close();

    ob_end_flush();
?>