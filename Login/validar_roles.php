<?php
Include 'Conexion/BD.php';
$User = $_POST['Usuario'];
$Pass = $_POST['Password'];
$Estado = 1;

    $stmt = $Con->prepare("SELECT u.id_usuario as ID, r.rol as Rol, c.nombre_completo as Titular, c.id_sede_u as Sede, s.codigo_s as CodigoS
        FROM usuarios u
        LEFT JOIN roles r ON u.id_rol = r.id_rol
        LEFT JOIN cargos c ON u.id_cargo = c.id_cargo
        LEFT JOIN sedes s ON c.id_sede_u = s.id_sede
        WHERE username = ?");
    $stmt->bind_param("s",$User);
    $stmt->execute();
    $Registro = $stmt->get_result();
    $NumCol=$Registro->num_rows;

    if ($NumCol>0) {
        while ($Reg = $Registro->fetch_assoc()){
            session_start();
            $_SESSION['Name'] = $User;
            $_SESSION['Password'] = $Pass;
            $_SESSION['Rol'] = $Reg['Rol'];
            $_SESSION['Titular'] = $Reg['Titular'];
            $_SESSION['Sede'] = $Reg['Sede'];
            $_SESSION['CodigoS'] = $Reg['CodigoS'];
            $_SESSION['ID'] = $Reg['ID'];
        }
        $stmt->close();
    }
    
    $stmt = $Con->prepare("UPDATE usuarios SET estado = ? WHERE username = ?");
    $stmt->bind_param("is",$Estado,$User);
    $stmt->execute();
    $stmt->close();

    $ID = $_SESSION['ID'];
    $stmt = $Con->prepare("SELECT pu.id_modulo_u as IDM, m.nombre_seccion as Temp
        FROM permisos_usuarios pu
        LEFT JOIN modulos m ON pu.id_modulo_u = m.id_seccion
        WHERE pu.id_usuario_u = ? ORDER BY pu.id_permiso_u DESC LIMIT 1");
    $stmt->bind_param("s",$ID);
    $stmt->execute();
    $Registro = $stmt->get_result();
    $NumCol=$Registro->num_rows;

    if ($NumCol>0) {
        while ($Reg = $Registro->fetch_assoc()){
            session_start();
            $_SESSION['IDM'] = $Reg['IDM'];
            $_SESSION['Modulo'] = $Reg['Temp'];
        }
        $stmt->close();
    }

    $Modulo = $_SESSION['Modulo'];
    $partes = explode("/", $Modulo);
    $Supervisor = $partes[0];


    if (!array_key_exists('rol', $_SESSION)) {
        switch ($_SESSION['Rol']) {
            case 'ADMINISTRADOR':
                header("Location: ". ($_POST["retorno"] ?? "Modulos/Configuración/Inicio.php"));
                break;
            case 'SUPERVISOR':
                header("Location: ". ($_POST["retorno"] ?? "Modulos/$Supervisor/Inicio.php"));
                break;
            case 'USUARIO':
                header("Location: ". ($_POST["retorno"] ?? "Modulos/$Supervisor/Inicio.php"));
                break;
        }
        
    }

?>