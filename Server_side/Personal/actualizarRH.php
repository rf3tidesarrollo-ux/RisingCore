<?php
date_default_timezone_set('America/Mexico_City');
require_once '../../../Librerias/zkteco/zklib/ZKLib.php';
require_once '../../../Conexion/BD.php';

$response = ['success' => false, 'messages' => []];

// 🔹 Activar depuración si quieres ver los datos de los dispositivos
$debug = false;

// 1️⃣ Última fecha registrada por dispositivo
$ultimaFechaPorDp = [];
$result = $Con->query("SELECT id_dispositivo, MAX(registro_check) AS ultima FROM rh_check GROUP BY id_dispositivo");
while ($row = $result->fetch_assoc()) {
    $ultimaFechaPorDp[$row['id_dispositivo']] = $row['ultima'];
}

// 2️⃣ Obtener todos los dispositivos
$dispositivos = $Con->query("SELECT id_dpbiometrico, ip, puerto, dispositivo FROM rh_dpbiometrico");

while ($dp = $dispositivos->fetch_object()) {
    $zk = new ZKLib($dp->ip, $dp->puerto);

    if (!$zk->connect()) {
        $response['messages'][] = "❌ No se pudo conectar al dispositivo {$dp->dispositivo}.";
        continue;
    }

    $zk->disableDevice();

    // 🔹 Traer solo registros desde la última fecha registrada
    $ultimaFecha = $ultimaFechaPorDp[$dp->id_dpbiometrico] ?? null;

    if ($ultimaFecha) {
        // Depende de la versión de la librería; algunos SDK permiten $zk->getAttendance($ultimaFecha)
        $attendance = $zk->getAttendance(); // ZKLib básico no permite rango; se filtra después
    } else {
        $attendance = $zk->getAttendance();
    }

    if ($debug) {
        echo "<pre>";
        var_dump($attendance);
        echo "</pre>";
        // exit; // descomenta si quieres detener la ejecución para revisar
    }

    if (!empty($attendance)) {
        $attendance = array_reverse($attendance, true); // cronológico
        $insertValues = [];

        foreach ($attendance as $att) {
            $badge = $Con->real_escape_string($att['id'] ?? $att['userid']);
            $reg   = $Con->real_escape_string(date("Y-m-d H:i:s", strtotime($att['timestamp'])));
            $idDp  = (int)$dp->id_dpbiometrico;

            // 🔹 Tipo de verificación
            $idDpTipo = isset($att['type']) ? (int)$att['type'] : 0; // Huella por defecto

            // 🔹 Solo insertar registros nuevos
            if ($ultimaFecha && $reg <= $ultimaFecha) continue;

            $insertValues[] = "('$badge', '$reg', $idDpTipo, $idDp)";
        }

        // Insertar en bloques de 2000 registros
        $chunks = array_chunk($insertValues, 2000);
        $totalInserted = 0;

        foreach ($chunks as $chunk) {
            $sql = "INSERT IGNORE INTO rh_check (badge, registro_check, id_dptipo, id_dispositivo) 
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
