<?php
include_once '../../Conexion/BD.php';
$RutaCS = "../../Login/Cerrar.php";
$RutaSC = "../../index.php";
include_once "../../Login/validar_sesion.php";
// $Pagina=basename(__FILE__);
// Historial($Pagina,$Con);
$Tipo="MERMA";

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
    4 => 'tipo_merma',
    5 => 'motivo',
    6 => 'nombre_p',
    7 => 'invernadero',
    8 => 'folio_carro',
    9 => 'nombre_tarima',
    10 => 'cantidad_tarima',
    11 => 'tipo_caja',
    12 => 'cantidad_caja',
    13 => 'p_bruto',
    14 => 'p_taraje',
    15 => 'p_neto',
    16 => 'fecha_r',
    17 => 'hora_r'
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
$orderColumn = isset($columnsMap[$orderColumnIndex]) ? $columnsMap[$orderColumnIndex] : 'id_inventario'; // Columna por defecto

// Procesa la paginación
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 25;

if ($TipoRol == "ADMINISTRADOR") {
    $totalQuery = "SELECT COUNT(*) as total FROM registro_empaque
                    LEFT JOIN tipo_variaciones ON registro_empaque.id_codigo_r = tipo_variaciones.id_variedad
                    LEFT JOIN tipos_cajas ON registro_empaque.id_tipo_caja = tipos_cajas.id_caja
                    LEFT JOIN tipos_tarimas ON registro_empaque.id_tipo_tarima = tipos_tarimas.id_tarima
                    LEFT JOIN tipos_carros ON registro_empaque.id_tipo_carro = tipos_carros.id_carro
                    LEFT JOIN variedades ON tipo_variaciones.id_nombre_v = variedades.id_nombre_v
                    LEFT JOIN tipos_presentacion ON tipo_variaciones.id_presentacion_v = tipos_presentacion.id_presentacion
                    LEFT JOIN ciclos ON tipo_variaciones.id_ciclo_v = ciclos.id_ciclo
                    LEFT JOIN invernaderos ON tipo_variaciones.id_modulo_v = invernaderos.id_invernadero
                    LEFT JOIN sedes ON invernaderos.id_sede_i = sedes.id_sede
                    LEFT JOIN clasificacion_merma ON registro_empaque.id_tipo_merma = clasificacion_merma.id_merma
                    WHERE registro_empaque.tipo_registro=? AND activo_r = 1 $whereSQL";
     $totalStmt = $Con->prepare($totalQuery);
    $totalStmt->bind_param("s", $Tipo);
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
                    LEFT JOIN tipos_presentacion ON tipo_variaciones.id_presentacion_v = tipos_presentacion.id_presentacion
                    LEFT JOIN ciclos ON tipo_variaciones.id_ciclo_v = ciclos.id_ciclo
                    LEFT JOIN invernaderos ON tipo_variaciones.id_modulo_v = invernaderos.id_invernadero
                    LEFT JOIN sedes ON invernaderos.id_sede_i = sedes.id_sede
                    LEFT JOIN clasificacion_merma ON registro_empaque.id_tipo_merma = clasificacion_merma.id_merma
                    WHERE registro_empaque.tipo_registro=? AND activo_r = 1 $whereSQL
                    ORDER BY $orderColumn $orderDir
                    LIMIT ?, ?";
    $dataStmt = $Con->prepare($dataQuery);
    $dataStmt->bind_param("sii", $Tipo, $start, $length);
    $dataStmt->execute();
    $dataResult = $dataStmt->get_result();
    
} else {
    $totalQuery = "SELECT COUNT(*) as total FROM registro_empaque
                    LEFT JOIN tipo_variaciones ON registro_empaque.id_codigo_r = tipo_variaciones.id_variedad
                    LEFT JOIN tipos_cajas ON registro_empaque.id_tipo_caja = tipos_cajas.id_caja
                    LEFT JOIN tipos_tarimas ON registro_empaque.id_tipo_tarima = tipos_tarimas.id_tarima
                    LEFT JOIN tipos_carros ON registro_empaque.id_tipo_carro = tipos_carros.id_carro
                    LEFT JOIN variedades ON tipo_variaciones.id_nombre_v = variedades.id_nombre_v
                    LEFT JOIN tipos_presentacion ON tipo_variaciones.id_presentacion_v = tipos_presentacion.id_presentacion
                    LEFT JOIN ciclos ON tipo_variaciones.id_ciclo_v = ciclos.id_ciclo
                    LEFT JOIN invernaderos ON tipo_variaciones.id_modulo_v = invernaderos.id_invernadero
                    LEFT JOIN sedes ON invernaderos.id_sede_i = sedes.id_sede
                    LEFT JOIN clasificacion_merma ON registro_empaque.id_tipo_merma = clasificacion_merma.id_merma
    WHERE registro_empaque.tipo_registro=? AND invernaderos.id_Sede_i = ? AND activo_r = 1 $whereSQL";
    $totalStmt = $Con->prepare($totalQuery);
    $totalStmt->bind_param("si", $Tipo, $Sede);
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRecords = $totalResult->fetch_assoc()['total'];

    $dataQuery = "SELECT * FROM registro_empaque
                    LEFT JOIN tipo_variaciones ON registro_empaque.id_codigo_r = tipo_variaciones.id_variedad
                    LEFT JOIN tipos_cajas ON registro_empaque.id_tipo_caja = tipos_cajas.id_caja
                    LEFT JOIN tipos_tarimas ON registro_empaque.id_tipo_tarima = tipos_tarimas.id_tarima
                    LEFT JOIN tipos_carros ON registro_empaque.id_tipo_carro = tipos_carros.id_carro
                    LEFT JOIN variedades ON tipo_variaciones.id_nombre_v = variedades.id_nombre_v
                    LEFT JOIN tipos_presentacion ON tipo_variaciones.id_presentacion_v = tipos_presentacion.id_presentacion
                    LEFT JOIN ciclos ON tipo_variaciones.id_ciclo_v = ciclos.id_ciclo
                    LEFT JOIN invernaderos ON tipo_variaciones.id_modulo_v = invernaderos.id_invernadero
                    LEFT JOIN sedes ON invernaderos.id_sede_i = sedes.id_sede
                    LEFT JOIN clasificacion_merma ON registro_empaque.id_tipo_merma = clasificacion_merma.id_merma
    WHERE registro_empaque.tipo_registro=? AND invernaderos.id_Sede_i = ? AND activo_r = 1 $whereSQL
    ORDER BY $orderColumn $orderDir
    LIMIT ?, ?";
    $dataStmt = $Con->prepare($dataQuery);
    $dataStmt->bind_param("iiii", $Tipo, $Sede, $start, $length);
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