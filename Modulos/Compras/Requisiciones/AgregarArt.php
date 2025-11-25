<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";

$FechaM = date("Y-m-d");
$HoraM = date("H:i:s");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addArt'])) {
    $Sede = $_POST['sede'] ?? 0;
    $Tipo = $_POST['tipoID'] ?? 0;
    $Producto = $_POST['artID'] ?? 0;
    $Cantidad = $_POST['cantidad'] ?? 0;
    $Fecha = $_POST['fecha'] ?? '';
    $Prioridad = $_POST['prioridad'] ?? 0;
    $Observacion = '';
    $Justificacion = $_POST['justificacion'] ?? '';
    $Estado = 'PENDIENTE';

    if ($Producto !== "0") {

        // Verificar si ya existe este producto para el usuario
        $verificar = $Con->prepare("SELECT id_producto_t FROM cp_requisicion_temp WHERE id_usuario_t = ? AND id_producto_t = ?");
        $verificar->bind_param("ii", $ID, $Producto);
        $verificar->execute();
        $resultado = $verificar->get_result();

        if ($resultado->num_rows > 0) {
            echo json_encode([
                'status' => 'duplicate',
                'message' => 'Este producto ya fue agregado previamente.'
            ]);
            exit;
        }
        $verificar->close();

        // Generar folio
        $nom = $Sede . '-' . date("Wy"); // año + semana

        // Buscar el folio más alto entre ambas tablas
        $query = "
            SELECT MAX(folio) AS Folio FROM (
                SELECT folio_p AS folio FROM cp_requisicion_pro WHERE folio_p LIKE ?
                UNION ALL
                SELECT folio_t AS folio FROM cp_requisicion_temp WHERE folio_t LIKE ?
            ) AS combined
        ";

        $stmt = $Con->prepare($query);
        $stmt->bind_param("ss", $likeNom, $likeNom);

        $likeNom = "%$nom%"; // o "$nom%" según tu formato

        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $lastFolio = $row['Folio'];
            if ($lastFolio) {
                $num = intval(substr($lastFolio, -3)) + 1;
                $Folio = $nom . '-' . str_pad($num, 3, '0', STR_PAD_LEFT);
            } else {
                $Folio = $nom . '-001';
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al generar folio']);
        }
        $stmt->close();

        // Insertar nueva requisición temporal
        $stmt = $Con->prepare("INSERT INTO cp_requisicion_temp (folio_t, id_producto_t, cantidad_t, fecha_rt, fecha_t, hora_t, prioridad_t, observacion, justificacion, estado_t, id_usuario_t) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siisssisssi", $Folio, $Producto, $Cantidad, $Fecha, $FechaR, $HoraR, $Prioridad, $Observacion, $Justificacion, $Estado, $ID);

        if ($stmt->execute()) {
            // Obtener totales actualizados
            $sum = $Con->prepare("SELECT COUNT(id_producto_t) AS total FROM cp_requisicion_temp WHERE id_usuario_t = ?");
            $sum->bind_param("i", $ID);
            $sum->execute();
            $result = $sum->get_result();
            $totals = $result->fetch_assoc();
            $sum->close();

            echo json_encode([
                'status' => 'ok',
                'total' => $totals['total'] ?? 0,
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
