<?php
include '../../../Conexion/BD.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_lote'])) {
    $id_lote = intval($_POST['id_lote']);

    $stmt = $Con->prepare("SELECT cajas_dis FROM registro_empaque WHERE id_registro_r = ?");
    $stmt->bind_param("i", $id_lote);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode(['status' => 'ok', 'cajas_dis' => $row['cajas_dis']]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No encontrado']);
    }

    $stmt->close();
}
