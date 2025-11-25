<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";

header('Content-Type: application/json; charset=utf-8');

$id = $_GET['id'] ?? '';
$fecha1 = $_GET['fecha1'] ?? '';
$fecha2 = $_GET['fecha2'] ?? '';
$tipo = $_GET['tipo'] ?? '';

switch ($tipo) {
    case '1':
        $msg = 'bonos';
        break;
    case '2':
        $msg = 'destajos';
        break;
    case '3':
        $msg = 'compensaciones';
        break;
    default:
        $msg = 'error';
        break;
}

if (!empty($id) && !empty($tipo) && !empty($fecha1) && !empty($fecha2)) {

    $stmt = $Con->prepare("SELECT i.fecha_inc, i.motivo, i.cantidad FROM rh_incentivos i 
                            WHERE i.id_personal_i = ?
                            AND i.id_incentivo_i = ?
                            AND i.estado_i = 2
                            AND i.fecha_i BETWEEN ? AND ?
                            ORDER BY i.fecha_i ASC;");
    $stmt->bind_param("iiss", $id, $tipo, $fecha1, $fecha2);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $incentivos = [];

    while ($fila = $resultado->fetch_assoc()) {
        $incentivos[] = [
            'fecha' => $fila['fecha_inc'],
            'motivo' => $fila['motivo'],
            'monto' => $fila['cantidad']
        ];
    }

    if (!empty($incentivos)) {
        echo json_encode(['status' => 'ok', 'incentivos' => $incentivos, 'msg' => $msg]);
    } else {
        echo json_encode(['status' => 'empty', 'message' => 'No se encontraron ' . $msg, 'msg' => $msg]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Tipo inválido']);
}

$Con->close();
?>

