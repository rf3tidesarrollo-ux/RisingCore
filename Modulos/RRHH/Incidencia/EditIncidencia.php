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
    <link rel="stylesheet" href="DesignLI.css">
    <title>RRHH: Incidencias</title>
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
                        ❗ Incidencias
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="#" style="color: #6c757d; text-decoration: none;">
                        📋 Registros
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <strong style="color: #333;">✏️ Registros de incidencias</strong>
                </nav>
            </div>
            
            <a title="Reporte" href="CatalogoCI.php"><div class="back"><i class="fa-solid fa-left-long fa-xl"></i></div></a>

            <section class="Registro">
                <h4>Registro de incidencia</h4>
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
                            <span class="FAS">Nombre</span>
                            <select class="FAI prueba2" name="Nombre" id="nombres">
                                <option value="0">Seleccione una sede primero</option>
                            </select>
                        </label>
                    </div>
                    <input type="hidden" id="nombreSeleccionado" value="<?= htmlspecialchars($Nombre) ?>">

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Tipo de permiso</span>
                            <select class="FAI prueba" id="Permiso" name="Permiso">
                                <option value="0">Seleccione el tipo de permiso:</option>
                                <?php
                                $stmt = $Con->prepare("SELECT id_permiso, tipo_permiso FROM rh_permisos WHERE id_permiso NOT IN (1) ORDER BY id_permiso");
                                $stmt->execute();
                                $result = $stmt->get_result();

                                while ($Row = $result->fetch_assoc()) {
                                    $selected = ($Permiso == $Row['id_permiso']) ? 'selected' : '';
                                    echo '<option value="'.$Row['id_permiso'].'" '.$selected.'>'.$Row['tipo_permiso'].'</option>';
                                }
                                $stmt->close();
                                ?>
                            </select>
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Fecha</span>
                            <input class="FAI" id="FI" type="date" name="FI" value="<?php echo !empty($FI) ? $FI : date('Y-m-d'); ?>">
                        </label>
                    </div>


                    <div class=Center>
                        <input class="Boton" id="AB" type="Submit" value="Actualizar" name="Modificar">
                    </div>
                </section>

            <?php if ($Correcto < 4) {
                $tipos = [
                    'Error' => ['cantidad' => $NumE, 'max' => 4, 'title' => 'Error!', 'type' => 'error'],
                    'Precaucion' => ['cantidad' => $NumP, 'max' => 1, 'title' => 'Precaución!', 'type' => 'warning'],
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