<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";
// $Pagina=basename(__FILE__);
// Historial($Pagina,$Con);
$Ver = TienePermiso($_SESSION['ID'], "Empaque/CamaraFria", 1, $Con);
$Crear = TienePermiso($_SESSION['ID'], "Empaque/CamaraFria", 2, $Con);
$Editar = TienePermiso($_SESSION['ID'], "Empaque/CamaraFria", 3, $Con);
$Eliminar = TienePermiso($_SESSION['ID'], "Empaque/CamaraFria", 4, $Con);

if ($TipoRol=="ADMINISTRADOR" || $Ver==true) {
?>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="../../../js/select.js"></script>
    <script src="../../../js/session.js"></script>
    <link rel="stylesheet" href="DesignCF.css">
    <title>Camára Fría: Registro</title>
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

                    <a href="/RisingCore/Modulos/Empaque/CamaraFria/Inicio.php" style="color: #6c757d; text-decoration: none;">
                        🌡️❄️ Camára Fría
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="#" style="color: #6c757d; text-decoration: none;">
                        📋 Registros
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <strong style="color: #333;">✏️ Registro de pallets en camára fría</strong>
                </nav>
            </div>

            <section class="Registro">
                <h4>Registro de embarque</h4>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" name="octavo" id="">
                    <div class="campos-cajas">
                        <div class="FAD" style="flex: 1;">
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

                    <div class="FAD">
                        <label class="FAL">
                            <span>Lotes</span>
                                <table id="basic-datatables" class="display table table-striped table-hover responsive nowrap">
                                    <thead>
                                        <tr>
                                        <th>Folio de pallet</th>
                                        <th>Presentación</th>
                                        <th>Cajas</th>
                                        <th>Mapeo</th>
                                        <th>Ubicación</th>
                                        <th>Acciones</th>
                                        </tr>
                                    </thead>
                                </table>               
                        </label>
                    </div>

                    <div class=Center2>
                        <!-- <input class="Boton" id="AB" type="Submit" value="Registrar" name="Insertar"> -->
                        <a id="PDFCFF" title="Mapa CF Llena" class="Boton" href="" target="_blank"><i class="fa-solid fa-file-lines fa-2xl" style="color: #ffffffff;"></i></a>
                        <a id="PDFCFV" title="Mapa CF Vacía" class="Boton" href="../../Plantillas/CamaraFria/pdf_cf.php?id=empty" target="_blank"><i class="fa-solid fa-file fa-2xl" style="color: #ffffffff;"></i></a>
                    </div>
              </section>
            
              <script src="../../../js/modulos.js"></script>
              <script src="../../../js/mapa_cf.js"></script>
        </main>
        
        <?php include '../../../Complementos/Footer.php'; ?>
    </body>
    
</html>

<script>
    let tablaPallets;
    
    $(document).ready(function() {
    tablaPallets = $('#basic-datatables').DataTable({
            serverSide: true,
            ajax: {
                url: 'get_pallets.php',
                type: 'POST',
                data: function(d) {
                d.embarque_id = $('#embarques').val(); //enviar embarque seleccionado
                },
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
                    { data: 'folio_p' },
                    { data: 'presentacion' },
                    { data: 'cajas_p'},
                    { data: 'mapeo'},
                    { data: 'ubicacion'},
                    { 
                        data: null,
                        render: function (data, type, row) {
                        return `<a href="#" class="btn btn-primary btn-sm edit-row" style="background: transparent; border: none;"
                                        data-id="${row.id_pallet}"
                                        data-folio="${row.folio_p}"
                                        data-presentacion="${row.presentacion}"
                                        data-cajas="${row.cajas_p}"
                                        data-mapeo="${row.mapeo}"
                                        data-ubicacion="${row.ubicacion}">
                                        <i class="fa-solid fa-pen-to-square fa-xl" style="color: #0a5ceb;"></i>
                                </a>`;
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
                                { responsivePriority: 2, targets: 3 },
                                { responsivePriority: 4, targets: 2 },
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

        $('#embarques').on('change', function() {
            tablaPallets.ajax.reload();
        });
    });

</script>

<?php } else { header("Location: Inicio.php"); }?>