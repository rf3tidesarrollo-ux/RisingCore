<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";
// $Pagina=basename(__FILE__);
// Historial($Pagina,$Con);
$IDR = $_GET['id'];

$orderColumnIndex = isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0; // Índice de la columna por defecto
$orderDir = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc'; // Dirección por defecto
$orderColumn = isset($columnsMap[$orderColumnIndex]) ? $columnsMap[$orderColumnIndex] : 'folio_t'; // Columna por defecto

// Procesa la paginación
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 5;

if ($IDR == 0) {
    $totalQuery = "SELECT COUNT(*) as total FROM cp_requisicion_temp t
                    LEFT JOIN cp_productos p ON t.id_producto_t = p.id_producto
                    WHERE t.id_usuario_t = ? AND t.confirmado = 0";
    $totalStmt = $Con->prepare($totalQuery);
    $totalStmt->bind_param("i", $ID);
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRecords = $totalResult->fetch_assoc()['total'];

    // Consulta de datos con orden y paginación
    $dataQuery = "SELECT * FROM cp_requisicion_temp t
                    LEFT JOIN cp_productos p ON t.id_producto_t = p.id_producto
                    WHERE t.id_usuario_t = ? AND t.confirmado = 0
                    ORDER BY $orderColumn $orderDir
                    LIMIT ?, ?";
    $dataStmt = $Con->prepare($dataQuery);
    $dataStmt->bind_param("iii", $ID, $start, $length);
    $dataStmt->execute();
    $dataResult = $dataStmt->get_result();
}else{
    $stmtCheckTemp = $Con->prepare("SELECT COUNT(*) as total FROM cp_requisicion_temp WHERE id_usuario_t = ? AND confirmado = 0");
    $stmtCheckTemp->bind_param("i", $ID);
    $stmtCheckTemp->execute();
    $resultTemp = $stmtCheckTemp->get_result();
    $tempCount = $resultTemp->fetch_assoc()['total'];

    if ($tempCount == 0) {
        $stmtCopy = $Con->prepare("SELECT * FROM cp_requisicion_pro WHERE id_requi_p = ?");
        $stmtCopy->bind_param("i", $IDR);
        $stmtCopy->execute();
        $resultCopy = $stmtCopy->get_result();

        $stmtInsertTemp = $Con->prepare("INSERT INTO cp_requisicion_temp (folio_t, id_producto_t, cantidad_t, fecha_rt, fecha_t, hora_t, solicitante_t, prioridad_t, observacion, justificacion, estado_t, id_usuario_t) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        while ($row = $resultCopy->fetch_assoc()) {
            $stmtInsertTemp->bind_param("siissssisssi", $row['folio_p'], $row['id_producto_p'], $row['cantidad_p'], $row['fecha_rp'], $row['fecha_p'], $row['hora_p'], $row['solicitante_p'], $row['prioridad_p'], $row['observacion'], $row['justificacion'], $row['estado_p'], $row['id_usuario_p']);
            $stmtInsertTemp->execute();
        }
    }
    
    $totalQuery = "SELECT COUNT(*) as total FROM cp_requisicion_temp t
                    LEFT JOIN cp_productos p ON t.id_producto_t = p.id_producto
                    WHERE t.id_usuario_t = ? AND t.confirmado = 0";
    $totalStmt = $Con->prepare($totalQuery);
    $totalStmt->bind_param("i", $ID);
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRecords = $totalResult->fetch_assoc()['total'];

    // Consulta de datos con orden y paginación
    $dataQuery = "SELECT * FROM cp_requisicion_temp t
                    LEFT JOIN cp_productos p ON t.id_producto_t = p.id_producto
                    WHERE t.id_usuario_t = ? AND t.confirmado = 0
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