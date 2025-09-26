<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addMezcla'])) {
    $Mezcla = $_POST['mezcla'] ?? '';
    $Cajas = $_POST['cajas'] ?? '';
    $Linea = $_POST['lineas'] ?? '';

    if ($Mezcla !== "0" && !empty($Cajas)) {

        // Verificar si ya existe este lote para el usuario
        $verificar = $Con->prepare("SELECT id_mezcla_t FROM pallet_mezclas_temp WHERE usuario_id = ? AND id_mezcla_t = ?");
        $verificar->bind_param("ii", $ID, $Mezcla);
        $verificar->execute();
        $resultado = $verificar->get_result();

        if ($resultado->num_rows > 0) {
            echo json_encode([
                'status' => 'duplicate',
                'message' => 'Esta mezcla ya fue agregada previamente.'
            ]);
            exit;
        }
        $verificar->close();

        // Insertar nuevo lote
        $stmt = $Con->prepare("INSERT INTO pallet_mezclas_temp (usuario_id, id_mezcla_t, cajas_t, id_linea_t) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiii", $ID, $Mezcla, $Cajas, $Linea);
        $stmt->execute();
        $stmt->close();

        echo json_encode([
                'status' => 'ok',
            ]);
            
        
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
    }
    exit;
}
?>
