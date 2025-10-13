<?php
include_once '../../../Conexion/BD.php';

$columna = $_POST['columna'] ?? null;

if ($columna !== null) {
    // Mapear índice de columna a nombre real de la columna en la DB
    $mapColumnas = [
        0 => 'codigo_s',
        1 => 'badge',
        2 => 'nombre_personal',
        3 => 'genero',
        4 => 'tipo_rh',
        5 => 'departamento',
        6 => 'tipo_h',
        7 => 'fecha_ingreso',
        8 => 'fecha_registro',
        9 => 'nombre_completo',
    ];

    if (isset($mapColumnas[$columna])) {
        $columnaDB = $mapColumnas[$columna];
        $sql = "SELECT DISTINCT $columnaDB FROM vw_pendientes ORDER BY $columnaDB ASC";

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
