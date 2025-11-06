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
            "RegistrarCF"   => "CamaraFria/RegistrarCF.php",
        ],
        "iconos" => [
            "Pesaje" => "fas fa-balance-scale",
            "Merma"  => "fa-solid fa-store",
            "Mezcla" => "fa-solid fa-mortar-pestle",
            "Pallets" => "fa-solid fa-tag",
            "Embarque" => "fa-solid fa-truck",
            "CamaraFria" => "fa-solid fa-temperature-low",
            "Horarios" => "fa-solid fa-business-time",
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
    "RRHH" => [
        "routes" => [
            "RegistrarNI" => "Ingreso/RegistrarNI.php",
            "CatalogoNI"  => "Ingreso/CatalogoNI.php",
            "CatalogoRI" => "Reingreso/CatalogoRI.php",
            "RegistrarPR" => "Personal/RegistrarPR.php",
            "CatalogoPR"  => "Personal/CatalogoPR.php",
            "RegistrarCC" => "Contratos/RegistrarCC.php",
            "CatalogoLA" => "Asistencia/CatalogoLA.php",
            "RegistrarLI"  => "Incidencia/RegistrarLI.php",
            "CatalogoLI"  => "Incidencia/CatalogoLI.php",
            "RegistrarH" => "Horarios/RegistrarH.php",
            "CatalogoH" => "Horarios/CatalogoH.php",
        ],
        "iconos" => [
            "Ingreso" => "fa-solid fa-fingerprint",
            "Personal" => "fa-solid fa-users-between-lines",
            "Asistencia" => "fa-solid fa-clipboard-list",
            "Incidencia" => "fa-solid fa-person-circle-exclamation",
            "Horarios" => "fa-solid fa-business-time",
            "Reingreso" => "fa-solid fa-person-walking-arrow-loop-left",
            "Contratos" => "fa-solid fa-file-contract",
        ]
    ],
    "Compras" => [
        "routes" => [
            "RegistrarRQ" => "Requisiciones/RegistrarRQ.php",
            "CatalogoRQ"  => "Requisiciones/CatalogoRQ.php",
            "RegistrarPO" => "Ordenes/RegistrarPO.php",
            "CatalogoPO"  => "Ordenes/CatalogoPO.php",
            "RegistrarCP" => "Pagos/RegistrarCP.php",
            "CatalogoCP"  => "Pagos/CatalogoCP.php",
            "RegistrarPD" => "Productos/RegistrarPD.php",
            "CatalogoPD"  => "Productos/CatalogoPD.php",
            "RegistrarPV" => "Proveedores/RegistrarPV.php",
            "CatalogoPV"  => "Proveedores/CatalogoPV.php",
        ],
        "iconos" => [
            "Requisiciones" => "fa-solid fa-bell-concierge",
            "Ordenes" => "fa-solid fa-cart-arrow-down",
            "Pagos" => "fa-solid fa-file-invoice-dollar",
            "Productos" => "fa-solid fa-cubes-stacked",
            "Proveedores" => "fa-solid fa-truck-moving",
        ]
    ],
    "Almacen" => [
        "routes" => [
            "RegistrarIN" => "Inventario/RegistrarIN.php",
            "CatalogoIN"  => "Inventario/CatalogoIN.php",
            "RegistrarPC" => "Consumo/RegistrarPC.php",
            "CatalogoPC"  => "Consumo/CatalogoPC.php",
        ],
        "iconos" => [
            "Inventario" => "fa-solid fa-boxes-stacked",
            "Consumos" => "fa-solid fa-cart-shopping",
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
    'CF'  => 'CamaraFria',
    'C'   => 'Codigos',
    'Cl'  => 'Cultivo',
    'U'   => 'Usuarios',
    'NI'  => 'Ingreso',
    'PR'  => 'Personal',
    'LA'  => 'Asistencia',
    'LI'  => 'Incidencia',
    'H'   => 'Horarios',
    'RI'  => 'Reingreso',
    'CC'  => 'Contratos',
    'RQ'  => 'Requisiciones',
    'PO'  => 'Ordenes',
    'CP'  => 'Pagos',
    'PD'  => 'Productos',
    'PV'  => 'Proveedores',
    'IN'  => 'Inventario',
    'PC'  => 'Consumos',
];

// Nombres personalizados por submódulo y tipo de menú
$nombreMenus = [
    'Pesaje' => [
        'registrar' => 'Pesaje',
        'catalogo'  => 'Pesajes'
    ],
    'Merma' => [
        'registrar' => 'Merma',
        'catalogo'  => 'Mermas'
    ],
    'Mezcla' => [
        'registrar' => 'Mezcla',
        'catalogo'  => 'Mezclas'
    ],
    'Pallets' => [
        'registrar' => 'Pallet',
        'catalogo'  => 'Pallets'
    ],
    'Embarque' => [
        'registrar' => 'Embarque',
        'catalogo'  => 'Embarques'
    ],
    'CamaraFria' => [
        'registrar' => 'Cámara Fría',
        'catalogo'  => 'Cámara Fría'
    ],
    'Codigos' => [
        'registrar' => 'Código',
        'catalogo'  => 'Códigos'
    ],
    'Cultivo' => [
        'registrar' => 'Cultivo',
        'catalogo'  => 'Cultivos'
    ],
    'Estimaciones' => [
        'registrar' => 'Estimación',
        'catalogo'  => 'Estimaciones'
    ],
    'Usuarios' => [
        'registrar' => 'Usuario',
        'catalogo'  => 'Usuarios'
    ],
    'Ingreso' => [
        'registrar' => 'Nuevo Ingreso',
        'catalogo'  => 'Pendientes de huella'
    ],
    'Personal' => [
        'registrar' => 'Personal',
        'catalogo'  => 'Personal'
    ],
    'Asistencia' => [
        'registrar' => 'Asistencia',
        'catalogo'  => 'Lista de Asistencias'
    ],
    'Incidencia' => [
        'registrar' => 'Incidencia',
        'catalogo'  => 'Lista de Incidencias'
    ],
    'Horarios' => [
        'registrar' => 'Horario',
        'catalogo'  => 'Horarios'
    ],
    'Reingreso' => [
        'registrar' => 'Reingreso',
        'catalogo'  => 'Reactivar personal'
    ],
    'Contratos' => [
        'registrar' => 'Contratos',
        'catalogo'  => 'Contratos'
    ],
    'Requisiciones' => [
        'registrar' => 'Requisición',
        'catalogo'  => 'Requisiciones'
    ],
    'Ordenes' => [
        'registrar' => 'Orden de Compra',
        'catalogo'  => 'Órdenes de Compra'
    ],
    'Pagos' => [
        'registrar' => 'Agregar pago',
        'catalogo'  => 'Pagos'
    ],
    'Productos' => [
        'registrar' => 'Agregar producto',
        'catalogo'  => 'Productos'
    ],
    'Proveedores' => [
        'registrar' => 'Agregar proveedor',
        'catalogo'  => 'Proveedores'
    ],
    'Inventario' => [
        'registrar' => 'Agregar artículo',
        'catalogo'  => 'Inventario'
    ],
    'Consumos' => [
        'registrar' => 'Consumo',
        'catalogo'  => 'Consumos'
    ],
];

$submodulos = [
    'Pesaje'       => "$modulo/Pesaje",
    'Merma'        => "$modulo/Merma",
    'Mezcla'       => "$modulo/Mezcla",
    'Pallets'      => "$modulo/Pallets",
    'Embarque'     => "$modulo/Embarque",
    'CamaraFria'  => "$modulo/CamaraFria",
    'Codigos'      => "$modulo/Codigos",
    'Cultivo'      => "$modulo/Cultivo",
    'Estimaciones' => "$modulo/Estimaciones",
    'Usuarios'     => "$modulo/Usuarios",
    'Ingreso'      => "$modulo/Ingreso",
    'Personal'     => "$modulo/Personal",
    'Asistencia'   => "$modulo/Asistencia",
    'Horarios'     => "$modulo/Horarios",
    'Incidencia'     => "$modulo/Incidencia",
    'Reingreso'     => "$modulo/Reingreso",
    'Contratos'     => "$modulo/Contratos",
    'Requisiciones' => "$modulo/Requisiciones",
    'Ordenes'       => "$modulo/Ordenes",
    'Pagos'         => "$modulo/Pagos",
    'Productos'     => "$modulo/Productos",
    'Proveedores'   => "$modulo/Proveedores",
    'Inventario'    => "$modulo/Inventario",
    'Consumos'      => "$modulo/Consumo",
];

// Función para generar ruta absoluta desde la raíz del sitio
function path_abs($rutaRelativa) {
    global $modulo;
    return "/RisingCore/Modulos/" . $modulo . "/" . $rutaRelativa;
}

// === RUTAS DE INICIO POR MÓDULO ===
$modulosInicio = [
    "Producción"    => ["path" => "Produccion/Inicio.php", "icon" => "fa-solid fa-seedling"],
    "Empaque"       => ["path" => "Empaque/Inicio.php", "icon" => "fa-solid fa-box-open"],
    "Exportacion"   => ["path" => "Exportacion/Inicio.php", "icon" => "fa-solid fa-truck-fast"],
    "Calidad"       => ["path" => "Calidad/Inicio.php", "icon" => "fa-solid fa-certificate"],
    "RRHH"          => ["path" => "RRHH/Inicio.php", "icon" => "fa-solid fa-users-rectangle"],
    "Compras"       => ["path" => "Compras/Inicio.php", "icon" => "fa-solid fa-cart-shopping"],
    "Almacén"       => ["path" => "Almacen/Inicio.php", "icon" => "fa-solid fa-warehouse"],
    "Sistemas"      => ["path" => "Sistemas/Inicio.php", "icon" => "fa-solid fa-laptop-code"],
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
                                            <?php
                                                $nombreMostrar = $nombreMenus[$clave]['registrar'] ?? ("Registrar " . $claveLabel[$clave]);
                                            ?>
                                            <i class="<?= $config['iconos'][$clave] ?>"></i> <?= $nombreMostrar ?>
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
                                            <?php
                                                $nombreMostrar = $nombreMenus[$clave]['catalogo'] ?? ("Catálogo de " . $claveLabel[$clave]);
                                            ?>
                                            <i class="<?= $config['iconos'][$clave] ?>"></i> <?= $nombreMostrar ?>
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
                                    $rutaModulo = ($modulo === $nombre) ? $basePath . "Inicio.php" : $basePath . "../" . $data['path'];
                                ?>
                                <li><a href="<?= $rutaModulo ?>"><i class="<?= $data['icon'] ?>"></i> <?= $nombre ?></a></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </li>

                <?php
                    // Ruta para cerrar sesión
                    $rutaCerrar = $Logo . "Login/Cerrar.php";
                ?>
                <li><a href="<?= $rutaCerrar;?>"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a></li>
            </ul>
        </div>
    </nav>
</header>
