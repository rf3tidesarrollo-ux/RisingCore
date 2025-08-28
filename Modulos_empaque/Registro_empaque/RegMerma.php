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
            
        </header>
        
        <div id="main-container">
        <?php if ($Rol!="USUARIO") { ?> <a title="Regresar" href="CatalogoR.php"><div class="back"><i class="fa-solid fa-left-long fa-xl" style="color: #ffffff;"></i></div></a> <?php } ?>

        <section class="Registro">
            <h4>Registro merma</h4>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" name="octavo" id="">
                <div class="FAD">
                    <label class="FAL">
                        <span class="FAS">Tipo de merma</span>
                        <select class="FAI prueba" id="tipo_registro" name="TipoRegistro" onchange="mostrarCampo()">
                            <option value="0">Seleccione un tipo de merma:</option>
                            <option value="A" <?php if ($Tipo == "Producción") echo 'selected'; ?>>Producción</option>
                            <option value="B" <?php if ($Tipo == "Nacional") echo 'selected'; ?>>Nacional</option>
                            <option value="C" <?php if ($Tipo == "Empaque") echo 'selected'; ?>>Empaque</option>
                        </select>
                    </label>
                </div>

                <div class="FAD" id="campo_codigo" style="display: none;">
                <label class="FAL">
                    <span class="FAS">Código</span>
                    <select class="FAI prueba" id="1" name="Codigo">
                        <option <?php if (($Codigo) != null): ?> value="<?php echo $Codigo; ?>"<?php endif; ?>>
                            <?php if ($Codigo != null) { ?>
                                <?php 
                                $stmt = $Con->prepare("SELECT codigo FROM codigos WHERE id_codigo=?");
                                $stmt->bind_param("i",$Codigo);
                                $stmt->execute();
                                $Registro = $stmt->get_result();
                                $Reg = $Registro->fetch_assoc();
                                $stmt->close();
                                if(isset($Reg['codigo'])){echo $Reg['codigo'];}else{?> Seleccione el código: <?php } ?>
                            <?php } else {?>
                                Seleccione el código:
                            <?php } ?>
                        </option>
                        <?php
                        $stmt = $Con->prepare("SELECT id_codigo,codigo FROM codigos ORDER BY id_codigo");
                        $stmt->execute();
                        $Registro = $stmt->get_result();
                
                        while ($Reg = $Registro->fetch_assoc()){
                            echo '<option value="'.$Reg['id_codigo'].'">'.$Reg['codigo'].'</option>';
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
                    <span class="FAS">Carro</span>
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
                                if(isset($Reg['folio_carro'])){echo $Reg['folio_carro'];}else{?> Seleccione el carro: <?php } ?>
                            <?php } else {?>
                                Seleccione el carro:
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
                                $stmt->bind_param("i",$Codigo);
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
                    <span class="FAS">Tipo de caja</span>
                    <select class="FAI prueba" id="5" name="Cajas">
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
                        <input class="FAI" autocomplete="off" id="6" type="Number" name="NoCajas" <?php if (isset($_POST['NoCajas']) != ''): ?> value="<?php echo $NoCajas; ?>"<?php endif; ?> size="15" maxLength="4">
                    </label>
                </div>

                <div class="FAD">
                    <label class="FAL">
                        <span class="FAS">Kilos brutos</span>
                        <input class="FAI" autocomplete="off" id="7" type="Number" step="0.01" name="KilosB" <?php if (isset($_POST['KilosB']) != ''): ?> value="<?php echo $KilosB; ?>"<?php endif; ?> size="15" maxLength="20">
                    </label>
                </div>
                
                 <div class="FAD">
                    <label class="FAL">
                        <span class="FAS">Folio</span>
                        <input class="FAI" autocomplete="off" id="8" type="Text" name="Folio" <?php if (isset($_POST['Folio']) != ''): ?> value="<?php echo $Folio; ?>"<?php endif; ?> size="15" maxLength="50" onkeyup="mayus(this);">
                    </label>
                </div>

            <div class=Center>
                <input class="Boton" id="AB" type="Submit" value="Registrar" name="Insertar">
            </div>
            </section>
        </div>

        <?php if ($Correcto < 9) {
                 if ($NumE>0) { 
                    for ($i=1; $i <= 9; $i++) {
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
                    for ($i=1; $i <= 6; $i++) {
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
                    <div class="logo_footer"><img src="../../Images/Logo_Rising.png" alt="RisingCore"></div>
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