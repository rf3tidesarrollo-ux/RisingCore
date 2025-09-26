<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";
// $Pagina=basename(__FILE__);
// Historial($Pagina,$Con);
$IDP = $_GET['id'];

$orderColumnIndex = isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0; // Índice de la columna por defecto
$orderDir = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc'; // Dirección por defecto
$orderColumn = isset($columnsMap[$orderColumnIndex]) ? $columnsMap[$orderColumnIndex] : 'id_pallet_temp'; // Columna por defecto

// Procesa la paginación
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 5;

if ($IDP == 0) {
    $totalQuery = "SELECT COUNT(*) as total FROM pallet_mezclas_temp p
                    LEFT JOIN mezclas m ON p.id_mezcla_t = m.id_mezcla
                    LEFT JOIN clientes c ON m.id_cliente_m = c.id_cliente
                    LEFT JOIN empaque_lineas l ON p.id_linea_t = l.id_linea
                    LEFT JOIN sedes s ON m.id_sede_m = s.id_sede
                    WHERE p.usuario_id = ? AND confirmado = 0";
    $totalStmt = $Con->prepare($totalQuery);
    $totalStmt->bind_param("i", $ID);
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRecords = $totalResult->fetch_assoc()['total'];

    // Consulta de datos con orden y paginación
    $dataQuery = "SELECT * FROM pallet_mezclas_temp p
                    LEFT JOIN mezclas m ON p.id_mezcla_t = m.id_mezcla
                    LEFT JOIN clientes c ON m.id_cliente_m = c.id_cliente
                    LEFT JOIN empaque_lineas l ON p.id_linea_t = l.id_linea
                    LEFT JOIN sedes s ON m.id_sede_m = s.id_sede
                    WHERE p.usuario_id = ? AND confirmado = 0
                    ORDER BY $orderColumn $orderDir
                    LIMIT ?, ?";
    $dataStmt = $Con->prepare($dataQuery);
    $dataStmt->bind_param("iii", $ID, $start, $length);
    $dataStmt->execute();
    $dataResult = $dataStmt->get_result();
}else{
    $stmtCheckTemp = $Con->prepare("SELECT COUNT(*) as total FROM pallet_mezclas_temp WHERE usuario_id = ? AND confirmado = 0");
    $stmtCheckTemp->bind_param("i", $ID);
    $stmtCheckTemp->execute();
    $resultTemp = $stmtCheckTemp->get_result();
    $tempCount = $resultTemp->fetch_assoc()['total'];

    if ($tempCount == 0) {
        $stmtCopy = $Con->prepare("SELECT id_pallet_m, id_mezcla_m, cajas_m, id_linea_m FROM pallet_mezclas WHERE id_pallet_m = ?");
        $stmtCopy->bind_param("i", $IDP);
        $stmtCopy->execute();
        $resultCopy = $stmtCopy->get_result();

        $stmtInsertTemp = $Con->prepare("INSERT INTO pallet_mezclas_temp (id_mezcla_t, cajas_t, id_linea_t, usuario_id, confirmado) VALUES (?, ?, ?, ?, 0)");
        while ($row = $resultCopy->fetch_assoc()) {
            $stmtInsertTemp->bind_param("iiii", $row['id_mezcla_m'], $row['cajas_m'], $row['id_linea_m'], $ID);
            $stmtInsertTemp->execute();
        }
    }
    
    $totalQuery = "SELECT COUNT(*) as total FROM pallet_mezclas_temp p
                    LEFT JOIN mezclas m ON p.id_mezcla_t = m.id_mezcla
                    LEFT JOIN clientes c ON m.id_cliente_m = c.id_cliente
                    LEFT JOIN empaque_lineas l ON p.id_linea_t = l.id_linea
                    LEFT JOIN sedes s ON m.id_sede_m = s.id_sede
                    WHERE p.usuario_id = ? AND confirmado = 0";
    $totalStmt = $Con->prepare($totalQuery);
    $totalStmt->bind_param("i", $ID);
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRecords = $totalResult->fetch_assoc()['total'];

    // Consulta de datos con orden y paginación
    $dataQuery = "SELECT *, p.cajas_t AS Cajas FROM pallet_mezclas_temp p
                    LEFT JOIN mezclas m ON p.id_mezcla_t = m.id_mezcla
                    LEFT JOIN clientes c ON m.id_cliente_m = c.id_cliente
                    LEFT JOIN empaque_lineas l ON p.id_linea_t = l.id_linea
                    LEFT JOIN sedes s ON m.id_sede_m = s.id_sede
                    WHERE p.usuario_id = ? AND confirmado = 0
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