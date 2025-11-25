<?php
include_once '../../../Conexion/BD.php';

$columna = $_POST['columna'] ?? null;

if ($columna !== null) {
    // Mapear índice de columna a nombre real de la columna en la DB
    $mapColumnas = [
        0 => 'codigo_s',
        1 => 'folio_req',
        2 => 'dep',
        3 => 'departamento',
        4 => 'solicitante',
        5 => 'cant_producto',
        6 => 'fecha_req',
        7 => 'estado_req',
        8 => 'username'
    ];

    if (isset($mapColumnas[$columna])) {
        $columnaDB = $mapColumnas[$columna];
        $sql = "SELECT DISTINCT $columnaDB FROM cp_requisiciones r
                    LEFT JOIN usuarios u ON r.id_usuario_req = u.id_usuario
                    LEFT JOIN rh_departamentos d ON r.id_area_req = d.id_departamento
                    LEFT JOIN cp_departamentos p ON d.id_dep_d = p.id_dep
                    LEFT JOIN sedes ON r.id_sede_req = sedes.id_sede
                    WHERE r.activo_req = 1 ORDER BY $columnaDB ASC";

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
