<!DOCTYPE html>
<html lang="en">
<head>
    <?php $Ruta = "../../../"; include_once '../../../Complementos/Logo_movil.php'; ?>

    <script src="https://code.jquery.com/jquery-3.7.1.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://kit.fontawesome.com/367278d2a4.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="../../../js/select.js"></script>
    <script src="../../../js/session.js"></script>
    <link rel="stylesheet" href="../../../css/eggy.css" />
    <link rel="stylesheet" href="../../../css/progressbar.css" />
    <link rel="stylesheet" href="../../../css/theme.css" />
    <link rel="stylesheet" href="DesignE.css">
    <title>Embarque: Editar</title>
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
                    <a href="/RisingCore/Modulos/Empaque/Inicio.php" style="color: #6c757d; text-decoration: none;">
                        📦 Empaque
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="/RisingCore/Modulos/Empaque/Embarque/Inicio.php" style="color: #6c757d; text-decoration: none;">
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
                                        <?php
                                        if ($TipoRol === 'ADMINISTRADOR' || $TipoRol==='SUPERVISOR') {
                                            echo '<option value="0">Seleccione la sede:</option>';
                                            $stmt = $Con->prepare("SELECT codigo_s FROM sedes ORDER BY codigo_s");
                                            $stmt->execute();
                                            $result = $stmt->get_result();

                                            while ($row = $result->fetch_assoc()) {
                                                $codigo = $row['codigo_s'];
                                                $selected = ($codigo == $Sede) ? ' selected' : '';
                                                echo "<option value='$codigo'$selected>$codigo</option>";
                                            }

                                            $stmt->close();
                                        } else {
                                            // Usuario normal → solo mostrar su propia sede
                                            echo "<option value='$CodigoS' $selected>$CodigoS</option>";
                                        }
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
                                <span class="FAS">Fecha de envió programada</span>
                                <input class="FAI" id="9" type="date" name="Fecha" value="<?php echo !empty($Fecha) ? $Fecha : date('Y-m-d'); ?>">
                            </label>
                        </div>

                        <div class="FAD">
                            <label class="FAL">
                                <span class="FAS">Hora de envió programada</span>
                                <input class="FAI" id="9" type="time" name="Hora" value="<?php echo !empty($Hora) ? $Hora : '12:00'; ?>">
                            </label>
                        </div>

                        <div class="FAD">
                            <label class="FAL">
                                <span class="FAS">Fecha de envió</span>
                                <input class="FAI" id="9" type="date" name="FechaE" value="<?php echo !empty($FechaE) ? $FechaE : date('Y-m-d'); ?>">
                            </label>
                        </div>

                        <div class="FAD">
                            <label class="FAL">
                                <span class="FAS">Hora de envió</span>
                                <input class="FAI" id="9" type="time" name="HoraE" value="<?php echo !empty($HoraE) ? $HoraE : '12:00'; ?>">
                            </label>
                        </div>

                        <div class="FAD" id="ciclo">
                            <label class="FAL">
                                <span class="FAS">Ciclo</span>
                                <select class="FAI prueba" name="Ciclo" id="ciclos">
                                    <option value="0">Seleccione el ciclo:</option>
                                    <?php
                                    $stmt = $Con->prepare("SELECT id_ciclo, ciclo FROM ciclos ORDER BY ciclo");
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    while ($Row = $result->fetch_assoc()) {
                                        $selected = ($Ciclo == $Row['id_ciclo']) ? 'selected' : '';
                                        echo '<option value="'.$Row['id_ciclo'].'" '.$selected.'>'.$Row['ciclo'].'</option>';
                                    }
                                    $stmt->close();
                                    ?>
                                </select>
                            </label>
                        </div>

                        <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Estado del envió</span>
                            <select class="FAI prueba" id="Estado" name="Estado">
                                <option value="0">Seleccione el estado:</option>
                                <option value="PENDIENTE" <?= ($Estado == 'PENDIENTE') ? 'selected' : '' ?>>PENDIENTE</option>
                                <option value="EN PROCESO" <?= ($Estado == 'EN PROCESO') ? 'selected' : '' ?>>EN PROCESO</option>
                                <option value="COMPLETADO" <?= ($Estado == 'COMPLETADO') ? 'selected' : '' ?>>COMPLETADO</option>
                            </select>
                        </label>
                    </div>

                    <div class=Center>
                        <input class="Boton" id="AB" type="Submit" value="Actualizar" name="Modificar">
                    </div>
                </section>

            <?php if ($Correcto < 12) {
                $tipos = [
                    'Error' => ['cantidad' => $NumE, 'max' => 12, 'title' => 'Error!', 'type' => 'error'],
                    'Precaucion' => ['cantidad' => $NumP, 'max' => 3, 'title' => 'Precaución!', 'type' => 'warning'],
                    'Informacion' => ['cantidad' => $NumI, 'max' => 1, 'title' => 'Info!', 'type' => 'info']
                ];

                foreach ($tipos as $prefijo => $datos) {
                    for ($i = 1; $i <= $datos['max']; $i++) {
                        $var = ${$prefijo.$i};
                        if (!empty($var)) { ?>
                            <script type="module">
                                import { Eggy } from '../../../js/eggy.js';
                                async function showMessage(msg) {
                                    await Eggy({
                                        title: '<?php echo $datos['title']; ?>',
                                        message: msg,
                                        type: '<?php echo $datos['type']; ?>',
                                        position: 'top-right',
                                        duration: 20000
                                    });
                                }
                                showMessage("<?php echo $var; ?>");
                            </script>
                        <?php }
                    }
                }
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