<?php
require_once '../../../Librerias/zkteco/zklib/ZKLib.php';

$ip = "192.168.1.113"; // IP del checador
$port = 4370;          // Puerto default

$zk = new ZKLib($ip, $port);

// Conectar al checador
$ret = $zk->connect();
if (!$ret) {
    die("No se pudo conectar al checador $ip:$port");
}

// Obtener usuarios
$users = $zk->getUser();
foreach ($users as $uid => $user) {
    echo "ID Usuario: {$user['userid']}\n";
    echo "Nombre: {$user['name']}\n";
    echo "Tarjeta: {$user['cardno']}\n";
    echo "Rol: {$user['role']}\n";

    // Intentar obtener la huella (algunos dispositivos soportan hasta 10 dedos: 0–9)
    for ($finger = 0; $finger < 10; $finger++) {
        $template = $zk->getUserTemplate($uid, $finger);
        if ($template) {
            echo "Huella #$finger: " . substr($template, 0, 50) . "...\n"; // solo mostramos parte
        }
    }

    // Intentar obtener rostro (si el equipo soporta reconocimiento facial)
    if (method_exists($zk, 'getUserFace')) {
        $face = $zk->getUserFace($uid);
        if ($face) {
            echo "Rostro (template): " . substr($face, 0, 50) . "...\n";
        }
    }

    echo "-----------------------\n";
}

$zk->disconnect();
?>

