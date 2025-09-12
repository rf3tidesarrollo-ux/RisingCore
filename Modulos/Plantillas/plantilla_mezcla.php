<?php
include_once '../../../Conexion/BD.php';

$id_mezcla = $_GET['id'] ?? 0;
$datosMezcla = [];
$partidas = [];

// Obtener datos de mezcla
$stmt = $Con->prepare("SELECT m.id_mezcla, m.fecha_produccion, m.kilogramos, m.cajas_ingresadas 
                       FROM mezclas m WHERE m.id_mezcla = ?");
$stmt->bind_param("i", $id_mezcla);
$stmt->execute();
$result = $stmt->get_result();
$datosMezcla = $result->fetch_assoc();
$stmt->close();

// Obtener partidas de mezcla
$stmt = $Con->prepare("SELECT mp.porcentaje, mp.nave, v.nombre_variedad 
                       FROM mezcla_partidas mp 
                       INNER JOIN variedades v ON v.id_nombre_v = mp.variedad_id 
                       WHERE mp.id_mezcla = ?");
$stmt->bind_param("i", $id_mezcla);
$stmt->execute();
$partidas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        .arriba { vertical-align: 10px; }
        table { font-family: nunito, arial, verdana; }
    </style>
</head>
<body>

<!-- Encabezado -->
<table>
<tr><td height="362" valign="top">
<table width="520" cellspacing="0">
    <tr>
        <td><img src="d_logo" width="175" height="60"/></td>
        <td></td>
        <td>
            <table align="right" cellspacing="0" width="170" style="border-collapse: collapse; border: 1px solid gray;">
                <tr>
                    <td align="center" style="background-color: rgb(203, 243, 153);">
                        <font size="5">Mezcla</font>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="center">
                        <font size="6" color="red"><b><?= $datosMezcla['id_mezcla'] ?? '-' ?></b></font>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- Espaciador -->
<table height="10"></table>

<!-- Tabla de partidas -->
<table width="518">
    <tr>
        <td width="320">
            <table cellpadding="0">
                <tr>
                    <td width="50" align="center" style="background-color: rgb(203, 243, 153);"><font size="4">%</font></td>
                    <td align="center" width="50" style="background-color: rgb(203, 243, 153);"><font size="4">Nave</font></td>
                    <td align="center" width="280" style="background-color: rgb(203, 243, 153);"><font size="4">Variedad</font></td>
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

        <!-- Detalles a la derecha -->
        <td align="center" width="190" valign="top">
            <font size="3">Fecha de producción:</font><br/>
            <font size="3"><b><?= $datosMezcla['fecha_produccion'] ?? '-' ?></b></font><br/>
            <font size="3">Kilogramos:</font><br/>
            <font size="3"><b><?= number_format($datosMezcla['kilogramos'] ?? 0, 2) ?></b></font><br/>
            <font size="3">Cajas Ingresadas:</font><br/>
            <font size="3"><b><?= $datosMezcla['cajas_ingresadas'] ?? 0 ?></b></font>
        </td>
    </tr>
</table>

</td></tr>
</table>

</body>
</html>
