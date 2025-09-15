<?php
include '../../../Conexion/BD.php';

$id = "";
$Reg = "";

if (!empty($_POST)) {

    $id = $_POST['id'];

    $stmt = $Con->prepare("SELECT m.folio_m AS f, s.codigo_s AS s, cl.nombre_cliente AS nc, m.cajas_t AS c, m.kilos_t AS k, m.fecha_m AS fc, m.hora_m AS h, c.nombre_completo AS nr
                    FROM mezclas m
                    LEFT JOIN usuarios u ON m.id_usuario_m = u.id_usuario
                    LEFT JOIN cargos c ON u.id_cargo = c.id_cargo
                    LEFT JOIN clientes cl ON m.id_cliente_m = cl.id_cliente
                    LEFT JOIN sedes s ON m.id_sede_m = s.id_sede
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
    header("Location: CatalogoMz.php"); 
}

?>
