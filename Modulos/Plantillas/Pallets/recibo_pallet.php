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
$TD = $_GET['id'] ?? '';
$FechaActual = date("d/m/Y");
$Sede = $_GET['Sede'] ?? '';
$Folio = $_GET['Folio'] ?? '';
$Pre = $_GET['Presentaciones'] ?? '';
$Linea = $_GET['Lineas'] ?? '';
$Tipo = $_GET['Tipo'] ?? '';
$Ta = $_GET['Tarima'] ?? '';
$Fecha = $_GET['Fecha'] ?? '';
$FechaE = $_GET['FechaE'] ?? '';

if ($Tipo == 0) {
    $Tipo = "-";
}


if ($TD=="mostrar") {
    // Obtener datos de mezcla
    $sum = $Con->prepare("SELECT SUM(cajas_t) AS Suma FROM pallet_mezclas_temp WHERE usuario_id = ?");
    $sum->bind_param("i", $ID);
    $sum->execute();
    $datosMezcla = $sum->get_result();
    $totals = $datosMezcla->fetch_assoc();
    $sum->close();

    $stmt = $Con->prepare("SELECT nombre_tarima AS tarima FROM tipos_tarimas WHERE id_tarima = ? ");
    $stmt->bind_param("i", $Ta);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $Tarima = $row['tarima'];
    }

    $stmt = $Con->prepare("SELECT nombre_s AS Sede FROM sedes WHERE codigo_s = ? ");
    $stmt->bind_param("s", $Sede);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $nSede = $row['Sede'];
    }

    $stmt = $Con->prepare("SELECT presentacion AS pre, item_cliente AS cliente, item_local AS Item FROM presentaciones_pallet WHERE id_presentacion_p = ? ");
    $stmt->bind_param("i", $Pre);
    $stmt->execute();
    $resultPre = $stmt->get_result();

    if ($row = $resultPre->fetch_assoc()) {
        $Presentacion = $row['pre'];
        $Cliente = $row['cliente']; 
        $Item = $row['Item']; 
    } else {
        $Presentacion = null;
        $Cliente = null;
        $Item = null;
    }
    $stmt->close();

    $stmt = $Con->prepare("SELECT COUNT(id_pallet) AS Folio FROM pallets p JOIN sedes s ON p.id_sede_p = s.id_sede WHERE codigo_s = ?");
    $stmt->bind_param("s", $Sede);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $Suma = $row['Folio'];
    }

    if ($Folio == '') {
        $Numero = str_pad($Suma + 1, 4, "0", STR_PAD_LEFT);
        $Folio = $Sede . "-" . $Numero;
    }
    
    $stmt = $Con->prepare("SELECT m.folio_m AS Mezcla, p.cajas_t AS Cajas, e.linea AS Linea, e.selladora AS Selladora
                            FROM pallet_mezclas_temp p
                            JOIN mezclas m ON p.id_mezcla_t = m.id_mezcla
                            JOIN empaque_lineas e ON p.id_linea_t = e.id_linea
                            WHERE p.usuario_id = ?");
    $stmt->bind_param("i", $ID);
    $stmt->execute();
    $tabla = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}else{
    // Obtener datos de mezcla
    $sum = $Con->prepare("SELECT SUM(cajas_m) AS Suma FROM pallet_mezclas WHERE id_pallet_m = ?");
    $sum->bind_param("i", $TD);
    $sum->execute();
    $datosMezcla = $sum->get_result();
    $totals = $datosMezcla->fetch_assoc();
    $sum->close();

    $stmt = $Con->prepare("SELECT r.item_local AS Item, p.folio_p AS Folio, t.nombre_tarima AS Tarima, r.item_cliente AS Cliente, r.presentacion AS Pre, s.nombre_s AS Sede, p.tipo_t AS Tipo, p.fecha_p AS FechaP, p.fecha_e AS FechaE
                            FROM pallets p
                            JOIN tipos_tarimas t ON p.id_tarima_p = t.id_tarima
                            JOIN presentaciones_pallet r ON p.id_presen_p = r.id_presentacion_p
                            JOIN sedes s ON p.id_sede_p = s.id_sede
                            WHERE p.id_pallet = ?");
    $stmt->bind_param("i", $TD);
    $stmt->execute();
    $results = $stmt->get_result();

    if ($row = $results->fetch_assoc()) {
        $Folio = $row['Folio'];
        $Tarima = $row['Tarima'];
        $Cliente = $row['Cliente'];
        $nSede = $row['Sede'];
        $Presentacion = $row['Pre'];
        $Cliente = $row['Cliente'];
        $Item = $row['Item'];
        $Tipo = $row['Tipo'];
        $Fecha = $row['FechaP'];
        $FehaE = $row['FechaE'];
    }
    $stmt->close();

    $stmt = $Con->prepare("SELECT m.folio_m AS Mezcla, t.cajas_m AS Cajas, e.linea AS Linea, e.selladora AS Selladora
                            FROM pallet_mezclas t
                            JOIN mezclas m ON t.id_mezcla_m = m.id_mezcla
                            JOIN empaque_lineas e ON t.id_linea_m = e.id_linea
                            WHERE id_pallet_m = ?");
    $stmt->bind_param("i", $TD);
    $stmt->execute();
    $tabla = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
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
            margin-bottom: 50px;
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
            PALLET TRAZABLE
        </div>
        <div class="folio">
            <span class="folio-box">No. Pallet<br><?= htmlspecialchars($Folio, ENT_QUOTES, 'UTF-8'); ?></span>
        </div>
    </div>

    <div class="detalles">
        <b>Company:</b> <?= $nSede ?? '-' ?> S.A.P.I de C.V. <br><br>
        <b>Date:</b> <?= $FechaActual ?? '-' ?> &nbsp;&nbsp; <b>Commodity:</b> Greenhouse Tomatoes <br><br>
        <b>Description:</b> <?= $Cliente ?? '-' ?> - <?= $Presentacion ?? '-' ?> &nbsp;&nbsp;<br>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Batch Code</th>
                <th>Line</th>
                <th>TS</th>
                <th>Packing Date</th>
                <th>PTI Date</th>
                <th>Cases</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tabla as $tbl): ?>
                <tr>
                    <td align="center"><?= $Item ?></td>
                    <td align="center"><?= $tbl['Mezcla'] ?></td>
                    <td align="center"><?= $tbl['Linea'] ?></td>
                    <td align="center"><?= $tbl['Selladora'] ?></td>
                    <td align="center"><?=  date("d/m/Y", strtotime($Fecha))?></td>
                    <td align="center"><?=  date("d/m/Y", strtotime($FechaE))?></td>
                    <td align="center"><?= $tbl['Cajas'] ?></td>
                </tr>
                <?php endforeach; ?>
            <tr class="total-row">
                <td colspan="6">BOXES</td>
                <td><?= $totals['Suma'] ?? 0 ?></td>
            </tr>
        </tbody>
    </table>
    <br>
    <b>Estibado en tarima:</b> <?= $Tarima ?? '-' ?> &nbsp;&nbsp;&nbsp;&nbsp; <b>Expedido en </b> <?= $nSede ?? '-' ?> S.A.P.I de C.V.<br>
</div>
    
<?php } ?>
</body>
</html>
