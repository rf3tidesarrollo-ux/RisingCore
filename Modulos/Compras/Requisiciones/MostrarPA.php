<?php
include '../../../Conexion/BD.php';

$ID = "";
$Reg = "";

if (!empty($_POST)) {

    $ID = $_POST['id'];

    $stmt = $Con->prepare("SELECT t.folio_t AS f, p.producto AS p, e.prioridad AS pr, t.cantidad_t AS c, p.unidad AS u, p.existencias_p AS e, t.fecha_rt AS fr, t.estado_t AS ed, t.justificacion AS j
                            FROM cp_requisicion_temp t
                            JOIN cp_productos p ON p.id_producto = t.id_producto_t
                            JOIN cp_tipo_prioridad e ON t.prioridad_t = e.id_prioridad
                            WHERE t.id_requisicion_t = ?");
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
