<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";
// $Pagina=basename(__FILE__);
// Historial($Pagina,$Con);
$Ver = TienePermiso($_SESSION['ID'], "Configuración/Usuarios", 1, $Con);
$Crear = TienePermiso($_SESSION['ID'], "Configuración/Usuarios", 2, $Con);
$Editar = TienePermiso($_SESSION['ID'], "Configuración/Usuarios", 3, $Con);
$Eliminar = TienePermiso($_SESSION['ID'], "Configuración/Usuarios", 4, $Con);

if ($TipoRol=="ADMINISTRADOR" || $Ver==true) {
?>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.colVis.min.js"></script>
    <script src="https://kit.fontawesome.com/367278d2a4.js" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="../../../js/script.js"></script>
    <script src="../../../js/eliminar.js"></script>
    <link rel="stylesheet" href="DesignU.css">
    <title>Configuración: Usuarios</title>
</head>

<body>
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
                        📋 Reportes
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <strong style="color: #333;">📊 Reporte de usuarios</strong>
                </nav>
            </div>

            <div class="tabla">
            <?php if ($TipoRol=="ADMINISTRADOR" || $Crear==true) { ?> <a title="Agregar" href="RegistrarR.php"><div id="wizard" class="btn-up"><i class="fa-solid fa-plus fa-2xl" style="color: #ffffff;"></i></div></a> <?php } ?>
                    
            
            <table id="basic-datatables" class="display table table-striped table-hover responsive nowrap" style="width:95%">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Sede</th>
                            <th>Titular</th>
                            <th>Cargo</th>
                            <th>Departamento</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <?php if ($TipoRol=="ADMINISTRADOR" || $Ver==true || $Editar==true || $Eliminar==true) { ?> <th class="no-export">Acciones</th> <?php } ?> 
                        </tr>
                    </thead>
                    
                    <tfoot>
                        <tr>
                            <th>Usuario</th>
                            <th>Sede</th>
                            <th>Titular</th>
                            <th>Cargo</th>
                            <th>Departamento</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <?php if ($TipoRol=="ADMINISTRADOR" || $Ver==true || $Editar==true || $Eliminar==true) { ?> <th class="no-export">Acciones</th> <?php } ?> 
                        </tr>
                    </tfoot>
                </table>
            </div>
        </main>
        
        <?php include '../../../Complementos/Footer.php'; ?>
    </body>
    
</html>

    <script>
    function truncateText(text, maxLength) {
    return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
    }
    
    $(document).ready(function() {
    $('#basic-datatables').DataTable({
        serverSide: true,
        ajax: {
            url: '../../Server_side/tabla_usuarios.php',
            type: 'POST',
        },
        columns: [
                  { data: 'username' },
                  { data: 'codigo_s' },
                  { data: 'nombre_completo' },
                  { data: 'cargo' },
                  { data: 'departamento' },
                  { data: 'rol' },
                  { data: 'estado',
                    "render": function ( data, type, row ) {
                        if(type === 'display'){
                            let state = row.estado;
                            
                            if (state===1) {
                                state = "ACTIVO";
                                return state;
                            }else{
                                state = "INACTIVO";
                                return state;
                            }
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

                        if (Ver || Editar || Eliminar) {
                            return `
                                ${Ver ? `<a title="Mostrar" href="#${row.id_usuario}" onclick="mostrarRegistroU(${row.id_usuario})"><i class="fa-solid fa-eye fa-xl" style="color: #16ac19;"></i></a>` : ''}
                                ${Editar ? `<a title="Editar" class="Edit" href="EditarU.php?id=${row.id_usuario}"><i class="fa-solid fa-pen-to-square fa-xl" style="color: #0a5ceb;"></i></a>` : ''}
                                ${Eliminar ? `<a title="Eliminar" class="Delete" href="#${row.id_usuario}" onclick="eliminarRegistro(${row.id_usuario})"><i class="fa-solid fa-trash fa-xl" style="color: #ca1212;"></i></a>` : ''}
                            `;
                        } else {
                            return '';
                        }
                    }
                }
                ],
        pageLength: 25,
        lengthMenu: [[10,25,50,100,500],[10,25,50,100,500]],
        stateSave: true,
        responsive: true,
        columnDefs: [
        <?php if ($TipoRol=="ADMINISTRADOR" || $Ver==true || $Editar==true || $Eliminar==true) { ?>
                            { responsivePriority: 1, targets: 7 },
        <?php  } ?>
                            { responsivePriority: 2, targets: 6 },
                            { responsivePriority: 2, targets: 5 },
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
        language: {
            "lengthMenu": "Mostrar _MENU_ registros por página",
            "emptyTable": "No hay datos",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 a 0 de 0 registros",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            search: "Buscar:",
            loadingRecords: "Cargando resultados...",
            "paginate": {
                "first": '<i class="fa-solid fa-backward-step fa-lg"></i>',
                "last": '<i class="fa-solid fa-forward-step fa-lg"></i>',
                "next": '<i class="fa-solid fa-caret-right fa-xl"></i>',
                "previous": '<i class="fa-solid fa-caret-left fa-xl"></i>'
            },
        },
        layout: {
            top1Start: {
        buttons: [
            {
                extend: 'excelHtml5',
                titleAttr: 'Exportar a Excel',
                footer: false,
                className: "btn btn-success btn-border btn-round",
                text: '<i class="fa-solid fa-file-excel fa-2xl" style="color: #1b7c0e;"></i>',
                exportOptions: {
                    columns: ':not(.no-export)',
                },
            },
            {
                extend: 'pdfHtml5',
                pageSize: 'LEGAL',
                footer: false,
                orientation: 'landscape',
                className: "btn btn-danger btn-border btn-round",
                text: '<i class="fa-solid fa-file-pdf fa-2xl" style="color: #c60c0c;"></i>',
                titleAttr: 'Exportar a PDF',
                exportOptions: {
                    columns: ':not(.no-export)',
                    format: {
                        body: function(data, row, column, node) {
                            return truncateText(data, 50);
                        }
                    },
                },    
                customize: function (doc) {
                    doc.content.splice(0, 1);
                    doc.defaultStyle.fontSize = 7;
                    doc.styles.tableHeader.fontSize = 7;
                    doc.styles.tableHeader.fillColor = '#1b7c0e';
                    doc.styles.tableHeader.color = 'white';
                    doc.content[0].table.widths = [
                        '*',
                        '5%', '5%', '4%', '4%', '7%', '3.7%', '4.5%', '4%', '*', 
                        '5%', '4.8%', '7%', '5%', '5%', '4%',
                        '*'
                    ];

                    if (doc.content[0] && doc.content[0].table) {
                    var rowCount = doc.content[0].table.body.length;
                    for (var i = 1; i < rowCount; i++) {
                        var colCount = doc.content[0].table.body[i].length;
                        for (var j = 0; j < colCount; j++) {
                            doc.content[0].table.body[i][j].alignment = 'center';
                        }
                    }
                    doc.content.forEach(function(section) {
                        if (section.table) {
                        section.margin = [0, 0, 0, 0];
                    }
                    });

                    var objLayout = {};
                    objLayout['hLineWidth'] = function(i) { return 0.5; };
                    objLayout['vLineWidth'] = function(i) { return 0.5; };
                    objLayout['hLineColor'] = function(i) { return '#000000'; };
                    objLayout['vLineColor'] = function(i) { return '#000000'; };
                    objLayout['paddingLeft'] = function(i) { return 4; };
                    objLayout['paddingRight'] = function(i) { return 4; };
                    objLayout['paddingTop'] = function(i) { return 2; };
                    objLayout['paddingBottom'] = function(i) { return 2; };
                    doc.content[0].layout = objLayout;
                }
                // Define el encabezado con la imagen
                doc['header'] = function(currentPage, pageCount) {
                    return {
                        image: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAA6IAAAAoCAIAAABrWdDzAAABJmlDQ1BBZG9iZSBSR0IgKDE5OTgpAAAoz2NgYDJwdHFyZRJgYMjNKykKcndSiIiMUmA/z8DGwMwABonJxQWOAQE+IHZefl4qAwb4do2BEURf1gWZxUAa4EouKCoB0n+A2CgltTiZgYHRAMjOLi8pAIozzgGyRZKywewNIHZRSJAzkH0EyOZLh7CvgNhJEPYTELsI6Akg+wtIfTqYzcQBNgfClgGxS1IrQPYyOOcXVBZlpmeUKBhaWloqOKbkJ6UqBFcWl6TmFit45iXnFxXkFyWWpKYA1ULcBwaCEIWgENMAarTQZKAyAMUDhPU5EBy+jGJnEGIIkFxaVAZlMjIZE+YjzJgjwcDgv5SBgeUPQsykl4FhgQ4DA/9UhJiaIQODgD4Dw745AMDGT/0ZOjZcAAAACXBIWXMAAAsTAAALEwEAmpwYAAAJTWlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxNDggNzkuMTY0MDM2LCAyMDE5LzA4LzEzLTAxOjA2OjU3ICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIgeG1sbnM6ZGM9Imh0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIDIxLjAgKFdpbmRvd3MpIiB4bXA6Q3JlYXRlRGF0ZT0iMjAyNC0wOS0wMlQyMToyNDozNi0wNjowMCIgeG1wOk1ldGFkYXRhRGF0ZT0iMjAyNC0wOS0wNVQwMTowNDowNy0wNjowMCIgeG1wOk1vZGlmeURhdGU9IjIwMjQtMDktMDVUMDE6MDQ6MDctMDY6MDAiIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJBZG9iZSBSR0IgKDE5OTgpIiBkYzpmb3JtYXQ9ImltYWdlL3BuZyIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpmOTJhZjhiMC05MjE2LWE5NDgtOTBhNS01OTRkNTg0MGViYTYiIHhtcE1NOkRvY3VtZW50SUQ9ImFkb2JlOmRvY2lkOnBob3Rvc2hvcDoxYzQ0N2U5OS1lYzdkLTRiNDgtOTg5ZS1kNDQzMTM5MzNlZTkiIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDowNDE2ZmI3MS1kYjg1LTk4NDEtOTFjNC0yODA0OTBhZTI2N2IiPiA8cGhvdG9zaG9wOlRleHRMYXllcnM+IDxyZGY6QmFnPiA8cmRmOmxpIHBob3Rvc2hvcDpMYXllck5hbWU9IklOVkVOVEFSSU8iIHBob3Rvc2hvcDpMYXllclRleHQ9IklOVkVOVEFSSU8iLz4gPC9yZGY6QmFnPiA8L3Bob3Rvc2hvcDpUZXh0TGF5ZXJzPiA8eG1wTU06SGlzdG9yeT4gPHJkZjpTZXE+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJjcmVhdGVkIiBzdEV2dDppbnN0YW5jZUlEPSJ4bXAuaWlkOjA0MTZmYjcxLWRiODUtOTg0MS05MWM0LTI4MDQ5MGFlMjY3YiIgc3RFdnQ6d2hlbj0iMjAyNC0wOS0wMlQyMToyNDozNi0wNjowMCIgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWRvYmUgUGhvdG9zaG9wIDIxLjAgKFdpbmRvd3MpIi8+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJzYXZlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDo5MmRjODI3MS1mMTFiLTUwNDQtYmI1My1kNmI2YTJjMTliM2UiIHN0RXZ0OndoZW49IjIwMjQtMDktMDVUMDE6MDQ6MDctMDY6MDAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCAyMS4wIChXaW5kb3dzKSIgc3RFdnQ6Y2hhbmdlZD0iLyIvPiA8cmRmOmxpIHN0RXZ0OmFjdGlvbj0iY29udmVydGVkIiBzdEV2dDpwYXJhbWV0ZXJzPSJmcm9tIGFwcGxpY2F0aW9uL3ZuZC5hZG9iZS5waG90b3Nob3AgdG8gaW1hZ2UvcG5nIi8+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJkZXJpdmVkIiBzdEV2dDpwYXJhbWV0ZXJzPSJjb252ZXJ0ZWQgZnJvbSBhcHBsaWNhdGlvbi92bmQuYWRvYmUucGhvdG9zaG9wIHRvIGltYWdlL3BuZyIvPiA8cmRmOmxpIHN0RXZ0OmFjdGlvbj0ic2F2ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6ZjkyYWY4YjAtOTIxNi1hOTQ4LTkwYTUtNTk0ZDU4NDBlYmE2IiBzdEV2dDp3aGVuPSIyMDI0LTA5LTA1VDAxOjA0OjA3LTA2OjAwIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgMjEuMCAoV2luZG93cykiIHN0RXZ0OmNoYW5nZWQ9Ii8iLz4gPC9yZGY6U2VxPiA8L3htcE1NOkhpc3Rvcnk+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjkyZGM4MjcxLWYxMWItNTA0NC1iYjUzLWQ2YjZhMmMxOWIzZSIgc3RSZWY6ZG9jdW1lbnRJRD0iYWRvYmU6ZG9jaWQ6cGhvdG9zaG9wOmJlY2Y3NDg5LTU0NGQtM2U0MC1hYzhhLTQwM2IzYzVjNjExYSIgc3RSZWY6b3JpZ2luYWxEb2N1bWVudElEPSJ4bXAuZGlkOjA0MTZmYjcxLWRiODUtOTg0MS05MWM0LTI4MDQ5MGFlMjY3YiIvPiA8L3JkZjpEZXNjcmlw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",
                        width: 930,
                        height: 40,
                        margin: [0, 10, 0, 40],
                        alignment: 'center'
                    };
                };
                doc.pageMargins = [50, 60, 50, 50];
            }
            },
            {
                extend: 'colvis',
                text: ['Mostrar/Ocultar'],
            },
        ]
    }
},
    });
});

// document.getElementById("downloadExcel").addEventListener("click", function() {
//     window.location.href = "../Server_side/generarExcel.php";
// });

</script>

<?php } else { header("Location: Inicio.php"); }?>