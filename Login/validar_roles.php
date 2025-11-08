<?php
include 'Conexion/BD.php';

session_start(); // ✅ siempre primero
session_regenerate_id(true); // ✅ ahora sí se regenera correctamente

$User = $_POST['Usuario'];
$Pass = $_POST['Password'];
$Estado = 1;
$sid = session_id();

// Verificar usuario
$stmt = $Con->prepare("SELECT u.id_usuario AS ID, r.rol AS Rol, c.nombre_completo AS Titular, 
                              c.id_sede_u AS Sede, s.codigo_s AS CodigoS
                        FROM usuarios u
                        LEFT JOIN roles r ON u.id_rol = r.id_rol
                        LEFT JOIN cargos c ON u.id_cargo = c.id_cargo
                        LEFT JOIN sedes s ON c.id_sede_u = s.id_sede
                        WHERE username = ?");
$stmt->bind_param("s", $User);
$stmt->execute();
$Registro = $stmt->get_result();

if ($Registro->num_rows > 0) {
    $Reg = $Registro->fetch_assoc();

    // Guardar variables de sesión
    $_SESSION['Name'] = $User;
    $_SESSION['Password'] = $Pass;
    $_SESSION['Rol'] = $Reg['Rol'];
    $_SESSION['Titular'] = $Reg['Titular'];
    $_SESSION['Sede'] = $Reg['Sede'];
    $_SESSION['CodigoS'] = $Reg['CodigoS'];
    $_SESSION['ID'] = $Reg['ID'];

    // Actualizar estado e ID de sesión en la BD
    $stmt2 = $Con->prepare("UPDATE usuarios SET estado = ?, id_session = ? WHERE id_usuario = ?");
    $stmt2->bind_param("isi", $Estado, $sid, $Reg['ID']);
    $stmt2->execute();
    $stmt2->close();

    // Buscar último módulo del usuario
    $stmt3 = $Con->prepare("SELECT pu.id_modulo_u AS IDM, m.nombre_seccion AS Temp
                            FROM permisos_usuarios pu
                            LEFT JOIN modulos m ON pu.id_modulo_u = m.id_seccion
                            WHERE pu.id_usuario_u = ?
                            ORDER BY pu.id_permiso_u DESC LIMIT 1");
    $stmt3->bind_param("i", $Reg['ID']);
    $stmt3->execute();
    $res3 = $stmt3->get_result();
    if ($res3->num_rows > 0) {
        $Reg3 = $res3->fetch_assoc();
        $_SESSION['IDM'] = $Reg3['IDM'];
        $_SESSION['Modulo'] = $Reg3['Temp'];
    }
    $stmt3->close();

    // Redirección según rol
    $Modulo = $_SESSION['Modulo'] ?? '';
    $partes = explode("/", $Modulo);
    $Supervisor = $partes[0] ?? '';

    switch ($_SESSION['Rol']) {
        case 'ADMINISTRADOR':
            header("Location: " . ($_POST["retorno"] ?? "Modulos/Configuración/Inicio.php"));
            break;
        case 'SUPERVISOR':
        case 'USUARIO':
            header("Location: " . ($_POST["retorno"] ?? "Modulos/$Supervisor/Inicio.php"));
            break;
        default:
            header("Location: index.php");
            break;
    }

} else {
    echo "Usuario no encontrado o datos incorrectos.";
}
?>
