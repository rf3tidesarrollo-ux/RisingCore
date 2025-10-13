<?php
include_once '../../../Conexion/BD.php';

$columna = $_POST['columna'] ?? null;

if ($columna !== null) {
    // Mapear índice de columna a nombre real de la columna en la DB
    $mapColumnas = [
        0 => 'no_serie_r',
        1 => 'codigo',
        2 => 'codigo_s',
        3 => 'nombre_variedad',
        4 => 'nombre_p',
        5 => 'invernadero',
        6 => 'folio_carro',
        7 => 'nombre_tarima',
        8 => 'cantidad_tarima',
        9 => 'tipo_caja',
        10 => 'cantidad_caja',
        11 => 'p_bruto',
        12 => 'p_taraje',
        13 => 'p_neto',
        14 => 'kilos_dis',
        15 => 'cajas_dis',
        16 => 'semana_r',
        17 => 'fecha_reg',
        18 => 'hora_r',
    ];

    if (isset($mapColumnas[$columna])) {
        $columnaDB = $mapColumnas[$columna];
        $sql = "SELECT DISTINCT $columnaDB FROM registro_empaque
                    LEFT JOIN tipo_variaciones ON registro_empaque.id_codigo_r = tipo_variaciones.id_variedad
                    LEFT JOIN tipos_cajas ON registro_empaque.id_tipo_caja = tipos_cajas.id_caja
                    LEFT JOIN tipos_tarimas ON registro_empaque.id_tipo_tarima = tipos_tarimas.id_tarima
                    LEFT JOIN tipos_carros ON registro_empaque.id_tipo_carro = tipos_carros.id_carro
                    LEFT JOIN variedades ON tipo_variaciones.id_nombre_v = variedades.id_nombre_v
                    LEFT JOIN tipos_presentacion ON registro_empaque.id_presentacion_r = tipos_presentacion.id_presentacion
                    LEFT JOIN ciclos ON tipo_variaciones.id_ciclo_v = ciclos.id_ciclo
                    LEFT JOIN invernaderos ON tipo_variaciones.id_modulo_v = invernaderos.id_invernadero
                    LEFT JOIN sedes ON invernaderos.id_sede_i = sedes.id_sede
                    WHERE activo_r = 1 ORDER BY $columnaDB ASC";

        $result = $Con->query($sql);

        $valores = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $valores[] = $row[$columnaDB];
            }
        }

        header('Content-Type: application/json');
        echo json_encode($valores);
        exit();
    }
}
http_response_code(400);
echo json_encode([]);
exit();
?>
