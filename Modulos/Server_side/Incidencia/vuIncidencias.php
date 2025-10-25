<?php
include_once '../../../Conexion/BD.php';

$columna = $_POST['columna'] ?? null;

if ($columna !== null) {
    // Mapear índice de columna a nombre real de la columna en la DB
    $mapColumnas = [
        0 => 'codigo_s',
        1 => 'nombre',
        2 => 'departamento',
        3 => 'tipo_permiso',
        4 => 'registro_check',
    ];

    if (isset($mapColumnas[$columna])) {
        $columnaDB = $mapColumnas[$columna];
        $sql = "SELECT DISTINCT $columnaDB FROM rh_check c
                    LEFT JOIN rh_personal p ON c.badge = p.badge
                    LEFT JOIN rh_permisos tp ON c.id_dptipo = tp.id_permiso
                    LEFT JOIN rh_departamentos d ON p.id_depto_pl = d.id_departamento
                    LEFT JOIN sedes s ON p.id_sede_pl = s.id_sede
                    WHERE c.id_dptipo NOT IN (1) ORDER BY $columnaDB ASC";

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
