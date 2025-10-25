<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";
if(isset($_SESSION['ID']) == false){
    echo json_encode(['expired' => true]);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

// ================================
// 1. Recibir filtros
// ================================
$ano          = !empty($_POST['ano']) ? $_POST['ano'] : null;
$semana       = !empty($_POST['semana']) ? $_POST['semana'] : null;
$departamento = !empty($_POST['departamento']) ? $_POST['departamento'] : 'Todos';
$tipo         = !empty($_POST['tipo']) ? $_POST['tipo'] : 'Todos';
$pago         = !empty($_POST['pago']) ? strtoupper($_POST['pago']) : 'Todos';

// ================================
// 2. Si no viene año/semana → última semana disponible
// ================================
if (!$ano || !$semana) {
    $hoy = new DateTime();
    $diaSemana = (int)$hoy->format('N'); // 1=Lunes,...,7=Domingo
    if ($pago === 'Todos' && $diaSemana <= 2) {
        $hoy->modify('-2 days');
    }
    $ano = $hoy->format('o');    
    $semana = $hoy->format('W');
}

// ================================
// 3. Calcular rango según tipo de pago
// ================================
$dto = new DateTime();
$dto->setISODate($ano, $semana);

if ($pago === 'QUINCENAL') {
    $inicioSemana = clone $dto;
    $inicioSemana->modify('monday this week');
    $finSemana = clone $inicioSemana;
    $finSemana->modify('+6 days'); // viernes
    $ordenDias = ['lunes','martes','miercoles','jueves','viernes','sabado','domingo'];
} else {
    $inicioSemana = clone $dto;
    $inicioSemana->modify('+2 days'); // miércoles
    $finSemana = clone $inicioSemana;
    $finSemana->modify('+6 days');     // martes
    $ordenDias = ['miercoles','jueves','viernes','sabado','domingo','lunes','martes'];
}

$inicioSemanaStr = $inicioSemana->format('Y-m-d');
$finSemanaStr    = $finSemana->format('Y-m-d');

// ================================
// 4. Filtros dinámicos
// ================================
$whereClauses = [];
$params = [$inicioSemanaStr, $finSemanaStr];
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
if($pago != 'Todos'){
    $whereClauses[] = 'tipo_pago = ?';
    $params[] = $pago;
    $types .= 's';
}

// búsqueda global
$searchColumns = ['empleado','nombre_completo','departamento'];
$globalSearchRaw = $_POST['search']['value'] ?? '';
if ($globalSearchRaw !== '') {
    $globalSearch = '%' . $globalSearchRaw . '%';
    $globalClauses = [];
    foreach ($searchColumns as $col) $globalClauses[] = "$col LIKE ?";
    $whereClauses[] = '(' . implode(' OR ', $globalClauses) . ')';
    foreach ($globalClauses as $gc) $params[] = $globalSearch;
    $types .= str_repeat('s', count($globalClauses));
}

$whereSQL = $whereClauses ? ' AND ' . implode(' AND ', $whereClauses) : '';

// ================================
// 5. Ordenamiento y paginación
// ================================
$columnMap = [
    'codigo_s'=>'codigo_s','empleado'=>'empleado','nombre_completo'=>'nombre_completo',
    'departamento'=>'departamento','lunes'=>'lunes','martes'=>'martes','miercoles'=>'miercoles',
    'jueves'=>'jueves','viernes'=>'viernes','sabado'=>'sabado','domingo'=>'domingo'
];
$orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
$orderDir = strtolower($_POST['order'][0]['dir'] ?? 'asc');
$orderColumnRaw = $_POST['columns'][$orderColumnIndex]['data'] ?? 'nombre_completo';
$orderColumn = $columnMap[$orderColumnRaw] ?? 'nombre_completo';

$start = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 25);

// ================================
// 6. Totales
// ================================
if ($TipoRol=="ADMINISTRADOR" || $TipoRol=="SUPERVISOR") {
    $totalSQL = "SELECT COUNT(DISTINCT empleado) as total FROM vw_incidencia WHERE dia BETWEEN ? AND ?";
    $stmt = $Con->prepare($totalSQL);
    $stmt->bind_param('ss',$inicioSemanaStr,$finSemanaStr);
    $stmt->execute();
    $totalRecords = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
    $stmt->close();

    $totalFilteredSQL = "SELECT COUNT(DISTINCT empleado) as total FROM vw_incidencia WHERE dia BETWEEN ? AND ? $whereSQL";
    $stmt = $Con->prepare($totalFilteredSQL);
    $stmt->bind_param($types,...$params);
    $stmt->execute();
    $totalFiltered = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
    $stmt->close();
}else{
    $totalSQL = "SELECT COUNT(DISTINCT empleado) as total FROM vw_incidencia WHERE id_sede_pl = ? AND dia BETWEEN ? AND ?";
    $stmt = $Con->prepare($totalSQL);
    $stmt->bind_param('iss', $Sede, $inicioSemanaStr, $finSemanaStr);
    $stmt->execute();
    $totalRecords = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
    $stmt->close();

    $totalFilteredSQL = "SELECT COUNT(DISTINCT empleado) as total FROM vw_incidencia WHERE dia BETWEEN ? AND ? AND id_sede_pl = ? $whereSQL";
    // 1️⃣ Preparar parámetros fijos en orden: inicio, fin, sede
    $paramsFiltered = [$inicioSemanaStr, $finSemanaStr, $Sede];
    $typesFiltered  = 'ssi';

    // 2️⃣ Agregar filtros dinámicos si existen
    if (!empty($params)) {
        // Omitimos las dos fechas de $params porque ya las pusimos arriba
        $paramsFiltered = array_merge($paramsFiltered, array_slice($params, 2));
        $typesFiltered .= substr($types, 2);
    }

    // 3️⃣ Preparar statement
    $stmt = $Con->prepare($totalFilteredSQL);
    $stmt->bind_param($typesFiltered, ...$paramsFiltered);
    $stmt->execute();
    $totalFiltered = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
    $stmt->close();
}

// ================================
// 7. Query principal
// ================================
if ($TipoRol=="ADMINISTRADOR" || $TipoRol=="SUPERVISOR") {
    $sql = "SELECT * FROM vw_incidencia WHERE dia BETWEEN ? AND ? $whereSQL ORDER BY $orderColumn $orderDir";
    $stmt = $Con->prepare($sql);
    $stmt->bind_param($types, ...$params);
} else {
    $sql = "SELECT * FROM vw_incidencia WHERE dia BETWEEN ? AND ? AND id_sede_pl = ? $whereSQL ORDER BY $orderColumn $orderDir";
    $paramsExec = [$inicioSemanaStr, $finSemanaStr, $Sede];
    if (!empty($params)) {
        $paramsExec = array_merge($paramsExec, array_slice($params, 2));
    }
    $typesExec = 'ssi' . substr($types, 2);

    $stmt = $Con->prepare($sql);
    $stmt->bind_param($typesExec, ...$paramsExec);
}
    $stmt->execute();
    $result = $stmt->get_result();
    $checadas = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

// ================================
// 8. Pivotear según $ordenDias
// ================================
$pivot = [];
$map = ['monday'=>'lunes','tuesday'=>'martes','wednesday'=>'miercoles','thursday'=>'jueves',
        'friday'=>'viernes','saturday'=>'sabado','sunday'=>'domingo'];

foreach($checadas as $row){
    $id = $row['empleado'] . '_' . $row['id_sede_pl'];
    
    if(!isset($pivot[$id])){
        $pivot[$id] = [
            'codigo_s'=>$row['codigo_s'],
            'empleado'=>$row['empleado'],
            'nombre_completo'=>$row['nombre_completo'],
            'departamento'=>$row['departamento'],
            'tipo_pago'=>$row['tipo_pago']
        ];
        // llenar días con '-'
        foreach($ordenDias as $dia) $pivot[$id][$dia] = '-';
    }

    $diaSemana = strtolower(date('l', strtotime($row['dia'])));
    $col = $map[$diaSemana] ?? null;
    if($col) $pivot[$id][$col] = $row['permisos_dia'];
}

$checadasPivot = array_values($pivot);
$checadasPage = array_slice($checadasPivot, $start, $length);


// ================================
// 9. Respuesta JSON
// ================================
echo json_encode([
    "draw" => intval($_POST['draw'] ?? 0),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFiltered,
    "data" => $checadasPage
], JSON_UNESCAPED_UNICODE);
?>
