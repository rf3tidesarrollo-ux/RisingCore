<?php
$zk = new ZKLib('192.168.2.113'); // IP del checador de la otra sede
if($zk->connect()){
    $zk->disableDevice();
    $usuarios = unserialize(file_get_contents('datosUsuarios.dat'));

    foreach($usuarios as $user){
        $zk->setUser(
            $user['uid'],
            $user['userid'],
            $user['name'],
            $user['password'] ?? '',
            ZK\Util::LEVEL_USER,
            $user['fingerprints'] ?? [],
            $user['face'] ?? []
        );
    }

    $zk->enableDevice();
    $zk->disconnect();
}

?>
