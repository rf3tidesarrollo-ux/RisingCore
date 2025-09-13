<?php
include_once '../../Conexion/BD.php';
$RutaCS = "../../Login/Cerrar.php";
$RutaSC = "../../index.php";
include_once "../../Login/validar_sesion.php";
// $Pagina=basename(__FILE__);
// Historial($Pagina,$Con);
$Tipo1="INDIVIDUAL";
$Tipo2="RACIMO";

// Procesa las columnas de búsqueda
$searchColumns = $_POST['columns'];
$whereClauses = [];

$globalSearch = isset($_POST['search']['value']) ? $Con->real_escape_string($_POST['search']['value']) : '';

// Mapeo de columnas
$columnsMap = [
    0 => 'no_serie_r',
    1 => 'codigo',
    2 => 'codigo_s',
    3 => 'nombre_variedad',
    4 => 'nombre_p',
    5 => 'invernadero',
    6 => 'folio_carro',
    7 => 'nombre_tarima',
    8 => 'cantidad_tarima',
    9 => 'tipo_caja',
    10 => 'cantidad_caja',
    11 => 'p_bruto',
    12 => 'p_taraje',
    13 => 'p_neto',
    14 => 'fecha_reg',
    15 => 'hora_r'
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
    $totalQuery = "SELECT COUNT(*) as total FROM registro_empaque
                    JOIN tipo_variaciones ON registro_empaque.id_codigo_r = tipo_variaciones.id_variedad
                    JOIN tipos_cajas ON registro_empaque.id_tipo_caja = tipos_cajas.id_caja
                    JOIN tipos_tarimas ON registro_empaque.id_tipo_tarima = tipos_tarimas.id_tarima
                    JOIN tipos_carros ON registro_empaque.id_tipo_carro = tipos_carros.id_carro
                    JOIN variedades ON tipo_variaciones.id_nombre_v = variedades.id_nombre_v
                    JOIN tipos_presentacion ON registro_empaque.id_presentacion_r = tipos_presentacion.id_presentacion
                    JOIN ciclos ON tipo_variaciones.id_ciclo_v = ciclos.id_ciclo
                    JOIN invernaderos ON tipo_variaciones.id_modulo_v = invernaderos.id_invernadero
                    JOIN sedes ON invernaderos.id_sede_i = sedes.id_sede
                    WHERE tipos_presentacion.nombre_p IN ('INDIVIDUAL', 'RACIMO') AND activo_r = 1 $whereSQL";
     $totalStmt = $Con->prepare($totalQuery);
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRecords = $totalResult->fetch_assoc()['total'];

    // Consulta de datos con orden y paginación
    $dataQuery = "SELECT * FROM registro_empaque
                    JOIN tipo_variaciones ON registro_empaque.id_codigo_r = tipo_variaciones.id_variedad
                    JOIN tipos_cajas ON registro_empaque.id_tipo_caja = tipos_cajas.id_caja
                    JOIN tipos_tarimas ON registro_empaque.id_tipo_tarima = tipos_tarimas.id_tarima
                    JOIN tipos_carros ON registro_empaque.id_tipo_carro = tipos_carros.id_carro
                    JOIN variedades ON tipo_variaciones.id_nombre_v = variedades.id_nombre_v
                    JOIN tipos_presentacion ON registro_empaque.id_presentacion_r = tipos_presentacion.id_presentacion
                    JOIN ciclos ON tipo_variaciones.id_ciclo_v = ciclos.id_ciclo
                    JOIN invernaderos ON tipo_variaciones.id_modulo_v = invernaderos.id_invernadero
                    JOIN sedes ON invernaderos.id_sede_i = sedes.id_sede
                    WHERE tipos_presentacion.nombre_p IN ('INDIVIDUAL', 'RACIMO') AND activo_r = 1 $whereSQL
                    ORDER BY $orderColumn $orderDir
                    LIMIT ?, ?";
    $dataStmt = $Con->prepare($dataQuery);
    $dataStmt->bind_param("ii", $start, $length);
    $dataStmt->execute();
    $dataResult = $dataStmt->get_result();
    
} else {
    $totalQuery = "SELECT COUNT(*) as total FROM registro_empaque
                    JOIN tipo_variaciones ON registro_empaque.id_codigo_r = tipo_variaciones.id_variedad
                    JOIN tipos_cajas ON registro_empaque.id_tipo_caja = tipos_cajas.id_caja
                    JOIN tipos_tarimas ON registro_empaque.id_tipo_tarima = tipos_tarimas.id_tarima
                    JOIN tipos_carros ON registro_empaque.id_tipo_carro = tipos_carros.id_carro
                    JOIN variedades ON tipo_variaciones.id_nombre_v = variedades.id_nombre_v
                    JOIN tipos_presentacion ON registro_empaque.id_presentacion_r = tipos_presentacion.id_presentacion
                    JOIN ciclos ON tipo_variaciones.id_ciclo_v = ciclos.id_ciclo
                    JOIN invernaderos ON tipo_variaciones.id_modulo_v = invernaderos.id_invernadero
                    JOIN sedes ON invernaderos.id_sede_i = sedes.id_sede
                    WHERE tipos_presentacion.nombre_p IN ('INDIVIDUAL', 'RACIMO') AND invernaderos.id_Sede_i = ? AND activo_r = 1 $whereSQL";
    $totalStmt = $Con->prepare($totalQuery);
    $totalStmt->bind_param("i", $Sede);
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRecords = $totalResult->fetch_assoc()['total'];

    $dataQuery = "SELECT * FROM registro_empaque
                    JOIN tipo_variaciones ON registro_empaque.id_codigo_r = tipo_variaciones.id_variedad
                    JOIN tipos_cajas ON registro_empaque.id_tipo_caja = tipos_cajas.id_caja
                    JOIN tipos_tarimas ON registro_empaque.id_tipo_tarima = tipos_tarimas.id_tarima
                    JOIN tipos_carros ON registro_empaque.id_tipo_carro = tipos_carros.id_carro
                    JOIN variedades ON tipo_variaciones.id_nombre_v = variedades.id_nombre_v
                    JOIN tipos_presentacion ON registro_empaque.id_presentacion_r = tipos_presentacion.id_presentacion
                    JOIN ciclos ON tipo_variaciones.id_ciclo_v = ciclos.id_ciclo
                    JOIN invernaderos ON tipo_variaciones.id_modulo_v = invernaderos.id_invernadero
                    JOIN sedes ON invernaderos.id_sede_i = sedes.id_sede
                    WHERE tipos_presentacion.nombre_p IN ('INDIVIDUAL', 'RACIMO') AND invernaderos.id_Sede_i = ? AND activo_r = 1 $whereSQL
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