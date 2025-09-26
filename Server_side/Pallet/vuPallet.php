<?php
include_once '../../../Conexion/BD.php';

$columna = $_POST['columna'] ?? null;

if ($columna !== null) {
    // Mapear índice de columna a nombre real de la columna en la DB
    $mapColumnas = [
        0 => 'folio_p',
        1 => 'codigo_s',
        2 => 'presentacion',
        3 => 'cliente_id',
        4 => 'cajas_p',
        5 => 'folio_em',
        6 => 'fecha_p',
        7 => 'hora_p',
        8 => 'fecha_e',
        9 => 'nombre_completo',
    ];

    if (isset($mapColumnas[$columna])) {
        $columnaDB = $mapColumnas[$columna];
        $sql = "SELECT DISTINCT $columnaDB FROM pallets p
                    LEFT JOIN usuarios u ON p.id_usuario_p = u.id_usuario
                    LEFT JOIN cargos c ON u.id_cargo = c.id_cargo
                    LEFT JOIN tipos_tarimas t ON p.id_tarima_p = t.id_tarima
                    LEFT JOIN presentaciones_pallet pp ON p.id_presen_p = pp.id_presentacion_p
                    LEFT JOIN sedes s ON p.id_sede_p = s.id_sede
                    LEFT JOIN embarques_pallets em ON p.id_embarque_p = em.id_embarque
                    WHERE p.activo_p = 1 ORDER BY $columnaDB ASC";

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
