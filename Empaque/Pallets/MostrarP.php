<?php
include '../../../Conexion/BD.php';

$id = "";
$Reg = "";

if (!empty($_POST)) {

    $id = $_POST['id'];

    $stmt = $Con->prepare("SELECT p.folio_p AS f, s.codigo_s AS s, pp.presentacion AS np, pp.cliente_id AS nc, p.cajas_p AS c, em.folio_em AS t, p.fecha_p AS fc, p.hora_p AS h, c.nombre_completo AS nr
                    FROM pallets p
                    JOIN usuarios u ON p.id_usuario_p = u.id_usuario
                    JOIN cargos c ON u.id_cargo = c.id_cargo
                    JOIN tipos_tarimas t ON p.id_tarima_p = t.id_tarima
                    JOIN presentaciones_pallet pp ON p.id_presen_p = id_presentacion_p
                    JOIN sedes s ON p.id_sede_p = s.id_sede
                    JOIN embarques_pallets em ON p.id_embarque_p = em.id_embarque
                    WHERE p.id_pallet=?");
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
