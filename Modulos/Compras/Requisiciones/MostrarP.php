<?php
include '../../../Conexion/BD.php';

$ID = "";
$Reg = "";

if (!empty($_POST)) {

    $ID = $_POST['id'];

    $stmt = $Con->prepare("SELECT tp.tipo_producto AS tp, p.producto AS p, u.unidad AS u, p.existencias AS ex, p.u_entrada AS ue, p.u_salida AS us, p.u_proveedor AS up, p.u_precio AS pr
                    FROM cp_productos p
                    JOIN cp_tipo_productos tp ON p.id_tipo_p = tp.id_tproducto
                    JOIN cp_unidades u ON p.unidad_p = u.id_unidad
                    WHERE p.id_producto=?");
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
    header("Location: CatalogoRQ.php"); 
}

?>
