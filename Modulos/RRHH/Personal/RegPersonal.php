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
    <link rel="stylesheet" href="DesignNI.css">
    <title>RRHH: Nuevo Ingreso</title>
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
                        🙎🏻 Personal
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="#" style="color: #6c757d; text-decoration: none;">
                        📋 Registros
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <strong style="color: #333;">✏️ Registros de personal</strong>
                </nav>
            </div>
            <?php if ($TipoRol=="ADMINISTRADOR" || $Ver=true) { ?> <a title="Reporte" href="CatalogoNI.php"><div class="back"><i class="fa-solid fa-fingerprint fa-xl"></i></div></a><?php } ?>

            <section class="Registro">
                <h4>Registro de personal</h4>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" name="octavo" id="">
                    <div class="FAD">
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
                                    $selected = ($codigo == $Sede) ? ' selected' : '';
                                    echo "<option value='$codigo'$selected>$codigo</option>";
                                }

                                $stmt->close();
                                ?>
                            </select>
                        </label>
                    </div>

                    <div class="FAD" id="campo_personal" style="display:none;">
                        <label class="FAL">
                            <span class="FAS">Personal</span>
                            <select class="FAI prueba" name="Personal" id="personal">
                                <option value="0">Seleccione una sede primero</option>
                            </select>
                        </label>
                    </div>
                    <input type="hidden" id="personalSeleccionado" value="<?= htmlspecialchars($Personal) ?>">

                    <div class="FAD" id="campo_municipio" style="display:none;">
                        <label class="FAL">
                            <span class="FAS">Municipio</span>
                            <select class="FAI prueba" name="Municipio" id="municipio">
                                <option value="0">Seleccione una sede primero</option>
                            </select>
                        </label>
                    </div>
                    <input type="hidden" id="municipioSeleccionado" value="<?= htmlspecialchars($Municipio) ?>">

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Fecha de nacimiento</span>
                            <?php $Fecha=date("2000-01-01");?>
                            <input class="FAI" id="Fecha" type="date" name="Fecha" value="<?php echo $Fecha; ?>">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">CURP</span>
                            <input class="FAI" id="CURP" type="Text" name="CURP" <?php if (isset($_POST['CURP']) != ''): ?> value="<?php echo $CURP; ?>"<?php endif; ?> size="25" maxLength="50" onkeyup="mayus(this);">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">NSS</span>
                            <input class="FAI" id="NSS" type="Text" name="NSS" <?php if (isset($_POST['NSS']) != ''): ?> value="<?php echo $NSS; ?>"<?php endif; ?> size="25" maxLength="50" onkeyup="mayus(this);">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">RFC</span>
                            <input class="FAI" id="RFC" type="Text" name="RFC" <?php if (isset($_POST['RFC']) != ''): ?> value="<?php echo $RFC; ?>"<?php endif; ?> size="25" maxLength="50" onkeyup="mayus(this);">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Apto</span>
                            <input class="FAI" id="Apto" type="Text" name="Apto" <?php if (isset($_POST['Apto']) != ''): ?> value="<?php echo $Apto; ?>"<?php endif; ?> size="25" maxLength="50" onkeyup="mayus(this);">
                        </label>
                    </div>

                    <div class=Center>
                        <input class="Boton" id="AB" type="Submit" value="Registrar" name="Insertar">
                    </div>
                </section>

            <?php if ($Correcto < 11) {
                $tipos = [
                    'Error' => ['cantidad' => $NumE, 'max' => 10, 'title' => 'Error!', 'type' => 'error'],
                    'Precaucion' => ['cantidad' => $NumP, 'max' => 3, 'title' => 'Precaución!', 'type' => 'warning'],
                    'Informacion' => ['cantidad' => $NumI, 'max' => 5, 'title' => 'Info!', 'type' => 'info']
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
            <script src="../../../js/ingresos.js"></script>
        </main>
    </body>

    <?php include '../../../Complementos/Footer.php'; ?>
</html>