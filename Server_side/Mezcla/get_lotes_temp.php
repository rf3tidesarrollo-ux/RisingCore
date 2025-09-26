<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";
// $Pagina=basename(__FILE__);
// Historial($Pagina,$Con);
$IDM = $_GET['id'];

$orderColumnIndex = isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0; // Índice de la columna por defecto
$orderDir = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc'; // Dirección por defecto
$orderColumn = isset($columnsMap[$orderColumnIndex]) ? $columnsMap[$orderColumnIndex] : 'id_registro_r'; // Columna por defecto

// Procesa la paginación
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 5;

if ($IDM == 0) {
    $totalQuery = "SELECT COUNT(*) as total FROM mezcla_lotes_temp
                    LEFT JOIN registro_empaque ON mezcla_lotes_temp.id_lote = registro_empaque.id_registro_r
                    LEFT JOIN tipo_variaciones ON registro_empaque.id_codigo_r = tipo_variaciones.id_variedad
                    LEFT JOIN tipos_cajas ON registro_empaque.id_tipo_caja = tipos_cajas.id_caja
                    LEFT JOIN tipos_tarimas ON registro_empaque.id_tipo_tarima = tipos_tarimas.id_tarima
                    LEFT JOIN tipos_carros ON registro_empaque.id_tipo_carro = tipos_carros.id_carro
                    LEFT JOIN variedades ON tipo_variaciones.id_nombre_v = variedades.id_nombre_v
                    LEFT JOIN tipos_presentacion ON tipo_variaciones.id_presentacion_v = tipos_presentacion.id_presentacion
                    LEFT JOIN invernaderos ON tipo_variaciones.id_modulo_v = invernaderos.id_invernadero
                    LEFT JOIN sedes ON invernaderos.id_sede_i = sedes.id_sede
                    WHERE mezcla_lotes_temp.usuario_id = ? AND confirmado = 0";
    $totalStmt = $Con->prepare($totalQuery);
    $totalStmt->bind_param("i", $ID);
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRecords = $totalResult->fetch_assoc()['total'];

    // Consulta de datos con orden y paginación
    $dataQuery = "SELECT * FROM mezcla_lotes_temp
                    LEFT JOIN registro_empaque ON mezcla_lotes_temp.id_lote = registro_empaque.id_registro_r
                    LEFT JOIN tipo_variaciones ON registro_empaque.id_codigo_r = tipo_variaciones.id_variedad
                    LEFT JOIN tipos_cajas ON registro_empaque.id_tipo_caja = tipos_cajas.id_caja
                    LEFT JOIN tipos_tarimas ON registro_empaque.id_tipo_tarima = tipos_tarimas.id_tarima
                    LEFT JOIN tipos_carros ON registro_empaque.id_tipo_carro = tipos_carros.id_carro
                    LEFT JOIN variedades ON tipo_variaciones.id_nombre_v = variedades.id_nombre_v
                    LEFT JOIN tipos_presentacion ON tipo_variaciones.id_presentacion_v = tipos_presentacion.id_presentacion
                    LEFT JOIN invernaderos ON tipo_variaciones.id_modulo_v = invernaderos.id_invernadero
                    LEFT JOIN sedes ON invernaderos.id_sede_i = sedes.id_sede
                    WHERE mezcla_lotes_temp.usuario_id = ? AND confirmado = 0
                    ORDER BY $orderColumn $orderDir
                    LIMIT ?, ?";
    $dataStmt = $Con->prepare($dataQuery);
    $dataStmt->bind_param("iii", $ID, $start, $length);
    $dataStmt->execute();
    $dataResult = $dataStmt->get_result();
}else{
    $stmtCheckTemp = $Con->prepare("SELECT COUNT(*) as total FROM mezcla_lotes_temp WHERE usuario_id = ? AND confirmado = 0");
    $stmtCheckTemp->bind_param("i", $ID);
    $stmtCheckTemp->execute();
    $resultTemp = $stmtCheckTemp->get_result();
    $tempCount = $resultTemp->fetch_assoc()['total'];

    if ($tempCount == 0) {
        $stmtCopy = $Con->prepare("SELECT id_lote_l, cajas_m, kilos_m FROM mezcla_lotes WHERE id_mezcla_l = ?");
        $stmtCopy->bind_param("i", $IDM);
        $stmtCopy->execute();
        $resultCopy = $stmtCopy->get_result();

        $stmtInsertTemp = $Con->prepare("INSERT INTO mezcla_lotes_temp (id_lote, cajas_m, kilos_m, usuario_id, confirmado) VALUES (?, ?, ?, ?, 0)");
        while ($row = $resultCopy->fetch_assoc()) {
            $stmtInsertTemp->bind_param("iidi", $row['id_lote_l'], $row['cajas_m'], $row['kilos_m'], $ID);
            $stmtInsertTemp->execute();
        }
    }
    
    $totalQuery = "SELECT COUNT(*) as total FROM mezcla_lotes_temp
                    LEFT JOIN registro_empaque ON mezcla_lotes_temp.id_lote = registro_empaque.id_registro_r
                    LEFT JOIN tipo_variaciones ON registro_empaque.id_codigo_r = tipo_variaciones.id_variedad
                    LEFT JOIN tipos_cajas ON registro_empaque.id_tipo_caja = tipos_cajas.id_caja
                    LEFT JOIN tipos_tarimas ON registro_empaque.id_tipo_tarima = tipos_tarimas.id_tarima
                    LEFT JOIN tipos_carros ON registro_empaque.id_tipo_carro = tipos_carros.id_carro
                    LEFT JOIN variedades ON tipo_variaciones.id_nombre_v = variedades.id_nombre_v
                    LEFT JOIN tipos_presentacion ON tipo_variaciones.id_presentacion_v = tipos_presentacion.id_presentacion
                    LEFT JOIN invernaderos ON tipo_variaciones.id_modulo_v = invernaderos.id_invernadero
                    LEFT JOIN sedes ON invernaderos.id_sede_i = sedes.id_sede
                    WHERE mezcla_lotes_temp.usuario_id = ? AND confirmado = 0";
    $totalStmt = $Con->prepare($totalQuery);
    $totalStmt->bind_param("i", $ID);
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRecords = $totalResult->fetch_assoc()['total'];

    // Consulta de datos con orden y paginación
    $dataQuery = "SELECT * FROM mezcla_lotes_temp
                    LEFT JOIN registro_empaque ON mezcla_lotes_temp.id_lote = registro_empaque.id_registro_r
                    LEFT JOIN tipo_variaciones ON registro_empaque.id_codigo_r = tipo_variaciones.id_variedad
                    LEFT JOIN tipos_cajas ON registro_empaque.id_tipo_caja = tipos_cajas.id_caja
                    LEFT JOIN tipos_tarimas ON registro_empaque.id_tipo_tarima = tipos_tarimas.id_tarima
                    LEFT JOIN tipos_carros ON registro_empaque.id_tipo_carro = tipos_carros.id_carro
                    LEFT JOIN variedades ON tipo_variaciones.id_nombre_v = variedades.id_nombre_v
                    LEFT JOIN tipos_presentacion ON tipo_variaciones.id_presentacion_v = tipos_presentacion.id_presentacion
                    LEFT JOIN invernaderos ON tipo_variaciones.id_modulo_v = invernaderos.id_invernadero
                    LEFT JOIN sedes ON invernaderos.id_sede_i = sedes.id_sede
                    WHERE mezcla_lotes_temp.usuario_id = ? AND confirmado = 0
                    ORDER BY $orderColumn $orderDir
                    LIMIT ?, ?";
    $dataStmt = $Con->prepare($dataQuery);
    $dataStmt->bind_param("iii", $ID, $start, $length);
    $dataStmt->execute();
    $dataResult = $dataStmt->get_result();
}
    
// Construye la respuesta JSON
$response = array(
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecords,
    "data" => array()
);

while ($data = $dataResult->fetch_assoc()) {
    $response["data"][] = $data;
}

$json = json_encode($response);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "Error de JSON: " . json_last_error_msg();
} else {
    echo $json;
}
?>