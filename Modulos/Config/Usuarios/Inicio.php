<header id="header">
            <nav class="rising">
                <div class="navbar-container">
                    <a href="#" class="logo">
                    <img src="../../../Images/Rising-core.png" alt="Logo">
                    </a>
                    <input type="checkbox" id="menu-toggle">
                    <label for="menu-toggle" class="hamburger"><i class="fas fa-bars"></i></label>
                    <ul class="menu">
                    <li><a href="#">Inicio</a></li>
                    <li class="submenu-parent">
                        <a href="#">Registros <i class="fas fa-caret-down"></i></a>
                        <ul class="submenu">
                        <?php if ($TipoRol=="ADMINISTRADOR" || $Ver==true) { ?><li><a href="RegistrarR.php"><i class="fas fa-balance-scale"></i> Pesaje</a></li><?PHP } ?>
                        <?php if ($TipoRol=="ADMINISTRADOR" || $Ver==true) { ?><li><a href="RegistrarM.php"><i class="fa-solid fa-store"></i> Merma</a></li><?PHP } ?>
                        <?php if ($TipoRol=="ADMINISTRADOR" || $Ver==true) { ?><li><a href="../Mezcla/RegistrarMz.php"><i class="fa-solid fa-mortar-pestle"></i> Mezcla</a></li><?PHP } ?>
                        <?php if ($TipoRol=="ADMINISTRADOR" || $Ver==true) { ?><li><a href="../Pallets/RegistrarP.php"><i class="fa-solid fa-tag"></i> Pallets</a></li><?PHP } ?>
                        <?php if ($TipoRol=="ADMINISTRADOR" || $Ver==true) { ?><li><a href="../Embarque/RegistrarE.php"><i class="fa-solid fa-truck"></i> Embarque</a></li><?PHP } ?>
                        </ul>
                    </li>
                    <li class="submenu-parent">
                        <a href="#">Reportes <i class="fas fa-caret-down"></i></a>
                        <ul class="submenu">
                        <?php if ($TipoRol=="ADMINISTRADOR" || $Ver==true) { ?><li><a href="CatalogoR.php"><i class="fas fa-balance-scale"></i> Pesaje</a></li><?PHP } ?>
                        <?php if ($TipoRol=="ADMINISTRADOR" || $Ver==true) { ?><li><a href="CatalogoM.php"><i class="fa-solid fa-store"></i> Merma</a></li><?PHP } ?>
                        <?php if ($TipoRol=="ADMINISTRADOR" || $Ver==true) { ?><li><a href="../Mezcla/CatalogoMz.php"><i class="fa-solid fa-mortar-pestle"></i> Mezcla</a></li><?PHP } ?>
                        <?php if ($TipoRol=="ADMINISTRADOR" || $Ver==true) { ?><li><a href="../Pallets/CatalogoP.php"><i class="fa-solid fa-tag"></i> Pallets</a></li><?PHP } ?>
                        <?php if ($TipoRol=="ADMINISTRADOR" || $Ver==true) { ?><li><a href="../Embarque/CatalogoE.php"><i class="fa-solid fa-truck"></i> Embarque</a></li><?PHP } ?>
                        </ul>
                    </li>
                    <li class="submenu-parent">
                        <a href="#">Módulos <i class="fas fa-caret-down"></i></a>
                        <ul class="submenu">
                        <?php if ($TipoRol=="ADMINISTRADOR" || PermisoModulo($_SESSION['ID'],"Produccion",$Con)) { ?><li><a href="../Inicio.php"><i class="fa-solid fa-seedling"></i> Producción</a></li><?PHP } ?>
                        <?php if ($TipoRol=="ADMINISTRADOR" || PermisoModulo($_SESSION['ID'],"Empaque",$Con)) { ?><li><a href="../../Inicio.php"><i class="fa-solid fa-box-open"></i> Empaque</a></li><?PHP } ?>
                        <?php if ($TipoRol=="ADMINISTRADOR" || PermisoModulo($_SESSION['ID'],"Exportacion",$Con)) { ?><li><a href="../../Inicio.php"><i class="fa-solid fa-truck-fast"></i> Exportacion</a></li><?PHP } ?>
                        <?php if ($TipoRol=="ADMINISTRADOR" || PermisoModulo($_SESSION['ID'],"Calidad",$Con)) { ?><li><a href="../../Inicio.php"><i class="fa-solid fa-certificate"></i> Calidad</a></li><?PHP } ?>
                        <?php if ($TipoRol=="ADMINISTRADOR" || PermisoModulo($_SESSION['ID'],"Sistemas",$Con)) { ?><li><a href="../../Config/Usuarios/RegistrarU.php"><i class="fa-solid fa-laptop-code"></i> Sistemas</a></li><?PHP } ?>
                        </ul>
                    </li>
                    <li><a href="../../../Login/Cerrar.php"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a></li>
                    </ul>
                </div>
            </nav>
        </header>