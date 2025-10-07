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
        6 => 'fecha_ingreso',
        7 => 'fecha_registro',
        8 => 'nombre_completo',
    ];

    if (isset($mapColumnas[$columna])) {
        $columnaDB = $mapColumnas[$columna];
        $sql = "SELECT DISTINCT $columnaDB FROM rh_personal p
                    LEFT JOIN usuarios u ON p.id_user_p = u.id_usuario
                    LEFT JOIN cargos c ON u.id_cargo = c.id_cargo
                    LEFT JOIN rh_generos g ON p.id_genero_pl = g.id_genero
                    LEFT JOIN rh_tipos_empleados te ON p.id_te_pl = te.id_tipo_rh
                    LEFT JOIN rh_departamentos d ON p.id_depto_pl = d.id_departamento
                    LEFT JOIN rh_check k ON p.badge = k.badge
                    LEFT JOIN sedes s ON p.id_sede_pl = s.id_sede
                    WHERE p.status_pl = 1 AND k.badge IS NULL ORDER BY $columnaDB ASC";

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
