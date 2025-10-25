<!DOCTYPE html>
<html lang="en">
<head>
    <?php $Ruta = "../../../"; include_once '../../../Complementos/Logo_movil.php'; ?>
    
    <script src="https://code.jquery.com/jquery-3.7.1.js" type="text/javascript"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://kit.fontawesome.com/367278d2a4.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="../../../js/select.js"></script>
    <script src="../../../js/session.js"></script>
    <link rel="stylesheet" href="../../../css/eggy.css" />
    <link rel="stylesheet" href="../../../css/progressbar.css" />
    <link rel="stylesheet" href="../../../css/theme.css" />
    <link rel="stylesheet" href="DesignH.css">
    <title>RRHH: Horarios</title>
</head>

<body onload="validar()">
        <?php
        $basePath = "../";
        $Logo = "../../../";
        $modulo = 'RRHH';
        include('../../../Complementos/Header.php');
        ?>

        <main>
            <div style="background: #f9f9f9; padding: 12px 25px; border-bottom: 1px solid #ccc; font-size: 16px;">
                <nav style="display: flex; flex-wrap: wrap; gap: 5px; align-items: center;">
                    <a href="/RisingCore/Modulos/RRHH/Inicio.php" style="color: #6c757d; text-decoration: none;">
                        👥 Recursos Humanos
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="/RisingCore/Modulos/RRHH/Ingreso/Inicio.php" style="color: #6c757d; text-decoration: none;">
                        🕐 Horarios
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="#" style="color: #6c757d; text-decoration: none;">
                        📋 Registros
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <strong style="color: #333;">✏️ Registros de horarios</strong>
                </nav>
            </div>
            <?php if ($TipoRol=="ADMINISTRADOR" || $Ver=true) { ?> <a title="Reporte" href="CatalogoH.php"><div class="back"><i class="fa-solid fa-business-time fa-xl"></i></div></a><?php } ?>

            <section class="Registro">
                <h4>Registro de horarios</h4>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" name="octavo" id="">
                    <div class="FAD">
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

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Horario</span>
                            <input class="FAI" id="Nombre" type="Text" name="Nombre" <?php if (isset($_POST['Nombre']) != ''): ?> value="<?php echo $Nombre; ?>"<?php endif; ?> size="25" maxLength="50" onkeyup="mayus(this);">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <?php $HoraE='06:30'; ?>
                            <span class="FAS">Hora de entrada</span>
                            <input class="FAI" id="HoraE" type="time" name="HoraE" value="<?php echo $HoraE; ?>">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <?php $HoraS='15:00'; ?>
                            <span class="FAS">Hora de salida</span>
                            <input class="FAI" id="HoraS" type="time" name="HoraS" value="<?php echo $HoraS; ?>">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <?php $HoraSE='06:30'; ?>
                            <span class="FAS">Hora de entrada sabádo</span>
                            <input class="FAI" id="HoraSE" type="time" name="HoraSE" value="<?php echo $HoraSE; ?>">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <?php $HoraSS='12:00'; ?>
                            <span class="FAS">Hora de salida sábado</span>
                            <input class="FAI" id="HoraSS" type="time" name="HoraSS" value="<?php echo $HoraSS; ?>">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <?php $HoraDE='06:30'; ?>
                            <span class="FAS">Hora de entrada domingo</span>
                            <input class="FAI" id="HoraDE" type="time" name="HoraDE" value="<?php echo $HoraDE; ?>">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <?php $HoraDS='12:00'; ?>
                            <span class="FAS">Hora de salida domingo</span>
                            <input class="FAI" id="HoraDS" type="time" name="HoraDS" value="<?php echo $HoraDS; ?>">
                        </label>
                    </div>

                    <div class=Center>
                        <input class="Boton" id="AB" type="Submit" value="Registrar" name="Insertar">
                    </div>
                </section>

            <?php if ($Correcto < 11) {
                $tipos = [
                    'Error' => ['cantidad' => $NumE, 'max' => 8, 'title' => 'Error!', 'type' => 'error'],
                    'Precaucion' => ['cantidad' => $NumP, 'max' => 4, 'title' => 'Precaución!', 'type' => 'warning'],
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
        </main>
    </body>

    <?php include '../../../Complementos/Footer.php'; ?>
</html>