<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";
// $Pagina=basename(__FILE__);
// Historial($Pagina,$Con);
$id = $_POST['embarque_id'];

$orderColumnIndex = isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0; // Índice de la columna por defecto
$orderDir = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc'; // Dirección por defecto
$orderColumn = isset($columnsMap[$orderColumnIndex]) ? $columnsMap[$orderColumnIndex] : 'id_pallet'; // Columna por defecto

// Procesa la paginación
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 5;

    $totalQuery = "SELECT COUNT(*) as total FROM pallets p
                            JOIN presentaciones_pallet pp ON p.id_presen_p = pp.id_presentacion_p
                            WHERE p.id_embarque_p = ?";
    $totalStmt = $Con->prepare($totalQuery);
    $totalStmt->bind_param("i", $id);
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRecords = $totalResult->fetch_assoc()['total'];

    // Consulta de datos con orden y paginación
    $dataQuery = "SELECT p.id_pallet, p.folio_p, p.cajas_p, pp.presentacion, p.mapeo, p.ubicacion
                    FROM pallets p
                    JOIN presentaciones_pallet pp ON p.id_presen_p = pp.id_presentacion_p
                    WHERE p.id_embarque_p = ?
                    ORDER BY $orderColumn $orderDir
                    LIMIT ?, ?";
    $dataStmt = $Con->prepare($dataQuery);
    $dataStmt->bind_param("iii", $id, $start, $length);
    $dataStmt->execute();
    $dataResult = $dataStmt->get_result();
    
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