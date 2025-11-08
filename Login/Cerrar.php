<?php
    ob_start();
        session_start();
        include_once 'Session.php';
        include_once 'Conexion.php';
        include_once '../Conexion/BD.php';

        $User = $_SESSION['ID'] ?? null;

        if ($User) {
            // 🔹 Poner al usuario como inactivo y eliminar el id_session
            $stmt = $Con->prepare("UPDATE usuarios SET estado = 0, id_session = NULL WHERE id_usuario = ?");
            $stmt->bind_param("i", $User);

            if ($stmt->execute()) {
                // 🔹 Cerrar sesión PHP
                if (class_exists('UserSession')) {
                    $UserSession = new UserSession();
                    $UserSession->closeSession();
                }

                session_unset();
                session_destroy();

                header("Location: ../index.php");
                exit;
            } else {
                echo "<script>alert('⚠️ Error al cerrar sesión. Por favor, inténtalo de nuevo.');</script>";
            }

            $stmt->close();
        } else {
            // Si no hay usuario en sesión, redirigir al login
            session_destroy();
            header("Location: ../index.php");
            exit;
        }

ob_end_flush();
?>