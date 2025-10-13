<?php
include '../../../Conexion/BD.php';

$id = "";
$Reg = "";

if (!empty($_POST)) {

    $id = $_POST['id'];

    $stmt = $Con->prepare("SELECT m.folio_m AS fm, s.codigo_s AS s, c.nombre_cliente AS nc, m.cajas_t AS ct, m.kilos_t AS kt, m.fecha_m AS f, m.hora_m AS h
                    FROM mezclas m
                    JOIN clientes c ON m.id_cliente_m = c.id_cliente
                    JOIN sedes s ON m.id_sede_m = s.id_sede
                    WHERE m.id_mezcla=?");
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
    header("Location: CatalogoP.php"); 
}

?>
