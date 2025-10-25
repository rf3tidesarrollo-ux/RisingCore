<?php
require_once '../../../Librerias/zkteco/zklib/ZKLib.php';
include_once '../../../Conexion/BD.php';

// =============================
// 1️⃣ Obtener lista de empleados activos
// =============================
$stmt = $Con->prepare("
    SELECT badge
    FROM rh_personal
    WHERE status_pl = 1 AND badge IS NOT NULL AND badge <> ''
");
$stmt->execute();
$empleados = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// =============================
// 2️⃣ Obtener los dispositivos biométricos de la sede
// =============================
$stmt = $Con->prepare("SELECT ip, puerto FROM rh_dpbiometrico WHERE id_dpbiometrico = 0");
$stmt->execute();
$dispositivos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// =============================
// 3️⃣ Función rápida para probar conexión (ping puerto TCP)
// =============================
function puerto_abierto($host, $port, $timeout = 1) {
    $conn = @fsockopen($host, $port, $errno, $errstr, $timeout);
    if ($conn) { fclose($conn); return true; }
    return false;
}

// Traer usuarios desde un dispositivo activo para calcular nextUID
            $nextUID = 1;
            foreach ($dispositivos as $disp) {
                if (!puerto_abierto($disp['ip'], $disp['puerto'], 1)) continue;

                $zk = new ZKLib($disp['ip'], $disp['puerto']);
                if ($zk->connect()) {
                    $users = $zk->getUser();
                    if (!empty($users)) {
                        $nextUID = max(array_keys($users)) + 1;
                    }
                    $zk->disconnect();
                    break; // ya tenemos los usuarios, no necesitamos más
                }
            }

// =============================
// 4️⃣ Enviar todos los usuarios a cada dispositivo
// =============================
foreach ($dispositivos as $disp) {
    $ip = $disp['ip'];
    $puerto = $disp['puerto'];

    echo "📡 Conectando a $ip:$puerto...<br>";

    if (!puerto_abierto($ip, $puerto, 1)) {
        echo "❌ No responde: $ip:$puerto<br>";
        continue;
    }

    $zk = new ZKLib($ip, $puerto);

    if ($zk->connect()) {
        echo "✅ Conectado a $ip<br>";
        $zk->disableDevice();
        $zk->clearUsers(); // ⚠️ Elimina TODOS los usuarios
        $count = 0;
        
        foreach ($empleados as $emp) {
            $user_id = trim($emp['badge']);
            $name = trim($emp['badge']); // usar el badge también como nombre
            $password = "";
            $role = 0;

            // aseguramos formato (por si acaso)
            $name = preg_replace('/[^A-Z0-9]/', '', strtoupper($name));
            $name = substr($name, 0, 24);

            $zk->setUser($nextUID, $user_id, $name, $password, $role);
            $nextUID++;
            $count++;
        }
            

        $zk->enableDevice();
        $zk->disconnect();
        echo "✅ $count usuarios enviados correctamente a $ip<br><br>";
    } else {
        echo "❌ No se pudo conectar a $ip:$puerto<br>";
    }
}

echo "<br>🚀 Proceso finalizado.";
?>
