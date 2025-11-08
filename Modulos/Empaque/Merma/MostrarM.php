<?php
include '../../../Conexion/BD.php';

$id = "";
$Reg = "";

if (!empty($_POST)) {

    $id = $_POST['id'];

    $stmt = $Con->prepare("SELECT m.no_serie_m AS ns, cm.tipo_merma AS tm, cm.motivo AS m, tc.folio_carro AS tc, tt.nombre_tarima AS nt, m.cantidad_tarima AS ct, tb.tipo_caja AS c, m.cantidad_caja AS cc, m.p_bruto AS pb, m.p_taraje AS pt, m.p_neto AS pn, m.semana_m AS sr, m.fecha_reg AS fr, m.hora_m AS hr FROM registro_merma m
                    LEFT JOIN tipo_variaciones tv ON m.id_codigo_m = tv.id_variedad
                    LEFT JOIN tipos_cajas tb ON m.id_tipo_caja = tb.id_caja
                    LEFT JOIN tipos_tarimas tt ON m.id_tipo_tarima = tt.id_tarima
                    LEFT JOIN tipos_carros tc ON m.id_tipo_carro = tc.id_carro
                    LEFT JOIN variedades v ON tv.id_nombre_v = v.id_nombre_v
                    LEFT JOIN tipos_presentacion tp ON tv.id_presentacion_v = tp.id_presentacion
                    LEFT JOIN ciclos c ON tv.id_ciclo_v = c.id_ciclo
                    LEFT JOIN invernaderos i ON tv.id_modulo_v = i.id_invernadero
                    LEFT JOIN sedes s ON i.id_sede_i = s.id_sede
                    LEFT JOIN clasificacion_merma cm ON m.id_clasificacion = cm.id_merma
                    WHERE m.id_registro_m=?");
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
    header("Location: CatalogoR.php"); 
}

?>
