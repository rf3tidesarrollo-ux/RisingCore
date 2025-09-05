<?php
    //Configuración de la sesión
    ob_start();
    session_start();
    $Inactividad=3600;

    if (isset($_SESSION["Timeout"])) {
        $TiempoSession = time() - $_SESSION["Timeout"];

        if ($TiempoSession > $Inactividad) {
            include_once 'Cerrar.php';
            session_destroy();
            ob_end_flush();
            header ("Location: $RutaCS");
        }
    }
    
    $_SESSION["Timeout"] = time();
    
    $Name=$_SESSION['Name'];
    $Password=$_SESSION['Password'];
    $Rol=$_SESSION['Rol'];
    $Titular=$_SESSION['Titular'];
    $Modulo=$_SESSION['Modulo'];
    $ID=$_SESSION['ID'];

    //Evitar error de sesión
    if (empty($Name) || empty($Password) || empty($Rol) || empty($Titular) || empty($Modulo)) {
        header ("Location: $RutaSC");
    }

    //Conocer permisos
    function Permisos($ID,$IDM,$TP,$Con){

        $stmt = $Con->prepare('SELECT m.nombre_seccion, p.nombre FROM permisos_usuarios pu 
                                JOIN modulos m ON pu.id_modulo_u = m.id_mseccion
                                JOIN permisos p ON pu.id_permisos_u = p.id_permiso 
                                WHERE pu.id_usuario_u = ? AND pu.id_modulo_u = ? AND pu.id_permisos_u = ?');
        $stmt->bind_param('iii', $ID, $IDM, $TP);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $existe = $resultado->num_rows > 0;
        $stmt->close();

        return $existe;
    }
?>