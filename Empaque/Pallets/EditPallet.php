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
        <link rel="stylesheet" href="DesignP.css">
        <title>Pallets: Editar</title>
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

                    <a href="/RisingCore/Modulos/Empaque/Pallets/Inicio.php" style="color: #6c757d; text-decoration: none;">
                        🏷️ Pallets
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="#" style="color: #6c757d; text-decoration: none;">
                        📋 Registros
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <strong style="color: #333;">✏️ Registros de Pallets</strong>
                </nav>
            </div>
        
        <div id="main-container">
        <?php if ($TipoRol=="ADMINISTRADOR" || $Ver=true) { ?> <a title="Reporte" href="CatalogoP.php"><div class="back"><i class="fas fa-left-long fa-xl"></i></div></a><?php } ?>

            <section class="Registro">
                <h4>Actualizar Pallet</h4>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" name="octavo" id="">
                    <input type="hidden" name="id" value="<?php echo $IDP; ?>">
                    <input type="hidden" id="folio" value="<?= $Folio ?>">
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

                    <div class="FAD" id="campo_presentaciones">
                        <label class="FAL">
                            <span class="FAS">Presentación</span>
                            <select class="FAI prueba" name="Presentaciones" id="presentaciones">
                                <option value="0">Seleccione una sede primero</option>
                            </select>
                        </label>
                    </div>
                    <input type="hidden" id="presentacionSeleccionada" value="<?= htmlspecialchars($Presentaciones) ?>">
                    
                    <div class="campos-cajas">
                        <div class="FAD" id="campo_tarimas" style="flex: 1;">
                            <label class="FAL">
                                <span class="FAS">Tarimas</span>
                                <select class="FAI prueba" name="Tarimas" id="tarimas">
                                    <option value="0">Seleccione una sede primero</option>
                                </select>
                            </label>
                        </div>
                        <input type="hidden" id="tarimaSeleccionada" value="<?= htmlspecialchars($Tarima) ?>">

                        <div class="FAD" id="campo_embarques" style="flex: 1;">
                            <label class="FAL">
                                <span class="FAS">Embarques</span>
                                <select class="FAI prueba" name="Embarques" id="embarques">
                                    <option value="0">Seleccione una sede primero</option>
                                </select>
                            </label>
                        </div>
                        <input type="hidden" id="embarqueSeleccionado" value="<?= htmlspecialchars($Embarque) ?>">
                    </div>

                    <div class="campos-cajas">
                        <div class="FAD" style="flex: 1;">
                            <label class="FAL">
                                <span class="FAS">Fecha de empaque</span>
                                <?php if (($Fecha == "")) { $Fecha=date("Y-m-d"); }?>
                                <input class="FAI" id="Fecha" type="date" name="Fecha" value="<?php echo $Fecha; ?>">
                            </label>
                        </div>

                        <div class="FAD" style="flex: 1;">
                            <label class="FAL">
                                <span class="FAS">Fecha de envió</span>
                                <?php if (($FechaE == "")) { $FechaE=date("Y-m-d"); }?>
                                <input class="FAI" id="FechaE" type="date" name="FechaE" value="<?php echo $FechaE; ?>">
                            </label>
                        </div>
                    </div>

                    <div id="datosMezcla">
                        <div class="ARCH">
                            <div class="AR">AGREGAR MEZCLA<a id="C1"><i id="Arrow1" class="fa-regular fa-circle-plus fa-lg" style="color: #fff;"></i></a></div>
                            <div id="F1" class=Close>
                                <div class="FAD" id="campo_mezclas">
                                    <label class="FAL">
                                        <span class="FAS">Mezcla</span>
                                        <select class="FAI prueba" name="Mezclas" id="mezclas">
                                            <option value="0">Seleccione la mezcla:</option>
                                        </select>
                                    </label>
                                </div>

                                <div class="FAD">
                                    <label class="FAL">
                                        <span class="FAS">Cajas</span>
                                        <input class="FAI" type="number" name="CajasT" id="cajasT" size="15" maxlength="10">
                                    </label>
                                </div>

                                <div class="FAD" id="campo_lineas">
                                    <label class="FAL">
                                        <span class="FAS">Línea</span>
                                        <select class="FAI prueba" name="Lineas" id="lineas">
                                            <option value="0">Seleccione la línea:</option>
                                        </select>
                                    </label>
                                </div>

                                <div class=Center2>
                                    <button class="BotonAgregar" type="button" id="btnAgregarMezcla"><i class="fa-solid fa-plus fa-xl" style="color: #ffffffff;"></i></button>
                                    <a title="Mostrar" class="BotonAgregar" onclick="mostrarIDM()"><i class="fa-solid fa-eye fa-xl" style="color: #ffffffff;"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="FAD">
                        <label class="FAL">
                            <span>Lotes</span>
                                <table id="basic-datatables" class="display table table-striped table-hover responsive nowrap">
                                    <thead>
                                        <tr>
                                        <th>Folio de mezcla</th>
                                        <th>Cajas</th>
                                        <th>Linea</th>
                                        <th>Selladora</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                        </tr>
                                    </thead>
                                </table>               
                        </label>

                <div class=Center2>
                    <input class="Boton" id="AB" type="Submit" value="Actualizar" name="Modificar">
                    <?php
                    if (isset($_SESSION['idPallet'])) {
                        $id = $_SESSION['idPallet'];
                        unset($_SESSION['idPallet']); // Limpia después de usar
                    ?>
                        <a id="PDF" title="Mostrar" class="Boton" href="../../Plantillas/Pallets/pdf_pallet.php?id=<?= $id ?>" target="_blank"><i class="fa-solid fa-file-circle-check fa-2xl" style="color: #ffffffff;"></i></a>
                    <?php } else { ?>
                        <a id="linkPdf" title="Mostrar" class="Boton" href="" target="_blank"><i class="fa-solid fa-file-circle-exclamation fa-2xl" style="color: #ffffffff;"></i></a>
                    <?php } ?>
                </div>
            </section>
        </div>

<?php if ($Correcto < 6) {
                $tipos = [
                    'Error' => ['cantidad' => $NumE, 'max' => 6, 'title' => 'Error!', 'type' => 'error'],
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

            window.permisosSeleccionados = {};
            document.querySelectorAll('#tablaPermisos input[type="checkbox"]').forEach(cb => cb.checked = false);
            </script>
        <?php }?>

        <script src="../../../js/modulos.js"></script>
        <script src="../../../js/pallet.js"></script>
        </main>
        
        <?php include '../../../Complementos/Footer.php'; ?>
    </body>
</html>

<script>
    let tablaMezclas;
    
    $(document).ready(function() {
    tablaMezclas = $('#basic-datatables').DataTable({
            serverSide: true,
            ajax: {
                url: '../../Server_side/Pallet/get_mezclas_temp.php?id=<?=$IDP?>',
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
                    { data: 'folio_m' },
                    { data: 'Cajas' },
                    { data: 'linea'},
                    { data: 'selladora'},
                    { data: 'fecha_m',
                        "render": function ( data, type, row ) {
                            if(type === 'display'){
                                // Asumiendo que viene como "yyyy-mm-dd"
                                let partes = row.fecha_m.split('-'); // [yyyy, mm, dd]
                                return partes[2] + '/' + partes[1] + '/' + partes[0]; // dd/mm/yyyy
                            }else{
                                return data;
                            }
                        }
                    },
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

                            if (Eliminar) {
                                return `
                                     ${Eliminar ? `<a title="Eliminar" class="Delete" href="#" data-id="${row.id_pallet_temp}"><i class="fa-solid fa-trash fa-xl" style="color: #ca1212;"></i></a>` : ''}
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
                                { responsivePriority: 1, targets: 5 },
            <?php  } ?>
                                { responsivePriority: 2, targets: 4 },
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