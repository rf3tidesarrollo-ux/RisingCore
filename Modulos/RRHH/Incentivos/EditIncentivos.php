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
    <link rel="stylesheet" href="DesignIC.css">
    <title>Incentivos: Editar</title>
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

                    <a href="/RisingCore/Modulos/RRHH/Bonos/Inicio.php" style="color: #6c757d; text-decoration: none;">
                        🪙 Incentivos
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="#" style="color: #6c757d; text-decoration: none;">
                        📋 Registros
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <strong style="color: #333;">✏️ Modificación de incentivos</strong>
                </nav>
            </div>
            
            <a title="Reporte" href="CatalogoIC.php"><div class="back"><i class="fa-solid fa-left-long fa-xl"></i></div></a>

            <section class="Registro">
                <h4>Modificación de incentivo</h4>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" name="octavo" id="">
                    <input class="Controles" id="0" type="hidden" name="id" value="<?php echo $ID; ?>">
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

                    <div class="FAD" id="campo_departamento">
                        <label class="FAL">
                            <span class="FAS">Área</span>
                            <select class="FAI prueba" name="Departamento" id="departamentos">
                                <?php
                                if ($TipoRol != "USUARIO") {
                                    echo '<option value="0">Seleccione el área:</option>';
                                    $stmt = $Con->prepare("SELECT id_departamento, departamento FROM rh_departamentos ORDER BY departamento");
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    while ($row = $result->fetch_assoc()) {
                                        $selected = ($Departamento == $row['id_departamento']) ? 'selected' : '';
                                        echo '<option value="'.$row['id_departamento'].'" '.$selected.'>'.$row['departamento'].'</option>';
                                    }

                                    $stmt->close();
                                } else {
                                    // Usuario normal → solo mostrar su propia sede
                                    echo "<option value='$IDArea' $selected>$Area</option>";
                                }
                                ?>
                            </select>
                        </label>
                    </div>

                    <div class="FAD" id="campo_nombres" style="display:none;">
                        <label class="FAL">
                            <span class="FAS">Empleado</span>
                            <select class="FAI prueba2" name="Nombre" id="nombres">
                                <option value="0">Seleccione una sede primero</option>
                            </select>
                        </label>
                    </div>
                    <input type="hidden" id="nombreSeleccionado" value="<?= htmlspecialchars($Nombre) ?>">

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Tipo de incentivo</span>
                            <select class="FAI prueba" id="Incentivo" name="Incentivo">
                                <option value="0">Seleccione el tipo de incentivo:</option>
                                <?php
                                $stmt = $Con->prepare("SELECT id_tincentivo, tipo_incentivo FROM rh_tipo_incentivos ORDER BY tipo_incentivo");
                                $stmt->execute();
                                $result = $stmt->get_result();

                                while ($Row = $result->fetch_assoc()) {
                                    $selected = ($Incentivo == $Row['id_tincentivo']) ? 'selected' : '';
                                    echo '<option value="'.$Row['id_tincentivo'].'" '.$selected.'>'.$Row['tipo_incentivo'].'</option>';
                                }
                                $stmt->close();
                                ?>
                            </select>
                        </label>
                    </div>

                    <div class="campos-cajas">
                        <div class="FAD" style="flex: 1;">
                            <label class="FAL">
                                <span class="FAS">Fecha</span>
                                <input class="FAI" id="Fecha" type="date" name="Fecha" value="<?php echo !empty($Fecha) ? $Fecha : date('Y-m-d'); ?>">
                            </label>
                        </div>

                        <div class="FAD" style="flex: 1;">
                            <label class="FAL">
                                <span class="FAS">Número de incentivos</span>
                                <input class="FAI" id="Numero" type="Number" name="Numero" value="<?php echo (!empty($Numero) ? $Numero : 1); ?>" size="10" maxLength="10">
                            </label>
                        </div>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Cantidad</span>
                            <input class="FAI" id="Cantidad" type="Number" step="0.01" name="Cantidad" value="<?php echo $Cantidad; ?>" size="10" maxLength="10">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS Top">Motivo</span>
                            <textarea class="FAI" id="Motivo" name="Motivo" rows="5" maxlength="250" onkeyup="mayus(this);" placeholder=""><?php echo isset($Motivo) ? htmlspecialchars($Motivo) : ''; ?></textarea>
                        </label>
                    </div>

                    <div class=Center>
                        <input class="Boton" id="AB" type="Submit" value="Actualizar" name="Modificar">
                    </div>
                </section>

             <?php if ($Correcto < 7) {
                $tipos = [
                    'Error' => ['cantidad' => $NumE, 'max' => 7, 'title' => 'Error!', 'type' => 'error'],
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
            <script src="../../../js/incidencias.js"></script>
        </main>
    </body>

    <?php include '../../../Complementos/Footer.php'; ?>
</html>