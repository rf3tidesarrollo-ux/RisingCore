<?php
include '../../../Conexion/BD.php';

$id = "";
$Reg = "";

if (!empty($_POST)) {

    $id = $_POST['id'];

    $stmt = $Con->prepare("SELECT tp.tipo_producto AS tp, p.producto AS p, o.unidad AS u, p.existencias AS e, p.u_entrada AS ue, p.u_salida AS us, p.u_proveedor AS up, p.u_precio AS ud, p.fecha_a AS fa, c.nombre_completo AS nc 
                    FROM cp_productos p
                    LEFT JOIN usuarios u ON p.user_p = u.id_usuario
                    LEFT JOIN cargos c ON u.id_cargo = c.id_cargo
                    LEFT JOIN cp_unidades o ON p.unidad_p = o.id_unidad 
                    LEFT JOIN cp_tipo_productos tp ON p.id_tipo_p = tp.id_tproducto
                    WHERE id_producto=?");
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
    header("Location: CatalogoPD.php"); 
}

?>
