<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";
if(isset($_SESSION['ID']) == false){
    echo json_encode(['expired' => true]);
    exit;
}

// Mapeo columnas front => columnas con alias correctos en SQL
$columnMap = [
    'no_serie_m'     => 'm.no_serie_m',
    'tipo_merma'     => 'cm.tipo_merma',
    'motivo'         => 'cm.motivo',
    'folio_carro'    => 'tc.folio_carro',
    'nombre_tarima'  => 'tt.nombre_tarima',
    'cantidad_tarima'=> 'm.cantidad_tarima',
    'tipo_caja'      => 'tb.tipo_caja',
    'cantidad_caja'  => 'm.cantidad_caja',
    'p_bruto'        => 'm.p_bruto',
    'p_neto'         => 'm.p_neto',
    'semana_m'       => 'm.semana_m',
    'fecha_reg'      => 'm.fecha_reg',
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
                if ($columnName === 'm.fecha_reg') {
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

// Consulta para obtener el total y los datos
if ($TipoRol == "ADMINISTRADOR") {
    $totalQuery = "SELECT COUNT(*) as total FROM registro_merma m
        LEFT JOIN tipo_variaciones tv ON m.id_codigo_m = tv.id_variedad
        LEFT JOIN tipos_cajas tb ON m.id_tipo_caja = tb.id_caja
        LEFT JOIN tipos_tarimas tt ON m.id_tipo_tarima = tt.id_tarima
        LEFT JOIN tipos_carros tc ON m.id_tipo_carro = tc.id_carro
        LEFT JOIN variedades v ON tv.id_nombre_v = v.id_nombre_v
        LEFT JOIN tipos_presentacion tp ON tv.id_presentacion_v = tp.id_presentacion
        LEFT JOIN ciclos c ON tv.id_ciclo_v = c.id_ciclo
        LEFT JOIN invernaderos i ON tv.id_modulo_v = i.id_invernadero
        LEFT JOIN sedes s ON i.id_sede_i = s.id_sede
        LEFT JOIN clasificacion_merma cm ON m.id_clasificacion = cm.id_merma
        WHERE activo_m = 1 AND (c.activo_c = 1 OR m.id_codigo_m IS NULL) $whereSQL";

    $totalStmt = $Con->prepare($totalQuery);
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRecords = $totalResult->fetch_assoc()['total'];
    
    $dataQuery = "SELECT * FROM registro_merma m
        LEFT JOIN tipo_variaciones tv ON m.id_codigo_m = tv.id_variedad
        LEFT JOIN tipos_cajas tb ON m.id_tipo_caja = tb.id_caja
        LEFT JOIN tipos_tarimas tt ON m.id_tipo_tarima = tt.id_tarima
        LEFT JOIN tipos_carros tc ON m.id_tipo_carro = tc.id_carro
        LEFT JOIN variedades v ON tv.id_nombre_v = v.id_nombre_v
        LEFT JOIN tipos_presentacion tp ON tv.id_presentacion_v = tp.id_presentacion
        LEFT JOIN ciclos c ON tv.id_ciclo_v = c.id_ciclo
        LEFT JOIN invernaderos i ON tv.id_modulo_v = i.id_invernadero
        LEFT JOIN sedes s ON i.id_sede_i = s.id_sede
        LEFT JOIN clasificacion_merma cm ON m.id_clasificacion = cm.id_merma
        WHERE activo_m = 1 AND (c.activo_c = 1 OR m.id_codigo_m IS NULL) $whereSQL
        ORDER BY $orderColumn $orderDir
        LIMIT ?, ?";

    $dataStmt = $Con->prepare($dataQuery);
    $dataStmt->bind_param("ii", $start, $length);
    $dataStmt->execute();
    $dataResult = $dataStmt->get_result();


} else {
    $totalQuery = "SELECT COUNT(*) as total FROM registro_merma m
        LEFT JOIN tipo_variaciones tv ON m.id_codigo_m = tv.id_variedad
        LEFT JOIN tipos_cajas tb ON m.id_tipo_caja = tb.id_caja
        LEFT JOIN tipos_tarimas tt ON m.id_tipo_tarima = tt.id_tarima
        LEFT JOIN tipos_carros tc ON m.id_tipo_carro = tc.id_carro
        LEFT JOIN variedades v ON tv.id_nombre_v = v.id_nombre_v
        LEFT JOIN tipos_presentacion tp ON tv.id_presentacion_v = tp.id_presentacion
        LEFT JOIN ciclos c ON tv.id_ciclo_v = c.id_ciclo
        LEFT JOIN invernaderos i ON tv.id_modulo_v = i.id_invernadero
        LEFT JOIN sedes s ON m.id_sede_m = s.id_sede
        LEFT JOIN clasificacion_merma cm ON m.id_clasificacion = cm.id_merma
        WHERE id_sede_m = ? AND activo_m = 1 AND (c.activo_c = 1 OR m.id_codigo_m IS NULL) $whereSQL";

    $totalStmt = $Con->prepare($totalQuery);
    $totalStmt->bind_param("s", $Sede);
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRecords = $totalResult->fetch_assoc()['total'];

    $dataQuery = "SELECT * FROM registro_merma m
        LEFT JOIN tipo_variaciones tv ON m.id_codigo_m = tv.id_variedad
        LEFT JOIN tipos_cajas tb ON m.id_tipo_caja = tb.id_caja
        LEFT JOIN tipos_tarimas tt ON m.id_tipo_tarima = tt.id_tarima
        LEFT JOIN tipos_carros tc ON m.id_tipo_carro = tc.id_carro
        LEFT JOIN variedades v ON tv.id_nombre_v = v.id_nombre_v
        LEFT JOIN tipos_presentacion tp ON tv.id_presentacion_v = tp.id_presentacion
        LEFT JOIN ciclos c ON tv.id_ciclo_v = c.id_ciclo
        LEFT JOIN invernaderos i ON tv.id_modulo_v = i.id_invernadero
        LEFT JOIN sedes s ON m.id_sede_m = s.id_sede
        LEFT JOIN clasificacion_merma cm ON m.id_clasificacion = cm.id_merma
        WHERE id_sede_m = ? AND activo_m = 1 AND (c.activo_c = 1 OR m.id_codigo_m IS NULL) $whereSQL
        ORDER BY $orderColumn $orderDir
        LIMIT ?, ?";

    $dataStmt = $Con->prepare($dataQuery);
    $dataStmt->bind_param("sii", $Sede, $start, $length);
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
