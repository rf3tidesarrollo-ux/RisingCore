<?php
include_once '../../../Conexion/BD.php';
include_once "../../../Login/validar_sesion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $idTemp = intval($_POST['id']);

    // Validar que el ID sea positivo
    if ($idTemp > 0) {
    // Contar cuántas mezclas HAY para el usuario SIN contar la que se desea eliminar
    $stmtCount = $Con->prepare("SELECT COUNT(*) as total FROM pallet_mezclas_temp WHERE usuario_id = ? AND id_pallet_temp != ?");
    $stmtCount->bind_param("ii", $ID, $idTemp);
    $stmtCount->execute();
    $result = $stmtCount->get_result();
    $row = $result->fetch_assoc();
    
        if ($row['total'] < 1) {
            echo json_encode([
                'status' => 'no', // No permitir eliminar si quedaría en 0
            ]);
        } else {
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
