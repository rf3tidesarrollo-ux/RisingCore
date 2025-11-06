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
    <link rel="stylesheet" href="DesignM.css">
    <title>Merma: Editar</title>
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
                        ♻️ Merma
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="#" style="color: #6c757d; text-decoration: none;">
                        📋 Registros
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <strong style="color: #333;">✏️ Registros de merma</strong>
                </nav>
            </div>
            
            <a title="Reporte" href="CatalogoM.php"><div class="back"><i class="fa-solid fa-left-long fa-xl"></i></div></a>

            <section class="Registro">
                <h4>Actualizar merma</h4>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" name="octavo" id="">
                    <input class="Controles" id="0" type="hidden" name="id" value="<?php echo $ID; ?>">
                        <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Sede</span>
                            <select class="FAI prueba" id="sede" name="Sede">
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
                                <span class="FAS">Tipo de merma</span>
                                <select class="FAI prueba" id="tipo_registro" name="TipoRegistro" onchange="mostrarCampo()">
                                    <?php
                                    if ($Tipo != null) {
                                        $stmt = $Con->prepare("SELECT tipo_merma FROM clasificacion_merma WHERE tipo_merma = ? GROUP BY tipo_merma");
                                        $stmt->bind_param("s", $Tipo);
                                        $stmt->execute();
                                        $Registro = $stmt->get_result();
                                        $Reg = $Registro->fetch_assoc();
                                        $stmt->close();

                                        if (isset($Reg['tipo_merma'])) {
                                            echo '<option value="' . htmlspecialchars($Reg['tipo_merma']) . '" selected>' . htmlspecialchars($Reg['tipo_merma']) . '</option>';
                                        } else {
                                            echo '<option value="0">Seleccione un tipo de merma:</option>';
                                        }
                                    } else {
                                        echo '<option value="0">Seleccione un tipo de merma:</option>';
                                    }

                                    $stmt = $Con->prepare("SELECT tipo_merma FROM clasificacion_merma GROUP BY tipo_merma");
                                    $stmt->execute();
                                    $Registro = $stmt->get_result();

                                    while ($Reg = $Registro->fetch_assoc()) {
                                        if ($Tipo != null && $Reg['tipo_merma'] == $Tipo) continue;
                                        echo '<option value="' . htmlspecialchars($Reg['tipo_merma']) . '">' . htmlspecialchars($Reg['tipo_merma']) . '</option>';
                                    }
                                    $stmt->close();
                                    ?>
                                </select>
                            </label>
                        </div>

                        <div class="FAD" id="campo_codigos" style="display: none;">
                            <label class="FAL">
                                <span class="FAS">Variedades</span>
                                <select class="FAI prueba" name="Codigo" id="codigos">
                                    <option value="0">Seleccione la variedad</option>
                                </select>
                            </label>
                        </div>

                        <div class="FAD" id="campo_presentacion" style="display: none;">
                            <label class="FAL">
                                <span class="FAS">Presentación</span>
                                <select class="FAI prueba" id="Presentacion" name="Presentacion">
                                    <option <?php if (($Presentacion) != null): ?> value="<?php echo $Presentacion; ?>"<?php endif; ?>>
                                        <?php if ($Presentacion != null) { ?>
                                            <?php 
                                            $stmt = $Con->prepare("SELECT nombre_p FROM tipos_presentacion WHERE id_presentacion=?");
                                            $stmt->bind_param("i",$Presentacion);
                                            $stmt->execute();
                                            $Registro = $stmt->get_result();
                                            $Reg = $Registro->fetch_assoc();
                                            $stmt->close();
                                            if(isset($Reg['nombre_p'])){echo $Reg['nombre_p'];}else{?> Seleccione la presentación: <?php } ?>
                                        <?php } else {?>
                                            Seleccione la presentación:
                                        <?php } ?>
                                    </option>
                                    <?php
                                    $stmt = $Con->prepare("SELECT id_presentacion,nombre_p FROM tipos_presentacion ORDER BY id_presentacion");
                                    $stmt->execute();
                                    $Registro = $stmt->get_result();
                            
                                    while ($Reg = $Registro->fetch_assoc()){
                                        echo '<option value="'.$Reg['id_presentacion'].'">'.$Reg['nombre_p'].'</option>';
                                    }
                                    $stmt->close();
                                    ?>
                                </select>
                            </label>
                        </div>
                        
                        <div class="FAD" id="campo_clasificacion">
                            <label class="FAL">
                                <span class="FAS">Clasificación</span>
                                <select class="FAI prueba" name="Clasificacion" id="clasificacion">
                                    <option value="0">Seleccione un tipo de merma primero</option>
                                </select>
                            </label>
                        </div>

                        <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Traila</span>
                            <select class="FAI prueba" id="3" name="Carro">
                                <option <?php if (($Carro) != null): ?> value="<?php echo $Carro; ?>"<?php endif; ?>>
                                    <?php if ($Carro != null) { ?>
                                        <?php 
                                        $stmt = $Con->prepare("SELECT folio_carro FROM tipos_carros WHERE id_carro=?");
                                        $stmt->bind_param("i",$Carro);
                                        $stmt->execute();
                                        $Registro = $stmt->get_result();
                                        $Reg = $Registro->fetch_assoc();
                                        $stmt->close();
                                        if(isset($Reg['folio_carro'])){echo $Reg['folio_carro'];}else{?> Seleccione la traila: <?php } ?>
                                    <?php } else {?>
                                        Seleccione la traila:
                                    <?php } ?>
                                </option>
                                <?php
                                $stmt = $Con->prepare("SELECT id_carro,folio_carro FROM tipos_carros ORDER BY id_carro");
                                $stmt->execute();
                                $Registro = $stmt->get_result();
                        
                                while ($Reg = $Registro->fetch_assoc()){
                                    echo '<option value="'.$Reg['id_carro'].'">'.$Reg['folio_carro'].'</option>';
                                }
                                $stmt->close();
                                ?>
                            </select>
                        </label>
                        </div>

                        <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Tipo de tarima</span>
                            <select class="FAI prueba" id="4" name="Tarima">
                                <option <?php if (($Tarima) != null): ?> value="<?php echo $Tarima; ?>"<?php endif; ?>>
                                    <?php if ($Tarima != null) { ?>
                                        <?php 
                                        $stmt = $Con->prepare("SELECT nombre_tarima FROM tipos_tarimas WHERE id_tarima=?");
                                        $stmt->bind_param("i",$Tarima);
                                        $stmt->execute();
                                        $Registro = $stmt->get_result();
                                        $Reg = $Registro->fetch_assoc();
                                        $stmt->close();
                                        if(isset($Reg['nombre_tarima'])){echo $Reg['nombre_tarima'];}else{?> Seleccione la tarima: <?php } ?>
                                    <?php } else {?>
                                        Seleccione la tarima:
                                    <?php } ?>
                                </option>
                                <?php
                                $stmt = $Con->prepare("SELECT id_tarima,nombre_tarima FROM tipos_tarimas ORDER BY id_tarima");
                                $stmt->execute();
                                $Registro = $stmt->get_result();
                        
                                while ($Reg = $Registro->fetch_assoc()){
                                    echo '<option value="'.$Reg['id_tarima'].'">'.$Reg['nombre_tarima'].'</option>';
                                }
                                $stmt->close();
                                ?>
                            </select>
                        </label>
                        </div>

                        <div class="FAD">
                            <label class="FAL">
                                <span class="FAS">Cantidad de tarimas</span>
                                <input class="FAI" autocomplete="off" id="5" type="Number" name="NoTarima" value="<?php echo $NoTarima; ?>"size="15" maxLength="4">
                            </label>
                        </div>

                        <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Tipo de caja</span>
                            <select class="FAI prueba" id="6" name="Cajas">
                                <option <?php if (($Caja) != null): ?> value="<?php echo $Caja; ?>"<?php endif; ?>>
                                    <?php if ($Caja != null) { ?>
                                        <?php 
                                        $stmt = $Con->prepare("SELECT tipo_caja FROM tipos_cajas WHERE id_caja=?");
                                        $stmt->bind_param("i",$Caja);
                                        $stmt->execute();
                                        $Registro = $stmt->get_result();
                                        $Reg = $Registro->fetch_assoc();
                                        $stmt->close();
                                        if(isset($Reg['tipo_caja'])){echo $Reg['tipo_caja'];}else{?> Seleccione la caja: <?php } ?>
                                    <?php } else {?>
                                        Seleccione la caja:
                                    <?php } ?>
                                </option>
                                <?php
                                $stmt = $Con->prepare("SELECT id_caja,tipo_caja FROM tipos_cajas ORDER BY id_caja");
                                $stmt->execute();
                                $Registro = $stmt->get_result();
                        
                                while ($Reg = $Registro->fetch_assoc()){
                                    echo '<option value="'.$Reg['id_caja'].'">'.$Reg['tipo_caja'].'</option>';
                                }
                                $stmt->close();
                                ?>
                            </select>
                        </label>
                        </div>

                        <div class="FAD">
                            <label class="FAL">
                                <span class="FAS">Cantidad de cajas</span>
                                <input class="FAI" autocomplete="off" id="7" type="Number" name="NoCajas" value="<?php echo $NoCaja; ?>" size="15" maxLength="4">
                            </label>
                        </div>

                        <div class="FAD">
                            <label class="FAL">
                                <span class="FAS">Kilos brutos</span>
                                <input class="FAI" autocomplete="off" id="8" type="Number" step="0.01" name="KilosB" value="<?php echo $KilosB; ?>" size="15" maxLength="20">
                            </label>
                        </div>
                        
                        <div class="FAD">
                            <label class="FAL">
                                <span class="FAS">Fecha</span>
                                <?php $Fecha=date("Y-m-d");?>
                                <input class="FAI" id="9" type="date" name="Fecha" value="<?php echo $Fecha; ?>">
                            </label>
                        </div>

                    <div class=Center>
                        <input class="Boton" id="AB" type="Submit" value="Actualizar" name="Modificar">
                    </div>
                </section>
            </div>

            <?php if ($Correcto < 13) {
                $tipos = [
                    'Error' => ['cantidad' => $NumE, 'max' => 13, 'title' => 'Error!', 'type' => 'error'],
                    'Precaucion' => ['cantidad' => $NumP, 'max' => 5, 'title' => 'Precaución!', 'type' => 'warning'],
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

                window.permisosSeleccionados = {};
                document.querySelectorAll('#tablaPermisos input[type="checkbox"]').forEach(cb => cb.checked = false);
                </script>
            <?php }?>

            <script src="../../../js/modulos.js"></script>
            <script>
                const variedadSeleccionada = <?= json_encode($Codigo) ?>;
                const clasificacionSeleccionada = <?= json_encode($Clasificacion) ?>;
            </script>
        </main>
        
        <?php include '../../../Complementos/Footer.php'; ?>
    </body>
</html>