<?php
include_once '../../../Conexion/BD.php';

$tipo = $_GET['tipo'] ?? '';


header('Content-Type: application/json');

if (!empty($tipo)) {

    $stmt = $Con->prepare("SELECT p.id_producto, p.producto
                            FROM cp_productos p 
                            WHERE p.id_tipo_p = ?  ORDER BY p.producto ASC");
    $stmt->bind_param("i", $tipo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $productos = [];

    while ($fila = $resultado->fetch_assoc()) {
        $productos[] = [
            'id' => $fila['id_producto'],
            'producto' => $fila['producto']
        ];
    }

    echo json_encode(['status' => 'ok', 'productos' => $productos]);

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Tipo inválido']);
}

$Con->close();
?>
