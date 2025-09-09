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
    $TipoRol=$_SESSION['Rol'];
    $Titular=$_SESSION['Titular'];
    $Modulo=$_SESSION['Modulo'];
    $Sede=$_SESSION['Sede'];
    $ID=$_SESSION['ID'];

    //Evitar error de sesión
    if (empty($Name) || empty($Password) || empty($TipoRol) || empty($Titular) || empty($Modulo)) {
        header ("Location: $RutaSC");
    }

    //Conocer permisos
    function TienePermiso($ID, $NM, $TP, $Con) {
    $stmt = $Con->prepare('SELECT 1 
        FROM permisos_usuarios pu 
        JOIN modulos m ON pu.id_modulo_u = m.id_seccion
        WHERE pu.id_usuario_u = ? 
        AND m.nombre_seccion = ? 
        AND pu.id_permisos_u = ?');
    $stmt->bind_param('isi', $ID, $NM, $TP);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $Permiso = $resultado->num_rows > 0;
    $stmt->close();

    return $Permiso;
    }

    function PermisoModulo($ID, $NM, $Con) {
    $stmt = $Con->prepare('SELECT 1 
        FROM permisos_usuarios pu 
        JOIN modulos m ON pu.id_modulo_u = m.id_seccion
        WHERE pu.id_usuario_u = ? 
        AND m.nombre_seccion LIKE ?');
    $stmt->bind_param('is', $ID, $NM);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $Acceso = $resultado->num_rows > 0;
    $stmt->close();

    return $Acceso;
    }
?>