<?php
include_once '../../Conexion/BD.php';
$RutaCS = "../../Login/Cerrar.php";
$RutaSC = "../../index.php";
include_once "../../Login/validar_sesion.php";
// $Pagina=basename(__FILE__);
// Historial($Pagina,$Con);

// Procesa las columnas de búsqueda
$searchColumns = $_POST['columns'];
$whereClauses = [];

$globalSearch = isset($_POST['search']['value']) ? $Con->real_escape_string($_POST['search']['value']) : '';

// Mapeo de columnas
$columnsMap = [
    0 => 'no_serie_m',
    1 => 'tipo_merma',
    2 => 'motivo',
    3 => 'folio_carro',
    4 => 'nombre_tarima',
    5 => 'cantidad_tarima',
    6 => 'tipo_caja',
    7 => 'cantidad_caja',
    8 => 'p_bruto',
    9 => 'p_neto',
    10 => 'semana_m',
    11 => 'fecha_reg',
    12 => 'hora_m'
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
$orderColumn = isset($columnsMap[$orderColumnIndex]) ? $columnsMap[$orderColumnIndex] : 'id_registro_r'; // Columna por defecto

// Procesa la paginación
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 25;

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
                    WHERE activo_m = 1 $whereSQL";
     $totalStmt = $Con->prepare($totalQuery);
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRecords = $totalResult->fetch_assoc()['total'];

    // Consulta de datos con orden y paginación
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
                    WHERE activo_m = 1 $whereSQL
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
                    LEFT JOIN sedes s ON i.id_sede_i = s.id_sede
                    LEFT JOIN clasificacion_merma cm ON m.id_clasificacion = cm.id_merma
                    WHERE invernaderos.codigo_s = ? AND activo_r = 1 $whereSQL";
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
                    LEFT JOIN sedes s ON i.id_sede_i = s.id_sede
                    LEFT JOIN clasificacion_merma cm ON m.id_clasificacion = cm.id_merma
                    WHERE invernaderos.codigo_s = ? AND activo_r = 1 $whereSQL
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