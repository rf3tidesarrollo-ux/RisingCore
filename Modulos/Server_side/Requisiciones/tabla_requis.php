<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";
if(isset($_SESSION['ID']) == false){
    echo json_encode(['expired' => true]);
    exit;
}

$FechaA = date('%Wy%');

// Mapeo de columnas
$columnsMap = [
    'folio_m' => 'folio_requ',
    'codigo_s' => 'codigo_s',
    'dep' => 'dep',
    'departamento' => 'departamento',
    'solicitante' => 'solicitante',
    'cant_producto' => 'cant_producto',
    'fecha_req' => 'fecha_req',
    'estado_req' => 'estado_req',
    'username' => 'username'
];

// Procesa las columnas de búsqueda
$searchColumns = $_POST['columns'];
$whereClauses = [];

$globalSearch = isset($_POST['search']['value']) ? $Con->real_escape_string($_POST['search']['value']) : '';

// Búsqueda individual por columna
foreach ($searchColumns as $index => $column) {
    if (!empty($column['search']['value'])) {
        $searchValue = $Con->real_escape_string($column['search']['value']);
        $rawColName = $column['data'] ?? '';

        if (isset($columnMap[$rawColName])) {
            $columnName = $columnMap[$rawColName];
        } else {
            // Si no está en el mapeo, usar nombre crudo escapado (por seguridad)
            $columnName = $Con->real_escape_string($rawColName);
        }

        if (!empty($columnName)) {
            if (preg_match('/^\^.*\$$/', $searchValue)) {
                // ===== Caso SELECT: coincidencia exacta =====
                $value = substr($searchValue, 1, -1); 
                $value = str_replace("\\", "", $value);
                $whereClauses[] = "$columnName = '$value'";
            } else {
                // ===== Caso INPUT: texto o fecha =====
                if ($columnName === 'r.fecha_reg') {
                    // Comparar fecha exacta
                    $whereClauses[] = "DATE($columnName) = '$searchValue'";
                } else {
                    // Texto normal con LIKE
                    $whereClauses[] = "$columnName LIKE '%$searchValue%'";
                }
            }
        }
    }
}

// Búsqueda global
if (!empty($globalSearch)) {
    $globalSearchClauses = [];
    foreach ($searchColumns as $column) {
        $rawColName = $column['data'] ?? '';
        if (isset($columnMap[$rawColName])) {
            $columnName = $columnMap[$rawColName];
        } else {
            $columnName = $Con->real_escape_string($rawColName);
        }
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
$orderColumnRaw = $_POST['columns'][$orderColumnIndex]['data'] ?? 'r.folio_req';
$orderColumn = $columnMap[$orderColumnRaw] ?? $Con->real_escape_string($orderColumnRaw);

// Paginación
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 25;

if ($TipoRol == "ADMINISTRADOR" || $TipoRol = "SUPERVISOR") {
    $totalQuery = "SELECT COUNT(*) as total FROM cp_requisiciones r
                    LEFT JOIN usuarios u ON r.id_usuario_req = u.id_usuario
                    LEFT JOIN sedes s ON r.id_sede_req = s.id_sede
                    LEFT JOIN rh_departamentos d ON r.id_area_req = d.id_departamento
                    LEFT JOIN cp_departamentos p ON d.id_dep_d = p.id_dep
                    WHERE r.activo_req = 1 $whereSQL";
    $totalStmt = $Con->prepare($totalQuery);
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRecords = $totalResult->fetch_assoc()['total'];

    // Consulta de datos con orden y paginación
    $dataQuery = "SELECT r.*, u.username, s.codigo_s, p.dep, d.departamento FROM cp_requisiciones r
                    LEFT JOIN usuarios u ON r.id_usuario_req = u.id_usuario
                    LEFT JOIN sedes s ON r.id_sede_req = s.id_sede
                    LEFT JOIN rh_departamentos d ON r.id_area_req = d.id_departamento
                    LEFT JOIN cp_departamentos p ON d.id_dep_d = p.id_dep
                    WHERE r.activo_req = 1 $whereSQL
                    ORDER BY $orderColumn $orderDir
                    LIMIT ?, ?";
    $dataStmt = $Con->prepare($dataQuery);
    $dataStmt->bind_param("ii", $start, $length);
    $dataStmt->execute();
    $dataResult = $dataStmt->get_result();
    
} else {
    $totalQuery = "SELECT COUNT(*) as total FROM cp_requisiciones r
                    LEFT JOIN usuarios u ON r.id_usuario_req = u.id_usuario
                    LEFT JOIN sedes s ON r.id_sede_req = s.id_sede
                    LEFT JOIN rh_departamentos d ON r.id_area_req = d.id_departamento
                    LEFT JOIN cp_departamentos p ON d.id_dep_d = p.id_dep
                    WHERE r.activo_req = 1 AND r.id_sede_req = ? AND r.id_area_req = ? $whereSQL";
    $totalStmt = $Con->prepare($totalQuery);
    $totalStmt->bind_param("iii", $Sede, $IDArea, $Sede);
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRecords = $totalResult->fetch_assoc()['total'];

    $dataQuery = "SELECT r.*, u.username, s.codigo_s, p.dep, d.departamento FROM cp_requisiciones r
                    LEFT JOIN usuarios u ON r.id_usuario_req = u.id_usuario
                    LEFT JOIN sedes s ON r.id_sede_req = s.id_sede
                    LEFT JOIN rh_departamentos d ON r.id_area_req = d.id_departamento
                    LEFT JOIN cp_departamentos p ON d.id_dep_d = p.id_dep
                    WHERE r.activo_req = 1 AND r.id_sede_req = ? AND r.id_area_req = ? $whereSQL
                    ORDER BY $orderColumn $orderDir
                    LIMIT ?, ?";
    $dataStmt = $Con->prepare($dataQuery);
    $dataStmt->bind_param("iiiii", $Sede, $IDArea, $Sede, $start, $length);
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