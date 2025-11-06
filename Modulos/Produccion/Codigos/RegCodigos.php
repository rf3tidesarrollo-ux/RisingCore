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
    <link rel="stylesheet" href="../../../css/eggy.css" />
    <link rel="stylesheet" href="../../../css/progressbar.css" />
    <link rel="stylesheet" href="../../../css/theme.css" />
    <link rel="stylesheet" href="DesignC.css">
    <title>Códigos: Registrar</title>
</head>

<body onload="validar()">
        <?php
        $basePath = "../";
        $Logo = "../../../";
        $modulo = 'Produccion';
        include('../../../Complementos/Header.php');
        ?>

        <main>
            <div style="background: #f9f9f9; padding: 12px 25px; border-bottom: 1px solid #ccc; font-size: 16px;">
                <nav style="display: flex; flex-wrap: wrap; gap: 5px; align-items: center;">
                    <a href="/RisingCore/Modulos/Produccion/Inicio.php" style="color: #6c757d; text-decoration: none;">
                        🌱 Producción
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="/RisingCore/Modulos/Produccion/Codigos/Inicio.php" style="color: #6c757d; text-decoration: none;">
                        📝 Códigos
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="/RisingCore/Modulos/Empaque/Pesajes" style="color: #6c757d; text-decoration: none;">
                        📋 Registros
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <strong style="color: #333;">✏️ Registro de Códigos</strong>
                </nav>
            </div>
        
        <div id="main-container">
        <?php if ($TipoRol=="ADMINISTRADOR" || $Ver=true) { ?> <a title="Reporte" href="CatalogoC.php"><div class="back"><i class="fa-solid fa-qrcode fa-xl"></i></div></a><?php } ?>

            <section class="Registro">
                <h4>Registro códigos</h4>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" name="octavo" id="">
                    
                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Sede</span>
                            <select class="FAI prueba" id="sedes" name="TipoRegistro" onchange="mostrarCampo2()">
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

                    <div class="FAD" id="campo_naves" style="display: none;">
                        <label class="FAL">
                            <span class="FAS">Naves</span>
                            <select class="FAI prueba" name="Nave" id="naves">
                                <option value="0">Seleccione una sede primero</option>
                            </select>
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Variedad</span>
                            <select class="FAI prueba" id="Variedad" name="Variedad">
                                <option <?php if (($Variedad) != null): ?> value="<?php echo $Variedad; ?>"<?php endif; ?>>
                                    <?php if ($Variedad != null) { ?>
                                        <?php 
                                        $stmt = $Con->prepare("SELECT nombre_variedad FROM variedades WHERE id_nombre_v=?");
                                        $stmt->bind_param("i",$Variedad);
                                        $stmt->execute();
                                        $Registro = $stmt->get_result();
                                        $Reg = $Registro->fetch_assoc();
                                        $stmt->close();
                                        if(isset($Reg['nombre_variedad'])){echo $Reg['nombre_variedad'];}else{?> Seleccione la variedad: <?php } ?>
                                    <?php } else {?>
                                        Seleccione la variedad:
                                    <?php } ?>
                                </option>
                                <?php
                                $stmt = $Con->prepare("SELECT id_nombre_v,nombre_variedad FROM variedades ORDER BY id_nombre_v");
                                $stmt->execute();
                                $Registro = $stmt->get_result();
                        
                                while ($Reg = $Registro->fetch_assoc()){
                                    echo '<option value="'.$Reg['id_nombre_v'].'">'.$Reg['nombre_variedad'].'</option>';
                                }
                                $stmt->close();
                                ?>
                            </select>
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Tipo</span>
                            <select class="FAI prueba" id="Tipo" name="Tipo">
                                <option value="0"<?php if ($Tipo == "0") echo " selected"; ?>>Seleccione el tipo:</option>
                                <option value="CHERRY"<?php if ($Tipo == "CHERRY") echo " selected"; ?>>CHERRY</option>
                                <option value="COOCKTAIL"<?php if ($Tipo == "COOCKTAIL") echo " selected"; ?>>COOCKTAIL</option>
                                <option value="ROMA"<?php if ($Tipo == "ROMA") echo " selected"; ?>>ROMA</option>
                                <option value="GRAPE"<?php if ($Tipo == "GRAPE") echo " selected"; ?>>GRAPE</option>
                            </select>
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Color</span>
                            <select class="FAI prueba" id="Color" name="Color">
                                <option value="0"<?php if ($Color == "0") echo " selected"; ?>>Seleccione el color:</option>
                                <option value="AMARILLO"<?php if ($Color == "AMARILLO") echo " selected"; ?>>AMARILLO</option>
                                <option value="ROJO"<?php if ($Color == "ROJO") echo " selected"; ?>>ROJO</option>
                                <option value="CAFÉ"<?php if ($Color == "CAFÉ") echo " selected"; ?>>CAFÉ</option>
                                <option value="NARANJA"<?php if ($Color == "NARANJA") echo " selected"; ?>>NARANJA</option>
                            </select>
                        </label>
                    </div>


                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Superficie</span>
                            <input class="FAI" autocomplete="off" id="Superficie" type="Number" step="0.01" name="Superficie" <?php if (isset($_POST['Superficie']) != ''): ?> value="<?php echo $Superficie; ?>"<?php endif; ?> size="15" maxLength="20">
                        </label>
                    </div>

                    <div class="FAD">
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
                    
                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Ciclo</span>
                            <select class="FAI prueba" id="Ciclo" name="Ciclo">
                                <option <?php if (($Ciclo) != null): ?> value="<?php echo $Ciclo; ?>"<?php endif; ?>>
                                    <?php if ($Ciclo != null) { ?>
                                        <?php 
                                        $stmt = $Con->prepare("SELECT ciclo FROM ciclos WHERE id_ciclo=? AND activo_c=1");
                                        $stmt->bind_param("i",$Ciclo);
                                        $stmt->execute();
                                        $Registro = $stmt->get_result();
                                        $Reg = $Registro->fetch_assoc();
                                        $stmt->close();
                                        if(isset($Reg['ciclo'])){echo $Reg['ciclo'];}else{?> Seleccione el ciclo: <?php } ?>
                                    <?php } else {?>
                                        Seleccione el ciclo:
                                    <?php } ?>
                                </option>
                                <?php
                                $stmt = $Con->prepare("SELECT id_ciclo,ciclo FROM ciclos ORDER BY id_ciclo DESC");
                                $stmt->execute();
                                $Registro = $stmt->get_result();
                        
                                while ($Reg = $Registro->fetch_assoc()){
                                    echo '<option value="'.$Reg['id_ciclo'].'">'.$Reg['ciclo'].'</option>';
                                }
                                $stmt->close();
                                ?>
                            </select>
                        </label>
                    </div>

                <div class=Center>
                    <input class="Boton" id="AB" type="Submit" value="Registrar" name="Insertar">
                </div>
                </section>
            </div>

            <?php if ($Correcto < 8) {
                $tipos = [
                    'Error' => ['cantidad' => $NumE, 'max' => 8, 'title' => 'Error!', 'type' => 'error'],
                    'Precaucion' => ['cantidad' => $NumP, 'max' => 2, 'title' => 'Precaución!', 'type' => 'warning'],
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
            <script>
                const naveSeleccionada = "<?php echo $Nave; ?>";
            </script>
        </main>
        
        <?php include '../../../Complementos/Footer.php'; ?>
    </body>
</html>