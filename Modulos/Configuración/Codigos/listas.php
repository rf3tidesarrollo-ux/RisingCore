<?php
$filename = "user.dat";
$contenido = file_get_contents($filename);

// Reemplazar o usar expresiones regulares para separar los campos
// Por ejemplo, separar por los caracteres de control (\x01 a \x1F)
$lineas = preg_split('/[\x01-\x1F]+/', $contenido);

$datos = [];
for ($i = 0; $i < count($lineas); $i += 3) {
    $badge = trim($lineas[$i] ?? '');
    $nombre = trim($lineas[$i+1] ?? '');
    $otro = trim($lineas[$i+2] ?? '');
    if ($badge && $nombre) {
        $datos[] = ['badge' => $badge, 'nombre' => $nombre, 'otro' => $otro];
    }
}

// Mostrar tabla
echo "<table border='1'>";
echo "<tr><th>Badge</th><th>Nombre</th><th>Otro</th></tr>";
foreach ($datos as $d) {
    echo "<tr><td>{$d['badge']}</td><td>{$d['nombre']}</td><td>{$d['otro']}</td></tr>";
}
echo "</table>";
?>
