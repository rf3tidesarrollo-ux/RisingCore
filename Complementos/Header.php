<?php
// header.php

// Configuración por módulo
$modulosConfig = [
    "Configuración" => [
        "routes" => [
            "RegistrarC" => "Codigos/RegistrarC.php",
            "CatalogoC"  => "Codigos/CatalogoC.php",
            "RegistrarU" => "Usuarios/RegistrarU.php",
            "CatalogoU"  => "Usuarios/CatalogoU.php",
        ],
        "iconos" => [
            "Codigos" => "fa-solid fa-qrcode",
            "Usuarios" => "fa-solid fa-users",
        ]
    ],
    "Empaque" => [
        "routes" => [
            "RegistrarR" => "Pesaje/RegistrarR.php",
            "CatalogoR"  => "Pesaje/CatalogoR.php",
            "RegistrarM" => "Merma/RegistrarM.php",
            "CatalogoM"  => "Merma/CatalogoM.php",
            "RegistrarMz" => "Mezcla/RegistrarMz.php",
            "CatalogoMz"  => "Mezcla/CatalogoMz.php",
            "RegistrarP" => "Pallets/RegistrarP.php",
            "CatalogoP"  => "Pallets/CatalogoP.php",
            "RegistrarE" => "Embarque/RegistrarE.php",
            "CatalogoE"  => "Embarque/CatalogoE.php",
        ],
        "iconos" => [
            "Pesaje" => "fas fa-balance-scale",
            "Merma"  => "fa-solid fa-store",
            "Mezcla" => "fa-solid fa-mortar-pestle",
            "Pallets" => "fa-solid fa-tag",
            "Embarque" => "fa-solid fa-truck",
        ]
    ],
    "Produccion" => [
        "routes" => [
            "RegistrarC" => "Codigos/RegistrarC.php",
            "CatalogoC"  => "Codigos/CatalogoC.php",
            "RegistrarCl" => "Cultivo/RegistrarCl.php",
            "CatalogoCl"  => "Cultivo/CatalogoCl.php",
            "RegistrarE" => "Estimaciones/RegistrarE.php",
            "CatalogoE"  => "Estimaciones/CatalogoE.php",
        ],
        "iconos" => [
            "Codigos" => "fa-solid fa-qrcode",
            "Cultivo" => "fa-solid fa-mortar-pestle",
            "Estimaciones" => "fa-solid fa-mortar-pestle",
        ]
    ],
    // Agrega más módulos si quieres...
];

// Verifica que el módulo esté definido
if (!isset($modulosConfig[$modulo])) {
    die("Módulo no válido.");
}

$config = $modulosConfig[$modulo];

// Mapa auxiliar para traducir sufijos a nombres de submódulo
$claveMap = [
    'R'   => 'Pesaje',
    'M'   => 'Merma',
    'Mz'  => 'Mezcla',
    'P'   => 'Pallets',
    'E'   => 'Embarque',
    'C'   => 'Codigos',
    'Cl'  => 'Cultivo',
    'U'  => 'Usuarios',
];

$submodulos = [
    'Pesaje'       => "$modulo/Pesaje",
    'Merma'        => "$modulo/Merma",
    'Mezcla'       => "$modulo/Mezcla",
    'Pallets'      => "$modulo/Pallets",
    'Embarque'     => "$modulo/Embarque",
    'Codigos'      => "$modulo/Codigos",
    'Cultivo'      => "$modulo/Cultivo",
    'Estimaciones' => "$modulo/Estimaciones",
    'Usuarios' => "$modulo/Usuarios"
];

// Función para generar ruta absoluta desde la raíz del sitio
function path_abs($rutaRelativa) {
    global $modulo;
    return "/RisingCore/Modulos/" . $modulo . "/" . $rutaRelativa;
}

$base = "../";

// === RUTAS DE INICIO POR MÓDULO ===
$modulosInicio = [
    "Produccion"   => ["path" => "Produccion/Inicio.php", "icon" => "fa-solid fa-seedling"],
    "Empaque"      => ["path" => "Empaque/Inicio.php", "icon" => "fa-solid fa-box-open"],
    "Exportacion"  => ["path" => "Exportacion/Inicio.php", "icon" => "fa-solid fa-truck-fast"],
    "Calidad"      => ["path" => "Calidad/Inicio.php", "icon" => "fa-solid fa-certificate"],
    "Sistemas"     => ["path" => "Sistemas/Inicio.php", "icon" => "fa-solid fa-laptop-code"],
    "Configuración" => ["path" => "Configuración/Inicio.php", "icon" => "fa-solid fa-gear"]
];

?>

<header id="header">
    <nav class="rising">
        <div class="navbar-container">
            <a href="#" class="logo">
                <img src="<?php echo $Logo . 'Images/Rising-core.png'; ?>" alt="Logo">
            </a>
            <input type="checkbox" id="menu-toggle">
            <label for="menu-toggle" class="hamburger"><i class="fas fa-bars"></i></label>
            <ul class="menu">
                <li><a href="Inicio.php">Inicio</a></li>

                <!-- Menú Registros -->
                <li class="submenu-parent">
                    <a href="#">Registros <i class="fas fa-caret-down"></i></a>
                    <ul class="submenu">
                        <?php if ($TipoRol != ""): ?>
                            <?php foreach ($config['routes'] as $nombreRuta => $ruta): ?>
                                <?php
                                    if (strpos($nombreRuta, "Registrar") !== 0) continue;

                                    preg_match('/Registrar(\w+)/', $nombreRuta, $matches);
                                    $claveRaw = $matches[1] ?? null;
                                    $clave = $claveMap[$claveRaw] ?? null;

                                    if (!$clave || !isset($config['iconos'][$clave])) continue;

                                    $permisoRuta = $submodulos[$clave] ?? null;

                                    if ($TipoRol == "ADMINISTRADOR" || ($permisoRuta && TienePermiso($_SESSION['ID'], $permisoRuta, 2, $Con))):
                                ?>
                                    <li>
                                        <a href="<?= path_abs($ruta) ?>">
                                            <i class="<?= $config['iconos'][$clave] ?>"></i> <?= $clave ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </li>

                <!-- Menú Reportes -->
                <li class="submenu-parent">
                    <a href="#">Reportes <i class="fas fa-caret-down"></i></a>
                    <ul class="submenu">
                        <?php if ($TipoRol != ""): ?>
                            <?php foreach ($config['routes'] as $nombreRuta => $ruta): ?>
                                <?php
                                    if (strpos($nombreRuta, "Catalogo") !== 0) continue;

                                    preg_match('/Catalogo(\w+)/', $nombreRuta, $matches);
                                    $claveRaw = $matches[1] ?? null;
                                    $clave = $claveMap[$claveRaw] ?? null;

                                    if (!$clave || !isset($config['iconos'][$clave])) continue;

                                    $permisoRuta = $submodulos[$clave] ?? null;

                                    if ($TipoRol == "ADMINISTRADOR" || ($permisoRuta && TienePermiso($_SESSION['ID'], $permisoRuta, 1, $Con))):
                                ?>
                                    <li>
                                        <a href="<?= path_abs($ruta) ?>">
                                            <i class="<?= $config['iconos'][$clave] ?>"></i> <?= $clave ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </li>

                 <!-- Menú Módulos -->
                <li class="submenu-parent">
                    <a href="#">Módulos <i class="fas fa-caret-down"></i></a>
                    <ul class="submenu">
                        <?php foreach ($modulosInicio as $nombre => $data): 
                            $nombreFiltrado = "%" . $nombre . "%"; ?>
                            <?php if ($TipoRol == "ADMINISTRADOR" || PermisoModulo($_SESSION['ID'], $nombreFiltrado, $Con)): ?>
                                <?php
                                    // Si estamos en el mismo módulo, usa ruta relativa a Inicio.php
                                    $rutaModulo = ($modulo === $nombre) ? $base . "Inicio.php" : $base . "../" . $data['path'];
                                ?>
                                <li><a href="<?= $rutaModulo ?>"><i class="<?= $data['icon'] ?>"></i> <?= $nombre ?></a></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </li>

                <li><a href="../../../Login/Cerrar.php"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a></li>
            </ul>
        </div>
    </nav>
</header>
