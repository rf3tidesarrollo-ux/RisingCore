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
    <link rel="stylesheet" href="DesignPR.css">
    <title>Personal: Editar</title>
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
                    <a href="/RisingCore/Modulos/RRHH/Inciio.php" style="color: #6c757d; text-decoration: none;">
                        👥 Recursos Humanos
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="/RisingCore/Modulos/RRHH/Merma/Inicio.php" style="color: #6c757d; text-decoration: none;">
                        🙎🏻 Nuevo Ingreso
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="#" style="color: #6c757d; text-decoration: none;">
                        📋 Registros
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <strong style="color: #333;">✏️ Registros de personal</strong>
                </nav>
            </div>
            
            <a title="Reporte" href="CatalogoPR.php"><div class="back"><i class="fa-solid fa-left-long fa-xl"></i></div></a>

            <section class="Registro">
                <h4>Registro de personal</h4>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" name="octavo" id="">
                    <input class="Controles" id="0" type="hidden" name="id" value="<?php echo $ID; ?>">
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

                    <div class="FAD" id="campo_departamento" style="display:none;">
                        <label class="FAL">
                            <span class="FAS">Departamento</span>
                            <select class="FAI prueba" name="Departamento" id="departamentos">
                                <option value="0">Seleccione el tipo de departamento:</option>
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

                    <div class="FAD" id="campo_nombres" style="display:none;">
                        <label class="FAL">
                            <span class="FAS">Personal</span>
                            <select class="FAI prueba2" name="Nombre" id="nombres">
                                <option value="0">Seleccione una sede primero</option>
                            </select>
                        </label>
                    </div>
                    <input type="hidden" id="nombreSeleccionado" value="<?= htmlspecialchars($Nombre) ?>">

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Fecha de nacimiento</span>
                            <input class="FAI" id="FechaN" type="date" name="FechaN" value="<?php echo !empty($FechaN) ? $FechaN : date('2000-01-01'); ?>">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Lugar de nacimiento</span>
                            <input class="FAI" id="Lugar" type="Text" name="Lugar" value="<?php echo $Lugar; ?>" size="25" maxLength="50" onkeyup="mayus(this);">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Clave</span>
                            <input class="FAI" id="Clave" type="Number" name="Clave" value="<?php echo $Clave; ?>" size="5" maxLength="5">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">NSS</span>
                            <input class="FAI" id="NSS" type="Text" name="NSS" value="<?php echo $NSS; ?>" size="25" maxLength="50" onkeyup="mayus(this);">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">RFC</span>
                            <input class="FAI" id="RFC" type="Text" name="RFC" value="<?php echo $RFC; ?>" size="25" maxLength="50" onkeyup="mayus(this);">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">CURP</span>
                            <input class="FAI" id="CURP" type="Text" name="CURP" value="<?php echo $CURP; ?>" size="25" maxLength="50" onkeyup="mayus(this);">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">INE</span>
                            <input class="FAI" id="INE" type="Text" name="INE" value="<?php echo $INE; ?>" size="25" maxLength="50" onkeyup="mayus(this);">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Codigo postal</span>
                            <input class="FAI" id="CP" type="Number" name="CP" value="<?php echo $CP; ?>" size="5" maxLength="5">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Rol</span>
                            <select class="FAI prueba" id="Rol" name="Rol">
                                <option value="0">Seleccione el rol:</option>
                                <option value="OPERATIVO" <?= ($Rol == 'OPERATIVO') ? 'selected' : '' ?>>OPERATIVO</option>
                                <option value="ADMINISTRATIVO" <?= ($Rol == 'ADMINISTRATIVO') ? 'selected' : '' ?>>ADMINISTRATIVO</option>
                            </select>
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Puesto</span>
                            <input class="FAI" id="Puesto" type="Text" name="Puesto" value="<?php echo $Puesto; ?>" size="25" maxLength="50" onkeyup="mayus(this);">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Salario diario</span>
                            <input class="FAI" id="Salario" type="Number" step="0.01" name="Salario" value="<?php echo $Salario; ?>" size="15" maxLength="20">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Escolaridad</span>
                            <select class="FAI prueba" id="Escolaridad" name="Escolaridad">
                                <option value="0">Seleccione la escolaridad:</option>
                                <?php
                                $stmt = $Con->prepare("SELECT id_escolaridad, escolaridad FROM rh_escolaridad ORDER BY id_escolaridad");
                                $stmt->execute();
                                $result = $stmt->get_result();

                                while ($Row = $result->fetch_assoc()) {
                                    $selected = ($Escolaridad == $Row['id_escolaridad']) ? 'selected' : '';
                                    echo '<option value="'.$Row['id_escolaridad'].'" '.$selected.'>'.$Row['escolaridad'].'</option>';
                                }
                                $stmt->close();
                                ?>
                            </select>
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Estado civil</span>
                            <select class="FAI prueba" id="EstadoC" name="EstadoC">
                                <option value="0">Seleccione el estado civil:</option>
                                <?php
                                $stmt = $Con->prepare("SELECT id_estado_c, estado_civil FROM rh_estado_c ORDER BY id_estado_c");
                                $stmt->execute();
                                $result = $stmt->get_result();

                                while ($Row = $result->fetch_assoc()) {
                                    $selected = ($EstadoC == $Row['id_estado_c']) ? 'selected' : '';
                                    echo '<option value="'.$Row['id_estado_c'].'" '.$selected.'>'.$Row['estado_civil'].'</option>';
                                }
                                $stmt->close();
                                ?>
                            </select>
                        </label>
                    </div>

                    <div class="FAD" id="campo_municipio" style="display:none;">
                        <label class="FAL">
                            <span class="FAS">Municipio</span>
                            <select class="FAI prueba2" name="Municipio" id="municipio">
                                <option value="0">Seleccione una sede primero</option>
                            </select>
                        </label>
                    </div>
                    <input type="hidden" id="municipioSeleccionado" value="<?= htmlspecialchars($Municipio) ?>">

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Domicilio</span>
                            <input class="FAI" id="Domicilio" type="Text" name="Domicilio" value="<?php echo $Domicilio; ?>" size="25" maxLength="50" onkeyup="mayus(this);">
                        </label>
                    </div>

                    <div class="FAD" id="campo_ruta" style="display:none;">
                        <label class="FAL">
                            <span class="FAS">Ruta</span>
                            <select class="FAI prueba" name="Ruta" id="ruta">
                                <option value="0">Seleccione una sede primero</option>
                            </select>
                        </label>
                    </div>
                    <input type="hidden" id="rutaSeleccionada" value="<?= htmlspecialchars($Camino) ?>">

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Beneficiario</span>
                            <input class="FAI" id="Beneficiario" type="Text" name="Beneficiario" value="<?php echo $Beneficiario; ?>" size="25" maxLength="50" onkeyup="mayus(this);">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Parentesco</span>
                            <input class="FAI" id="Parentesco" type="Text" name="Parentesco" value="<?php echo $Parentesco; ?>" size="25" maxLength="50" onkeyup="mayus(this);">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Termino de contrato</span>
                            <input class="FAI" id="FechaTC" type="date" name="FechaTC" value="<?php echo !empty($FechaTC) ? $FechaTC : date('Y-m-d'); ?>">
                        </label>
                    </div>

                    <div class=Center>
                        <input class="Boton" id="AB" type="Submit" value="Actualizar" name="Modificar">
                    </div>
                </section>

            <?php if ($Correcto < 21) {
                    $tipos = [
                    'Error' => ['cantidad' => $NumE, 'max' => 22, 'title' => 'Error!', 'type' => 'error'],
                    'Precaucion' => ['cantidad' => $NumP, 'max' => 12, 'title' => 'Precaución!', 'type' => 'warning'],
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
            <script src="../../../js/personal.js"></script>
        </main>
    </body>

    <?php include '../../../Complementos/Footer.php'; ?>
</html>