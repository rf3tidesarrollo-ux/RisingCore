<?php
include '../../../Conexion/BD.php';

$id = "";
$Reg = "";

if (!empty($_POST)) {

    $id = $_POST['id'];

    $stmt = $Con->prepare("SELECT s.codigo_s AS fs, em.folio_em AS fe, em.po_em AS po, em.cajas_em AS ce, em.kilos_em AS ke, em.cajas_emt AS ct, em.kilos_emt AS kt, em.fecha_em AS fe, em.semana_em AS se, d.folio_d AS de, c.nombre_completo AS nc, estado_em AS ee 
                    FROM embarques_pallets em
                    LEFT JOIN usuarios u ON em.usuario_id = u.id_usuario
                    LEFT JOIN cargos c ON u.id_cargo = c.id_cargo
                    LEFT JOIN sedes s ON em.id_sede_em= s.id_sede
                    LEFT JOIN destinos_embarque d ON em.id_destino_em = d.id_destino
                    WHERE em.id_embarque=?");
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
    header("Location: CatalogoE.php"); 
}

?>
