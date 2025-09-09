<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="shortcut icon" href="../../Images/MiniLogo.png">
    <script src="https://code.jquery.com/jquery-3.7.1.js" type="text/javascript"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://kit.fontawesome.com/367278d2a4.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="../../js/select.js"></script>
    <link rel="stylesheet" href="../../css/eggy.css" />
    <link rel="stylesheet" href="../../css/progressbar.css" />
    <link rel="stylesheet" href="../../css/theme.css" />
    <link rel="stylesheet" href="DesignR.css">
    <title>Empaque: Registrar</title>
</head>

<body onload="validar(); mostrarCampo();">
        <header id="header">
            <nav class="navbar">
                <div class="navbar-container">
                    <a href="#" class="logo">
                    <img src="../../Images/Rising-core.png" alt="Logo">
                    </a>
                    <input type="checkbox" id="menu-toggle">
                    <label for="menu-toggle" class="hamburger"><i class="fas fa-bars"></i></label>
                    <ul class="menu">
                    <li><a href="#">Inicio</a></li>
                    <li class="submenu-parent">
                        <a href="#">Registro <i class="fas fa-caret-down"></i></a>
                        <ul class="submenu">
                        <li><a href="RegistrarR.php"><i class="fas fa-balance-scale"></i> Pesaje</a></li>
                        <li><a href="RegistrarM.php"><i class="fa-solid fa-store"></i> Merma</a></li>
                        <li><a href="RegistrarMz.php"><i class="fa-solid fa-mortar-pestle"></i> Mezcla</a></li>
                        <li><a href="RegistrarE.php"><i class="fa-solid fa-truck"></i> Embarque</a></li>
                        <li><a href="RegistrarC.php"><i class="fa-solid fa-qrcode"></i></i> Códigos</a></li>
                        </ul>
                    </li>
                    <li class="submenu-parent">
                        <a href="#">Reportes <i class="fas fa-caret-down"></i></a>
                        <ul class="submenu">
                        <li><a href="RegistrarR.php"><i class="fas fa-balance-scale"></i> Pesaje</a></li>
                        <li><a href="RegistrarM.php"><i class="fa-solid fa-store"></i> Merma</a></li>
                        <li><a href="RegistrarMz.php"><i class="fa-solid fa-mortar-pestle"></i> Mezcla</a></li>
                        <li><a href="RegistrarE.php"><i class="fa-solid fa-truck"></i> Embarque</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Cerrar sesión</a></li>
                    </ul>
                </div>
            </nav>
        </header>

        <section class="Registro">
            <h4>Registro códigos</h4>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" name="octavo" id="">
                
                <div class="FAD">
                    <label class="FAL">
                        <span class="FAS">Sede</span>
                        <select class="FAI prueba" id="sedes" name="TipoRegistro" onchange="mostrarCampo2()">
                            <option value="0">Seleccione la sede:</option>
                            <option value="A"<?php if ($Sede == "A") echo " selected"; ?>>RF1</option>
                            <option value="B"<?php if ($Sede == "B") echo " selected"; ?>>RF2</option>
                            <option value="C"<?php if ($Sede == "C") echo " selected"; ?>>RF3</option>
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
                        <select class="FAI prueba" id="2" name="Variedad">
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
                        <select class="FAI prueba" id="3" name="Tipo">
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
                        <select class="FAI prueba" id="4" name="Color">
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
                        <input class="FAI" autocomplete="off" id="5" type="Number" step="0.01" name="Superficie" <?php if (isset($_POST['Superficie']) != ''): ?> value="<?php echo $Superficie; ?>"<?php endif; ?> size="15" maxLength="20">
                    </label>
                </div>

                <div class="FAD">
                <label class="FAL">
                    <span class="FAS">Presentación</span>
                    <select class="FAI prueba" id="2" name="Presentacion">
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
                        <select class="FAI prueba" id="6" name="Ciclo">
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
                 if ($NumE>0) { 
                    for ($i=1; $i <= 8; $i++) {
                        $Error=${"Error".$i};
                        if (!empty($Error)) { ?>
                            <script type="module">
                                var error="<?php echo $Error;?>";
                                import { Eggy } from '../../js/eggy.js';
                                await Eggy({title: 'Error!', message: error, type: 'error', position: 'top-right', duration: 20000});
                            </script>
                        <?php } ?>
                    <?php } ?>
                <?php }
                if ($NumP>0) { 
                    for ($i=1; $i <= 2; $i++) {
                        $Precaucion=${"Precaucion".$i};
                        if (!empty($Precaucion)) { ?>
                            <script type="module">
                                var error="<?php echo $Precaucion;?>";
                                import { Eggy } from '../../js/eggy.js';
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
                                import { Eggy } from '../../js/eggy.js';
                                await Eggy({title: 'Error!', message: error, type: 'info', position: 'top-right', duration: 20000});
                            </script>
                        <?php } ?>
                    <?php } ?>
                <?php }
        } else { ?>
            <script type="module">
            var error="<?php echo $Finalizado;?>";
            import { Eggy } from '../../js/eggy.js';
            await Eggy({title: 'Correcto!', message: error, type: 'success', position: 'top-right', duration: 50000});
            </script>
        <?php } ?>

        <script src="../../js/modulos.js"></script>
        <script>
            const naveSeleccionada = "<?php echo $Nave; ?>";
        </script>     
    </body>
    <footer>
        <div class="container_footer">
            <div class="colum1">
                <div class="footer_line one">
                    <hr class="hrLinea">
                </div>
            </div>
            <div class="colum2">
                <div class="box">
                    <div class="footer_icons">
                        <ul>
                            <li><a href="#"><i class="fa-brands fa-bounce fa-facebook fa-2xl" style="color: #0865ff;"></i></a></li>
                            <li><a href="#"><i class="fa-brands fa-bounce fa-instagram fa-2xl"  style="color: #000000ff;"></i></a></li>
                            <li><a href="#"><i class="fa-brands fa-bounce fa-tiktok fa-2xl"  style="color: #000000;"></i></a></li>
                            <li><a href="#"><i class="fa-brands fa-bounce fa-x-twitter fa-2xl"  style="color: #030303ff;"></i></a></li>
                            <li><a href="#"><i class="fa-solid fa-globe fa-bounce fa-2xl" style="color: #808080;"></i></a></li>
                            <li><a href="#"><i class="fa-brands fa-youtube fa-bounce fa-2xl" style="color: #ff0000;"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="box_footer">
                    <div class="logo_footer"><img src="../../Images/Rising-core.png" alt="RisingCore"></div>
                    <div class="links">
                        <ul>
                            <li>
                                <a href="#">Aviso de privacidad</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="box_copyright">
                    <?php $Year = date("Y");  ?>
                    <p>Todos los derechos reservados © <?php echo $Year ?> <b>RisingCore</b>
                    </p>
                </div>
            </div>
            <div class="colum3">
                <div class="footer_line two">
                    <hr class="hrLinea">
                </div>
            </div>
        </div>
    </footer>
</html>