<?php
include '../../../Conexion/BD.php';

$id = "";
$Reg = "";

if (!empty($_POST)) {

    $id = $_POST['id'];

    $stmt = $Con->prepare("SELECT s.codigo_s AS s, r.folio_req AS f, p.dep AS d, d.departamento AS a, r.solicitante AS p, r.cant_producto AS t, r.fecha_req AS fr, r.estado_req AS e, u.username AS u
                    FROM cp_requisiciones r
                    LEFT JOIN usuarios u ON r.id_usuario_req = u.id_usuario
                    LEFT JOIN rh_departamentos d ON r.id_area_req = d.id_departamento
                    LEFT JOIN cp_departamentos p ON d.id_dep_d = p.id_dep
                    LEFT JOIN sedes s ON r.id_sede_req = s.id_sede
                    WHERE r.id_requisicion = ?");
    $stmt->bind_param("i",$id);
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
    header("Location: CatalogoRQ.php"); 
}

?>
