<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";

header('Content-Type: application/json; charset=utf-8');

// ================================
// 1. Recibir filtros
// ================================
$ano          = !empty($_POST['ano']) ? $_POST['ano'] : null;
$semana       = !empty($_POST['semana']) ? $_POST['semana'] : null;
$departamento = !empty($_POST['departamento']) ? $_POST['departamento'] : 'Todos';
$tipo         = !empty($_POST['tipo']) ? $_POST['tipo'] : 'Todos';

// ================================
// 2. Si no viene año/semana → última semana disponible
// ================================
if (!$ano || !$semana) {
    $row = $Con->query("SELECT MAX(registro_check) as ultima FROM rh_check")->fetch_assoc();
    if ($row && $row['ultima']) {
        $ultima = new DateTime($row['ultima']);
        $ano = $ultima->format('o');
        $semana = $ultima->format('W');
    } else {
        echo json_encode([
            "draw" => intval($_POST['draw'] ?? 0),
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => []
        ]);
        exit;
    }
}

// ================================
// 3. Calcular rango de la semana
// ================================
$dto = new DateTime();
$dto->setISODate($ano, $semana);
$inicioSemana = $dto->format('Y-m-d');
$dto->modify('+6 days');
$finSemana = $dto->format('Y-m-d');

// ================================
// 4. Construir filtros dinámicos
// ================================
$whereClauses = [];
$params = [$inicioSemana, $finSemana];
$types  = 'ss';

if ($departamento != 'Todos') {
    $whereClauses[] = 'departamento = ?';
    $params[] = $departamento;
    $types .= 's';
}

if ($tipo != 'Todos') {
    $whereClauses[] = 'empleado LIKE ?';
    $params[] = $tipo . '%';
    $types .= 's';
}

// búsqueda global
$searchColumns = $_POST['columns'] ?? [];
$globalSearchRaw = $_POST['search']['value'] ?? '';
if ($globalSearchRaw !== '') {
    $globalSearch = '%' . $globalSearchRaw . '%';
    $globalClauses = [];
    foreach ($searchColumns as $col) {
        $raw = $col['data'] ?? '';
        if ($raw) $globalClauses[] = "$raw LIKE ?";
    }
    if ($globalClauses) {
        $whereClauses[] = '(' . implode(' OR ', $globalClauses) . ')';
        foreach ($globalClauses as $gc) $params[] = $globalSearch;
        $types .= str_repeat('s', count($globalClauses));
    }
}

$whereSQL = $whereClauses ? ' AND ' . implode(' AND ', $whereClauses) : '';

// ================================
// 5. Ordenamiento y paginación
// ================================
$columnMap = [
    'codigo_s'        => 'codigo_s',
    'empleado'        => 'empleado',
    'nombre_completo' => 'nombre_completo',
    'departamento'    => 'departamento',
    'lunes'           => 'lunes',
    'martes'          => 'martes',
    'miercoles'       => 'miercoles',
    'jueves'          => 'jueves',
    'viernes'         => 'viernes',
    'sabado'          => 'sabado',
    'domingo'         => 'domingo'
];

$orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
$orderDir = strtolower($_POST['order'][0]['dir'] ?? 'asc');
$orderColumnRaw = $_POST['columns'][$orderColumnIndex]['data'] ?? 'nombre_completo';
$orderColumn = $columnMap[$orderColumnRaw] ?? 'nombre_completo';

$start = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 25);

// ================================
// 6. Totales para DataTables
// ================================

// total sin filtros
$totalSQL = "SELECT COUNT(DISTINCT empleado) as total FROM vw_asistencia WHERE dia BETWEEN ? AND ?";
$stmt = $Con->prepare($totalSQL);
$stmt->bind_param('ss', $inicioSemana, $finSemana);
$stmt->execute();
$totalRecords = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
$stmt->close();

// total con filtros
$totalFilteredSQL = "SELECT COUNT(DISTINCT empleado) as total 
                     FROM vw_asistencia WHERE dia BETWEEN ? AND ? $whereSQL";
$stmt = $Con->prepare($totalFilteredSQL);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$totalFiltered = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
$stmt->close();

// ================================
// 7. Query principal
// ================================
$sql = "SELECT * FROM vw_asistencia 
        WHERE dia BETWEEN ? AND ? $whereSQL 
        ORDER BY $orderColumn $orderDir 
        LIMIT ?, ?";
$paramsPag = array_merge($params, [$start, $length]);
$typesPag  = $types . 'ii';
$stmt = $Con->prepare($sql);
$stmt->bind_param($typesPag, ...$paramsPag);
$stmt->execute();
$result = $stmt->get_result();
$checadas = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// ================================
// 8. Pivotear por días de la semana
// ================================
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
            'codigo_s'       => $row['codigo_s'],
            'badge'          => $row['badge'],
            'tipo_empleado'  => $row['tipo_empleado'],
            'empleado'       => $row['empleado'],
            'nombre_completo'=> $row['nombre_completo'],
            'departamento'   => $row['departamento'],
            'lunes' => '', 'martes' => '', 'miercoles' => '',
            'jueves'=> '', 'viernes'=> '', 'sabado' => '', 'domingo' => ''
        ];
    }

    $diaSemana = strtolower(date('l', strtotime($row['dia'])));
    if (isset($map[$diaSemana])) {
        $col = $map[$diaSemana];
        $entrada = $row['entrada'] ? date('H:i', strtotime($row['entrada'])) : '';
        $salida  = $row['salida'] ? date('H:i', strtotime($row['salida'])) : '';
        $pivot[$id][$col] = trim("$entrada - $salida", ' -');
    }
}

$checadasPivot = array_values($pivot);

// ================================
// 9. Respuesta JSON
// ================================
echo json_encode([
    "draw" => intval($_POST['draw'] ?? 0),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFiltered,
    "data" => $checadasPivot
], JSON_UNESCAPED_UNICODE);
