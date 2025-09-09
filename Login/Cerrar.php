<?php
    ob_start();
        session_start();
        include_once 'Session.php';
        include_once 'Conexion.php';
        include_once '../Conexion/BD.php';

        $User = $_SESSION['Name'];
        $Estado = 0;
        
<<<<<<< HEAD
        $stmt = $Con->prepare("UPDATE usuarios SET estado = ? WHERE username = ?");
=======
        $stmt = $Con->prepare("UPDATE usuarios SET estado = ? WHERE user = ?");
>>>>>>> b5226a49ccee15b7388121ff0078837832ff8736
        $stmt->bind_param("is",$Estado,$User);
        $stmt->execute();
        $stmt->close();

        $UserSession = new UserSession();
        $UserSession->closeSession();
        
        header("location: ../index.php");
    ob_end_flush();
?>