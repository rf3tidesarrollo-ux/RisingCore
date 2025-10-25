<?php
date_default_timezone_set('America/Mexico_City');
require_once '../../../Librerias/zkteco/zklib/ZKLib.php';
require_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
require_once "../../../Login/validar_sesion.php";

$response = ['success' => false, 'messages' => []];
$debug = false;

// ======================================================
// 1️⃣ Función para ping rápido al puerto (verifica conexión)
// ======================================================
function puerto_abierto($host, $port, $timeout = 1) {
    $conn = @fsockopen($host, $port, $errno, $errstr, $timeout);
    if ($conn) { fclose($conn); return true; }
    return false;
}

// ======================================================
// 2️⃣ Última fecha registrada por dispositivo
// ======================================================
$ultimaFechaPorDp = [];
$result = $Con->query("SELECT id_dispositivo, MAX(registro_check) AS ultima FROM rh_check GROUP BY id_dispositivo");
while ($row = $result->fetch_assoc()) {
    $ultimaFechaPorDp[$row['id_dispositivo']] = $row['ultima'];
}

// ======================================================
// 3️⃣ Mapear horas de salida por badge
// ======================================================
$horaSalidaMap = [];
$sql = "
    SELECT p.badge, th.hora_salida, th.hora_sabado, th.hora_domingo, p.id_sede_pl
    FROM rh_personal p
    INNER JOIN rh_tipos_horarios th ON p.id_tipo_h = th.id_thorario
    WHERE p.status_pl = 1
";
$res = $Con->query($sql);
while ($row = $res->fetch_assoc()) {
    $horaSalidaMap[$row['badge']] = [
        'hora_salida'  => $row['hora_salida'],
        'hora_sabado'  => $row['hora_sabado'] ?: $row['hora_salida'],
        'hora_domingo' => $row['hora_domingo'] ?: $row['hora_salida'],
        'sede'         => $row['id_sede_pl']
    ];
}

// ======================================================
// 4️⃣ Obtener dispositivos biométricos
// ======================================================
$response['messages'][] = $Sede;
$stmt = $Con->prepare("SELECT id_dpbiometrico, ip, puerto, dispositivo FROM rh_dpbiometrico WHERE id_sede_dp = ?");
$stmt->bind_param('i', $Sede);
$stmt->execute();
$dispositivos = $stmt->get_result();

// ======================================================
// 5️⃣ Procesar cada dispositivo
// ======================================================
while ($dp = $dispositivos->fetch_object()) {

    // 🔹 Verificar si el dispositivo responde al puerto
    if (!puerto_abierto($dp->ip, $dp->puerto, 1)) {
        $response['messages'][] = "❌ Dispositivo {$dp->dispositivo} ({$dp->ip}:{$dp->puerto}) no responde.";
        continue;
    }

    $zk = new ZKLib($dp->ip, $dp->puerto);
    if (!$zk->connect()) {
        $response['messages'][] = "❌ No se pudo conectar al dispositivo {$dp->dispositivo}.";
        continue;
    }

    $zk->disableDevice();
    $ultimaFecha = $ultimaFechaPorDp[$dp->id_dpbiometrico] ?? null;

    // ==================================================
    // Obtener registros del dispositivo
    // ==================================================
    $attendance = $zk->getAttendance();
    if ($debug) { echo "<pre>"; var_dump($attendance); echo "</pre>"; }

    if (!empty($attendance)) {
        $attendance = array_reverse($attendance, true); // asegurar orden cronológico
        $insertValues = [];

        foreach ($attendance as $att) {
            $badge = $Con->real_escape_string($att['id'] ?? $att['userid']);
            $reg   = $Con->real_escape_string(date("Y-m-d H:i:s", strtotime($att['timestamp'])));
            $idDp  = (int)$dp->id_dpbiometrico;
            $idDpTipo = 1;

            if ($ultimaFecha && $reg <= $ultimaFecha) continue;

            // Obtener horas para este badge (ahora con sede)
            $horas = $horaSalidaMap[$badge] ?? [
                'hora_salida'  => 'NULL',
                'hora_sabado'  => 'NULL',
                'hora_domingo' => 'NULL',
                'sede'         => 'NULL'
            ];

            $hora_salida  = $horas['hora_salida']  ? "'{$horas['hora_salida']}'" : "NULL";
            $hora_sabado  = $horas['hora_sabado']  ? "'{$horas['hora_sabado']}'" : "NULL";
            $hora_domingo = $horas['hora_domingo'] ? "'{$horas['hora_domingo']}'" : "NULL";

            // Sede como entero si existe, sino NULL
            $sede = isset($horas['sede']) && $horas['sede'] != '' ? (int)$horas['sede'] : "NULL";

            // Construcción del registro para insertar
            $insertValues[] = "($sede, '$badge', '$reg', $idDpTipo, $idDp, $hora_salida, $hora_sabado, $hora_domingo)";

        }

        // Insertar en bloques de 2000 registros
        $chunks = array_chunk($insertValues, 2000);
        $totalInserted = 0;
        foreach ($chunks as $chunk) {
            $sql = "INSERT IGNORE INTO rh_check 
            (sede, badge, registro_check, id_dptipo, id_dispositivo, hora_salida, hora_sabado, hora_domingo) 
        VALUES " . implode(',', $chunk);

            $res = $Con->query($sql);
            if ($res) $totalInserted += $Con->affected_rows;
        }

        $response['messages'][] = "✅ Dispositivo {$dp->dispositivo} actualizó $totalInserted registros.";
    } else {
        $response['messages'][] = "ℹ️ No hay registros nuevos en {$dp->dispositivo}.";
    }

    // ==================================================
    // 🔹 NUEVO BLOQUE: Actualizar usuarios y registros
    // ==================================================
    $users = $zk->getUser(); // lista de usuarios en el dispositivo
    $totalUsuarios = is_array($users) ? count($users) : 0;
    $totalRegistros = is_array($attendance) ? count($attendance) : 0;

    $stmtUpdate = $Con->prepare("
        UPDATE rh_dpbiometrico 
        SET usuarios = ?, registros = ? 
        WHERE id_dpbiometrico = ?
    ");
    $stmtUpdate->bind_param('iii', $totalUsuarios, $totalRegistros, $dp->id_dpbiometrico);
    $stmtUpdate->execute();
    $stmtUpdate->close();

    // Mensaje informativo adicional
    $response['messages'][] = "📊 {$dp->dispositivo}: {$totalUsuarios} usuarios registrados, {$totalRegistros} marcas en total.";

    // ==================================================
    // Desconexión segura
    // ==================================================
    $zk->enableDevice();
    $zk->disconnect();
}

// ======================================================
// 6️⃣ Respuesta final JSON
// ======================================================
$response['success'] = true;
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
