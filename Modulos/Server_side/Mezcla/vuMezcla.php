<?php
include_once '../../../Conexion/BD.php';

$columna = $_POST['columna'] ?? null;

if ($columna !== null) {
    // Mapear índice de columna a nombre real de la columna en la DB
    $mapColumnas = [
        0 => 'folio_m',
        1 => 'codigo_s',
        2 => 'nombre_cliente',
        3 => 'cajas_t',
        4 => 'kilos_t',
        5 => 'fecha_m',
        6 => 'hora_m',
        7 => 'nombre_completo',
    ];

    if (isset($mapColumnas[$columna])) {
        $columnaDB = $mapColumnas[$columna];
        $sql = "SELECT DISTINCT $columnaDB FROM mezclas
                    LEFT JOIN usuarios ON mezclas.id_usuario_m = usuarios.id_usuario
                    LEFT JOIN cargos ON usuarios.id_cargo = cargos.id_cargo
                    LEFT JOIN clientes ON mezclas.id_cliente_m = clientes.id_cliente
                    LEFT JOIN sedes ON mezclas.id_sede_m = sedes.id_sede
                    WHERE mezclas.activo_m = 1 ORDER BY $columnaDB ASC";

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
