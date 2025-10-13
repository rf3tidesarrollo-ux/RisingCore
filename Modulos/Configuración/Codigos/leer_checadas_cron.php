<?php
// leer_checadas_cron.php
date_default_timezone_set('America/Mexico_City');

// Archivo de log
$logFile = __DIR__ . "/cron_debug.log.txt";
file_put_contents($logFile, date("Y-m-d H:i:s") . " - Script iniciado\n", FILE_APPEND);

// Función de log
function logMsg($msg) {
    global $logFile;
    file_put_contents($logFile, date("Y-m-d H:i:s") . " - " . $msg . "\n", FILE_APPEND);
}

// Conexión a BD
include_once("/checador/conexionBD.php"); // Ajusta la ruta
if (!$connMS) {
    logMsg("Error: No se pudo conectar a la base de datos");
    exit;
}

// Función para guardar asistencia
function setRecord($badge, $reg, $idDp) {
    global $connMS;
    $query = "INSERT INTO tbl_checador (badge, registro, id_dptipo, id_dpbiometrico) 
              SELECT :badge, :reg, 1, :idDp
              WHERE NOT EXISTS (
                  SELECT 1 FROM tbl_checador WHERE badge=:badge AND registro=:reg
              )";
    $stmt = $connMS->prepare($query);
    $stmt->execute([
        ':badge' => $badge,
        ':reg'   => $reg,
        ':idDp'  => $idDp
    ]);
}

// Obtener dispositivos
try {
    $stmt = $connMS->query("SELECT id_dpbiometrico, ip, puerto, nombre FROM tbl_dpbiometrico");
    $dispositivos = $stmt->fetchAll(PDO::FETCH_OBJ);
    $countDisp = count($dispositivos);
    logMsg("Dispositivos encontrados: $countDisp");

    if ($countDisp === 0) {
        logMsg("No hay dispositivos configurados en la base de datos");
        exit;
    }

    foreach ($dispositivos as $dp) {
        logMsg("Intentando conectar al dispositivo: $dp->nombre ($dp->ip:$dp->puerto)");

        require_once('/checador/zklib/ZKLib.php'); // Ajusta la ruta
        $zk = new ZKLib($dp->ip, $dp->puerto);

        if ($zk->connect()) {
            logMsg("✅ Conectado a $dp->ip");
            $zk->disableDevice();

            $attendance = $zk->getAttendance();
            if (!empty($attendance)) {
                $regCount = 0;
                foreach ($attendance as $att) {
                    $badge = $att['id']; // o 'userid' según librería
                    $reg = date("Y-m-d H:i:s", strtotime($att['timestamp']));
                    setRecord($badge, $reg, $dp->id_dpbiometrico);
                    $regCount++;
                    logMsg("Registro guardado: Badge $badge, $reg, DP $dp->id_dpbiometrico");
                }
                logMsg("Total de registros insertados desde $dp->nombre: $regCount");
            } else {
                logMsg("No hay registros de asistencia en $dp->nombre");
            }

            $zk->enableDevice();
            $zk->disconnect();
        } else {
            logMsg("❌ FALLO al conectar a $dp->ip:$dp->puerto");
        }
    }
} catch (Exception $e) {
    logMsg("Error general: " . $e->getMessage());
}

logMsg("Script finalizado\n");
