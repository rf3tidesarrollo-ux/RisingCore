<!DOCTYPE html>
<html lang="en">
<head>
    <?php $Ruta = "../../../"; include_once '../../../Complementos/Logo_movil.php'; ?>
    
    <script src="https://code.jquery.com/jquery-3.7.1.js" type="text/javascript"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://kit.fontawesome.com/367278d2a4.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="../../../js/select.js"></script>
    <script src="../../../js/session.js"></script>
    <link rel="stylesheet" href="../../../css/eggy.css" />
    <link rel="stylesheet" href="../../../css/progressbar.css" />
    <link rel="stylesheet" href="../../../css/theme.css" />
    <link rel="stylesheet" href="DesignPD.css">
    <title>Productos: Editar</title>
</head>

    <body onload="validar()">
        <?php
        $basePath = "../";
        $Logo = "../../../";
        $modulo = 'Compras';
        include('../../../Complementos/Header.php');
        ?>

        <main>
            <div style="background: #f9f9f9; padding: 12px 25px; border-bottom: 1px solid #ccc; font-size: 16px;">
                <nav style="display: flex; flex-wrap: wrap; gap: 5px; align-items: center;">
                    <a href="/RisingCore/Modulos/Compras/Inicio.php" style="color: #6c757d; text-decoration: none;">
                        🛒 Compras
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="/RisingCore/Modulos/Compras/Productos/Inicio.php" style="color: #6c757d; text-decoration: none;">
                        📦🆕 Productos
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="#" style="color: #6c757d; text-decoration: none;">
                        📋 Registros
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <strong style="color: #333;">✏️ Actualizar un producto</strong>
                </nav>
            </div>
            
            <a title="Reporte" href="CatalogoPD.php"><div class="back"><i class="fa-solid fa-left-long fa-xl"></i></div></a>

            <section class="Registro">
                <h4>Actualizar producto</h4>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" name="octavo" id="">
                    <input class="Controles" id="0" type="hidden" name="id" value="<?php echo $ID; ?>">

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Nombre del producto</span>
                            <input class="FAI" id="Nombre" type="Text" name="Nombre" value="<?php echo $Nombre; ?>" size="25" maxLength="50" onkeyup="mayus(this);">
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Tipo de producto</span>
                            <select class="FAI prueba" id="tipo" name="Tipo">
                                <option value="0">Seleccione el tipo de producto:</option>
                                <?php
                                $stmt = $Con->prepare("SELECT id_tproducto, tipo_producto FROM cp_tipo_productos ORDER BY tipo_producto");
                                $stmt->execute();
                                $Result = $stmt->get_result();
                        
                                while ($Row = $Result->fetch_assoc()){
                                    $selected = ($Tipo == $Row['id_tproducto']) ? 'selected' : '';
                                    echo '<option value="'.$Row['id_tproducto'].'" '.$selected.'>'.$Row['tipo_producto'].'</option>';
                                }
                                $stmt->close();
                                ?>
                            </select>
                        </label>
                    </div>            
                    
                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Unidad</span>
                            <select class="FAI prueba2" id="Unidad" name="Unidad">
                                <option value="0">Seleccione el tipo de unidad:</option>
                                <?php
                                $stmt = $Con->prepare("SELECT id_unidad, nombre_u FROM cp_unidades ORDER BY nombre_u");
                                $stmt->execute();
                                $Result = $stmt->get_result();
                        
                                while ($Row = $Result->fetch_assoc()){
                                    $selected = ($Unidad == $Row['id_unidad']) ? 'selected' : '';
                                    echo '<option value="'.$Row['id_unidad'].'" '.$selected.'>'.$Row['nombre_u'].'</option>';
                                }
                                $stmt->close();
                                ?>
                            </select>
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Existencias</span>
                            <input class="FAI" id="Existencias" type="Number" name="Existencias" value="<?php echo $Existencias; ?>" size="10" maxLength="10">
                        </label>
                    </div>

                    <div class=Center>
                        <input class="Boton" id="AB" type="Submit" value="Actualizar" name="Modificar">
                    </div>
                </section>

                <?php if ($Correcto < 4) {
                    $tipos = [
                        'Error' => ['cantidad' => $NumE, 'max' => 4, 'title' => 'Error!', 'type' => 'error'],
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
            <script src="../../../js/productos.js"></script>
        </main>
    </body>

    <?php include '../../../Complementos/Footer.php'; ?>
</html>