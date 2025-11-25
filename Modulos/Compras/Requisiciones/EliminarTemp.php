<?php
include_once '../../../Conexion/BD.php';
include_once "../../../Login/validar_sesion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $idTemp = intval($_POST['id']);

    // Validar que el ID sea positivo
    if ($idTemp > 0) {
        $stmtCount = $Con->prepare("SELECT id_requisicion_t FROM cp_requisicion_temp WHERE id_usuario_t = ?");
        $stmtCount->bind_param("i", $ID);
        $stmtCount->execute();
        $result = $stmtCount->get_result();
            if ($result->num_rows === 1) {
                echo json_encode([
                        'status' => 'no',
                    ]);
            }else{
                $stmt = $Con->prepare("DELETE FROM cp_requisicion_temp WHERE id_requisicion_t = ? AND id_usuario_t = ?");
                $stmt->bind_param("ii", $idTemp, $ID);
                if ($stmt->execute()) {
                    $sum = $Con->prepare("SELECT COUNT(id_producto_t) AS total FROM cp_requisicion_temp WHERE id_usuario_t = ?");
                    $sum->bind_param("i", $ID);
                    $sum->execute();
                    $result = $sum->get_result();
                    $totals = $result->fetch_assoc();
                    $sum->close();

                    echo json_encode([
                        'status' => 'ok',
                        'total' => $totals['total'] ?? 0
                    ]);
                } else {
                    echo "Error en la consulta";
                }
                $stmt->close();
            }
        $stmtCount->close();
    } else {
        echo "id inválido";
    }
} else {
    echo "Solicitud no válida";
}
?>
