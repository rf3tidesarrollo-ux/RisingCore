<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";

$imgPath = realpath('../../../Images/Rising_Logo.jpg');
$imgData = base64_encode(file_get_contents($imgPath));
$src = 'data:image/jpeg;base64,' . $imgData;

$datosRequi = [];
$tabla = [];
$TD = $_GET['id'] ?? '';
$FechaActual = date("d/m/Y");
$Sede = $_GET['Sede'] ?? '';
$Depto = $_GET['Depto'] ?? '';
$Area = $_GET['Area'] ?? '';
$Folio = $_GET['Folio'] ?? '';

if ($TD=="mostrar") {
    // Obtener datos de mezcla
    $sum = $Con->prepare("SELECT COUNT(id_requisicion_t) AS Total FROM cp_requisicion_temp WHERE id_usuario_t = ?");
    $sum->bind_param("i", $ID);
    $sum->execute();
    $datosMezcla = $sum->get_result();
    $totals = $datosMezcla->fetch_assoc();
    $sum->close();

    $stmt = $Con->prepare("SELECT d.dep, a.departamento FROM rh_departamentos a JOIN cp_departamentos d ON a.id_dep_d = d.id_dep WHERE a.id_departamento = ? ");
    $stmt->bind_param("i", $Area);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $Depto = $row['dep'];
        $Area = $row['departamento'];
    }

    $stmt = $Con->prepare("SELECT nombre_s AS Sede FROM sedes WHERE codigo_s = ? ");
    $stmt->bind_param("s", $Sede);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $nSede = $row['Sede'];
    }

    $stmt = $Con->prepare("SELECT t.folio_t AS Folio, p.producto AS Producto, e.prioridad AS Prioridad, t.cantidad_t AS Cantidad, p.unidad AS Unidad, p.existencias_p AS Exist, t.fecha_rt AS FechaR, t.estado_t AS Estado
                            FROM cp_requisicion_temp t
                            JOIN cp_productos p ON p.id_producto = t.id_producto_t
                            JOIN cp_tipo_prioridad e ON t.prioridad_t = e.id_prioridad
                            WHERE t.id_usuario_t = ? ORDER BY t.folio_t ASC");
    $stmt->bind_param("i", $ID);
    $stmt->execute();
    $tabla = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}else{
    // Obtener datos de mezcla
    $sum = $Con->prepare("SELECT COUNT(id_requisicion_p) AS Total FROM cp_requisicion_pro WHERE id_requi_p = ?");
    $sum->bind_param("i", $TD);
    $sum->execute();
    $datosMezcla = $sum->get_result();
    $totals = $datosMezcla->fetch_assoc();
    $sum->close();

    $stmt = $Con->prepare("SELECT r.folio_req AS Folio, s.nombre_s AS Sede, d.departamento AS Area, dp.dep AS Depto, r.fecha_req AS FechaR, r.solicitante AS Titular
                            FROM cp_requisiciones r
                            JOIN rh_departamentos d ON r.id_area_req = d.id_departamento
                            JOIN cp_departamentos dp ON d.id_dep_d = dp.id_dep
                            JOIN sedes s ON r.id_sede_req = s.id_sede
                            WHERE r.id_requisicion = ?");
    $stmt->bind_param("i", $TD);
    $stmt->execute();
    $results = $stmt->get_result();

    if ($row = $results->fetch_assoc()) {
        $Folio = $row['Folio'];
        $nSede = $row['Sede'];
        $Depto = $row['Depto'];
        $Area = $row['Area'];
        $FechaR = $row['FechaR'];
        $Titular = $row['Titular'];
    }
    $stmt->close();

    $stmt = $Con->prepare("SELECT pr.folio_p AS Folio, p.producto AS Producto, e.prioridad AS Prioridad, pr.cantidad_p AS Cantidad, p.unidad AS Unidad, p.existencias_p AS Exist, pr.fecha_rp AS FechaR, pr.estado_p AS Estado
                            FROM cp_requisicion_pro pr
                            JOIN cp_productos p ON p.id_producto = pr.id_producto_p
                            JOIN cp_tipo_prioridad e ON pr.prioridad_p = e.id_prioridad
                            WHERE pr.id_requi_p = ? ORDER BY pr.folio_p ASC");
    $stmt->bind_param("i", $TD);
    $stmt->execute();
    $tabla = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="UTF-8" />
<style>
</style>
</head>

<style>
  body {
    font-family: DejaVu Sans, sans-serif; /* compatible con dompdf */
    font-size: 10px;
  }

  .contenedor {
    width: 100%;
    max-width: 730px; /* aprox. ancho útil en A4 menos márgenes */
    margin: 0 auto;
  table-layout: fixed;
}

  .tabla-principal {
    border-collapse: collapse;
    width: 100%;
    table-layout: fixed;
  }

  .tabla-principal td {
    border: 0.5px solid #727272ff;
    text-align: center;
    vertical-align: middle;
    width: 12.6mm;   /* 190mm ÷ 15 */
    height: 15mm;    /* 277mm ÷ 6 */
    font-size: 7pt;
    word-wrap: break-word;
  }

  .title {
    border: 1px solid gray;
    text-align: left;
    vertical-align: top;
    position: relative;
    font-size: 9pt;
    padding-bottom: 0px;
  }

  .rojo {
    color: red;
  }

.celda-num {
  border: 0.5px solid #727272ff;
  width: 48px;    /* ancho fijo */
  height: 68px;   /* alto fijo para calcular */
  padding: 0;
  position: relative;
}

.numeracion {
  position: absolute;
  top: 2px;      /* distancia desde arriba */
  right: 4px;    /* distancia desde la derecha */
  color: red;
  font-weight: bold;
  font-size: 10pt; /* ajusta según tamaño */
}

  .w364 { width: 337px; }
  .w208 { width: 186px; }
  .w104 { width: 102px; }
  .ma1 { width: 310px; }
  .ma2 { width: 256px; }
  .na1 { width: 65px; }
  .na2 { width: 95px; }
  .muelle {
    background: #aaa;
    color: white;
    font-weight: bold;
    text-align: center;
  }

  .vacio {
    background: #ebebebff;
    color: black;
    font-weight: bold;
    text-align: center;
  }

  table {
      width: 100%;
      border-collapse: collapse;
      font-size: 10px;
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

  .footer {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    text-align: center;
    font-size: 10px;
    border-top: 1px solid #aaa;
    background: #fff;
    padding: 10px 0;
  }

  .firmas {
    width: 100%;
    border-collapse: collapse;
    margin: 0 auto;
  }

  .firmas td {
    width: 33.3%;
    text-align: center;
    vertical-align: top;
    padding: 8px;
    border: none;
    font-size: 9px;
  }

</style>

    <body>
        <div class="contenedor">
            <table width="100%" style="border-collapse: collapse; font-size:12px;">
                <tr>
                    <td rowspan="4" style="width:90px; height:70px; padding:0; margin:0; overflow:hidden;">
                        <img src="<?= $src ?>" width="90" height="70"/>
                    </td>
                    <td colspan="2" class="title" style="text-align:center; font-weight:bold;">REQUISICIÓN INTERNA DE COMPRA</td>
                </tr>
                <tr>
                    <td style="width:60%;" class="title"><b>Solicitante:</b> <br><?= $Titular ?? '-' ?></td>
                    <td class="title" style="width:40%;"><b>Folio:</b> <br><span class="rojo"><?= $Folio ?? '-' ?></span></td>
                    
                </tr>
                <tr>
                    <td style="width:60%;" class="title"><b>Área:</b> <br> <?= $Area ?? '-' ?></td>
                    <td class="title" style="width:40%;"><b>Requisición:</b> <br> <?= $FechaActual ?? '-' ?></td>
                </tr>
                <tr>
                    <td class="title" colspan="2" style="text-align:center;"><?= $nSede ?? '-' ?> S.A.P.I de C.V.</td>
                </tr>
            </table>
            <br>
            <table>
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Descripción del producto</th>
                        <th>Prioridad</th>
                        <th>Existencias</th>
                        <th>Cantidad</th>
                        <th>Unidad</th>
                        <th>Requerido</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                  <?php foreach ($tabla as $tbl): ?>
                    <tr>
                        <td align="center"><?= $tbl['Folio'] ?></td>
                        <td align="center"><?= $tbl['Producto'] ?></td>
                        <td align="center"><?= $tbl['Prioridad'] ?></td>
                        <td align="center"><?= $tbl['Exist'] ?></td>
                        <td align="center"><?= $tbl['Cantidad'] ?></td>
                        <td align="center"><?= $tbl['Unidad'] ?></td>
                        <td align="center"><?=  date("d/m/Y", strtotime($tbl['FechaR']))?></td>
                        <td align="center"><?= $tbl['Estado'] ?></td>
                        
                    </tr>
                  <?php endforeach; ?>
                  
                    <tr class="total-row">
                        <td colspan="7">Total de productos requeridos</td>
                        <td><?= $totals['Total'] ?? 0 ?></td>
                    </tr>
                </tbody>
            </table>
            <div class="footer">
              <table class="firmas">
                <tr>
                  <td>
                    <b>SOLICITANTE</b><br>
                    <?= strtoupper($Titular ?? '-') ?><br>
                    <?= $Area ?? '-' ?>
                  </td>
                  <td>
                    <b>COTIZADO</b><br>
                    -<br>
                    AUXILIAR DE COMPRA
                  </td>
                  <td>
                    <b>AUTORIZADO</b><br>
                    -<br>
                    COORDINADOR DE ADMINISTRACIÓN
                  </td>
                </tr>
              </table>
            </div>
        </div>
    </body>

</html>