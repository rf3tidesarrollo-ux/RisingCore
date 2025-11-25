<?php
include_once '../../../Conexion/BD.php';

$columna = $_POST['columna'] ?? null;

if ($columna !== null) {
    // Mapear índice de columna a nombre real de la columna en la DB
    $mapColumnas = [
        0 => 'producto',
        1 => 'unidad',
        2 => 'tipo_producto',
        3 => 'existencias',
        4 => 'u_entrada',
        5 => 'u_salida',
        6 => 'u_proveedor',
        7 => 'u_precio',
        8 => 'fecha_a',
        9 => 'nombre_completo'
    ];

    if (isset($mapColumnas[$columna])) {
        $columnaDB = $mapColumnas[$columna];
        $sql = "SELECT DISTINCT $columnaDB FROM cp_productos p
                    LEFT JOIN usuarios u ON p.user_p = u.id_usuario
                    LEFT JOIN cargos c ON u.id_cargo = c.id_cargo
                    LEFT JOIN cp_unidades o ON p.unidad_p = o.id_unidad
                    LEFT JOIN cp_tipo_productos tp ON p.id_tipo_p = tp.id_tproducto
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
