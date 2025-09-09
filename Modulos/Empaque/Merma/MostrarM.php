<?php
include '../../../Conexion/BD.php';

$ID = "";
$Reg = "";

if (!empty($_POST)) {

    $ID = $_POST['id'];

    $stmt = $Con->prepare("SELECT registro_empaque.no_serie_r AS ns, tipo_variaciones.codigo AS cv, sedes.codigo_s AS s, variedades.nombre_variedad AS nv, clasificacion_merma.tipo_merma AS tm, clasificacion_merma.motivo AS m, tipos_presentacion.nombre_p AS np, invernaderos.invernadero AS i, registro_empaque.cantidad_caja AS cc, tipos_cajas.tipo_caja AS tp, registro_empaque.p_bruto AS pb, registro_empaque.p_taraje AS pt, registro_empaque.p_neto AS pn, registro_empaque.fecha_r AS fr, registro_empaque.hora_r AS hr, registro_empaque.semana_r AS sr, tipos_carros.folio_carro AS tc, tipos_tarimas.nombre_tarima AS nt, registro_empaque.cantidad_tarima AS ct
                    FROM registro_empaque
                    JOIN tipo_variaciones ON registro_empaque.id_codigo_r = tipo_variaciones.id_variedad
                    JOIN tipos_cajas ON registro_empaque.id_tipo_caja = tipos_cajas.id_caja
                    JOIN tipos_tarimas ON registro_empaque.id_tipo_tarima = tipos_tarimas.id_tarima
                    JOIN tipos_carros ON registro_empaque.id_tipo_carro = tipos_carros.id_carro
                    JOIN variedades ON tipo_variaciones.id_nombre_v = variedades.id_nombre_v
                    JOIN tipos_presentacion ON tipo_variaciones.id_presentacion_v = tipos_presentacion.id_presentacion
                    JOIN ciclos ON tipo_variaciones.id_ciclo_v = ciclos.id_ciclo
                    JOIN invernaderos ON tipo_variaciones.id_modulo_v = invernaderos.id_invernadero
                    JOIN sedes ON invernaderos.id_sede_i = sedes.id_sede
                    JOIN clasificacion_merma ON registro_empaque.id_tipo_merma = clasificacion_merma.id_merma
                    WHERE registro_empaque.id_registro_r=?");
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
