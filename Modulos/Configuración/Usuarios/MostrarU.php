<?php
include '../../../Conexion/BD.php';

$ID = "";
$Reg = "";

if (!empty($_POST)) {

    $ID = $_POST['id'];

    $stmt = $Con->prepare("SELECT usuarios.username AS un, sedes.codigo_s AS s, cargos.nombre_completo AS nc, cargos.cargo AS c, cargos.departamento AS d, roles.rol AS r, usuarios.estado AS e
                    FROM usuarios
                    JOIN cargos ON usuarios.id_cargo = cargos.id_cargo
                    JOIN roles ON usuarios.id_rol = roles.id_rol
                    JOIN sedes ON cargos.id_sede_u = sedes.id_Sede
                    WHERE usuarios.id_usuario=?");
    $stmt->bind_param("i",$ID);
    $stmt->execute();
    $Registro = $stmt->get_result();
    $NumCol=$Registro->num_rows;

    if ($NumCol>0) {
        $data = mysqli_fetch_assoc($Registro);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
        $stmt->close();
    }
    
    echo '<script>swal("¡Error!", "¡Ha ocurrido un error!", "error");</script>';
    exit;
    
}else{ 
    header("Location: CatalogoR.php"); 
}

?>
