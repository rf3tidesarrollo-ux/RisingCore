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
    <link rel="stylesheet" href="DesignM.css">
    <title>Empaque: Pesaje</title>
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

                    <strong style="color: #333;">📊 Registros de merma</strong>
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
                                    <option value="0">Seleccione la sede:</option>
                                    <option value="RF1"<?php if ($Sede == "RF1") echo " selected"; ?>>RF1</option>
                                    <option value="RF2"<?php if ($Sede == "RF2") echo " selected"; ?>>RF2</option>
                                    <option value="RF3"<?php if ($Sede == "RF3") echo " selected"; ?>>RF3</option>
                                </select>
                            </label>
                        </div>

                        <div class="FAD">
                            <label class="FAL">
                                <span class="FAS">Tipo de merma</span>
                                <select class="FAI prueba" id="tipo_registro" name="TipoRegistro" onchange="mostrarCampo()">
                                    <option value="0">Seleccione un tipo de merma:</option>
                                    <option value="PRODUCCIÓN" <?php if ($Tipo == "PRODUCCIÓN") echo 'selected'; ?>>PRODUCCIÓN</option>
                                    <option value="EMPAQUE-NACIONAL" <?php if ($Tipo == "EMPAQUE-NACIONAL") echo 'selected'; ?>>EMPAQUE-NACIONAL</option>
                                    <option value="EMPAQUE-MERMA" <?php if ($Tipo == "EMPAQUE-MERMA") echo 'selected'; ?>>EMPAQUE-MERMA</option>
                                    <option value="MERMA" <?php if ($Tipo == "MERMA") echo 'selected'; ?>>MERMA</option>
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

            <?php if ($Correcto < 14) {
                    if ($NumE>0) { 
                        for ($i=1; $i <= 14; $i++) {
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
                        for ($i=1; $i <= 5; $i++) {
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
                        for ($i=1; $i <= 4; $i++) {
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