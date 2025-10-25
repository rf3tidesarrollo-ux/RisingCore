<?php
include '../../../Conexion/BD.php';

$id = "";
$Reg = "";

if (!empty($_POST)) {

    $id = $_POST['id'];

    $stmt = $Con->prepare("SELECT codigo_s AS s, tipo_h AS n, hora_entrada AS he, hora_salida AS hs, hora_sabado_e AS se, hora_sabado AS ss, hora_domingo_e AS de, hora_domingo AS ds 
                    FROM rh_tipos_horarios h JOIN sedes s ON h.id_sede_h = s.id_sede WHERE id_thorario=?");
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
    header("Location: CatalogoH.php"); 
}

?>
