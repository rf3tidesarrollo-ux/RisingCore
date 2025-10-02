<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";

$imgPath = realpath('../../../Images/Rising_Logo.jpg');
$imgData = base64_encode(file_get_contents($imgPath));
$src = 'data:image/jpeg;base64,' . $imgData;
$embarque_id = $_GET['embarque'] ?? '';
$Fecha = date('d/m/Y');

$stmt = $Con->prepare("SELECT c.nombre_completo FROM usuarios u JOIN cargos c ON u.id_cargo = c.id_cargo WHERE u.id_usuario = ?");
$stmt->bind_param("i", $ID);
$stmt->execute();
$result = $stmt->get_result();
$Usuario = $result->fetch_assoc()['nombre_completo'];
$stmt->close();

if ($embarque_id != 0) {
    $stmt = $Con->prepare("SELECT em.folio_em AS Folio, em.po_em AS PO, em.fecha_em AS Fecha, d.lugar_d AS Destino
                        FROM embarques_pallets em
                        LEFT JOIN destinos_embarque d ON em.id_destino_em = d.id_destino
                        WHERE em.id_embarque = ?");
    $stmt->bind_param("i", $embarque_id);
    $stmt->execute();
    $Registro = $stmt->get_result();
    $NumCol=$Registro->num_rows;

    if ($NumCol>0) {
        while ($Reg = $Registro->fetch_assoc()){
            $Folio = $Reg['Folio'];
            $PO = $Reg['PO'];
            $Destino = $Reg['Destino'];
        }
        $stmt->close();
    }
}else{
    $Folio = "---";
    $PO = "---";
    $Destino = "---";
}

$stmt = $Con->prepare("SELECT CONCAT(pp.item_cliente, ' - ', pp.presentacion) AS presentacion_p, p.folio_p, p.mapeo, p.ubicacion
                        FROM pallets p
                        LEFT JOIN presentaciones_pallet pp ON p.id_presen_p = pp.id_presentacion_p
                        WHERE p.id_embarque_p = ?");
$stmt->bind_param("i", $embarque_id);
$stmt->execute();
$mapa = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Normalizamos el array por posición
$datosMapa = [];
foreach ($mapa as $m) {
    $datosMapa[$m['mapeo']] = $m;
}
$stmt->close();
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


.celda-num {
  border: 0.5px solid #727272ff;
  width: 48px;    /* ancho fijo */
  height: 68px;   /* alto fijo para calcular */
  padding: 0;
  position: relative;
}

.dato-top {
  flex: 8;   /* 80% de 48px */
  font-size: 4pt;
  /* font-weight: 600; */
  display: flex;
  justify-content: center;
  align-items: center;
  vertical-align: middle;
  overflow: hidden;
  word-wrap: break-word;
  line-height: 1.1;
  text-align: center;
  padding: 1px;
}

.dato-middle {
  flex: 1;   /* 10% de 61px */
  font-size: 6pt;
  display: flex;
  justify-content: center;
  align-items: center;
  border-top: 0.5px solid #727272ff;
  border-bottom: 0.5px solid #727272ff;
  background-color: rgb(210, 221, 226);
}

.dato-bottom {
  flex: 1;       /* 10% de 61px */
  font-size: 6pt;
  display: flex;
  justify-content: center;
  align-items: center;
  color: black;
}

  .dato {
    font-size: 6pt;
    color: #555;
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

  .tabla-doble {
    text-align: center;
    border-collapse: separate;
    border-spacing: 20px;
    font-size: 7pt;
  }

 .bloque {
    border: 0.5px solid #727272ff;
    padding: 5px;
    vertical-align: top !important;
  }

  .bloque table {
    border-collapse: collapse;
    width: 100%;
    text-align: center;
  }

  .bloque th {
    background: #0fb948ff;
    color: #fff;
    padding: 4px;
  }

  .bloque td {
    border: none;
    padding: 3px;
  }

.numeracion {
  position: absolute;
  top: 2px;      /* distancia desde arriba */
  right: 4px;    /* distancia desde la derecha */
  color: red;
  font-weight: bold;
  font-size: 10pt; /* ajusta según tamaño */
}

</style>

    <body>
        <div class="contenedor">
                <table width="100%" height="12" >
                    <tr height="6">
                        <td rowspan="3" style="width:90px; height:70px; padding:0; margin:0; overflow:hidden;"><img src="<?= $src ?>" width="90" height="70"/></td>
                        <td colspan="2" class="title" style="text-align:center">Mapa de Camára Fría y Check List de carga</td>
                        <td class="title">Fecha: <?=$Fecha?></td>
                    </tr>
                    <tr height="6">
                        <td class="title" style="width:30%">Embarque: <?=$Folio?></td>
                        <td class="title" style="width:30%">PO#: <?=$PO?></td>
                        <td class="title" style="width:40%">Destino: <?=$Destino?></td>
                    </tr>
                    <tr height="6">
                        <td class="title" colspan="4">Elaboró: <?=$Usuario?></td>
                    </tr>
                </table>
                <br>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="w364"></td>
                        <td class="w104 muelle">MUELLE 3</td>
                        <td class="w208"></td>
                        <td class="w104 muelle">MUELLE 2</td>
                    </tr>
                </table>
                <table class="tabla-principal" cellspacing="0"  cellpadding="0">
                    <tr>
                        <td style="border: none"></td>
                        <?php for ($i = 1; $i <= 7; $i++): ?>
                            <td class="celda-num">
                                <?php if (isset($datosMapa[$i])): ?>
                                    <div class="dato-top"><?= $datosMapa[$i]['presentacion_p'] ?></div>
                                    <div class="dato-middle"><?= $datosMapa[$i]['folio_p'] ?></div>
                                    <div class="dato-bottom"><?= $datosMapa[$i]['ubicacion'] ?></div>
                                <?php else: ?>
                                    <div class="numeracion"><?= $i ?></div>
                                <?php endif; ?>
                            </td>
                        <?php endfor; ?>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                         <?php for ($i = 8; $i <= 12; $i++): ?>
                            <td class="celda-num">
                                <?php if (isset($datosMapa[$i])): ?>
                                    <div class="dato-top"><?= $datosMapa[$i]['presentacion_p'] ?></div>
                                    <div class="dato-middle"><?= $datosMapa[$i]['folio_p'] ?></div>
                                    <div class="dato-bottom"><?= $datosMapa[$i]['ubicacion'] ?></div>
                                <?php else: ?>
                                    <div class="numeracion"><?= $i ?></div>
                                <?php endif; ?>
                            </td>
                        <?php endfor; ?>
                    </tr>
                     <tr>
                        <?php for ($i = 13; $i <= 20; $i++): ?>
                            <td class="celda-num">
                                <?php if (isset($datosMapa[$i])): ?>
                                    <div class="dato-top"><?= $datosMapa[$i]['presentacion_p'] ?></div>
                                    <div class="dato-middle"><?= $datosMapa[$i]['folio_p'] ?></div>
                                    <div class="dato-bottom"><?= $datosMapa[$i]['ubicacion'] ?></div>
                                <?php else: ?>
                                    <div class="numeracion"><?= $i ?></div>
                                <?php endif; ?>
                            </td>
                        <?php endfor; ?>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <?php for ($i = 21; $i <= 25; $i++): ?>
                            <td class="celda-num">
                                <?php if (isset($datosMapa[$i])): ?>
                                    <div class="dato-top"><?= $datosMapa[$i]['presentacion_p'] ?></div>
                                    <div class="dato-middle"><?= $datosMapa[$i]['folio_p'] ?></div>
                                    <div class="dato-bottom"><?= $datosMapa[$i]['ubicacion'] ?></div>
                                <?php else: ?>
                                    <div class="numeracion"><?= $i ?></div>
                                <?php endif; ?>
                            </td>
                        <?php endfor; ?>
                    </tr>
                    <tr>
                        <?php for ($i = 26; $i <= 32; $i++): ?>
                            <td class="celda-num">
                                <?php if (isset($datosMapa[$i])): ?>
                                    <div class="dato-top"><?= $datosMapa[$i]['presentacion_p'] ?></div>
                                    <div class="dato-middle"><?= $datosMapa[$i]['folio_p'] ?></div>
                                    <div class="dato-bottom"><?= $datosMapa[$i]['ubicacion'] ?></div>
                                <?php else: ?>
                                    <div class="numeracion"><?= $i ?></div>
                                <?php endif; ?>
                            </td>
                        <?php endfor; ?>
                        <td style="border: none"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <?php for ($i = 33; $i <= 37; $i++): ?>
                            <td class="celda-num">
                                <?php if (isset($datosMapa[$i])): ?>
                                    <div class="dato-top"><?= $datosMapa[$i]['presentacion_p'] ?></div>
                                    <div class="dato-middle"><?= $datosMapa[$i]['folio_p'] ?></div>
                                    <div class="dato-bottom"><?= $datosMapa[$i]['ubicacion'] ?></div>
                                <?php else: ?>
                                    <div class="numeracion"><?= $i ?></div>
                                <?php endif; ?>
                            </td>
                        <?php endfor; ?>
                    </tr>
                    <tr>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <td style="background-color: rgb(210, 221, 226); border: none;"></td>
                        <?php for ($i = 38; $i <= 42; $i++): ?>
                            <td class="celda-num">
                                <?php if (isset($datosMapa[$i])): ?>
                                    <div class="dato-top"><?= $datosMapa[$i]['presentacion_p'] ?></div>
                                    <div class="dato-middle"><?= $datosMapa[$i]['folio_p'] ?></div>
                                    <div class="dato-bottom"><?= $datosMapa[$i]['ubicacion'] ?></div>
                                <?php else: ?>
                                    <div class="numeracion"><?= $i ?></div>
                                <?php endif; ?>
                            </td>
                        <?php endfor; ?>
                    </tr>
                    <tr>
                        <td style="border: none"></td>
                        <?php for ($i = 43; $i <= 56; $i++): ?>
                            <td class="celda-num">
                                <?php if (isset($datosMapa[$i])): ?>
                                    <div class="dato-top"><?= $datosMapa[$i]['presentacion_p'] ?></div>
                                    <div class="dato-middle"><?= $datosMapa[$i]['folio_p'] ?></div>
                                    <div class="dato-bottom"><?= $datosMapa[$i]['ubicacion'] ?></div>
                                <?php else: ?>
                                    <div class="numeracion"><?= $i ?></div>
                                <?php endif; ?>
                            </td>
                        <?php endfor; ?>
                    </tr>
                    <tr>
                        <td style="border: none"></td>
                        <?php for ($i = 57; $i <= 70; $i++): ?>
                            <td class="celda-num">
                                <?php if (isset($datosMapa[$i])): ?>
                                    <div class="dato-top"><?= $datosMapa[$i]['presentacion_p'] ?></div>
                                    <div class="dato-middle"><?= $datosMapa[$i]['folio_p'] ?></div>
                                    <div class="dato-bottom"><?= $datosMapa[$i]['ubicacion'] ?></div>
                                <?php else: ?>
                                    <div class="numeracion"><?= $i ?></div>
                                <?php endif; ?>
                            </td>
                        <?php endfor; ?>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="ma1"></td>
                        <td class="na1 muelle">NARIZ</td>
                        <td class="ma2"></td>
                        <td class="na2 vacio">VACÍO</td>
                    </tr>
                </table>
                <table class="tabla-doble">
                    <tr>
                        <!-- Bloque IZQ -->
                        <td class="bloque">
                        <table>
                            <tr>
                            <th style="width: 25px">IZQ</th>
                            <th style="width:60px">Folio</th>
                            <th style="width:170px">Nombre Corto</th>
                            </tr>
                            <?php
                              for ($fila = 1; $fila <= 30; $fila += 2) { // Pallets impares IZQ
                                  // Buscar pallet que tenga esa ubicación
                                  $folio = '';
                                  $ubicacion = '';
                                  foreach ($datosMapa as $pallet) {
                                      if ($pallet['ubicacion'] == $fila) {
                                          $folio = $pallet['folio_p'];
                                          $ubicacion = $pallet['presentacion_p'];
                                          break;
                                      }
                                  }
                                  echo "<tr>
                                          <td>{$fila}</td>
                                          <td>{$folio}</td>
                                          <td>{$ubicacion}</td>
                                        </tr>";
                              }
                            ?>
                        </table>
                        </td>

                        <!-- Bloque DER -->
                        <td class="bloque">
                        <table>
                            <tr>
                            <th style="width:25px">DER</th>
                            <th style="width:60px">Folio</th>
                            <th style="width:170px">Nombre Corto</th>
                            </tr>
                            <?php
                              for ($fila = 2; $fila <= 30; $fila += 2) { // Pallets impares IZQ
                                  // Buscar pallet que tenga esa ubicación
                                  $folio = '';
                                  $ubicacion = '';
                                  foreach ($datosMapa as $pallet) {
                                      if ($pallet['ubicacion'] == $fila) {
                                          $folio = $pallet['folio_p'];
                                          $ubicacion = $pallet['presentacion_p'];
                                          break;
                                      }
                                  }
                                  echo "<tr>
                                          <td>{$fila}</td>
                                          <td>{$folio}</td>
                                          <td>{$ubicacion}</td>
                                        </tr>";
                              }
                            ?>
                        </table>
                        </td>
                    </tr>
                </table>
        </div>
    </body>
</html>
