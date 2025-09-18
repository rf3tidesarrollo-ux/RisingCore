<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";

$FechaM = date("Y-m-d");
$HoraM = date("H:i:s");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addLote'])) {
    $Lote = $_POST['lote'] ?? '';
    $CajasA = $_POST['cajas'] ?? '';
    $CajasD = 0;
    $KilosD = 0.0;

    // Obtener datos de cajas y kilos disponibles del lote original
    $stmt = $Con->prepare("SELECT cajas_dis, kilos_dis FROM registro_empaque WHERE id_registro_r = ?");
    $stmt->bind_param("i", $Lote);
    $stmt->execute();
    $Registro = $stmt->get_result();

    if ($Registro->num_rows > 0) {
        $Reg = $Registro->fetch_assoc();
        $CajasD = $Reg['cajas_dis'];
        $KilosD = $Reg['kilos_dis'];
    }
    $stmt->close();

    $Total = ($CajasD > 0) ? ($KilosD / $CajasD) * $CajasA : 0;

    if ($Lote !== "0" && !empty($CajasA)) {

        // Verificar si ya existe este lote para el usuario
        $verificar = $Con->prepare("SELECT id_lote FROM mezcla_lotes_temp WHERE usuario_id = ? AND id_lote = ?");
        $verificar->bind_param("ii", $ID, $Lote);
        $verificar->execute();
        $resultado = $verificar->get_result();

        if ($resultado->num_rows > 0) {
            echo json_encode([
                'status' => 'duplicate',
                'message' => 'Este lote ya fue agregado previamente.'
            ]);
            exit;
        }
        $verificar->close();

        // Insertar nuevo lote
        $stmt = $Con->prepare("INSERT INTO mezcla_lotes_temp (usuario_id, id_lote, cajas_m, kilos_m) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $ID, $Lote, $CajasA, $Total);

        if ($stmt->execute()) {
            // Obtener totales actualizados
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
            echo json_encode(['status' => 'error', 'message' => 'Error al insertar']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
    }
    exit;
}
?>
