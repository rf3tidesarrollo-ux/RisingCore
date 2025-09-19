<?php
    ob_start();
        session_start();
        include_once 'Session.php';
        include_once 'Conexion.php';
        include_once '../Conexion/BD.php';

        $User = $_SESSION['ID'];
        $Estado = 0;
        
        $stmt = $Con->prepare("DELETE FROM mezcla_lotes_temp WHERE usuario_id = ?");
        $stmt->bind_param("i", $User);
        $stmt->execute();
        $stmt->close();

        $stmt = $Con->prepare("DELETE FROM pallet_mezclas_temp WHERE usuario_id = ?");
        $stmt->bind_param("i", $User);
        $stmt->execute();
        $stmt->close();

        $stmt = $Con->prepare("UPDATE usuarios SET estado = ? WHERE username = ?");
        $stmt->bind_param("ii",$Estado,$User);
        $stmt->execute();
        $stmt->close();

        $UserSession = new UserSession();
        $UserSession->closeSession();
        
        header("location: ../index.php");
    ob_end_flush();
?>