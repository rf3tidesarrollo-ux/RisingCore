<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";
if(isset($_SESSION['ID']) == false){
    echo json_encode(['expired' => true]);
    exit;
}

// Mapeo de columnas (usar siempre $columnMap)
$columnMap = [
    'codigo_s'        => 'codigo_s',
    'badge'           => 'badge',
    'nombre_personal' => 'nombre_personal',
    'genero'          => 'genero',
    'tipo_rh'         => 'tipo_rh',
    'departamento'    => 'departamento',
    'tipo_pago'       => 'tipo_pago',
    'tipo_h'          => 'tipo_h',
    'fecha_ingreso'   => 'fecha_ingreso',
    'fecha_registro'  => 'fecha_registro',
    'nombre_completo' => 'nombre_completo',
];

// Recuperar columnas seguro
$searchColumns = isset($_POST['columns']) ? $_POST['columns'] : [];
$whereClauses = [];

$globalSearchRaw = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
$globalSearch = $Con->real_escape_string($globalSearchRaw);

// Búsqueda individual por columna
foreach ($searchColumns as $index => $column) {
    $rawSearch = isset($column['search']['value']) ? $column['search']['value'] : '';
    if ($rawSearch === '') continue;

    $rawColName = isset($column['data']) ? $column['data'] : '';
    // obtener columna DB del mapa
    $columnName = isset($columnMap[$rawColName]) ? $columnMap[$rawColName] : $Con->real_escape_string($rawColName);

    if (empty($columnName)) continue;

    // Si viene con el patrón ^valor$ (selects de DataTables)
    if (preg_match('/^\^.*\$$/', $rawSearch)) {
        $valueRaw = substr($rawSearch, 1, -1);        // quitar ^ y $
        $valueRaw = stripslashes($valueRaw);         // quitar backslashes (ej. SAM\'S -> SAM'S)
        $value = $Con->real_escape_string($valueRaw); // escapar para SQL
        $whereClauses[] = "$columnName = '$value'";
    } else {
        // input normal (texto o fecha)
        $escaped = $Con->real_escape_string($rawSearch);
        // Ajustar si la columna es fecha (usar DATE())
        if ($columnName === 'p.fecha_ingreso' || $columnName === 'p.fecha_registro') {
            $whereClauses[] = "DATE($columnName) = '$escaped'";
        } else {
            $whereClauses[] = "$columnName LIKE '%$escaped%'";
        }
    }
}

// Búsqueda global (search box)
if ($globalSearchRaw !== '') {
    $globalSearchClauses = [];
    foreach ($searchColumns as $column) {
        $rawColName = isset($column['data']) ? $column['data'] : '';
        $columnName = isset($columnMap[$rawColName]) ? $columnMap[$rawColName] : $Con->real_escape_string($rawColName);
        if (!empty($columnName)) {
            $globalSearchClauses[] = "$columnName LIKE '%$globalSearch%'";
        }
    }
    if (!empty($globalSearchClauses)) {
        $whereClauses[] = '(' . implode(' OR ', $globalSearchClauses) . ')';
    }
}

// Construye el WHERE
$whereSQL = '';
if (!empty($whereClauses)) {
    $whereSQL = " AND " . implode(" AND ", $whereClauses);
}

// Ordenamiento
$orderColumnIndex = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 0;
$orderDir = (isset($_POST['order'][0]['dir']) && in_array(strtolower($_POST['order'][0]['dir']), ['asc','desc'])) 
            ? $_POST['order'][0]['dir'] : 'asc';
$orderColumnRaw = isset($_POST['columns'][$orderColumnIndex]['data']) ? $_POST['columns'][$orderColumnIndex]['data'] : 'fecha_registro';
$orderColumn = isset($columnMap[$orderColumnRaw]) ? $columnMap[$orderColumnRaw] : 'p.fecha_registro';

// Paginación
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 25;

if ($TipoRol == "ADMINISTRADOR" || $TipoRol == "SUPERVISOR") {
    $totalQuery = "SELECT COUNT(*) as total FROM vw_reingresos WHERE 1=1 $whereSQL";
    $totalStmt = $Con->prepare($totalQuery);
    if ($totalStmt === false) { error_log("Prepare totalQuery error: " . $Con->error); }
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRecords = $totalResult->fetch_assoc()['total'];

    // Datos con paginación
    $dataQuery = "SELECT * FROM vw_reingresos WHERE 1=1 $whereSQL ORDER BY $orderColumn $orderDir LIMIT ?, ?";
    $dataStmt = $Con->prepare($dataQuery);
    if ($dataStmt === false) { error_log("Prepare dataQuery error: " . $Con->error); }
    $dataStmt->bind_param("ii", $start, $length);
    $dataStmt->execute();
    $dataResult = $dataStmt->get_result();

} else {
    // No admin -> filtrar por sede
    $totalQuery = "SELECT COUNT(*) as total FROM vw_reingresos WHERE id_sede_pl = ? $whereSQL";
    $totalStmt = $Con->prepare($totalQuery);
    if ($totalStmt === false) { error_log("Prepare totalQuery (sede) error: " . $Con->error); }
    $totalStmt->bind_param("i", $Sede);
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRecords = $totalResult->fetch_assoc()['total'];

    $dataQuery = "SELECT * FROM vw_reingresos WHERE id_sede_pl = ? $whereSQL ORDER BY $orderColumn $orderDir LIMIT ?, ?";
    $dataStmt = $Con->prepare($dataQuery);
    if ($dataStmt === false) { error_log("Prepare dataQuery (sede) error: " . $Con->error); }
    $dataStmt->bind_param("iii", $Sede, $start, $length);
    $dataStmt->execute();
    $dataResult = $dataStmt->get_result();
}

// Construye la respuesta JSON
$response = array(
    "draw" => isset($_POST['draw']) ? intval($_POST['draw']) : 0,
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecords,
    "data" => array()
);

while ($row = $dataResult->fetch_assoc()) {
    $response["data"][] = $row;
}

$json = json_encode($response);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "Error de JSON: " . json_last_error_msg();
} else {
    echo $json;
}
?>
