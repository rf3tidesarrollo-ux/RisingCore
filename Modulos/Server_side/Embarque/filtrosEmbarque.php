<?php
include_once '../../../Conexion/BD.php';

$filtro = $_GET['filtro'] ?? '';

switch ($filtro) {
    case 'estado':
        $sql = "SELECT DISTINCT estado_em as estado FROM embarques_pallets ORDER BY estado ASC";
        break;

    default:
        echo json_encode([]);
        exit;
}

$result = $Con->query($sql);
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data, JSON_UNESCAPED_UNICODE);
