<?php
include '../../../Conexion/BD.php';

$ID = "";
$Reg = "";

if (!empty($_POST)) {

    $ID = $_POST['id'];

    $stmt = $Con->prepare("SELECT re.no_serie_r AS ns, tv.codigo AS cv, s.codigo_s AS s, v.nombre_variedad AS nv, tp.nombre_p AS np, i.invernadero AS i, re.cantidad_caja AS cc, tc.tipo_caja AS c, re.p_bruto AS pb, re.p_taraje AS pt, re.p_neto AS pn, re.fecha_reg AS fr, re.hora_r AS hr, re.semana_r AS sr, cr.folio_carro AS tc, tt.nombre_tarima AS nt, re.cantidad_tarima AS ct
                    FROM registro_empaque re
                    LEFT JOIN tipo_variaciones tv ON re.id_codigo_r = tv.id_variedad
                    LEFT JOIN tipos_cajas tc ON re.id_tipo_caja = tc.id_caja
                    LEFT JOIN tipos_tarimas tt ON re.id_tipo_tarima = tt.id_tarima
                    LEFT JOIN tipos_carros cr ON re.id_tipo_carro = cr.id_carro
                    LEFT JOIN variedades v ON tv.id_nombre_v = v.id_nombre_v
                    LEFT JOIN tipos_presentacion tp ON re.id_presentacion_r = tp.id_presentacion
                    LEFT JOIN ciclos c ON tv.id_ciclo_v = c.id_ciclo
                    LEFT JOIN invernaderos i ON tv.id_modulo_v = i.id_invernadero
                    LEFT JOIN sedes s ON i.id_sede_i = s.id_sede
                    WHERE re.id_registro_r=?");
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
    header("Location: CatalogoR.php"); 
}

?>
