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
    <link rel="stylesheet" href="DesignU.css">
    <title>Configuración: Usuarios</title>
</head>

    <body onload="validar()">
        <?php
        $basePath = "../";
        $Logo = "../../../";
        $modulo = 'Configuración';
        include('../../../Complementos/Header.php');
        ?>
        <main>

        <div style="background: #f9f9f9; padding: 12px 25px; border-bottom: 1px solid #ccc; font-size: 16px;">
                <nav style="display: flex; flex-wrap: wrap; gap: 5px; align-items: center;">
                    <a href="/RisingCore/Modulos/index.php" style="color: #6c757d; text-decoration: none;">
                        ⚙️ Configuración
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="/RisingCore/Modulos/Empaque/index.php" style="color: #6c757d; text-decoration: none;">
                        👤 Usuarios
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="/RisingCore/Modulos/Empaque/Pesajes" style="color: #6c757d; text-decoration: none;">
                        📋 Registros
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <strong style="color: #333;">✏️ Edición de usuarios</strong>
                </nav>
            </div>
        
        <div id="main-container">
        <a title="Regresar" href="CatalogoU.php"><div class="back"><i class="fa-solid fa-left-long fa-xl"></i></div></a>


        <section class="Registro">
            <h4>Actualizar usuario</h4>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" name="octavo" id="">
                <input class="Controles" id="0" type="hidden" name="id" value="<?php echo $ID; ?>">

                <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Usuario</span>
                            <input class="FAI" autocomplete="off" id="1" type="Text" name="User" value="<?php echo $User; ?>" size="15" maxLength="50">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Contraseña</span>
                            <input class="FAI" autocomplete="off" id="2" type="Password" name="Pass" value="<?php echo $Pass; ?>" size="15" maxLength="50">
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
                <input class="Boton" id="AB" type="Submit" value="Actualizar" name="Modificar">
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
            } 
            
            if (isset($_SESSION['correcto'])) { $Finalizado = $_SESSION['correcto']; unset($_SESSION['correcto']); ?>
                <script type="module">
                var error="<?php echo $Finalizado;?>";
                import { Eggy } from '../../../js/eggy.js';
                await Eggy({title: 'Correcto!', message: error, type: 'success', position: 'top-right', duration: 10000});
                </script>
            <?php }?>
            <script>
                var permisosSeleccionados = <?php echo json_encode($permisosSeleccionados); ?>;
            </script>
            <script src="../../../js/modulos.js"></script>
            <script src="../../../js/ajax.js"></script>
            
        </main>
        <?php include '../../../Complementos/Footer.php'; ?>
    </body>
</html>