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
    'no_serie_m' => 'no_serie_r',
    'codigo' => 'codigo',
    'codigo_s' => 'codigo_s',
    'nombre_variedad' => 'nombre_variedad',
    'nombre_p' => 'nombre_p',
    'invernadero' => 'invernadero',
    'folio_carro' => 'folio_carro',
    'nombre_tarima' => 'nombre_tarima',
    'cantidad_tarima' => 'cantidad_tarima',
    'tipo_caja' => 'tipo_caja',
    'cantidad_tarima' => 'cantidad_tarima',
    'p_bruto' => 'p_bruto',
    'p_taraje' => 'p_taraje',
    'p_neto' => 'p_neto',
    'kilos_dis' => 'kilos_dis',
    'cajas_dis' => 'cajas_dis',
    'semana_r' => 'semana_r',
    'fecha_reg' => 'fecha_reg',
    'hora_r' => 'hora_r',
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
    $totalQuery = "SELECT COUNT(*) as total FROM registro_empaque
                    LEFT JOIN tipo_variaciones ON registro_empaque.id_codigo_r = tipo_variaciones.id_variedad
                    LEFT JOIN tipos_cajas ON registro_empaque.id_tipo_caja = tipos_cajas.id_caja
                    LEFT JOIN tipos_tarimas ON registro_empaque.id_tipo_tarima = tipos_tarimas.id_tarima
                    LEFT JOIN tipos_carros ON registro_empaque.id_tipo_carro = tipos_carros.id_carro
                    LEFT JOIN variedades ON tipo_variaciones.id_nombre_v = variedades.id_nombre_v
                    LEFT JOIN tipos_presentacion ON registro_empaque.id_presentacion_r = tipos_presentacion.id_presentacion
                    LEFT JOIN ciclos ON tipo_variaciones.id_ciclo_v = ciclos.id_ciclo
                    LEFT JOIN invernaderos ON tipo_variaciones.id_modulo_v = invernaderos.id_invernadero
                    LEFT JOIN sedes ON invernaderos.id_sede_i = sedes.id_sede
                    WHERE activo_r = 1 AND activo_c = 1 $whereSQL";
     $totalStmt = $Con->prepare($totalQuery);
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRecords = $totalResult->fetch_assoc()['total'];

    // Consulta de datos con orden y paginación
    $dataQuery = "SELECT * FROM registro_empaque
                    LEFT JOIN tipo_variaciones ON registro_empaque.id_codigo_r = tipo_variaciones.id_variedad
                    LEFT JOIN tipos_cajas ON registro_empaque.id_tipo_caja = tipos_cajas.id_caja
                    LEFT JOIN tipos_tarimas ON registro_empaque.id_tipo_tarima = tipos_tarimas.id_tarima
                    LEFT JOIN tipos_carros ON registro_empaque.id_tipo_carro = tipos_carros.id_carro
                    LEFT JOIN variedades ON tipo_variaciones.id_nombre_v = variedades.id_nombre_v
                    LEFT JOIN tipos_presentacion ON registro_empaque.id_presentacion_r = tipos_presentacion.id_presentacion
                    LEFT JOIN ciclos ON tipo_variaciones.id_ciclo_v = ciclos.id_ciclo
                    LEFT JOIN invernaderos ON tipo_variaciones.id_modulo_v = invernaderos.id_invernadero
                    LEFT JOIN sedes ON invernaderos.id_sede_i = sedes.id_sede
                    WHERE activo_r = 1 AND activo_c = 1 $whereSQL
                    ORDER BY $orderColumn $orderDir
                    LIMIT ?, ?";
    $dataStmt = $Con->prepare($dataQuery);
    $dataStmt->bind_param("ii", $start, $length);
    $dataStmt->execute();
    $dataResult = $dataStmt->get_result();
    
} else {
    $totalQuery = "SELECT COUNT(*) as total FROM registro_empaque
                    LEFT JOIN tipo_variaciones ON registro_empaque.id_codigo_r = tipo_variaciones.id_variedad
                    LEFT JOIN tipos_cajas ON registro_empaque.id_tipo_caja = tipos_cajas.id_caja
                    LEFT JOIN tipos_tarimas ON registro_empaque.id_tipo_tarima = tipos_tarimas.id_tarima
                    LEFT JOIN tipos_carros ON registro_empaque.id_tipo_carro = tipos_carros.id_carro
                    LEFT JOIN variedades ON tipo_variaciones.id_nombre_v = variedades.id_nombre_v
                    LEFT JOIN tipos_presentacion ON registro_empaque.id_presentacion_r = tipos_presentacion.id_presentacion
                    LEFT JOIN ciclos ON tipo_variaciones.id_ciclo_v = ciclos.id_ciclo
                    LEFT JOIN invernaderos ON tipo_variaciones.id_modulo_v = invernaderos.id_invernadero
                    LEFT JOIN sedes ON invernaderos.id_sede_i = sedes.id_sede
                    WHERE invernaderos.id_Sede_i = ? AND activo_r = 1 AND activo_c = 1 $whereSQL";
    $totalStmt = $Con->prepare($totalQuery);
    $totalStmt->bind_param("i", $Sede);
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRecords = $totalResult->fetch_assoc()['total'];

    $dataQuery = "SELECT * FROM registro_empaque
                    LEFT JOIN tipo_variaciones ON registro_empaque.id_codigo_r = tipo_variaciones.id_variedad
                    LEFT JOIN tipos_cajas ON registro_empaque.id_tipo_caja = tipos_cajas.id_caja
                    LEFT JOIN tipos_tarimas ON registro_empaque.id_tipo_tarima = tipos_tarimas.id_tarima
                    LEFT JOIN tipos_carros ON registro_empaque.id_tipo_carro = tipos_carros.id_carro
                    LEFT JOIN variedades ON tipo_variaciones.id_nombre_v = variedades.id_nombre_v
                    LEFT JOIN tipos_presentacion ON registro_empaque.id_presentacion_r = tipos_presentacion.id_presentacion
                    LEFT JOIN ciclos ON tipo_variaciones.id_ciclo_v = ciclos.id_ciclo
                    LEFT JOIN invernaderos ON tipo_variaciones.id_modulo_v = invernaderos.id_invernadero
                    LEFT JOIN sedes ON invernaderos.id_sede_i = sedes.id_sede
                    WHERE invernaderos.id_Sede_i = ? AND activo_r = 1 AND activo_c = 1 $whereSQL
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