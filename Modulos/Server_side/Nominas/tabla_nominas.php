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
$anio          = !empty($_POST['ano']) ? $_POST['ano'] : null;
$semana       = !empty($_POST['semana']) ? $_POST['semana'] : null;
$sede         = !empty($_POST['sede']) ? $_POST['sede'] : 'Todos';
$departamento = !empty($_POST['departamento']) ? $_POST['departamento'] : 'Todos';
$tipo         = !empty($_POST['tipo']) ? $_POST['tipo'] : 'Todos';
$pago         = !empty($_POST['pago']) ? strtoupper($_POST['pago']) : 'Todos';

// ================================
// 2. Si no viene año/semana → última semana disponible
// ================================
if (!$anio || !$semana) {
    $hoy = new DateTime();
    $diaSemana = (int)$hoy->format('N'); // 1=Lunes,...,7=Domingo
    if ($pago === 'Todos' && $diaSemana <= 2) {
        $hoy->modify('-2 days');
    }
    $anio = $hoy->format('o');    
    $semana = $hoy->format('W');
}

// ================================
// 3. Calcular la fecha del miercoles
// ================================
$fecha = new DateTime();
$fecha->setISODate($anio, $semana);

// Mover del lunes al miércoles (+2 días)
$fecha->modify('+2 day');

$miercoles = $fecha->format('Y-m-d');

$fecha_martes = clone $fecha;
$fecha_martes->modify('+6 days');
$martes = $fecha_martes->format('Y-m-d');

// ================================
// 4. Filtros dinámicos
// ================================
$whereClauses = [];
$params = [$miercoles];
$types  = 's';

if ($departamento != 'Todos') {
    $whereClauses[] = 'area = ?';
    $params[] = $departamento;
    $types .= 's';
}
if ($tipo != 'Todos') {
    $whereClauses[] = 'clave LIKE ?';
    $params[] = $tipo . '%';
    $types .= 's';
}
if($sede != 'Todos'){
    $whereClauses[] = 'id_sede = ?';
    $params[] = $sede;
    $types .= 's';
}

// búsqueda global
$searchColumns = ['clave','nombre_completo','area','id_sede'];
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
    'sede'                   => 'sede',
    'clave'                  => 'clave',
    'nombre_completo'        => 'nombre_completo',
    'area'                   => 'area',
    'total_dias'             => 'total_dias',
    'vino_domingo'           => 'vino_domingo',
    'total_horas_extra'      => 'total_horas_extra',
    'total_horas_trabajadas' => 'total_horas_trabajadas',
    'total_bonos'            => 'total_bonos',
    'total_destajos'         => 'total_destajos',
    'total_compensaciones'   => 'total_compensaciones'
];

// Mapear índice de columna enviado por DataTables (si existe)
$orderColumnIndex = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : null;
$orderDirRaw = isset($_POST['order'][0]['dir']) ? strtolower($_POST['order'][0]['dir']) : null;

// Sanear dirección
$orderDir = ($orderDirRaw === 'asc') ? 'ASC' : 'DESC';

// Si no se recibió columna de ordenamiento, usar fecha_i DESC por defecto
if ($orderColumnIndex === null) {
    $orderColumn = 'clave';
    $orderDir = 'ASC';
} else {
    // Obtener el nombre de la columna solicitado por índice (seguro usando $columnMap)
    $orderColumnRaw = isset($_POST['columns'][$orderColumnIndex]['data']) ? $_POST['columns'][$orderColumnIndex]['data'] : null;
    if ($orderColumnRaw !== null && isset($columnMap[$orderColumnRaw])) {
        $orderColumn = $columnMap[$orderColumnRaw];
        // Asegurar que $orderDir sea ASC o DESC
        $orderDir = ($orderDirRaw === 'asc') ? 'ASC' : 'DESC';
    } else {
        // Valor inválido enviado desde el cliente: usar fallback seguro
        $orderColumn = 'clave';
        $orderDir = 'DESC';
    }
}

$start = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 25);

// ================================
// 6. Totales
// ================================
if ($TipoRol == "ADMINISTRADOR" || $TipoRol == "SUPERVISOR") {
    // Total registros sin filtros
    $totalSQL = "SELECT COUNT(*) as total FROM vw_nomina_semanal WHERE semana_laboral = ?";
    $stmt = $Con->prepare($totalSQL);
    $stmt->bind_param('s', $miercoles);
    $stmt->execute();
    $totalRecords = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
    $stmt->close();

    // Total registros con filtros
    $totalFilteredSQL = "SELECT COUNT(*) as total FROM vw_nomina_semanal WHERE semana_laboral = ? $whereSQL";
    $stmt = $Con->prepare($totalFilteredSQL);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $totalFiltered = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
    $stmt->close();

} else {
    // Usuario de sede: total registros sin filtros
    $totalSQL = "SELECT COUNT(*) as total FROM vw_nomina_semanal WHERE semana_laboral = ?";
    $stmt = $Con->prepare($totalSQL);
    $stmt->bind_param('is', $Sede, $miercoles);
    $stmt->execute();
    $totalRecords = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
    $stmt->close();

    // Usuario de sede: total registros con filtros
    $totalFilteredSQL = "SELECT COUNT(*) as total FROM vw_nomina_semanal WHERE semana_laboral = ? AND id_sede = ? $whereSQL";

    // 1️⃣ Preparar parámetros fijos en orden: inicio, fin, sede
    $paramsFiltered = [$miercoles, $Sede];
    $typesFiltered  = 'si';

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
// 7. Query principal con paginación
// ================================
if ($TipoRol=="ADMINISTRADOR" || $TipoRol=="SUPERVISOR") {
    $sql = "SELECT * 
            FROM vw_nomina_semanal 
            WHERE semana_laboral = ? 
            $whereSQL 
            ORDER BY $orderColumn $orderDir
            LIMIT ?, ?";
    $paramsExec = $params;
    $paramsExec[] = $start;
    $paramsExec[] = $length;
    $typesExec = $types . "ii";
    $stmt = $Con->prepare($sql);
    $stmt->bind_param($typesExec, ...$paramsExec);
} else {
    $sql = "SELECT * 
            FROM vw_nomina_semanal 
            WHERE semana_laboral = ? 
            AND id_sede = ?
            $whereSQL
            ORDER BY $orderColumn $orderDir
            LIMIT ?, ?";
    $paramsExec = [$miercoles, $Sede];
    if (!empty($params)) {
        $paramsExec = array_merge($paramsExec, array_slice($params, 2));
    }
    $paramsExec[] = $start;
    $paramsExec[] = $length;
    $typesExec = 'si' . substr($types, 2) . "ii";
    $stmt = $Con->prepare($sql);
    $stmt->bind_param($typesExec, ...$paramsExec);
}

$stmt->execute();
$dataResult = $stmt->get_result();


// Construye la respuesta JSON
$response = array(
    "draw" => isset($_POST['draw']) ? intval($_POST['draw']) : 0,
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFiltered,
    "data" => array()
);

while ($row = $dataResult->fetch_assoc()) {
    $row['miercoles'] = $miercoles;
    $row['martes'] = $martes;
    $response["data"][] = $row;
}

$json = json_encode($response);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "Error de JSON: " . json_last_error_msg();
} else {
    echo $json;
}
?>
