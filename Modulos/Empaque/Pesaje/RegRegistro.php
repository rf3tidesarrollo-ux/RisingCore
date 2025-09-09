<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<<<<<<< HEAD
    <link rel="shortcut icon" href="../../../Images/MiniLogo.png">
=======
    <link rel="shortcut icon" href="../../Images/MiniLogo.png">
>>>>>>> b5226a49ccee15b7388121ff0078837832ff8736
    <script src="https://code.jquery.com/jquery-3.7.1.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://kit.fontawesome.com/367278d2a4.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<<<<<<< HEAD
    <script src="../../../js/select.js"></script>
    <link rel="stylesheet" href="../../../css/eggy.css" />
    <link rel="stylesheet" href="../../../css/progressbar.css" />
    <link rel="stylesheet" href="../../../css/theme.css" />
    <link rel="stylesheet" href="DesignR.css">
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
                    <a href="/RisingCore/Modulos/index.php" style="color: #6c757d; text-decoration: none;">
                        📦 Empaque
                    </a>
<<<<<<<< HEAD:Modulos/Empaque/Pesaje/RegRegistro.php
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="/RisingCore/Modulos/Empaque/index.php" style="color: #6c757d; text-decoration: none;">
                        ⚖️ Pesaje
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="/RisingCore/Modulos/Empaque/Pesajes" style="color: #6c757d; text-decoration: none;">
                        📋 Registros
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <strong style="color: #333;">📊 Registro de Pesaje</strong>
                </nav>
            </div>

========
=======
    <script src="../../js/select.js"></script>
    <link rel="stylesheet" href="../../css/eggy.css" />
    <link rel="stylesheet" href="../../css/progressbar.css" />
    <link rel="stylesheet" href="../../css/theme.css" />
    <link rel="stylesheet" href="DesignR.css">
    <title>Empaque: Registrar</title>
</head>

<body onload="validar()">
        <header id="header">
            <nav class="navbar">
                <div class="navbar-container">
                    <a href="#" class="logo">
                    <img src="../../Images/Rising-core.png" alt="Logo">
                    </a>
>>>>>>> b5226a49ccee15b7388121ff0078837832ff8736
                    <input type="checkbox" id="menu-toggle">
                    <label for="menu-toggle" class="hamburger"><i class="fas fa-bars"></i></label>
                    <ul class="menu">
                    <li><a href="#">Inicio</a></li>
                    <li class="submenu-parent">
                        <a href="#">Registro <i class="fas fa-caret-down"></i></a>
                        <ul class="submenu">
                        <?php if ($Rol=="ADMINISTRADOR" || $Acceso==2) { ?><li><a href="RegistrarR.php"><i class="fas fa-balance-scale"></i> Pesaje</a></li><?php } ?>
                        <?php if ($Rol=="ADMINISTRADOR" || $Acceso==2) { ?><li><a href="RegistrarM.php"><i class="fa-solid fa-store"></i> Merma</a></li><?php } ?>
                        <?php if ($Rol=="ADMINISTRADOR" || $Acceso==2) { ?><li><a href="RegistrarMz.php"><i class="fa-solid fa-mortar-pestle"></i> Mezcla</a></li><?php } ?>
                        <?php if ($Rol=="ADMINISTRADOR" || $Acceso==2) { ?><li><a href="RegistrarP.php"><i class="fa-solid fa-truck"></i> Pallets</a></li><?php } ?>
                        <?php if ($Rol=="ADMINISTRADOR" || $Acceso==2) { ?><li><a href="RegistrarE.php"><i class="fa-solid fa-qrcode"></i></i> Embarque</a></li><?php } ?>
                        </ul>
                    </li>
                    <li class="submenu-parent">
                        <a href="#">Reportes <i class="fas fa-caret-down"></i></a>
                        <ul class="submenu">
                        <?php if ($Rol=="ADMINISTRADOR" || $Acceso==1) { ?><li><a href="CatalogoR.php"><i class="fas fa-balance-scale"></i> Pesaje</a></li><?php } ?>
                        <?php if ($Rol=="ADMINISTRADOR" || $Acceso==1) { ?><li><a href="CatalogoM.php"><i class="fa-solid fa-store"></i> Merma</a></li><?php } ?>
                        <?php if ($Rol=="ADMINISTRADOR" || $Acceso==1) { ?><li><a href="CatalogoMz.php"><i class="fa-solid fa-mortar-pestle"></i> Mezcla</a></li><?php } ?>
                        <?php if ($Rol=="ADMINISTRADOR" || $Acceso==1) { ?><li><a href="CatalogoP.php"><i class="fa-solid fa-truck"></i> Pallets</a></li><?php } ?>
                        <?php if ($Rol=="ADMINISTRADOR" || $Acceso==1) { ?><li><a href="CatalogoE.php"><i class="fa-solid fa-qrcode"></i></i> Embarque</a></li><?php } ?>
                        </ul>
                    </li>
                    <li><a href="#">Cerrar sesión</a></li>
                    </ul>
                </div>
            </nav>
        </header>
        
<<<<<<< HEAD
>>>>>>>> b5226a49ccee15b7388121ff0078837832ff8736:Modulos_empaque/Registro_empaque/RegRegistro.php
        <div id="main-container">
        <?php if ($TipoRol=="ADMINISTRADOR" || $Ver=true) { ?> <a title="Reporte" href="CatalogoR.php"><div class="back"><i class="fas fa-balance-scale fa-xl"></i></div></a><?php } ?>
=======
        <div id="main-container">
>>>>>>> b5226a49ccee15b7388121ff0078837832ff8736

        <section class="Registro">
            <h4>Registro pesaje</h4>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" name="octavo" id="">
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

<<<<<<< HEAD
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

=======
>>>>>>> b5226a49ccee15b7388121ff0078837832ff8736
                <div class="FAD" id="campo_codigos">
                    <label class="FAL">
                        <span class="FAS">Variedades</span>
                        <select class="FAI prueba" name="Codigo" id="codigos">
                            <option value="0">Seleccione la variedad</option>
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
                        $stmt = $Con->prepare("SELECT id_carro,folio_carro FROM tipos_carros ORDER BY folio_carro");
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
                                $stmt = $Con->prepare("SELECT nombre_tarima FROM tipos_tarimas WHERE id_tarima=? ORDER BY nombre_tarima");
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
                        <input class="FAI" autocomplete="off" id="5" type="Number" name="NoTarima" <?php if (isset($_POST['NoTarima']) != ''): ?> value="<?php echo $NoTarima; ?>"<?php endif; ?> size="15" maxLength="4">
                    </label>
                </div>

                <div class="FAD">
                <label class="FAL">
                    <span class="FAS">Tipo de caja</span>
                    <select class="FAI prueba" id="6" name="Cajas">
                        <option <?php if (($Caja) != null): ?> value="<?php echo $Caja; ?>"<?php endif; ?>>
                            <?php if ($Caja != null) { ?>
                                <?php 
                                $stmt = $Con->prepare("SELECT tipo_caja FROM tipos_cajas WHERE id_caja=? ORDER BY tipo_caja");
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
                        <input class="FAI" autocomplete="off" id="7" type="Number" name="NoCajas" <?php if (isset($_POST['NoCajas']) != ''): ?> value="<?php echo $NoCaja; ?>"<?php endif; ?> size="15" maxLength="4">
                    </label>
                </div>

                <div class="FAD">
                    <label class="FAL">
                        <span class="FAS">Kilos brutos</span>
                        <input class="FAI" autocomplete="off" id="8" type="Number" step="0.01" name="KilosB" <?php if (isset($_POST['KilosB']) != ''): ?> value="<?php echo $KilosB; ?>"<?php endif; ?> size="15" maxLength="20">
                    </label>
                </div>
                
                 <div class="FAD">
                    <label class="FAL">
                        <span class="FAS">Folio</span>
                        <input class="FAI" autocomplete="off" id="9" type="Text" name="Folio" <?php if (isset($_POST['Folio']) != ''): ?> value="<?php echo $Folio; ?>"<?php endif; ?> size="15" maxLength="50">
                    </label>
                </div>

            <div class=Center>
                <input class="Boton" id="AB" type="Submit" value="Registrar" name="Insertar">
            </div>
            </section>
        </div>

<<<<<<< HEAD
        <?php if ($Correcto < 12) {
                 if ($NumE>0) { 
                    for ($i=1; $i <= 11; $i++) {
=======
        <?php if ($Correcto < 11) {
                 if ($NumE>0) { 
                    for ($i=1; $i <= 10; $i++) {
>>>>>>> b5226a49ccee15b7388121ff0078837832ff8736
                        $Error=${"Error".$i};
                        if (!empty($Error)) { ?>
                            <script type="module">
                                var error="<?php echo $Error;?>";
<<<<<<< HEAD
                                import { Eggy } from '../../../js/eggy.js';
=======
                                import { Eggy } from '../../js/eggy.js';
>>>>>>> b5226a49ccee15b7388121ff0078837832ff8736
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
<<<<<<< HEAD
                                import { Eggy } from '../../../js/eggy.js';
=======
                                import { Eggy } from '../../js/eggy.js';
>>>>>>> b5226a49ccee15b7388121ff0078837832ff8736
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
<<<<<<< HEAD
                                import { Eggy } from '../../../js/eggy.js';
=======
                                import { Eggy } from '../../js/eggy.js';
>>>>>>> b5226a49ccee15b7388121ff0078837832ff8736
                                await Eggy({title: 'Error!', message: error, type: 'info', position: 'top-right', duration: 20000});
                            </script>
                        <?php } ?>
                    <?php } ?>
                <?php }
<<<<<<< HEAD
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
        </script>
        </main>
        
        <?php include '../../../Complementos/Footer.php'; ?>
    </body>
=======
        } else { ?>
            <script type="module">
            var error="<?php echo $Finalizado;?>";
            import { Eggy } from '../../js/eggy.js';
            await Eggy({title: 'Correcto!', message: error, type: 'success', position: 'top-right', duration: 10000});
            </script>
        <?php } ?>

        <script src="../../js/modulos.js"></script>
        <script>
            const variedadSeleccionada = "<?php echo $VariedadSeleccionada; ?>";
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
>>>>>>> b5226a49ccee15b7388121ff0078837832ff8736
</html>