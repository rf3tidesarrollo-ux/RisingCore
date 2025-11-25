<?php
include_once '../../../Conexion/BD.php';

$filtro = $_GET['filtro'] ?? '';

switch ($filtro) {
    case 'anos':
        $sql = "SELECT DISTINCT YEAR(registro_check) as ano FROM rh_check ORDER BY ano ASC";
        break;

    case 'semanas':
        $sql = "SELECT DISTINCT YEAR(registro_check) as ano, WEEK(registro_check, 1) as semana 
                FROM rh_check 
                ORDER BY semana DESC";
        break;

    case 'sedes':
        $sql = "SELECT id_sede, codigo_s as sede FROM sedes ORDER BY sede DESC";
        break;

    case 'departamentos':
        $sql = "SELECT id_departamento, departamento FROM rh_departamentos ORDER BY departamento";
        break;

    case 'tipos':
        $sql = "SELECT id_tipo_rh, tipo_rh, prefijo_te FROM rh_tipos_empleados ORDER BY tipo_rh";
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
