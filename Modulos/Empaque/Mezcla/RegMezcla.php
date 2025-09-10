<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="shortcut icon" href="../../../Images/MiniLogo.png">
    <script src="https://code.jquery.com/jquery-3.7.1.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js" ></script>
    <link href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css" rel="stylesheet"/>
    <link href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.dataTables.css" rel="stylesheet"/>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js" ></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.dataTables.js" ></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://kit.fontawesome.com/367278d2a4.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="../../../js/select.js"></script>
    <link rel="stylesheet" href="../../../css/eggy.css" />
    <link rel="stylesheet" href="../../../css/progressbar.css" />
    <link rel="stylesheet" href="../../../css/theme.css" />
    <link rel="stylesheet" href="DesignMz.css">
    <title>Empaque: Mezclas</title>
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
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="/RisingCore/Modulos/Empaque/index.php" style="color: #6c757d; text-decoration: none;">
                        ⚖️ Pesaje
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="/RisingCore/Modulos/Empaque/Pesajes" style="color: #6c757d; text-decoration: none;">
                        📋 Registros
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <strong style="color: #333;">✏️ Registro de Mezclas</strong>
                </nav>
            </div>
        
        <div id="main-container">
        <?php if ($TipoRol=="ADMINISTRADOR" || $Ver=true) { ?> <a title="Reporte" href="CatalogoMz.php"><div class="back"><i class="fas fa-balance-scale fa-xl"></i></div></a><?php } ?>

        <div id="main-container">

            <section class="Registro">
                <h4>Registro mezclas</h4>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" name="octavo" id="">
                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Sede</span>
                            <select class="FAI prueba" id="sede2" name="Sede">
                                <option value="0">Seleccione la sede:</option>
                                <option value="RF1"<?php if ($Sede == "RF1") echo " selected"; ?>>RF1</option>
                                <option value="RF2"<?php if ($Sede == "RF2") echo " selected"; ?>>RF2</option>
                                <option value="RF3"<?php if ($Sede == "RF3") echo " selected"; ?>>RF3</option>
                            </select>
                        </label>
                    </div>

                    <div class="FAD" id="campo_clientes">
                        <label class="FAL">
                            <span class="FAS">Cliente</span>
                            <select class="FAI prueba" name="Clientes" id="clientes">
                                <option value="0">Seleccione el cliente</option>
                            </select>
                        </label>
                    </div>
                    <input type="hidden" id="clienteSeleccionado" value="<?= htmlspecialchars($Cliente) ?>">

                    <div id="datosCliente" style="display: none;">
                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Folio</span>
                            <input class="FAI" type="Text" name="Folio" <?php if (isset($_POST['Folio']) != ''): ?> value="<?php echo $Folio; ?>"<?php endif; ?> id="folio" readonly>
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Cajas disponibles</span>
                            <input class="FAI"  type="Number" name="CajasT" <?php if (isset($_POST['CajasT']) != ''): ?> value="<?php echo $CajasT; ?>"<?php endif; ?> id="cajasT" readonly>
                        </label>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Kilos disponibles</span>
                            <input class="FAI" type="Number" name="KilosT" <?php if (isset($_POST['KilosT']) != ''): ?> value="<?php echo $KilosT; ?>"<?php endif; ?> id="kilosT" readonly>
                        </label>
                    </div>
                    </div>

                    <div class="FAD">
                        <label class="FAL">
                            <span>Lotes</span>
                                <table id="basic-datatables" class="display table table-striped table-hover responsive nowrap">
                                    <thead>
                                        <tr>
                                        <th>Lote mezcla</th>
                                        <th>Código</th>
                                        <th>Fecha</th>
                                        <th>Sede</th>
                                        <th>Módulo</th>
                                        <th>Cajas disponibles</th>
                                        <th>Kilos disponibles</th>
                                        <th>Cajas solicitadas</th>
                                        <th>Kilos solicitados</th>
                                        <th>Acciones</th>
                                        </tr>
                                    </thead>
                                </table>
                                <button type="button" id="btnAgregarMezcla">+</button>                
                        </label>

                        <div id="formRegistro" style="display: none; margin-top: 20px;">
                        <div class="FAD" id="campo_clientes">
                            <label class="FAL">
                                <span class="FAS">Cliente</span>
                                <select class="FAI prueba" name="Clientes" id="clientes">
                                    <option value="0">Seleccione el cliente</option>
                                </select>
                            </label>
                        </div>
                        <div class="FAD">
                            <label class="FAL">
                                <span class="FAS">Cajas</span>
                                <input class="FAI"  type="Number" name="CajasS" <?php if (isset($_POST['CajasS']) != ''): ?> value="<?php echo $CajasT; ?>"<?php endif; ?> id="cajasS" readonly>
                            </label>
                        </div>
                            <button id="guardarRegistro">Guardar</button>
                        <button id="cancelarRegistro">Cancelar</button>
                        </div>
                    </div>

                <div class=Center>
                    <input class="Boton" id="AB" type="Submit" value="Registrar" name="Insertar">
                    <input class="Boton" id="AC" type="Submit" value="Visualizar" name="Mostrar">
                </div>
            </section>
        </div>

        <?php if ($Correcto < 5) {
                 if ($NumE>0) { 
                    for ($i=1; $i <= 5; $i++) {
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
                    for ($i=1; $i <= 5; $i++) {
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
                    for ($i=1; $i <= 4; $i++) {
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

            window.permisosSeleccionados = {};
            document.querySelectorAll('#tablaPermisos input[type="checkbox"]').forEach(cb => cb.checked = false);
            </script>
        <?php }?>

        <script src="../../../js/modulos.js"></script>
        <script src="../../../js/tbl_mezclas.js"></script>
        <script>
            const clienteSeleccionado = <?= json_encode($Cliente) ?>;
        </script>
        </main>
        
        <?php include '../../../Complementos/Footer.php'; ?>
    </body>
</html>

<script>
    $(document).ready(function() {
        $('#basic-datatables').DataTable({
            serverSide: true,
            ajax: {
                url: '../../Server_side/get_lotes.php',
                type: 'POST',
            },
            columns: [
                    { data: 'no_serie_r' },
                    { data: 'codigo' },
                    { data: 'fecha_r' },
                    { data: 'codigo_s' },
                    { data: 'invernadero' },
                    { data: 'nombre_variedad' },
                    { data: 'p_neto' },
                    { data: 'cantidad_caja' },
                    { data: 'kilos_dis'},
                    { data: 'cajas_dis'},
                    { 
                        data: null,
                        "render": function (data, type, row) {
                            var Rol = <?php echo json_encode($TipoRol); ?>;
                            var Ver = <?php echo json_encode($Ver); ?>;
                            var Editar = <?php echo json_encode($Editar); ?>;
                            var Eliminar = <?php echo json_encode($Eliminar); ?>;

                            if (Rol === "ADMINISTRADOR") {
                                Ver = true;
                                Editar = true;
                                Eliminar = true;
                            }

                            if (Ver || Editar || Eliminar) {
                                return `
                                    ${Editar ? `<a title="Editar" class="Edit" href="EditarU.php?id=${row.id_usuario}"><i class="fa-solid fa-pen-to-square fa-xl" style="color: #0a5ceb;"></i></a>` : ''}
                                    ${Eliminar ? `<a title="Eliminar" class="Delete" href="#${row.id_usuario}" onclick="eliminarRegistro(${row.id_usuario})"><i class="fa-solid fa-trash fa-xl" style="color: #ca1212;"></i></a>` : ''}
                                `;
                            } else {
                                return '';
                            }
                        }
                    }
                    ],
            stateSave: true,
            responsive: true,
            columnDefs: [
            <?php if ($TipoRol=="ADMINISTRADOR" || $Ver==true || $Editar==true || $Eliminar==true) { ?>
                                { responsivePriority: 1, targets: 10 },
            <?php  } ?>
                                { responsivePriority: 1, targets: 8 },
                                { responsivePriority: 2, targets: 7 },
                                { responsivePriority: 2, targets: 2 },
                                { responsivePriority: 2, targets: 1 },
                                { responsivePriority: 1, targets: 0 }
                        ],
            fixedColumns: true,
            autoWidth: true,
            initComplete: function () {
                $('.dt-buttons').appendTo('#datatable-buttons');
                this.api().columns().every(function () {
                    var column = this;
                    var title = $(column.footer()).text();
                    
                    if (title !== "Acciones") {
                        $('<input type="text" placeholder="Buscar ' + title + '" />')
                        .appendTo($(column.footer()).empty())
                        .on('keyup change clear', function () {
                            if (column.search() !== this.value) {
                                column.search(this.value).draw();
                            }
                        });
                    }
                });
            },
            drawCallback: function() {
                this.api().responsive.recalc();
            },
            searching: false,    // Quita el buscador
            lengthChange: false, // Quita el selector de cantidad de registros por página
            info: false, 
        });
    });
</script>