<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";
if(!isset($_SESSION['ID'])){
    echo json_encode(['expired' => true]);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

// 1️⃣ Recibir filtros
$ano          = !empty($_POST['ano']) ? $_POST['ano'] : null;
$semana       = !empty($_POST['semana']) ? $_POST['semana'] : null;
$departamento = !empty($_POST['departamento']) ? $_POST['departamento'] : 'Todos';
$tipo         = !empty($_POST['tipo']) ? $_POST['tipo'] : 'Todos';
$pago         = !empty($_POST['pago']) ? strtoupper($_POST['pago']) : 'SEMANAL';

// 2️⃣ Si no viene año/semana → última semana disponible
if (!$ano || !$semana) {
    $hoy = new DateTime();
    $diaSemana = (int)$hoy->format('N');
    if ($pago === 'SEMANAL' && $diaSemana <= 2) $hoy->modify('-2 days');
    $ano = $hoy->format('o');
    $semana = $hoy->format('W');
}

// 3️⃣ Calcular rango y orden de días
$dto = new DateTime();
$dto->setISODate($ano, $semana);

if ($pago === 'SEMANAL') {
    $inicioSemana = clone $dto; $inicioSemana->modify('+2 days'); // miércoles
    $finSemana    = clone $inicioSemana; $finSemana->modify('+6 days'); // martes
    $ordenDias = ['miercoles','jueves','viernes','sabado','domingo','lunes','martes'];
} else {
    $inicioSemana = clone $dto; $inicioSemana->modify('monday this week');
    $finSemana    = clone $inicioSemana; $finSemana->modify('+6 days'); // viernes
    $ordenDias = ['lunes','martes','miercoles','jueves','viernes','sabado','domingo'];
}

$inicioSemanaStr = $inicioSemana->format('Y-m-d');
$finSemanaStr    = $finSemana->format('Y-m-d');

// 4️⃣ Filtros dinámicos
$whereClauses = [];
$params = [$inicioSemanaStr, $finSemanaStr];
$types  = 'ss';
if ($departamento != 'Todos') { $whereClauses[] = 'departamento = ?'; $params[] = $departamento; $types .= 's'; }
if ($tipo != 'Todos') { $whereClauses[] = 'empleado LIKE ?'; $params[] = $tipo.'%'; $types .= 's'; }

// Búsqueda global
$searchColumns = ['empleado','nombre_completo','departamento'];
$globalSearchRaw = $_POST['search']['value'] ?? '';
if ($globalSearchRaw !== '') {
    $globalSearch = '%'.$globalSearchRaw.'%';
    $globalClauses = [];
    foreach($searchColumns as $col) $globalClauses[] = "$col LIKE ?";
    $whereClauses[] = '('.implode(' OR ', $globalClauses).')';
    foreach($globalClauses as $_) $params[] = $globalSearch;
    $types .= str_repeat('s', count($globalClauses));
}
$whereSQL = $whereClauses ? ' AND '.implode(' AND ', $whereClauses) : '';

// 5️⃣ Totales
$totalSQL = "SELECT COUNT(DISTINCT empleado) as total FROM vw_asistencia WHERE dia BETWEEN ? AND ?";
$stmt = $Con->prepare($totalSQL); $stmt->bind_param('ss', $inicioSemanaStr, $finSemanaStr);
$stmt->execute(); $totalRecords = $stmt->get_result()->fetch_assoc()['total'] ?? 0; $stmt->close();

$totalFilteredSQL = "SELECT COUNT(DISTINCT empleado) as total FROM vw_asistencia WHERE dia BETWEEN ? AND ? $whereSQL";
$stmt = $Con->prepare($totalFilteredSQL); $stmt->bind_param($types, ...$params);
$stmt->execute(); $totalFiltered = $stmt->get_result()->fetch_assoc()['total'] ?? 0; $stmt->close();

// 6️⃣ Query principal (sin paginación para simplificar)
$sql = "SELECT * FROM vw_asistencia WHERE dia BETWEEN ? AND ? $whereSQL ORDER BY empleado ASC";
$stmt = $Con->prepare($sql); $stmt->bind_param($types, ...$params);
$stmt->execute(); $checadas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); $stmt->close();

// 7️⃣ Crear mapping de fechas según orden de columnas
$fechaPorDia = [];
$dtoTemp = clone $inicioSemana;
foreach ($ordenDias as $dia) {
    $fechaPorDia[$dia] = $dtoTemp->format('Y-m-d');
    $dtoTemp->modify('+1 day');
}

// 8️⃣ Pivotear por empleado y días
$pivot = [];
foreach($checadas as $row){
    $id = $row['empleado'];

    if(!isset($pivot[$id])){
        $pivot[$id] = [
            'codigo_s' => $row['codigo_s'],
            'empleado' => $row['empleado'],
            'nombre_completo' => $row['nombre_completo'],
            'departamento' => $row['departamento']
        ];
        foreach($ordenDias as $dia) $pivot[$id][$dia] = '';
    }

    foreach ($ordenDias as $dia) {
        if($row['dia'] === $fechaPorDia[$dia]){
            $entrada = $row['entrada'] ?? '';
            $salida  = $row['salida'] ?? '';
            $pivot[$id][$dia] = trim("$entrada - $salida", ' -');
        }
    }
}

// Convertir pivot a array de valores
$checadasPivot = array_values($pivot);

// 9️⃣ Respuesta JSON
echo json_encode([
    "draw" => intval($_POST['draw'] ?? 0),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFiltered,
    "dias" => $ordenDias,
    "data" => $checadasPivot
], JSON_UNESCAPED_UNICODE);
?>
