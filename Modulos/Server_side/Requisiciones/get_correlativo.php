<?php
    // Conexión
    include_once '../../../Conexion/BD.php';

    $base = $_GET['base'] ?? '';

    if ($base === '') {
        echo json_encode(['correlativo' => '01']);
        exit;
    }

    $stmt = $Con->prepare("SELECT COUNT(*) as total FROM cp_requisiciones WHERE folio_req LIKE CONCAT(?, '%')");
    $stmt->bind_param("s", $base);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $next = $row['total'] + 1;
    $correlativo = str_pad($next, 2, '0', STR_PAD_LEFT);

    echo json_encode(['correlativo' => $correlativo]);
?>