<?php
require_once '../../../Conexion/BD.php';

$targetUserId = intval($_POST['id_usuario'] ?? 0);

if ($targetUserId <= 0) {
    echo json_encode(['ok' => false, 'msg' => 'ID inválido']);
    exit;
}

$stmt = $Con->prepare("SELECT id_session FROM usuarios WHERE id_usuario = ?");
$stmt->bind_param("i", $targetUserId);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$stmt->close();

$sid = $row['id_session'] ?? '';

if ($sid) {
    $savePath = session_save_path();
    if (empty($savePath)) {
        $savePath = sys_get_temp_dir();
    }
    $file = rtrim($savePath, '/\\') . DIRECTORY_SEPARATOR . 'sess_' . $sid;

    if (file_exists($file)) {
        @unlink($file); // destruye la sesión del usuario
    }

    // Limpia el id_session en la base de datos
    $stmt2 = $Con->prepare("UPDATE usuarios SET id_session = NULL, estado = 0 WHERE id_usuario = ?");
    $stmt2->bind_param("i", $targetUserId);
    $stmt2->execute();
    $stmt2->close();

    echo json_encode(['ok' => true]);
} else {
    echo json_encode(['ok' => false, 'msg' => 'Usuario sin sesión activa']);
}
?>
