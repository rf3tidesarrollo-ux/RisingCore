<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";

$departamento = $_GET['departamento'] ?? 'Todos';
$empleado     = $_GET['empleado'] ?? 'Todos';
$ano          = $_GET['ano'] ?? null;
$semana       = $_GET['semana'] ?? null;

// Última semana si no se especifica
if (!$ano || !$semana) {
    $row = $Con->query("SELECT MAX(registro_check) as ultima FROM rh_check")->fetch_assoc();
    $ultima = new DateTime($row['ultima']);
    $ano = $ultima->format('o');
    $semana = $ultima->format('W');
}

// Rango de la semana
$dto = new DateTime();
$dto->setISODate($ano, $semana);
$inicioSemana = $dto->format('Y-m-d');
$dto->modify('+6 days');
$finSemana = $dto->format('Y-m-d');

// Mapeo de columnas para ordenamiento
$columnMap = [
    'codigo_s'       => 'codigo_s',
    'empleado'       => 'empleado',
    'nombre_completo'=> 'nombre_completo',
    'departamento'   => 'departamento',
    'lunes'          => 'lunes',
    'martes'         => 'martes',
    'miercoles'      => 'miercoles',
    'jueves'         => 'jueves',
    'viernes'        => 'viernes',
    'sabado'         => 'sabado',
    'domingo'        => 'domingo'
];


// Búsqueda global
$searchColumns = $_POST['columns'] ?? [];
$globalSearchRaw = $_POST['search']['value'] ?? '';
$globalSearch = $Con->real_escape_string($globalSearchRaw);
$whereClauses = [];
$params = [$inicioSemana, $finSemana];
$types = 'ss';

if ($departamento != 'Todos') {
    $whereClauses[] = 'departamento = ?';
    $params[] = $departamento;
    $types .= 's';
}

if ($empleado != 'Todos') {
    $whereClauses[] = 'empleado = ?';
    $params[] = $empleado;
    $types .= 's';
}

// Búsqueda global en columnas seleccionadas
if ($globalSearchRaw !== '') {
    $globalSearchClauses = [];
    foreach ($searchColumns as $column) {
        $rawColName = $column['data'] ?? '';
        $columnName = $columnMap[$rawColName] ?? $Con->real_escape_string($rawColName);
        if ($columnName) $globalSearchClauses[] = "$columnName LIKE '%$globalSearch%'";
    }
    if ($globalSearchClauses) $whereClauses[] = '(' . implode(' OR ', $globalSearchClauses) . ')';
}

// Construir WHERE final
$whereSQL = '';
if ($whereClauses) $whereSQL = ' AND ' . implode(' AND ', $whereClauses);

// Ordenamiento
$orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
$orderDir = strtolower($_POST['order'][0]['dir'] ?? 'asc');
$orderColumnRaw = $_POST['columns'][$orderColumnIndex]['data'] ?? 'nombre_completo';
$orderColumn = $columnMap[$orderColumnRaw] ?? 'nombre_completo';

// Paginación
$start = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 25);

// ----------------------------
// Query principal
// ----------------------------
$sql = "SELECT * FROM vw_asistencia WHERE dia BETWEEN ? AND ? $whereSQL ORDER BY $orderColumn $orderDir LIMIT ?, ?";
$stmt = $Con->prepare($sql);
$bindParams = array_merge($params, [$start, $length]);
$bindTypes = $types . 'ii';
$stmt->bind_param($bindTypes, ...$bindParams);
$stmt->execute();
$result = $stmt->get_result();
$checadas = $result->fetch_all(MYSQLI_ASSOC);

// ----------------------------
// Pivot días de la semana
// ----------------------------
$pivot = [];
$map = [
    'monday'    => 'lunes',
    'tuesday'   => 'martes',
    'wednesday' => 'miercoles',
    'thursday'  => 'jueves',
    'friday'    => 'viernes',
    'saturday'  => 'sabado',
    'sunday'    => 'domingo'
];

foreach ($checadas as $row) {
    $id = $row['empleado'];
    if (!isset($pivot[$id])) {
        $pivot[$id] = [
            'codigo_s'      => $row['codigo_s'],
            'badge'         => $row['badge'],
            'tipo_empleado' => $row['tipo_empleado'],
            'empleado'      => $row['empleado'],
            'nombre_completo'=> $row['nombre_completo'],
            'departamento'  => $row['departamento'],
            'lunes'         => '',
            'martes'        => '',
            'miercoles'     => '',
            'jueves'        => '',
            'viernes'       => '',
            'sabado'        => '',
            'domingo'       => ''
        ];
    }

    $diaSemana = strtolower(date('l', strtotime($row['dia'])));
    if (isset($map[$diaSemana])) {
        $columna = $map[$diaSemana];
        $entrada = $row['entrada'] ? date('H:i', strtotime($row['entrada'])) : '';
        $salida  = $row['salida']  ? date('H:i', strtotime($row['salida']))  : '';

        $pivot[$id][$columna] = "$entrada - " . ($salida ? "$salida" : '');
    }
}

$checadasPivot = array_values($pivot);

// ----------------------------
// Construir respuesta JSON
// ----------------------------
header('Content-Type: application/json; charset=utf-8');
$response = [
    "draw" => intval($_POST['draw'] ?? 0),
    "recordsTotal" => count($checadasPivot),
    "recordsFiltered" => count($checadasPivot),
    "data" => $checadasPivot
];

echo json_encode($response, JSON_UNESCAPED_UNICODE);


<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";

// Recibir filtros desde POST
$ano          = $_POST['ano'] ?? null;
$semana       = $_POST['semana'] ?? null;
$departamento = $_POST['departamento'] ?? 'Todos';
$tipo         = $_POST['tipo'] ?? 'Todos'; // si agregaste filtro de tipo empleado

// Última semana si no se especifica
if (!$ano || !$semana) {
    $row = $Con->query("SELECT MAX(registro_check) as ultima FROM rh_check")->fetch_assoc();
    $ultima = new DateTime($row['ultima']);
    $ano = $ultima->format('o');
    $semana = $ultima->format('W');
}

// Calcular rango de la semana
$dto = new DateTime();
$dto->setISODate($ano, $semana);
$inicioSemana = $dto->format('Y-m-d');
$dto->modify('+6 days');
$finSemana = $dto->format('Y-m-d');

$params = [$inicioSemana, $finSemana];
$types = 'ss';

if ($departamento != 'Todos') {
    $whereClauses[] = 'departamento = ?';
    $params[] = $departamento;
    $types .= 's';
}

if ($tipo != 'Todos') {
    $whereClauses[] = 'tipo_empleado = ?';
    $params[] = $tipo;
    $types .= 's';
}

// Mapeo de columnas para ordenamiento
$columnMap = [
    'codigo_s'       => 'codigo_s',
    'empleado'       => 'empleado',
    'nombre_completo'=> 'nombre_completo',
    'departamento'   => 'departamento',
    'lunes'          => 'lunes',
    'martes'         => 'martes',
    'miercoles'      => 'miercoles',
    'jueves'         => 'jueves',
    'viernes'        => 'viernes',
    'sabado'         => 'sabado',
    'domingo'        => 'domingo'
];


// Búsqueda global
$searchColumns = $_POST['columns'] ?? [];
$globalSearchRaw = $_POST['search']['value'] ?? '';
$globalSearch = $Con->real_escape_string($globalSearchRaw);
$whereClauses = [];
$params = [$inicioSemana, $finSemana];
$types = 'ss';

if ($departamento != 'Todos') {
    $whereClauses[] = 'departamento = ?';
    $params[] = $departamento;
    $types .= 's';
}

// Búsqueda global en columnas seleccionadas
if ($globalSearchRaw !== '') {
    $globalSearchClauses = [];
    foreach ($searchColumns as $column) {
        $rawColName = $column['data'] ?? '';
        $columnName = $columnMap[$rawColName] ?? $Con->real_escape_string($rawColName);
        if ($columnName) $globalSearchClauses[] = "$columnName LIKE '%$globalSearch%'";
    }
    if ($globalSearchClauses) $whereClauses[] = '(' . implode(' OR ', $globalSearchClauses) . ')';
}

// Construir WHERE final
$whereSQL = '';
if ($whereClauses) $whereSQL = ' AND ' . implode(' AND ', $whereClauses);

// Ordenamiento
$orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
$orderDir = strtolower($_POST['order'][0]['dir'] ?? 'asc');
$orderColumnRaw = $_POST['columns'][$orderColumnIndex]['data'] ?? 'nombre_completo';
$orderColumn = $columnMap[$orderColumnRaw] ?? 'nombre_completo';

// Paginación
$start = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 25);

// ----------------------------
// Query principal
// ----------------------------
$sql = "SELECT * FROM vw_asistencia WHERE dia BETWEEN ? AND ? $whereSQL ORDER BY $orderColumn $orderDir LIMIT ?, ?";
$stmt = $Con->prepare($sql);
$bindParams = array_merge($params, [$start, $length]);
$bindTypes = $types . 'ii';
$stmt->bind_param($bindTypes, ...$bindParams);
$stmt->execute();
$result = $stmt->get_result();
$checadas = $result->fetch_all(MYSQLI_ASSOC);

// ----------------------------
// Pivot días de la semana
// ----------------------------
$pivot = [];
$map = [
    'monday'    => 'lunes',
    'tuesday'   => 'martes',
    'wednesday' => 'miercoles',
    'thursday'  => 'jueves',
    'friday'    => 'viernes',
    'saturday'  => 'sabado',
    'sunday'    => 'domingo'
];

foreach ($checadas as $row) {
    $id = $row['empleado'];
    if (!isset($pivot[$id])) {
        $pivot[$id] = [
            'codigo_s'      => $row['codigo_s'],
            'badge'         => $row['badge'],
            'tipo_empleado' => $row['tipo_empleado'],
            'empleado'      => $row['empleado'],
            'nombre_completo'=> $row['nombre_completo'],
            'departamento'  => $row['departamento'],
            'lunes'         => '',
            'martes'        => '',
            'miercoles'     => '',
            'jueves'        => '',
            'viernes'       => '',
            'sabado'        => '',
            'domingo'       => ''
        ];
    }

    $diaSemana = strtolower(date('l', strtotime($row['dia'])));
    if (isset($map[$diaSemana])) {
        $columna = $map[$diaSemana];
        $entrada = $row['entrada'] ? date('H:i', strtotime($row['entrada'])) : '';
        $salida  = $row['salida']  ? date('H:i', strtotime($row['salida']))  : '';

        $pivot[$id][$columna] = "$entrada - " . ($salida ? "$salida" : '');
    }
}

$checadasPivot = array_values($pivot);

// ----------------------------
// Construir respuesta JSON
// ----------------------------
header('Content-Type: application/json; charset=utf-8');
$response = [
    "draw" => intval($_POST['draw'] ?? 0),
    "recordsTotal" => count($checadasPivot),
    "recordsFiltered" => count($checadasPivot),
    "data" => $checadasPivot
];

echo json_encode($response, JSON_UNESCAPED_UNICODE);
