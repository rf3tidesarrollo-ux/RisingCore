<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";

header('Content-Type: application/json; charset=utf-8');

$tipo = $_GET['tipo'] ?? '';

if (!empty($tipo) && is_numeric($tipo)) {

    if ($TipoRol === 'USUARIO') {
        $stmt = $Con->prepare("SELECT d.id_departamento, d.departamento, d.prefijo
                            FROM rh_departamentos d
                            WHERE d.id_dep_d = ? AND d.id_departamento = ? ORDER BY d.departamento ASC ");
        $stmt->bind_param("ii", $tipo, $IDArea);
    } else {
        $stmt = $Con->prepare(" SELECT d.id_departamento, d.departamento, d.prefijo
                            FROM rh_departamentos d
                            WHERE d.id_dep_d = ? ORDER BY d.departamento ASC ");
        $stmt->bind_param("i", $tipo);
    }

    $stmt->execute();
    $resultado = $stmt->get_result();

    $areas = [];

    while ($fila = $resultado->fetch_assoc()) {
        $areas[] = [
            'id' => $fila['id_departamento'],
            'area' => $fila['departamento'],
            'sub' => $fila['prefijo']
        ];
    }

    if (!empty($areas)) {
        echo json_encode(['status' => 'ok', 'areas' => $areas]);
    } else {
        echo json_encode(['status' => 'empty', 'message' => 'No se encontraron áreas']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Tipo inválido']);
}

$Con->close();
?>

