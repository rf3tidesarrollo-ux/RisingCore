<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";

$datosMezcla = [];
$tabla = [];
$Fecha=date("Y-m-d");

// Obtener datos de mezcla
$sum = $Con->prepare("SELECT SUM(cajas_m) AS total_cajas, SUM(kilos_m) AS total_kilos FROM mezcla_lotes_temp WHERE usuario_id = ?");
$sum->bind_param("i", $ID);
$sum->execute();
$datosMezcla = $sum->get_result();
$totals = $datosMezcla->fetch_assoc();
$sum->close();

$stmt = $Con->prepare("SELECT v.nombre_variedad AS Variedad, GROUP_CONCAT(DISTINCT i.invernadero ORDER BY i.invernadero SEPARATOR ', ') AS Invernaderos,
                        SUM(m.kilos_m) AS total_kilos,
                            ROUND(SUM(m.kilos_m) * 100.0 / (
                                SELECT SUM(m2.kilos_m)
                                FROM mezcla_lotes_temp m2
                                WHERE m2.usuario_id = m.usuario_id
                            ), 2) AS Porcentaje
                        FROM mezcla_lotes_temp m 
                        JOIN registro_empaque re ON m.id_lote = re.id_registro_r
                        JOIN tipo_variaciones tv ON re.id_codigo_r = tv.id_variedad
                        JOIN variedades v ON tv.id_nombre_v = v.id_nombre_v
                        JOIN tipos_presentacion tp ON re.id_presentacion_r = tp.id_presentacion
                        JOIN ciclos c ON tv.id_ciclo_v = c.id_ciclo
                        JOIN invernaderos i ON tv.id_modulo_v = i.id_invernadero
                        JOIN sedes s ON i.id_sede_i = s.id_sede 
                        WHERE m.usuario_id = ? GROUP BY v.nombre_variedad");
$stmt->bind_param("i", $ID);
$stmt->execute();
$tabla = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
    }

    table {
        width: 55%;
        border-collapse: collapse;
    }

    .logo {
        width: 120px;
    }

    .mezcla-box {
        width: 170px;
        border: 1px solid gray;
        text-align: center;
        float: right;
        margin-bottom: 10px;
    }

    .mezcla-box .title {
        background-color: #cbf399;
        font-size: 16px;
        font-weight: bold;
        padding: 5px;
    }

    .mezcla-box .id {
        color: red;
        font-size: 20px;
        font-weight: bold;
        padding: 5px;
    }

    .mezcla-table {
        margin-top: 10px;
        width: 60%;
    }

    .mezcla-table th {
        background-color: #cbf399;
        padding: 5px;
        text-align: center;
        border: 1px solid #000;
    }

    .mezcla-table td {
        text-align: center;
        border: 1px solid #000;
        padding: 5px;
    }

    .info {
        float: right;
        text-align: left;
        font-size: 13px;
    }

    .info b {
        display: block;
        font-weight: bold;
    }

    .section {
        margin-bottom: 60px;
    }
</style>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="../../../Images/MiniLogo.png">
    <title>PDF: Mezcla</title>
</head>
<body>

<!-- Encabezado -->
<table>
<tr><td height="362" valign="top">
<table width="520" cellspacing="0">
    <tr>
        <td><img src="<?= realpath('../../../Images/Rising-core.png') ?>" width="120" height="56"></td>
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
                        <font size="6" color="red"><b><?= $ID ?? '-' ?></b></font>
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
                    <td align="center" width="60" style="background-color: rgb(203, 243, 153);"><font size="4">%</font></td>
                    <td align="center" width="50" style="background-color: rgb(203, 243, 153);"><font size="4">Nave/s</font></td>
                    <td align="center" width="280" style="background-color: rgb(203, 243, 153);"><font size="4">Variedad</font></td>
                </tr>

                <?php foreach ($tabla as $tbl): ?>
                <tr>
                    <td align="center"><?= $tbl['Porcentaje'] ?>%</td>
                    <td align="center"><?= $tbl['Invernaderos'] ?></td>
                    <td align="center"><?= $tbl['Variedad'] ?></td>
                </tr>
                <?php endforeach; ?>

            </table>
        </td>

        <!-- Detalles a la derecha -->
        <td align="center" width="190" valign="top">
            <font size="3">Fecha de producción:</font><br/>
            <font size="3"><b><?= $Fecha ?? '-' ?></b></font><br/>
            <font size="3">Kilogramos:</font><br/>
            <font size="3"><b><?= number_format($totals['total_kilos'] ?? 0, 2) ?></b></font><br/>
            <font size="3">Cajas Ingresadas:</font><br/>
            <font size="3"><b><?= $totals['total_cajas'] ?? 0 ?></b></font>
        </td>
    </tr>
</table>

</td></tr>
</table>

<!-- Encabezado -->
<table>
<tr><td height="362" valign="top">
<table width="520" cellspacing="0">
    <tr>
        <td><img src="../../../Images/Rising-core.png" width="120" height="56"/></td>
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
                        <font size="6" color="red"><b><?= $ID ?? '-' ?></b></font>
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
                    <td align="center" width="60" style="background-color: rgb(203, 243, 153);"><font size="4">%</font></td>
                    <td align="center" width="50" style="background-color: rgb(203, 243, 153);"><font size="4">Nave/s</font></td>
                    <td align="center" width="280" style="background-color: rgb(203, 243, 153);"><font size="4">Variedad</font></td>
                </tr>

                <?php foreach ($tabla as $tbl): ?>
                <tr>
                    <td align="center"><?= $tbl['Porcentaje'] ?>%</td>
                    <td align="center"><?= $tbl['Invernaderos'] ?></td>
                    <td align="center"><?= $tbl['Variedad'] ?></td>
                </tr>
                <?php endforeach; ?>

            </table>
        </td>

        <!-- Detalles a la derecha -->
        <td align="center" width="190" valign="top">
            <font size="3">Fecha de producción:</font><br/>
            <font size="3"><b><?= $Fecha ?? '-' ?></b></font><br/>
            <font size="3">Kilogramos:</font><br/>
            <font size="3"><b><?= number_format($totals['total_kilos'] ?? 0, 2) ?></b></font><br/>
            <font size="3">Cajas Ingresadas:</font><br/>
            <font size="3"><b><?= $totals['total_cajas'] ?? 0 ?></b></font>
        </td>
    </tr>
</table>

</td></tr>
</table>

</body>
</html>
