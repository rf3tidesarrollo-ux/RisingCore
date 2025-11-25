<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $Ruta = "../../../"; include_once '../../../Complementos/Logo_movil.php'; ?>

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
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <script src="../../../js/select.js"></script>
        <script src="../../../js/session.js"></script>
        <link rel="stylesheet" href="../../../css/eggy.css" />
        <link rel="stylesheet" href="../../../css/progressbar.css" />
        <link rel="stylesheet" href="../../../css/theme.css" />
        <link rel="stylesheet" href="DesignRQ.css">
        <title>Requisiciones: Registrar</title>
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

                    <a href="/RisingCore/Modulos/Compras/Requisiciones/Inicio.php" style="color: #6c757d; text-decoration: none;">
                        🛍️ Requisiciones
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="#" style="color: #6c757d; text-decoration: none;">
                        📋 Registros
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <strong style="color: #333;">✏️ Registrar una requisición</strong>
                </nav>
            </div>
        
        <div id="main-container">
        <?php if ($TipoRol=="ADMINISTRADOR" || $Ver=true) { ?> <a title="Reporte" href="CatalogoRQ.php"><div class="back"><i class="fa-solid fa-left-long fa-xl"></i></div></a><?php } ?>

            <section class="Registro">
                <h4>Modificar requisición</h4>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" name="octavo" id="">
                    <input class="Controles" id="0" type="hidden" name="id" value="<?php echo $IDR; ?>">
                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Sede</span>
                            <select class="FAI prueba" id="sede3" name="Sede">
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

                    <div class="FAD">
                        <label class="FAL">
                            <span class="FAS">Departamento</span>
                            <select class="FAI prueba" id="depto" name="Departamento">
                                <?php
                                if ($TipoRol === 'ADMINISTRADOR' || $TipoRol==='SUPERVISOR') {
                                    echo '<option value="0">Seleccione el departamento:</option>';
                                    $stmt = $Con->prepare("SELECT id_dep, dep FROM cp_departamentos ORDER BY dep");
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    while ($Row = $result->fetch_assoc()) {
                                        $selected = ($Departamento == $Row['id_dep']) ? 'selected' : '';
                                        echo '<option value="'.$Row['id_dep'].'" '.$selected.'>'.$Row['dep'].'</option>';
                                    }
                                    $stmt->close();
                                } else {
                                    // Usuario normal → solo mostrar su propio depto
                                    echo '<option value="'.$IDDep.'" '.$selected.'>'.$Depto.'</option>';
                                }
                                ?>
                            </select>
                        </label>
                    </div>

                    <div class="FAD" id="campo_areas">
                        <label class="FAL">
                            <span class="FAS">Área</span>
                            <select class="FAI prueba" name="Area" id="areas">
                                <?php if ($TipoRol!='USUARIO') { ?> <option value="0">Seleccione el área:</option> <?php }  ?>
                            </select>
                        </label>
                    </div>
                    <input type="hidden" id="areaSeleccionada" value="<?= htmlspecialchars($Area) ?>">

                    <div id="datosArticulos" style="display: none;">
                        <div class="fila-dos-campos">
                            <!-- Folio -->
                            <div class="campo campo-grande">
                                <div class="FAD">
                                    <label class="FAL Gris">
                                        <span class="FAS Top Gris">Folio</span>
                                        <input class="FAI Gris" type="text" name="Folio" id="folio" value="<?php echo $Folio; ?>" readonly>
                                    </label>
                                </div>
                            </div>

                            <!-- Contenedor para Cajas y Kilos -->
                            <div class="campo campo-grande">
                                <div class="FAD">
                                    <label class="FAL Gris">
                                        <span class="FAS Top Gris">Productos</span>
                                        <input class="FAI Gris" type="text" name="Producto" id="producto" value="<?php echo $Producto; ?>" readonly>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="ARCH">
                            <div class="AR">AGREGAR PRODUCTO<a id="C1"><i id="Arrow1" class="fa-regular fa-circle-plus fa-lg" style="color: #fff;"></i></a></div>
                            <div id="F1" class=Close>
                                <div class="FAD">
                                    <label class="FAL">
                                        <span class="FAS">Tipo de producto</span>
                                        <select class="FAI prueba" id="tipo" name="Tipo">
                                             <!-- <option value="0" selected disabled>Seleccione el tipo de producto:</option> -->
                                              <option value="0">Seleccione el tipo de producto:</option>
                                            <?php
                                            $stmt = $Con->prepare("SELECT id_tproducto, tipo_producto FROM cp_tipo_productos ORDER BY tipo_producto");
                                            $stmt->execute();
                                            $Registro = $stmt->get_result();
                                    
                                            while ($Reg = $Registro->fetch_assoc()){
                                                echo '<option value="'.$Reg['id_tproducto'].'">'.$Reg['tipo_producto'].'</option>';
                                            }
                                            $stmt->close();
                                            ?>
                                        </select>
                                    </label>
                                </div>

                                <div class="FAD" id="campo_articulos">
                                    <label class="FAL">
                                        <span class="FAS">Producto</span>
                                        <select class="FAI prueba" name="Articulo" id="articulos">
                                            <option value="0">Seleccione el producto:</option>
                                        </select>
                                    </label>
                                </div>

                                <div class="campos-cajas">
                                    <div class="FAD" style="flex: 1;">
                                        <label class="FAL">
                                            <span class="FAS">Cantidad</span>
                                            <input class="FAI" type="number" name="Cantidad" id="cantidad" size="15" maxlength="10">
                                        </label>
                                    </div>

                                    <div class="FAD" style="flex: 1;">
                                        <label class="FAL">
                                            <span class="FAS">Requerido</span>
                                            <input class="FAI" id="requerido" type="date" name="Requerido" value="<?php echo !empty($Requerido) ? $Requerido : date('Y-m-d'); ?>">
                                        </label>
                                    </div>
                                </div>

                                <div class="FAD">
                                    <label class="FAL">
                                        <span class="FAS">Prioridad</span>
                                        <select class="FAI prueba" id="prioridad" name="Priodidad">
                                            <option value="0">Seleccione un tipo de prioridad:</option>
                                            <option value="1">NORMAL</option>
                                            <option value="2">URGENTE</option>
                                            <option value="3">CRÍTICO</option>
                                        </select>
                                    </label>
                                </div>

                                <div class="FAD">
                                    <label class="FAL">
                                        <span class="FAS Top">Justificación</span>
                                        <textarea class="FAI" id="justificacion" name="Justificacion" rows="5" maxlength="250" onkeyup="mayus(this);" placeholder=""><?php echo isset($Justificacion) ? htmlspecialchars($Justificacion) : ''; ?></textarea>
                                    </label>
                                </div>

                                <!-- <div class="FAD">
                                    <label class="FAL">
                                        <span class="FAS">Observación</span>
                                        <textarea class="FAI" id="observacion" name="Observacion" rows="5" maxlength="250" onkeyup="mayus(this);" placeholder=""><?php echo isset($Observacion) ? htmlspecialchars($Observacion) : ''; ?></textarea>
                                    </label>
                                </div> -->

                                <div class=Center2>
                                    <button class="BotonAgregar" type="button" id="btnAgregarArt"><i class="fa-solid fa-plus fa-xl" style="color: #ffffffff;"></i></button>
                                    <a title="Mostrar" class="BotonAgregar" onclick="mostrarIDA()"><i class="fa-solid fa-eye fa-xl" style="color: #ffffffff;"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="FAD">
                        <label class="FAL">
                            <span>Productos</span>
                                <table id="basic-datatables" class="display table table-striped table-hover responsive nowrap">
                                    <thead>
                                        <tr>
                                        <th>Folio</th>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Fecha</th>
                                        <th>Justificación<noscript></noscript></th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                        </tr>
                                    </thead>
                                </table>               
                        </label>

                <div class=Center2>
                    <input class="Boton" id="AB" type="Submit" value="Actualizar" name="Modificar">
                    <?php
                    if (isset($_SESSION['idRequi'])) {
                        $id = $_SESSION['idRequi'];
                        unset($_SESSION['idRequi']); // Limpia después de usar
                    ?>
                        <a id="PDF" title="Mostrar" class="Boton" href="../../Plantillas/Requis/pdf_requi.php?id=<?= $id ?>" target="_blank"><i class="fa-solid fa-file-circle-check fa-2xl" style="color: #ffffffff;"></i></a>
                    <?php } else { ?>
                        <a id="PDF" title="Mostrar" class="Boton" href="../../Plantillas/Requis/pdf_requi.php?id=<?= $ID ?>" target="_blank"><i class="fa-solid fa-file-circle-exclamation fa-2xl" style="color: #ffffffff;"></i></a>
                    <?php } ?>
                </div>
            </section>
        </div>

        <?php if ($Correcto < 4) {
                $tipos = [
                    'Error' => ['cantidad' => $NumE, 'max' => 4, 'title' => 'Error!', 'type' => 'error'],
                    'Precaucion' => ['cantidad' => $NumP, 'max' => 1, 'title' => 'Precaución!', 'type' => 'warning'],
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
        <script src="../../../js/Compras/requisiciones.js"></script>
        </main>
        
        <?php include '../../../Complementos/Footer.php'; ?>
    </body>
</html>

<script>
    let tablaArticulos;
    
    $(document).ready(function() {
    tablaArticulos = $('#basic-datatables').DataTable({
            serverSide: true,
            ajax: {
                url: '../../Server_side/Requisiciones/get_art_temp.php?id=<?=$IDR?>',
                type: 'POST',
                dataFilter: function(data){
                // Intentar parsear JSON
                    try {
                        var json = JSON.parse(data);
                        if(json.expired) {
                            location.href = '../../../index.php';
                            return JSON.stringify({ data: [] }); // evitar error en DataTables
                        }
                        return data;
                    } catch(e) {
                        return data; // si no es JSON, seguir normal
                    }
                }
            },
            columns: [
                    { data: 'folio_t' },
                    { data: 'producto' },
                    { data: 'cantidad_t' },
                    {
                    data: 'fecha_rt',
                    render: function(data, type, row) {
                        if (type === 'display') {
                        // Validar si existe fecha
                        if (!row.fecha_rt || row.fecha_rt === '0000-00-00') {
                            return 'N/A';
                        }

                        const partes = row.fecha_rt.split('-'); // [yyyy, mm, dd]
                        return partes[2] + '/' + partes[1] + '/' + partes[0]; // dd/mm/yyyy
                        } else {
                        return data;
                        }
                    }
                    },
                    { data: 'justificacion' },
                    { data: 'estado_t' },
                    { 
                        data: null,
                        "render": function (data, type, row) {
                            var Rol = <?php echo json_encode($TipoRol); ?>;
                            var Ver = <?php echo json_encode($Ver); ?>;
                            var Crear = <?php echo json_encode($Crear); ?>;
                            var Editar = <?php echo json_encode($Editar); ?>;
                            var Eliminar = <?php echo json_encode($Eliminar); ?>;

                            if (Rol === "ADMINISTRADOR") {
                                Ver = true;
                                Crear = true;
                                Editar = true;
                                Eliminar = true;
                            }

                            if (Ver || Editar || Eliminar) {
                                return `
                                    ${Ver ? `<a title="Mostrar" href="#${row.id_requisicion_t}" onclick="mostrarRegistroPA(${row.id_requisicion_t})"><i class="fa-solid fa-eye fa-xl" style="color: #16ac19;"></i></a>` : ''}
                                    ${Crear ? `<a title="Eliminar" class="Delete" href="#" data-id="${row.id_requisicion_t}"><i class="fa-solid fa-trash fa-xl" style="color: #ca1212;"></i></a>` : ''}
                                `;
                            } else {
                                return '';
                            }
                        }
                    }
                    ],
            language: {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "emptyTable": "No hay datos",
                "zeroRecords": "No se encontraron resultados",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                "infoFiltered": "(filtrado de _MAX_ registros totales)",
                loadingRecords: "Cargando resultados...",
                "paginate": {
                    "first": '<i class="fa-solid fa-backward-step fa-lg"></i>',
                    "last": '<i class="fa-solid fa-forward-step fa-lg"></i>',
                    "next": '<i class="fa-solid fa-caret-right fa-xl"></i>',
                    "previous": '<i class="fa-solid fa-caret-left fa-xl"></i>'
                },
            },
            stateSave: true,
            responsive: true,
            columnDefs: [
            <?php if ($TipoRol=="ADMINISTRADOR" || $Ver==true || $Editar==true || $Eliminar==true) { ?>
                                { responsivePriority: 1, targets: 6 },
            <?php  } ?>
                                { responsivePriority: 1, targets: 5 },
                                { responsivePriority: 2, targets: 4 },
                                { responsivePriority: 2, targets: 3 },
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