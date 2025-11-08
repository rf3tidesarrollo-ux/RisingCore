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
    <script src="../../../js/session.js"></script>
    <link rel="stylesheet" href="../../../css/eggy.css" />
    <link rel="stylesheet" href="../../../css/progressbar.css" />
    <link rel="stylesheet" href="../../../css/theme.css" />
    <link rel="stylesheet" href="DesignR.css">
    <title>Pesaje: Registrar</title>
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
                    <a href="/RisingCore/Modulos/Empaque/Inicio.php" style="color: #6c757d; text-decoration: none;">
                        📦 Empaque
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="/RisingCore/Modulos/Empaque/Pesaje/Inicio.php" style="color: #6c757d; text-decoration: none;">
                        ⚖️ Pesaje
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="#" style="color: #6c757d; text-decoration: none;">
                        📋 Registros
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <strong style="color: #333;">✏️ Registro de Pesaje</strong>
                </nav>
            </div>
        
        <div id="main-container">
        <?php if ($TipoRol=="ADMINISTRADOR" || $Ver=true) { ?> <a title="Reporte" href="CatalogoR.php"><div class="back"><i class="fas fa-balance-scale fa-xl"></i></div></a><?php } ?>

        <div id="main-container">

        <section class="Registro">
            <h4>Registro pesaje</h4>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" name="octavo" id="">
                <div class="FAD">
                    <label class="FAL">
                        <span class="FAS">Sede</span>
                        <select class="FAI prueba" id="sede" name="Sede">
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
                
                <div class="campos-cajas">
                    <div class="FAD" id="campo_codigos" style="flex: 1;">
                        <label class="FAL">
                            <span class="FAS">Variedades</span>
                            <select class="FAI prueba" name="Codigo" id="codigos">
                                <option value="0">Seleccione la variedad</option>
                            </select>
                        </label>
                    </div>
                    <input type="hidden" id="variedadSeleccionada" value="<?= htmlspecialchars($Codigo) ?>">

                    <div class="FAD" style="flex: 1;">
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
                </div>

                <div class="FAD" id="campo_trailas">
                        <label class="FAL">
                            <span class="FAS">Trailas</span>
                            <select class="FAI prueba" name="Carro" id="trailas">
                                <option value="0">Seleccione una sede primero</option>
                            </select>
                        </label>
                    </div>
                    <input type="hidden" id="trailaSeleccionada" value="<?= htmlspecialchars($Carro) ?>">
                
                <div class="campos-cajas">
                    <div class="FAD" id="campo_tarimas" style="flex: 1;">
                        <label class="FAL">
                            <span class="FAS">Tarimas</span>
                            <select class="FAI prueba" name="Tarima" id="tarimas">
                                <option value="0">Seleccione una sede primero</option>
                            </select>
                        </label>
                    </div>
                    <input type="hidden" id="tarimaSeleccionada" value="<?= htmlspecialchars($Tarima) ?>">   
                    
                    <div class="FAD" style="flex: 1;">
                        <label class="FAL">
                            <span class="FAS">Cantidad de tarimas</span>
                            <input class="FAI" autocomplete="off" id="5" type="Number" name="NoTarima" <?php if (isset($_POST['NoTarima']) != ''): ?> value="<?php echo $NoTarima; ?>"<?php endif; ?> size="15" maxLength="4">
                        </label>
                        </div>
                    </div>

                <div class="campos-cajas">
                    <div class="FAD" id="campo_cajas" style="flex: 1;">
                        <label class="FAL">
                            <span class="FAS">Cajas</span>
                            <select class="FAI prueba" name="Cajas" id="cajas">
                                <option value="0">Seleccione una sede primero</option>
                            </select>
                        </label>
                    </div>
                    <input type="hidden" id="cajaSeleccionada" value="<?= htmlspecialchars($Caja) ?>">    

                    <div class="FAD" style="flex: 1;">
                        <label class="FAL">
                            <span class="FAS">Cantidad de cajas</span>
                            <input class="FAI" autocomplete="off" id="7" type="Number" name="NoCajas" <?php if (isset($_POST['NoCajas']) != ''): ?> value="<?php echo $NoCaja; ?>"<?php endif; ?> size="15" maxLength="4">
                        </label>
                    </div>
                </div>

                <div class="FAD">
                    <label class="FAL">
                        <span class="FAS">Kilos brutos</span>
                        <input class="FAI" autocomplete="off" id="8" type="Number" step="0.01" name="KilosB" <?php if (isset($_POST['KilosB']) != ''): ?> value="<?php echo $KilosB; ?>"<?php endif; ?> size="15" maxLength="20">
                    </label>
                </div>
                
                 <div class="FAD">
                    <label class="FAL">
                        <span class="FAS">Fecha</span>
                        <input class="FAI" id="9" type="date" name="Fecha" value="<?php echo !empty($Fecha) ? $Fecha : date('Y-m-d'); ?>">
                    </label>
                </div>

            <div class=Center>
                <input class="Boton" id="AB" type="Submit" value="Registrar" name="Insertar">
            </div>
            </section>
        </div>

        <?php if ($Correcto < 12) {
                $tipos = [
                    'Error' => ['cantidad' => $NumE, 'max' => 11, 'title' => 'Error!', 'type' => 'error'],
                    'Precaucion' => ['cantidad' => $NumP, 'max' => 5, 'title' => 'Precaución!', 'type' => 'warning'],
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
        <script src="../../../js/pesaje.js"></script>
        <script>
            const variedadSeleccionada = <?= json_encode($Codigo) ?>;
        </script>
        </main>
        
        <?php include '../../../Complementos/Footer.php'; ?>
    </body>
</html>