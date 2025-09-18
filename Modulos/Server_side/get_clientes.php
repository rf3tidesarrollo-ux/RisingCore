<?php
include_once '../../Conexion/BD.php';

$tipo = $_GET['tipo'] ?? '';

// Mapeo
$mapa_tipos = [
    'RF1' => 1,
    'RF2' => 2,
    'RF3' => 3
];

header('Content-Type: application/json');

if (isset($mapa_tipos[$tipo])) {
    $clientes = $mapa_tipos[$tipo];

    $stmt = $Con->prepare("SELECT c.id_cliente, c.nombre_cliente, c.abreviatura
                            FROM clientes c
                            JOIN sedes s ON c.id_sede_c = s.id_sede
                            WHERE c.id_sede_c = ? AND c.activo_c = 1 ORDER BY c.id_cliente ASC");
    $stmt->bind_param("i", $clientes);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $clientes = [];

    while ($fila = $resultado->fetch_assoc()) {
        $clientes[] = [
            'id' => $fila['id_cliente'],
            'cliente' => $fila['nombre_cliente'],
            'sub' => $fila['abreviatura']
        ];
    }

    echo json_encode(['status' => 'ok', 'clientes' => $clientes]);

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Tipo inválido']);
}

$Con->close();
?>
