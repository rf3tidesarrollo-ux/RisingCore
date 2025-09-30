<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";

$imgPath = realpath('../../../Images/Rising_Logo.jpg');
$imgData = base64_encode(file_get_contents($imgPath));
$src = 'data:image/jpeg;base64,' . $imgData;

$datosMezcla = [];
$tabla = [];
$Fecha=date("d/m/Y");
$TD = $_GET['id'] ?? '';
$Folio = $_GET['folio'] ?? '';

    $stmt = $Con->prepare("SELECT c.nombre_completo FROM usuarios u JOIN Cargos c ON u.id_cargo = c.id_cargo WHERE id_usuario = ?");
    $stmt->bind_param("i", $ID);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $Titular = '';
    if ($fila = $resultado->fetch_assoc()) {
        $Titular = $fila['nombre_completo'];
    }
    $stmt->close();

if ($TD=="mostrar") {
    // Obtener datos de mezcla
    $sum = $Con->prepare("SELECT SUM(cajas_m) AS total_cajas, SUM(kilos_m) AS total_kilos FROM mezcla_lotes_temp WHERE usuario_id = ?");
    $sum->bind_param("i", $ID);
    $sum->execute();
    $datosMezcla = $sum->get_result();
    $totals = $datosMezcla->fetch_assoc();
    $sum->close();

    $stmt = $Con->prepare("SELECT s.codigo_s AS Sede, v.nombre_variedad AS Variedad, tp.nombre_p AS Presentacion, re.fecha_reg AS Fecha,
                            GROUP_CONCAT(DISTINCT i.invernadero ORDER BY i.invernadero SEPARATOR ', ') AS Invernaderos,
                            SUM(m.cajas_m) AS Cajas, SUM(m.kilos_m) AS Kilos,
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
                            WHERE m.usuario_id = ? GROUP BY v.nombre_variedad, tp.nombre_p");
    $stmt->bind_param("i", $ID);
    $stmt->execute();
    $tabla = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}else{
    // Obtener datos de mezcla
    $sum = $Con->prepare("SELECT SUM(cajas_m) AS total_cajas, SUM(kilos_m) AS total_kilos FROM mezcla_lotes WHERE id_mezcla_l = ?");
    $sum->bind_param("i", $TD);
    $sum->execute();
    $datosMezcla = $sum->get_result();
    $totals = $datosMezcla->fetch_assoc();
    $sum->close();

    $stmt = $Con->prepare("SELECT s.codigo_s AS Sede, v.nombre_variedad AS Variedad, tp.nombre_p AS Presentacion, re.fecha_reg AS Fecha,
                            GROUP_CONCAT(DISTINCT i.invernadero ORDER BY i.invernadero SEPARATOR ', ') AS Invernaderos,
                            SUM(m.cajas_m) AS Cajas, SUM(m.kilos_m) AS Kilos,
                                ROUND(SUM(m.kilos_m) * 100.0 / (
                                    SELECT SUM(m2.kilos_m)
                                    FROM mezcla_lotes m2
                                    WHERE m2.id_mezcla_l = m.id_mezcla_l
                                ), 2) AS Porcentaje
                            FROM mezcla_lotes m
                            JOIN registro_empaque re ON m.id_lote_l = re.id_registro_r
                            JOIN tipo_variaciones tv ON re.id_codigo_r = tv.id_variedad
                            JOIN variedades v ON tv.id_nombre_v = v.id_nombre_v
                            JOIN tipos_presentacion tp ON re.id_presentacion_r = tp.id_presentacion
                            JOIN ciclos c ON tv.id_ciclo_v = c.id_ciclo
                            JOIN invernaderos i ON tv.id_modulo_v = i.id_invernadero
                            JOIN sedes s ON i.id_sede_i = s.id_sede
                            WHERE m.id_mezcla_l = ? GROUP BY v.nombre_variedad, tp.nombre_p");
    $stmt->bind_param("i", $TD);
    $stmt->execute();
    $tabla = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $fol = $Con->prepare("SELECT folio_m AS Folio FROM mezclas WHERE id_mezcla = ?");
    $fol->bind_param("i", $TD);
    $fol->execute();
    $datoFolio = $fol->get_result();
    $total = $datoFolio->fetch_assoc();
    $fol->close();
    $Folio=$total['Folio'];
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 30px;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .contenedor {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 100px;
        }

        .encabezado {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .logo {
            display: table-cell;
            vertical-align: middle;
            width: 33%;
        }

        .titulo {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            width: 33%;
        }

        .folio {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 33%;
        }

        .folio-box {
            background-color: #f2f2f2;
            display: inline-block;
            padding: 5px 10px;
            font-weight: bold;
            color: #b30000;
            border-radius: 4px;
            font-size: 14px;
            text-align: center;
        }

        .detalles {
            margin-bottom: 10px;
        }

        .detalles b {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th, td {
            border: 1px solid #aca9a9ff;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: rgba(226, 226, 226, 1);
            font-weight: bold;
        }

        .total-row td {
            font-weight: bold;
            background-color: rgba(226, 226, 226, 1);
            text-align: center;
        }

        .total-row td:last-child {
            text-align: center;
        }

    </style>
</head>
<body>

<?php for ($i=0; $i < 2; $i++) { ?>
<div class="contenedor">
    <div class="encabezado">
        <div class="logo">
            <img src="<?= $src ?>" width="100" height="70"/>
        </div>
        <div class="titulo">
            RECIBO DE MEZCLA
        </div>
        <div class="folio">
            <span class="folio-box">FOLIO<br><?= htmlspecialchars($Folio, ENT_QUOTES, 'UTF-8'); ?></span>
        </div>
    </div>

    <div class="detalles">
        <b>Fecha:</b> <?= $Fecha ?? '-' ?> &nbsp;&nbsp;
        <b>Registró:</b> <?= $Titular ?? '-' ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>Sede</th>
                <th>Módulo/s</th>
                <th>Variedad</th>
                <th>Tipo Registro</th>
                <th>Fecha</th>
                <th>Porcentaje</th>
                <th>Cajas</th>
                <th>Kilos</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tabla as $tbl): ?>
                <tr>
                    <td align="center"><?= $tbl['Sede'] ?></td>
                    <td align="center"><?= $tbl['Invernaderos'] ?></td>
                    <td align="center"><?= $tbl['Variedad'] ?></td>
                    <td align="center"><?= $tbl['Presentacion'] ?></td>
                    <td align="center"><?=  date("d/m/Y", strtotime($tbl['Fecha'] ))?></td>
                    <td align="center"><?= $tbl['Porcentaje'] ?>%</td>
                    <td align="center"><?= $tbl['Cajas'] ?></td>
                    <td align="center"><?= $tbl['Kilos'] ?> kg</td>
                </tr>
                <?php endforeach; ?>
            <tr class="total-row">
                <td colspan="6">TOTAL</td>
                <td><?= $totals['total_cajas'] ?? 0 ?></td>
                <td><?= number_format($totals['total_kilos'] ?? 0, 2) ?> kg</td>
            </tr>
        </tbody>
    </table>
</div>
    
<?php } ?>
</body>
</html>
