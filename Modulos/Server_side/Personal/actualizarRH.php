<?php
date_default_timezone_set('America/Mexico_City');
require_once '../../../Librerias/zkteco/zklib/ZKLib.php';
require_once '../../../Conexion/BD.php';

$response = ['success' => false, 'messages' => []];
$debug = false;

// Función para ping rápido al puerto
function puerto_abierto($host, $port, $timeout = 1) {
    $conn = @fsockopen($host, $port, $errno, $errstr, $timeout);
    if ($conn) { fclose($conn); return true; }
    return false;
}

// 1️⃣ Última fecha por dispositivo
$ultimaFechaPorDp = [];
$result = $Con->query("SELECT id_dispositivo, MAX(registro_check) AS ultima FROM rh_check GROUP BY id_dispositivo");
while ($row = $result->fetch_assoc()) {
    $ultimaFechaPorDp[$row['id_dispositivo']] = $row['ultima'];
}

// 2️⃣ Mapear horas por badge (si no hay sábado/domingo, usar hora_salida)
$horaSalidaMap = [];
$sql = "
    SELECT p.badge, th.hora_salida, th.hora_sabado, th.hora_domingo
    FROM rh_personal p
    INNER JOIN rh_tipos_horarios th ON p.id_tipo_h = th.id_thorario
    WHERE p.status_pl = 1
";
$res = $Con->query($sql);
while ($row = $res->fetch_assoc()) {
    $horaSalidaMap[$row['badge']] = [
        'hora_salida'  => $row['hora_salida'],
        'hora_sabado'  => $row['hora_sabado'] ?: $row['hora_salida'],
        'hora_domingo' => $row['hora_domingo'] ?: $row['hora_salida']
    ];
}

// 3️⃣ Obtener dispositivos
$dispositivos = $Con->query("SELECT id_dpbiometrico, ip, puerto, dispositivo FROM rh_dpbiometrico");

// 4️⃣ Procesar cada dispositivo
while ($dp = $dispositivos->fetch_object()) {

    // 🔹 Ping previo
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

    $attendance = $zk->getAttendance();
    if ($debug) { echo "<pre>"; var_dump($attendance); echo "</pre>"; }

    if (!empty($attendance)) {
        $attendance = array_reverse($attendance, true); // asegurar orden cronológico
        $insertValues = [];

        foreach ($attendance as $att) {
            $badge = $Con->real_escape_string($att['id'] ?? $att['userid']);
            $reg   = $Con->real_escape_string(date("Y-m-d H:i:s", strtotime($att['timestamp'])));
            $idDp  = (int)$dp->id_dpbiometrico;
            $idDpTipo = isset($att['type']) ? (int)$att['type'] : 0;

            if ($ultimaFecha && $reg <= $ultimaFecha) continue;

            // Obtener horas para este badge
            $horas = $horaSalidaMap[$badge] ?? [
                'hora_salida'  => 'NULL',
                'hora_sabado'  => 'NULL',
                'hora_domingo' => 'NULL'
            ];

            $hora_salida  = $horas['hora_salida']  ? "'{$horas['hora_salida']}'" : "NULL";
            $hora_sabado  = $horas['hora_sabado']  ? "'{$horas['hora_sabado']}'" : "NULL";
            $hora_domingo = $horas['hora_domingo'] ? "'{$horas['hora_domingo']}'" : "NULL";

            $insertValues[] = "('$badge', '$reg', $idDpTipo, $idDp, $hora_salida, $hora_sabado, $hora_domingo)";
        }

        // Insertar en bloques de 2000
        $chunks = array_chunk($insertValues, 2000);
        $totalInserted = 0;
        foreach ($chunks as $chunk) {
            $sql = "INSERT IGNORE INTO rh_check 
                        (badge, registro_check, id_dptipo, id_dispositivo, hora_salida, hora_sabado, hora_domingo) 
                    VALUES " . implode(',', $chunk);
            $res = $Con->query($sql);
            if ($res) $totalInserted += $Con->affected_rows;
        }

        $response['messages'][] = "✅ Dispositivo {$dp->dispositivo} actualizó $totalInserted registros.";
    } else {
        $response['messages'][] = "ℹ️ No hay registros nuevos en {$dp->dispositivo}.";
    }

    $zk->enableDevice();
    $zk->disconnect();
}

$response['success'] = true;
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
