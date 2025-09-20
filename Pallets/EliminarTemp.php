<?php
include_once '../../../Conexion/BD.php';
include_once "../../../Login/validar_sesion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $idTemp = intval($_POST['id']);

    // Validar que el ID sea positivo
    if ($idTemp > 0) {
        $stmtCount = $Con->prepare("SELECT id_pallet_temp FROM pallet_mezclas_temp WHERE usuario_id = ?");
        $stmtCount->bind_param("i", $ID);
        $stmtCount->execute();
        $result = $stmtCount->get_result();
            if ($result->num_rows === 1) {
                echo json_encode([
                        'status' => 'no',
                    ]);
            }else{
                $stmt = $Con->prepare("DELETE FROM pallet_mezclas_temp WHERE id_pallet_temp = ? AND usuario_id = ?");
                $stmt->bind_param("ii", $idTemp, $ID);
                if ($stmt->execute()) {

                    echo json_encode([
                        'status' => 'ok'
                    ]);
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
