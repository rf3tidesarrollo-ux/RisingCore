<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
set_time_limit(0);

// Para mostrar progreso en tiempo real
ob_implicit_flush(true);
while (ob_get_level()) { ob_end_flush(); }

require_once '../../../Librerias/autoload.php';
use CodingLibs\ZktecoPhp\Libs\ZKTeco;

$ip = "192.168.1.113";
$port = 4370;
$zk = new ZKTeco($ip, $port);

echo "Intentando conectar...\n";

if (!$zk->connect()) die("No se pudo conectar\n");

echo "Conectado al dispositivo\n";

// Obtenemos todos los usuarios
$users = $zk->getUsers();
$totalUsers = count($users);
echo "Usuarios encontrados: $totalUsers\n";

foreach ($users as $uid => $user) {
    echo "Usuario: {$user['name']} (UID: $uid)\n";

    // Obtener información de huellas disponibles para el usuario
    $fingerprints = $zk->getFingerprint($uid); // devuelve un array de dedos con datos
    if (!empty($fingerprints) && is_array($fingerprints)) {
        foreach ($fingerprints as $finger => $tpl) {
            if (!empty($tpl)) {
                $fn = "huella_{$user['user_id']}_{$finger}.bin";
                file_put_contents($fn, $tpl);
                echo "  Huella dedo $finger guardada en $fn\n";
            }
        }
    } else {
        echo "  No tiene huellas registradas\n";
    }
}

$zk->disconnect();
echo "Desconectado\n";
?>
