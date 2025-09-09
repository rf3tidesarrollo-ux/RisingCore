<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<<<<<<< HEAD
    <link rel="shortcut icon" href="../../../Images/MiniLogo.png">
=======
    <link rel="shortcut icon" href="../Images/MiniLogo.png">
>>>>>>> b5226a49ccee15b7388121ff0078837832ff8736
    <script src="https://code.jquery.com/jquery-3.7.1.js" type="text/javascript"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://kit.fontawesome.com/367278d2a4.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="../../../js/select.js"></script>
    <link rel="stylesheet" href="../../../css/eggy.css" />
    <link rel="stylesheet" href="../../../css/progressbar.css" />
    <link rel="stylesheet" href="../../../css/theme.css" />
    <link rel="stylesheet" href="DesignU.css">
    <title>Usuarios: Registrar</title>
</head>

<body onload="validar()">
        <header id="header">
<<<<<<< HEAD
            <nav class="rising">
=======
            <nav class="navbar">
>>>>>>> b5226a49ccee15b7388121ff0078837832ff8736
                <div class="navbar-container">
                    <a href="#" class="logo">
                    <img src="../../../Images/Rising-core.png" alt="Logo">
                    </a>
                    <input type="checkbox" id="menu-toggle">
                    <label for="menu-toggle" class="hamburger"><i class="fas fa-bars"></i></label>
                    <ul class="menu">
                    <li><a href="#">Inicio</a></li>
                    <li class="submenu-parent">
<<<<<<< HEAD
                        <a href="#">Registros <i class="fas fa-caret-down"></i></a>
                        <ul class="submenu">
                        <?php if ($TipoRol=="ADMINISTRADOR" || $Ver==true) { ?><li><a href="../../Empaque/Pesaje/RegistrarR.php"><i class="fas fa-balance-scale"></i> Pesaje</a></li><?PHP } ?>
                        <?php if ($TipoRol=="ADMINISTRADOR" || $Ver==true) { ?><li><a href="../../Empaque/Pesaje/RegistrarM.php"><i class="fa-solid fa-store"></i> Merma</a></li><?PHP } ?>
                        <?php if ($TipoRol=="ADMINISTRADOR" || $Ver==true) { ?><li><a href="../../Empaque/Mezcla/RegistrarMz.php"><i class="fa-solid fa-mortar-pestle"></i> Mezcla</a></li><?PHP } ?>
                        <?php if ($TipoRol=="ADMINISTRADOR" || $Ver==true) { ?><li><a href="../../Empaque/Pallets/RegistrarP.php"><i class="fa-solid fa-tag"></i> Pallets</a></li><?PHP } ?>
                        <?php if ($TipoRol=="ADMINISTRADOR" || $Ver==true) { ?><li><a href="../../Empaque/Embarque/RegistrarE.php"><i class="fa-solid fa-truck"></i> Embarque</a></li><?PHP } ?>
=======
                        <a href="#">Registro <i class="fas fa-caret-down"></i></a>
                        <ul class="submenu">
                        <li><a href="RegistrarR.php"><i class="fas fa-balance-scale"></i> Pesaje</a></li>
                        <li><a href="RegistrarM.php"><i class="fa-solid fa-store"></i> Merma</a></li>
                        <li><a href="RegistrarMz.php"><i class="fa-solid fa-mortar-pestle"></i> Mezcla</a></li>
                        <li><a href="RegistrarE.php"><i class="fa-solid fa-truck"></i> Embarque</a></li>
                        <li><a href="RegistrarC.php"><i class="fa-solid fa-qrcode"></i></i> Códigos</a></li>
>>>>>>> b5226a49ccee15b7388121ff0078837832ff8736
                        </ul>
                    </li>
                    <li class="submenu-parent">
                        <a href="#">Reportes <i class="fas fa-caret-down"></i></a>
                        <ul class="submenu">
<<<<<<< HEAD
                        <?php if ($TipoRol=="ADMINISTRADOR" || $Ver==true) { ?><li><a href="../../Empaque/Pesaje/CatalogoR.php"><i class="fas fa-balance-scale"></i> Pesaje</a></li><?PHP } ?>
                        <?php if ($TipoRol=="ADMINISTRADOR" || $Ver==true) { ?><li><a href="../../Empaque/Pesaje/CatalogoM.php"><i class="fa-solid fa-store"></i> Merma</a></li><?PHP } ?>
                        <?php if ($TipoRol=="ADMINISTRADOR" || $Ver==true) { ?><li><a href="../../Empaque/Mezcla/CatalogoMz.php"><i class="fa-solid fa-mortar-pestle"></i> Mezcla</a></li><?PHP } ?>
                        <?php if ($TipoRol=="ADMINISTRADOR" || $Ver==true) { ?><li><a href="../../Empaque/Pallets/CatalogoP.php"><i class="fa-solid fa-tag"></i> Pallets</a></li><?PHP } ?>
                        <?php if ($TipoRol=="ADMINISTRADOR" || $Ver==true) { ?><li><a href="../../Empaque/Embarque/CatalogoE.php"><i class="fa-solid fa-truck"></i> Embarque</a></li><?PHP } ?>
                        </ul>
                    </li>
                    <li class="submenu-parent">
                        <a href="#">Módulos <i class="fas fa-caret-down"></i></a>
                        <ul class="submenu">
                        <?php if ($TipoRol=="ADMINISTRADOR" || PermisoModulo($_SESSION['ID'],"Produccion",$Con)) { ?><li><a href="../../Inicio.php"><i class="fa-solid fa-seedling"></i> Producción</a></li><?PHP } ?>
                        <?php if ($TipoRol=="ADMINISTRADOR" || PermisoModulo($_SESSION['ID'],"Empaque",$Con)) { ?><li><a href="../../Inicio.php"><i class="fa-solid fa-box-open"></i> Empaque</a></li><?PHP } ?>
                        <?php if ($TipoRol=="ADMINISTRADOR" || PermisoModulo($_SESSION['ID'],"Exportacion",$Con)) { ?><li><a href="../../Inicio.php"><i class="fa-solid fa-truck-fast"></i> Exportacion</a></li><?PHP } ?>
                        <?php if ($TipoRol=="ADMINISTRADOR" || PermisoModulo($_SESSION['ID'],"Calidad",$Con)) { ?><li><a href="../../Inicio.php"><i class="fa-solid fa-certificate"></i> Calidad</a></li><?PHP } ?>
                        <?php if ($TipoRol=="ADMINISTRADOR" || PermisoModulo($_SESSION['ID'],"Sistemas",$Con)) { ?><li><a href="../../Inicio.php"><i class="fa-solid fa-laptop-code"></i> Sistemas</a></li><?PHP } ?>
                        </ul>
                    </li>
                    <li><a href="../../../Login/Cerrar.php"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a></li>
=======
                        <li><a href="RegistrarR.php"><i class="fas fa-balance-scale"></i> Pesaje</a></li>
                        <li><a href="RegistrarM.php"><i class="fa-solid fa-store"></i> Merma</a></li>
                        <li><a href="RegistrarMz.php"><i class="fa-solid fa-mortar-pestle"></i> Mezcla</a></li>
                        <li><a href="RegistrarE.php"><i class="fa-solid fa-truck"></i> Embarque</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Cerrar sesión</a></li>
>>>>>>> b5226a49ccee15b7388121ff0078837832ff8736
                    </ul>
                </div>
            </nav>
        </header>
        
        <div id="main-container">

        <section class="Registro">
            <h4>Registro de usuarios</h4>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" name="octavo" id="">
                <div class="FAD">
                    <label class="FAL">
                        <span class="FAS">Usuario</span>
                        <input class="FAI" autocomplete="off" id="1" type="Text" name="User" <?php if (isset($_POST['User']) != ''): ?> value="<?php echo $User; ?>"<?php endif; ?> size="15" maxLength="50">
                    </label>
                </div>

                <div class="FAD">
                    <label class="FAL">
                        <span class="FAS">Contraseña</span>
                        <input class="FAI" autocomplete="off" id="2" type="Password" name="Pass" <?php if (isset($_POST['Pass']) != ''): ?> value="<?php echo $Pass; ?>"<?php endif; ?> size="15" maxLength="50">
                        <i id="eye" class="fa-regular fa-eye fa-xl" style="color: #000000;"></i>
                    </label>
                </div>

                <div class="FAD">
                    <label class="FAL">
                        <span class="FAS">Rol</span>
                        <select class="FAI prueba" id="3" name="Rol">
                            <option <?php if (($Rol) != null): ?> value="<?php echo $Rol; ?>"<?php endif; ?>>
                                <?php if ($Rol != null) { ?>
                                    <?php 
                                    $stmt = $Con->prepare("SELECT rol FROM roles WHERE id_rol=?");
                                    $stmt->bind_param("i",$Rol);
                                    $stmt->execute();
                                    $Registro = $stmt->get_result();
                                    $Reg = $Registro->fetch_assoc();
                                    $stmt->close();
                                    if(isset($Reg['rol'])){echo $Reg['rol'];}else{?> Seleccione el rol: <?php } ?>
                                <?php } else {?>
                                    Seleccione el rol:
                                <?php } ?>
                            </option>
                            <?php
                            $stmt = $Con->prepare("SELECT id_rol,rol FROM roles ORDER BY rol");
                            $stmt->execute();
                            $Registro = $stmt->get_result();
                    
                            while ($Reg = $Registro->fetch_assoc()){
                                echo '<option value="'.$Reg['id_rol'].'">'.$Reg['rol'].'</option>';
                            }
                            $stmt->close();
                            ?>
                        </select>
                    </label>
                </div>

                <div class="FAD">
                    <label class="FAL">
                        <span class="FAS">Titular</span>
                        <select class="FAI prueba" id="4" name="Titular">
                            <option <?php if (($Titular) != null): ?> value="<?php echo $Titular; ?>"<?php endif; ?>>
                                <?php if ($Titular != null) { ?>
                                    <?php 
                                    $stmt = $Con->prepare("SELECT nombre_completo FROM cargos WHERE id_cargo=?");
                                    $stmt->bind_param("i",$Titular);
                                    $stmt->execute();
                                    $Registro = $stmt->get_result();
                                    $Reg = $Registro->fetch_assoc();
                                    $stmt->close();
                                    if(isset($Reg['nombre_completo'])){echo $Reg['nombre_completo'];}else{?> Seleccione el titular: <?php } ?>
                                <?php } else {?>
                                    Seleccione el titular:
                                <?php } ?>
                            </option>
                            <?php
                            $stmt = $Con->prepare("SELECT id_cargo,nombre_completo FROM cargos ORDER BY nombre_completo");
                            $stmt->execute();
                            $Registro = $stmt->get_result();
                    
                            while ($Reg = $Registro->fetch_assoc()){
                                echo '<option value="'.$Reg['id_cargo'].'">'.$Reg['nombre_completo'].'</option>';
                            }
                            $stmt->close();
                            ?>
                        </select>
                    </label>
                </div>

                <div class="FAD">
                    <label class="FAL">
                        <span>Permisos por módulo</span>
                            <table class="tabla-permisos">
                                <thead>
                                    <tr>
                                    <th>Módulo</th>
                                    <th>Ver</th>
                                    <th>Crear</th>
                                    <th>Editar</th>
                                    <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaPermisos">
                                    <!-- Rellenado dinámicamente por JS -->
                                </tbody>
                            </table>                    
                    </label>
                </div>

            <div class=Center>
                <input class="Boton" id="AB" type="Submit" value="Registrar" name="Insertar">
            </div>
            </section>
        </div>

        <?php if ($Correcto < 5) {
                 if ($NumE>0) { 
                    for ($i=1; $i <= 4; $i++) {
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
<<<<<<< HEAD
        } 
        
        if (isset($_SESSION['correcto'])) { $Finalizado = $_SESSION['correcto']; unset($_SESSION['correcto']); ?>
=======
        } else { ?>
>>>>>>> b5226a49ccee15b7388121ff0078837832ff8736
            <script type="module">
            var error="<?php echo $Finalizado;?>";
            import { Eggy } from '../../../js/eggy.js';
            await Eggy({title: 'Correcto!', message: error, type: 'success', position: 'top-right', duration: 10000});

<<<<<<< HEAD
            window.permisosSeleccionados = {};
            document.querySelectorAll('#tablaPermisos input[type="checkbox"]').forEach(cb => cb.checked = false);
            </script>
        <?php }?>
=======
            window.permisosSeleccionados = {};  // ← LIMPIA LA VARIABLE

            // Opcional: Limpiar checkboxes (si ya están renderizados)
            document.querySelectorAll('#tablaPermisos input[type="checkbox"]').forEach(cb => cb.checked = false);
            </script>
        <?php } ?>
>>>>>>> b5226a49ccee15b7388121ff0078837832ff8736

        <script src="../../../js/modulos.js"></script>
        <script src="../../../js/ajax.js"></script>
        <script>
        var permisosSeleccionados = <?php echo json_encode($_POST['permisos'] ?? []); ?>;
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
                    <div class="logo_footer"><img src="../../../Images/Rising-core.png" alt="RisingCore"></div>
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