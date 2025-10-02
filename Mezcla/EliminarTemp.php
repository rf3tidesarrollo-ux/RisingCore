<?php
include_once '../../../Conexion/BD.php';
include_once "../../../Login/validar_sesion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $idTemp = intval($_POST['id']);

    // Validar que el ID sea positivo
    if ($idTemp > 0) {
        $stmtCount = $Con->prepare("SELECT id_mezcla_temp FROM mezcla_lotes_temp WHERE usuario_id = ?");
        $stmtCount->bind_param("i", $ID);
        $stmtCount->execute();
        $result = $stmtCount->get_result();
            if ($result->num_rows === 1) {
                echo json_encode([
                        'status' => 'no',
                    ]);
            }else{
                $stmt = $Con->prepare("DELETE FROM mezcla_lotes_temp WHERE id_mezcla_temp = ? AND usuario_id = ?");
                $stmt->bind_param("ii", $idTemp, $ID);
                if ($stmt->execute()) {
                    $sum = $Con->prepare("SELECT SUM(cajas_m) AS total_cajas, SUM(kilos_m) AS total_kilos FROM mezcla_lotes_temp WHERE usuario_id = ?");
                    $sum->bind_param("i", $ID);
                    $sum->execute();
                    $result = $sum->get_result();
                    $totals = $result->fetch_assoc();
                    $sum->close();

                    echo json_encode([
                        'status' => 'ok',
                        'total_cajas' => $totals['total_cajas'] ?? 0,
                        'total_kilos' => $totals['total_kilos'] ?? 0
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
