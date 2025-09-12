<?php
include_once '../../../Conexion/BD.php';
include_once "../../../Login/validar_sesion.php";

$FechaHoy = date("Y-m-d");

// Datos temporales del usuario
$stmt = $Con->prepare("
    SELECT mt.cajas_m, mt.kilos_m, re.nave, v.nombre_variedad 
    FROM mezcla_lotes_temp mt
    INNER JOIN registro_empaque re ON mt.id_lote = re.id_registro_r
    INNER JOIN variedades v ON re.id_variedad = v.id_nombre_v
    WHERE mt.usuario_id = ?
");
$stmt->bind_param("i", $ID);
$stmt->execute();
$partidas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Sumar totales
$total_cajas = 0;
$total_kilos = 0;
foreach ($partidas as $p) {
    $total_cajas += $p['cajas_m'];
    $total_kilos += $p['kilos_m'];
}

// Calcular % por variedad
foreach ($partidas as &$p) {
    $p['porcentaje'] = round(($p['kilos_m'] / $total_kilos) * 100, 1);
}
unset($p); // limpiar referencia
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Previsualización Mezcla</title>
    <style>
        table { font-family: nunito, arial, verdana; font-size: 14px; }
        .header { background-color: rgb(203, 243, 153); }
    </style>
</head>
<body>

<table width="520">
    <tr>
        <td><img src="d_logo" width="175" height="60"/></td>
        <td></td>
        <td>
            <table align="right" width="170" style="border: 1px solid gray;">
                <tr><td align="center" class="header"><font size="5">Mezcla</font></td></tr>
                <tr><td align="center"><font size="6" color="red"><b>PREVIA</b></font></td></tr>
            </table>
        </td>
    </tr>
</table>

<br>

<table width="518">
    <tr>
        <td width="320">
            <table cellpadding="0">
                <tr class="header">
                    <td width="50" align="center"><b>%</b></td>
                    <td width="50" align="center"><b>Nave</b></td>
                    <td width="220" align="center"><b>Variedad</b></td>
                </tr>
                <?php foreach ($partidas as $p): ?>
                    <tr>
                        <td align="center"><?= $p['porcentaje'] ?>%</td>
                        <td align="center"><?= $p['nave'] ?></td>
                        <td><?= $p['nombre_variedad'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </td>
        <td align="center" width="190">
            <div>Fecha de producción:</div>
            <b><?= $FechaHoy ?></b>
            <div>Kilogramos:</div>
            <b><?= number_format($total_kilos, 2) ?></b>
            <div>Cajas ingresadas:</div>
            <b><?= $total_cajas ?></b>
        </td>
    </tr>
</table>

</body>
</html>
