<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";
// $Pagina=basename(__FILE__);
// Historial($Pagina,$Con);

// Procesa las columnas de búsqueda
$searchColumns = $_POST['columns'];
$whereClauses = [];

$globalSearch = isset($_POST['search']['value']) ? $Con->real_escape_string($_POST['search']['value']) : '';

// Mapeo de columnas
$columnsMap = [
    0 => 'username',
    1 => 'codigo_s',
    2 => 'nombre_completo',
    3 => 'cargo',
    4 => 'departamento',
    5 => 'rol',
    6 => 'estado',
];

foreach ($searchColumns as $index => $column) {
    if (!empty($column['search']['value'])) {
        $searchValue = $Con->real_escape_string($column['search']['value']);
        
        $columnName = isset($columnsMap[$index]) ? $columnsMap[$index] : '';

        if (!empty($columnName)) {
            $whereClauses[] = "$columnName LIKE '%$searchValue%'";
        }
    }
}

if (!empty($globalSearch)) {
    $globalSearchClauses = [];

    // Añadir búsqueda para el resto de columnas
    foreach ($columnsMap as $index => $column) {
            $globalSearchClauses[] = "$column LIKE '%$globalSearch%'";
    }

    // Combina todas las cláusulas en una sola
    $whereClauses[] = '(' . implode(' OR ', $globalSearchClauses) . ')';
}

$whereSQL = '';
if (!empty($whereClauses)) {
    if ($TipoRol != "") {
        $whereSQL = "AND " . implode(" AND ", $whereClauses);
    } else {
        $whereSQL = " AND " . implode(" AND ", $whereClauses);
    }
}

// Procesa el ordenamiento
$orderColumnIndex = isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0; // Índice de la columna por defecto
$orderDir = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc'; // Dirección por defecto
$orderColumn = isset($columnsMap[$orderColumnIndex]) ? $columnsMap[$orderColumnIndex] : 'id_sede'; // Columna por defecto

// Procesa la paginación
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 25;

if ($TipoRol == "ADMINISTRADOR") {
    $totalQuery = "SELECT COUNT(*) as total FROM usuarios
                    LEFT JOIN cargos ON usuarios.id_cargo = cargos.id_cargo
                    LEFT JOIN roles ON usuarios.id_rol = roles.id_rol
                    LEFT JOIN sedes ON cargos.id_sede_u = sedes.id_sede
                    WHERE usuarios.estado IN (0,1) $whereSQL";
    $totalStmt = $Con->prepare($totalQuery);
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRecords = $totalResult->fetch_assoc()['total'];

    // Consulta de datos con orden y paginación
    $dataQuery = "SELECT * FROM usuarios
                    LEFT JOIN cargos ON usuarios.id_cargo = cargos.id_cargo
                    LEFT JOIN roles ON usuarios.id_rol = roles.id_rol
                    LEFT JOIN sedes ON cargos.id_sede_u = sedes.id_Sede
                    WHERE usuarios.estado IN (0,1) $whereSQL
                    ORDER BY $orderColumn $orderDir
                    LIMIT ?, ?";
    $dataStmt = $Con->prepare($dataQuery);
    $dataStmt->bind_param("ii", $start, $length);
    $dataStmt->execute();
    $dataResult = $dataStmt->get_result();
    
} else {
    $totalQuery = "SELECT COUNT(*) as total FROM usuarios
                    LEFT JOIN cargos ON usuarios.id_cargo = cargos.id_cargo
                    LEFT JOIN roles ON usuarios.id_rol = roles.id_rol
                    LEFT JOIN sedes ON cargos.id_sede_u = sedes.id_Sede
                    WHERE usuarios.estado IN (0,1) $whereSQL";
    $totalStmt = $Con->prepare($totalQuery);
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRecords = $totalResult->fetch_assoc()['total'];

    $dataQuery = "SELECT * FROM usuarios
                    LEFT JOIN cargos ON usuarios.id_cargo = cargos.id_cargo
                    LEFT JOIN roles ON usuarios.id_rol = roles.id_rol
                    LEFT JOIN sedes ON cargos.id_sede_u = sedes.id_Sede
                    WHERE usuarios.estado IN (0,1) $whereSQL
                    ORDER BY $orderColumn $orderDir
                    LIMIT ?, ?";
    $dataStmt = $Con->prepare($dataQuery);
    $dataStmt->bind_param("ii", $start, $length);
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