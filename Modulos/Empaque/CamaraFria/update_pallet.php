<?php
include_once '../../../Conexion/BD.php';

// Sanitizar entrada
$id_pallet = isset($_POST['id']) ? intval($_POST['id']) : 0;
$mapeo = isset($_POST['mapeo']) ? intval($_POST['mapeo']) : 0;
$ubicacion = isset($_POST['ubicacion']) ? intval($_POST['ubicacion']) : 0;

if ($id_pallet <= 0) {
    echo json_encode(["status" => "error", "msg" => "ID inválido"]);
    exit;
}

// Validaciones de rango
if ($mapeo <= 0 || $mapeo > 70) {
    echo json_encode(["status" => "error", "msg" => "El mapeo debe estar entre 1 y 70"]);
    exit;
}
if ($ubicacion <= 0 || $ubicacion > 30) {
    echo json_encode(["status" => "error", "msg" => "La ubicación debe estar entre 1 y 30"]);
    exit;
}

// Validar duplicados en BD
$stmt = $Con->prepare("SELECT COUNT(*) as total FROM pallets WHERE (mapeo = ? OR ubicacion = ?) AND id_pallet <> ?");
$stmt->bind_param("iii", $mapeo, $ubicacion, $id_pallet);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if ($result['total'] > 0) {
    echo json_encode(["status" => "error", "msg" => "Ese mapeo o ubicación ya está ocupado"]);
    exit;
}
$stmt->close();

// Actualizar pallet
$stmt = $Con->prepare("UPDATE pallets SET mapeo = ?, ubicacion = ? WHERE id_pallet = ?");
$stmt->bind_param("iii", $mapeo, $ubicacion, $id_pallet);

if ($stmt->execute()) {
    echo json_encode(["status" => "ok", "msg" => "Pallet actualizado correctamente"]);
} else {
    echo json_encode(["status" => "error", "msg" => "No se pudo actualizar"]);
}
$stmt->close();
