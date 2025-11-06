<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";
if(isset($_SESSION['ID']) == false){
    echo json_encode(['expired' => true]);
    exit;
}

// Mapeo de columnas
$columnsMap = [
    'folio_m' => 'folio_m',
    'codigo_s' => 'codigo_s',
    'nombre_cliente' => 'nombre_cliente',
    'cajas_t' => 'cajas_t',
    'kilos_t' => 'kilos_t',
    'fecha_m' => 'fecha_m',
    'hora_m' => 'hora_m',
    'nombre_completo' => 'nombre_completo',
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
$orderColumnRaw = $_POST['columns'][$orderColumnIndex]['data'] ?? 'm.id_registro_r';
$orderColumn = $columnMap[$orderColumnRaw] ?? $Con->real_escape_string($orderColumnRaw);

// Paginación
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 25;

if ($TipoRol == "ADMINISTRADOR") {
    $totalQuery = "SELECT COUNT(*) as total FROM mezclas m
                    LEFT JOIN usuarios u ON m.id_usuario_m = u.id_usuario
                    LEFT JOIN cargos cr ON u.id_cargo = cr.id_cargo
                    LEFT JOIN clientes cl ON m.id_cliente_m = cl.id_cliente
                    LEFT JOIN sedes s ON m.id_sede_m = s.id_sede
                    LEFT JOIN mezcla_lotes ml ON m.id_mezcla = ml.id_mezcla_l
                    LEFT JOIN registro_empaque p ON ml.id_lote_l = p.id_registro_r
                    LEFT JOIN tipo_variaciones tv ON p.id_codigo_r = tv.id_variedad
                    LEFT JOIN ciclos c ON tv.id_ciclo_v = c.id_ciclo
                    WHERE m.activo_m = 1 AND c.activo_c = 1 $whereSQL";
    $totalStmt = $Con->prepare($totalQuery);
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRecords = $totalResult->fetch_assoc()['total'];

    // Consulta de datos con orden y paginación
    $dataQuery = "SELECT m.*, cr.nombre_completo, s.codigo_s, cl.nombre_cliente FROM mezclas m
                    LEFT JOIN usuarios u ON m.id_usuario_m = u.id_usuario
                    LEFT JOIN cargos cr ON u.id_cargo = cr.id_cargo
                    LEFT JOIN clientes cl ON m.id_cliente_m = cl.id_cliente
                    LEFT JOIN sedes s ON m.id_sede_m = s.id_sede
                    LEFT JOIN mezcla_lotes ml ON m.id_mezcla = ml.id_mezcla_l
                    LEFT JOIN registro_empaque p ON ml.id_lote_l = p.id_registro_r
                    LEFT JOIN tipo_variaciones tv ON p.id_codigo_r = tv.id_variedad
                    LEFT JOIN ciclos c ON tv.id_ciclo_v = c.id_ciclo
                    WHERE m.activo_m = 1 AND c.activo_c = 1 $whereSQL
                    ORDER BY $orderColumn $orderDir
                    LIMIT ?, ?";
    $dataStmt = $Con->prepare($dataQuery);
    $dataStmt->bind_param("ii", $start, $length);
    $dataStmt->execute();
    $dataResult = $dataStmt->get_result();
    
} else {
    $totalQuery = "SELECT COUNT(*) as total FROM mezclas m
                    LEFT JOIN usuarios u ON m.id_usuario_m = u.id_usuario
                    LEFT JOIN cargos cr ON u.id_cargo = cr.id_cargo
                    LEFT JOIN clientes cl ON m.id_cliente_m = cl.id_cliente
                    LEFT JOIN sedes s ON m.id_sede_m = s.id_sede
                    LEFT JOIN mezcla_lotes ml ON m.id_mezcla = ml.id_mezcla_l
                    LEFT JOIN registro_empaque p ON ml.id_lote_l = p.id_registro_r
                    LEFT JOIN tipo_variaciones tv ON p.id_codigo_r = tv.id_variedad
                    LEFT JOIN ciclos c ON tv.id_ciclo_v = c.id_ciclo
                    WHERE m.activo_m = 1 AND c.activo_c = 1 AND id_sede_m = ? $whereSQL";
    $totalStmt = $Con->prepare($totalQuery);
    $totalStmt->bind_param("i", $Sede);
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRecords = $totalResult->fetch_assoc()['total'];

    $dataQuery = "SELECT m.*, cr.nombre_completo, s.codigo_s, cl.nombre_cliente FROM mezclas m
                    LEFT JOIN usuarios u ON m.id_usuario_m = u.id_usuario
                    LEFT JOIN cargos cr ON u.id_cargo = cr.id_cargo
                    LEFT JOIN clientes cl ON m.id_cliente_m = cl.id_cliente
                    LEFT JOIN sedes s ON m.id_sede_m = s.id_sede
                    LEFT JOIN mezcla_lotes ml ON m.id_mezcla = ml.id_mezcla_l
                    LEFT JOIN registro_empaque p ON ml.id_lote_l = p.id_registro_r
                    LEFT JOIN tipo_variaciones tv ON p.id_codigo_r = tv.id_variedad
                    LEFT JOIN ciclos c ON tv.id_ciclo_v = c.id_ciclo
                    WHERE m.activo_m = 1 AND c.activo_c = 1 AND id_sede_m = ? $whereSQL
                    ORDER BY $orderColumn $orderDir
                    LIMIT ?, ?";
    $dataStmt = $Con->prepare($dataQuery);
    $dataStmt->bind_param("iii", $Sede, $start, $length);
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