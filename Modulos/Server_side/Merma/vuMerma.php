<?php
include_once '../../../Conexion/BD.php';

$columna = $_POST['columna'] ?? null;

if ($columna !== null) {
    // Mapear índice de columna a nombre real de la columna en la DB
    $mapColumnas = [
        0 => 'no_serie_m',
        1 => 'tipo_merma',
        2 => 'motivo',
        3 => 'folio_carro',
        4 => 'nombre_tarima',
        5 => 'cantidad_tarima',
        6 => 'tipo_caja',
        7 => 'cantidad_caja',
        8 => 'p_bruto',
        9 => 'p_neto',
        10 => 'semana_m',
        11 => 'fecha_reg',
        12 => 'hora_m',
    ];

    if (isset($mapColumnas[$columna])) {
        $columnaDB = $mapColumnas[$columna];
        $sql = "SELECT DISTINCT $columnaDB FROM registro_merma  m
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
                    WHERE activo_m = 1 ORDER BY $columnaDB ASC";

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
