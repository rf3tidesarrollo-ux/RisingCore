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
                    <a href="/RisingCore/Modulos/Empaque/index.php" style="color: #6c757d; text-decoration: none;">
                        👥 Recursos Humanos
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="/RisingCore/Modulos/Empaque/Merma/index.php" style="color: #6c757d; text-decoration: none;">
                        🙎🏻 Nuevo Ingreso
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="#" style="color: #6c757d; text-decoration: none;">
                        📋 Registros
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <strong style="color: #333;">✏️ Registros de nuevos ingresos</strong>
                </nav>
            </div>
            
            <a title="Reporte" href="CatalogoNI.php"><div class="back"><i class="fa-solid fa-left-long fa-xl"></i></div></a>

            <section class="Registro">
                <h4>Registro de nuevo de ingreso</h4>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" name="octavo" id="">
                    <input class="Controles" id="0" type="hidden" name="id" value="<?php echo $ID; ?>">
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

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Nombre</span>
                            <input class="FAI" autocomplete="off" id="Nombre" type="Text" name="Nombre" value="<?php echo $Nombre; ?>" size="25" maxLength="50" onkeyup="mayus(this);">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Apellido paterno</span>
                            <input class="FAI" autocomplete="off" id="AP" type="Text" name="AP" value="<?php echo $AP; ?>" size="25" maxLength="50" onkeyup="mayus(this);">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Apellido materno</span>
                            <input class="FAI" autocomplete="off" id="AM" type="Text" name="AM" value="<?php echo $AM; ?>" size="25" maxLength="50" onkeyup="mayus(this);">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Género</span>
                            <select class="FAI prueba" id="Genero" name="Genero">
                                <option value="0">Seleccione el género:</option>
                                <?php
                                $stmt = $Con->prepare("SELECT id_genero, genero FROM rh_generos ORDER BY id_genero");
                                $stmt->execute();
                                $result = $stmt->get_result();

                                while ($Row = $result->fetch_assoc()) {
                                    $selected = ($Genero == $Row['id_genero']) ? 'selected' : '';
                                    echo '<option value="'.$Row['id_genero'].'" '.$selected.'>'.$Row['genero'].'</option>';
                                }
                                $stmt->close();
                                ?>
                            </select>
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Departamento</span>
                            <select class="FAI prueba" id="Departamento" name="Departamento">
                                <option value="0">Seleccione el departamento:</option>
                                <?php
                                $stmt = $Con->prepare("SELECT id_departamento, departamento FROM rh_departamentos ORDER BY id_departamento");
                                $stmt->execute();
                                $result = $stmt->get_result();

                                while ($Row = $result->fetch_assoc()) {
                                    $selected = ($Departamento == $Row['id_departamento']) ? 'selected' : '';
                                    echo '<option value="'.$Row['id_departamento'].'" '.$selected.'>'.$Row['departamento'].'</option>';
                                }
                                $stmt->close();
                                ?>
                            </select>
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Tipo de empleado</span>
                            <select class="FAI prueba" id="Tipo" name="Tipo">
                                <option value="0">Seleccione el tipo de empleado:</option>
                                <?php
                                $stmt = $Con->prepare("SELECT id_tipo_rh, tipo_rh FROM rh_tipos_empleados ORDER BY id_tipo_rh");
                                $stmt->execute();
                                $result = $stmt->get_result();

                                while ($Row = $result->fetch_assoc()) {
                                    $selected = ($Tipo == $Row['id_tipo_rh']) ? 'selected' : '';
                                    echo '<option value="'.$Row['id_tipo_rh'].'" '.$selected.'>'.$Row['tipo_rh'].'</option>';
                                }
                                $stmt->close();
                                ?>
                            </select>
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Fecha de ingreso</span>
                            <?php $Fecha=date("Y-m-d");?>
                            <input class="FAI" id="Fecha" type="date" name="Fecha" value="<?php echo $Fecha; ?>">
                        </label>
                    </div>

                    <div class=Center>
                        <input class="Boton" id="AB" type="Submit" value="Actualizar" name="Modificar">
                    </div>
                </section>

            <?php if ($Correcto < 8) {
                    if ($NumE>0) { 
                        for ($i=1; $i <= 8; $i++) {
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
        </main>
    </body>

    <?php include '../../../Complementos/Footer.php'; ?>
</html>