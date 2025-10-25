<?php
date_default_timezone_set('America/Mexico_City');
require_once '../../../Librerias/zkteco/zklib/ZKLib.php'; // Librería
require_once '../../../Conexion/BD.php'; // Conexión mysqli

// 1. Obtener la última fecha por dispositivo
$ultimaFechaPorDp = [];
$result = $Con->query("SELECT id_dispositivo, MAX(registro_check) AS ultima FROM rh_check GROUP BY id_dispositivo");
while ($row = $result->fetch_assoc()) {
    $ultimaFechaPorDp[$row['id_dispositivo']] = $row['ultima'];
}

// 2. Obtener todos los dispositivos
$dispositivos = $Con->query("SELECT id_dpbiometrico, ip, puerto, dispositivo FROM rh_dpbiometrico WHERE id_dpbiometrico = 2");

while ($dp = $dispositivos->fetch_object()) {
    $zk = new ZKLib($dp->ip, $dp->puerto);

    if (!$zk->connect()) {
        echo "❌ No se pudo conectar al dispositivo {$dp->dispositivo}.<br>";
        continue;
    }

    $zk->disableDevice();
    $attendance = $zk->getAttendance();

    if (!empty($attendance)) {
        $attendance = array_reverse($attendance, true); // Orden cronológico

        $insertValues = [];
        $ultimaFecha = $ultimaFechaPorDp[$dp->id_dpbiometrico] ?? null;

        foreach ($attendance as $att) {
            $badge = $att['userid'] ?? $att['id'];
            $reg   = date("Y-m-d H:i:s", strtotime($att['timestamp']));

            // Solo registros nuevos
            if ($ultimaFecha && $reg <= $ultimaFecha) continue;

            $badgeEsc = $Con->real_escape_string($badge);
            $regEsc   = $Con->real_escape_string($reg);
            $idDp     = (int)$dp->id_dpbiometrico;

            $insertValues[] = "('$badgeEsc', '$regEsc', 1, $idDp)";
        }

        // Insert masivo en un solo query
        if (!empty($insertValues)) {
            $Con->query("INSERT INTO rh_check (badge, registro_check, id_dptipo, id_dispositivo) VALUES " . implode(',', $insertValues));
            echo "✅ Dispositivo {$dp->dispositivo} actualizó " . count($insertValues) . " registros.<br>";
        } else {
            echo "ℹ️ Dispositivo {$dp->dispositivo} no tenía registros nuevos.<br>";
        }
    } else {
        echo "ℹ️ No hay registros en {$dp->dispositivo}.<br>";
    }

    $zk->enableDevice();
    $zk->disconnect();
}
?>
