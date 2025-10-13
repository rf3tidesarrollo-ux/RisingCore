<?php
include_once '../../../Conexion/BD.php';

$columna = $_POST['columna'] ?? null;

if ($columna !== null) {
    // Mapear índice de columna a nombre real de la columna en la DB
    $mapColumnas = [
        0 => 'codigo_s',
        1 => 'folio_em',
        2 => 'folio_d',
        3 => 'po_em',
        4 => 'cajas_em',
        5 => 'kilos_em',
        6 => 'cajas_emt',
        7 => 'kilos_emt',
        8 => 'fecha_em',
        9 => 'semana_em',
        10 => 'nombre_completo',
    ];

    if (isset($mapColumnas[$columna])) {
        $columnaDB = $mapColumnas[$columna];
        $sql = "SELECT DISTINCT $columnaDB FROM embarques_pallets em
                    LEFT JOIN usuarios u ON em.usuario_id = u.id_usuario
                    LEFT JOIN cargos c ON u.id_cargo = c.id_cargo
                    LEFT JOIN sedes s ON em.id_sede_em= s.id_sede
                    LEFT JOIN destinos_embarque d ON em.id_destino_em = d.id_destino
                    WHERE em.activo_em = 1 ORDER BY $columnaDB ASC";

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
