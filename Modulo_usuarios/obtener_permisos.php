<?php
include_once '../Conexion/BD.php';
$modulos = $Con->query("SELECT id_seccion, nombre_seccion FROM modulos")->fetch_all(MYSQLI_ASSOC);
$permisos = $Con->query("SELECT id_permiso, nombre FROM permisos")->fetch_all(MYSQLI_ASSOC);

echo json_encode(["modulos" => $modulos, "permisos" => $permisos]);

?>
