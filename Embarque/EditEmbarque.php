<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="shortcut icon" href="../../../Images/MiniLogo.png">
    <script src="https://code.jquery.com/jquery-3.7.1.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://kit.fontawesome.com/367278d2a4.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="../../../js/select.js"></script>
    <link rel="stylesheet" href="../../../css/eggy.css" />
    <link rel="stylesheet" href="../../../css/progressbar.css" />
    <link rel="stylesheet" href="../../../css/theme.css" />
    <link rel="stylesheet" href="DesignE.css">
    <title>Empaque: Embarque</title>
</head>

    <body onload="validar()">
        <?php
        $basePath = "../";
        $Logo = "../../../";
        $modulo = 'Empaque';
        include('../../../Complementos/Header.php');
        ?>

        <main>
            <div style="background: #f9f9f9; padding: 12px 25px; border-bottom: 1px solid #ccc; font-size: 16px;">
                <nav style="display: flex; flex-wrap: wrap; gap: 5px; align-items: center;">
                    <a href="/RisingCore/Modulos/Empaque/index.php" style="color: #6c757d; text-decoration: none;">
                        📦 Empaque
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="/RisingCore/Modulos/Empaque/Merma/index.php" style="color: #6c757d; text-decoration: none;">
                        🚚 Embarque
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="#" style="color: #6c757d; text-decoration: none;">
                        📋 Registros
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <strong style="color: #333;">✏️ Registros de embarque</strong>
                </nav>
            </div>
            
            <a title="Reporte" href="CatalogoE.php"><div class="back"><i class="fa-solid fa-left-long fa-xl"></i></div></a>

            <section class="Registro">
                <h4>Actualizar embarque</h4>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" name="octavo" id="">
                    <input class="Controles" id="0" type="hidden" name="id" value="<?php echo $ID; ?>">
                        <div class="campos-cajas">
                            <div class="FAD" style="flex: 1;">
                                <label class="FAL">
                                    <span class="FAS">Sede</span>
                                    <select class="FAI prueba" id="sede3" name="Sede">
                                        <option value="0">Seleccione la sede:</option>
                                        <?php
                                        $stmt = $Con->prepare("SELECT codigo_s FROM sedes ORDER BY codigo_s");
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        while ($row = $result->fetch_assoc()) {
                                            $codigo = $row['codigo_s'];
                                            // Si coincide con la variable $Sede, lo marca como seleccionado
                                            $selected = ($codigo == $Sede) ? ' selected' : '';
                                            echo "<option value='$codigo'$selected>$codigo</option>";
                                        }

                                        $stmt->close();
                                        ?>
                                    </select>
                                </label>
                            </div>

                            <div class="FAD" style="flex: 1;">
                                <label class="FAL Gris">
                                    <span class="FAS Top Gris">Folio</span>
                                    <input class="FAI Gris" type="text" name="Folio" id="folio" value="<?php echo $FolioE; ?>" readonly>
                                </label>
                            </div>
                        </div>

                        <div class="FAD">
                            <label class="FAL">
                                <span class="FAS">PO (Purchase Order)</span>
                                <input class="FAI" autocomplete="off" id="PO" type="Text" name="PO" value="<?php echo $PO; ?>" size="25" maxLength="25">
                            </label>
                        </div>

                        <div class="FAD" id="campo_destino">
                            <label class="FAL">
                                <span class="FAS">Destino</span>
                                <select class="FAI prueba" name="Destino" id="destino">
                                    <option value="0">Seleccione primero una sede:</option>
                                </select>
                            </label>
                        </div>
                        <input type="hidden" id="destinoSeleccionado" value="<?= htmlspecialchars($Destino) ?>">               

                        <div class="campos-cajas">
                            <div class="FAD" style="flex: 1;">
                                <label class="FAL">
                                    <span class="FAS">Cajas solicitadas</span>
                                    <input class="FAI" autocomplete="off" id="CajasT" type="Number" name="CajasT" value="<?php echo $CajasT; ?>" size="15" maxLength="4">
                                </label>
                            </div>

                            <div class="FAD" style="flex: 1;">
                                <label class="FAL">
                                    <span class="FAS">Kilos solicitados</span>
                                    <input class="FAI" autocomplete="off" id="KilosT" type="Number" step="0.01" name="KilosT" value="<?php echo $KilosT; ?>" size="15" maxLength="11">
                                </label>
                            </div>
                        </div>

                        <div class="FAD">
                            <label class="FAL">
                                <span class="FAS">Tipo de descargo</span>
                                <input class="FAI" autocomplete="off" id="Tipo" type="Text" name="Tipo" value="<?php echo $Tipo; ?>" size="25" maxLength="25">
                            </label>
                        </div>
                        
                        <div class="FAD">
                            <label class="FAL">
                                <span class="FAS">Fecha de envió</span>
                                <?php $Fecha=date("Y-m-d");?>
                                <input class="FAI" id="9" type="date" name="Fecha" value="<?php echo $Fecha; ?>">
                            </label>
                        </div>

                    <div class=Center>
                        <input class="Boton" id="AB" type="Submit" value="Actualizar" name="Modificar">
                    </div>
                </section>

            <?php if ($Correcto < 7) {
                    if ($NumE>0) { 
                        for ($i=1; $i <= 7; $i++) {
                            $Error=${"Error".$i};
                            if (!empty($Error)) { ?>
                                <script type="module">
                                    var error="<?php echo $Error;?>";
                                    import { Eggy } from '../../../js/eggy.js';
                                    await Eggy({title: 'Error!', message: error, type: 'error', position: 'top-right', duration: 20000});
                                </script>
                            <?php } ?>
                        <?php } ?>
                    <?php }
                    if ($NumP>0) { 
                        for ($i=1; $i <= 3; $i++) {
                            $Precaucion=${"Precaucion".$i};
                            if (!empty($Precaucion)) { ?>
                                <script type="module">
                                    var error="<?php echo $Precaucion;?>";
                                    import { Eggy } from '../../../js/eggy.js';
                                    await Eggy({title: 'Precaución!', message: error, type: 'warning', position: 'top-right', duration: 20000});
                                </script>
                            <?php } ?>
                        <?php } ?>
                    <?php }
                    if ($NumI>0) { 
                        for ($i=1; $i <= 1; $i++) {
                            $Informacion=${"Informacion".$i};
                            if (!empty($Informacion)) { ?>
                                <script type="module">
                                    var error="<?php echo $Informacion;?>";
                                    import { Eggy } from '../../../js/eggy.js';
                                    await Eggy({title: 'Error!', message: error, type: 'info', position: 'top-right', duration: 20000});
                                </script>
                            <?php } ?>
                        <?php } ?>
                    <?php }
            } 
            if (isset($_SESSION['correcto'])) { $Finalizado = $_SESSION['correcto']; unset($_SESSION['correcto']); ?>
                <script type="module">
                var error="<?php echo $Finalizado;?>";
                import { Eggy } from '../../../js/eggy.js';
                await Eggy({title: 'Correcto!', message: error, type: 'success', position: 'top-right', duration: 10000});
                </script>
            <?php }?>
            
            <script src="../../../js/modulos.js"></script>
            <script src="../../../js/embarque.js"></script>
        </main>
    </body>

    <?php include '../../../Complementos/Footer.php'; ?>
</html>