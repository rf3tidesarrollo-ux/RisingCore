<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";
// $Pagina=basename(__FILE__);
// Historial($Pagina,$Con);
$Ver = TienePermiso($_SESSION['ID'], "Empaque/Pesaje", 1, $Con);
$Crear = TienePermiso($_SESSION['ID'], "Empaque/Pesaje", 2, $Con);
$Editar = TienePermiso($_SESSION['ID'], "Empaque/Pesaje", 3, $Con);
$Eliminar = TienePermiso($_SESSION['ID'], "Empaque/Pesaje", 4, $Con);

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
    <link rel="stylesheet" href="DesignM.css">
    <title>Empaque: Reporte</title>
</head>

<body>
        <?php
        $basePath = "../";
        $Logo = "../../../";
        $modulo = 'Empaque';
        include('../../../Complementos/Header.php');
        ?>

        <main>
            <div style="background: #f9f9f9; padding: 12px 25px; border-bottom: 1px solid #ccc; font-size: 16px;">
                <nav style="display: flex; flex-wrap: wrap; gap: 5px; align-items: center;">
                    <a href="/RisingCore/Modulos/Empaque/index.php" style="color: #6c757d; text-decoration: none;">
                        📦 Empaque
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="/RisingCore/Modulos/Empaque/Merma/index.php" style="color: #6c757d; text-decoration: none;">
                        ♻️ Merma
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="#" style="color: #6c757d; text-decoration: none;">
                        📋 Registros
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <strong style="color: #333;">📊 Registros de merma</strong>
                </nav>
            </div>

            <div class="tabla">
            <?php if ($TipoRol=="ADMINISTRADOR" || $Crear==true) { ?> <a title="Agregar" href="RegistrarM.php"><div id="wizard" class="btn-up"><i class="fa-solid fa-plus fa-2xl" style="color: #ffffff;"></i></div></a> <?php } ?>
                    
            
            <table id="basic-datatables" class="display table table-striped table-hover responsive nowrap" style="width:95%">
                    <thead>
                        <tr>
                            <th>Número de serie</th>
                            <th>Clasificación</th>
                            <th>Motivo</th>
                            <th>Traila</th>
                            <th>Tipo de tarima</th>
                            <th>Cant. tarimas</th>
                            <th>Tipo de caja</th>
                            <th>Cant. cajas</th>
                            <th>Peso bruto</th>
                            <th>Peso neto</th>
                            <th>Semana</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <?php if ($TipoRol=="ADMINISTRADOR" || $Editar==true || $Eliminar==true) { ?> <th class="no-export">Acciones</th> <?php } ?> 
                        </tr>
                    </thead>
                    
                    <tfoot>
                        <tr>
                            <th>Número de serie</th>
                            <th>Clasificación</th>
                            <th>Motivo</th>
                            <th>Traila</th>
                            <th>Tipo de tarima</th>
                            <th>Cant. tarimas</th>
                            <th>Tipo de caja</th>
                            <th>Cant. cajas</th>
                            <th>Peso bruto</th>
                            <th>Peso neto</th>
                            <th>Semana</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <?php if ($TipoRol=="ADMINISTRADOR" || $Editar==true || $Eliminar==true) { ?> <th class="no-export">Acciones</th> <?php } ?> 
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
            url: '../../Server_side/Merma/tabla_merma.php',
            type: 'POST',
        },
        columns: [
                  { data: 'no_serie_m' },
                  { data: 'tipo_merma' },
                  { data: 'motivo' },
                  { data: 'folio_carro', },
                  { data: 'nombre_tarima', },
                  { data: 'cantidad_tarima', },
                  { data: 'tipo_caja', },
                  { data: 'cantidad_caja' },
                  { data: 'p_bruto' },
                  { data: 'p_neto' },
                  { data: 'semana_m' },
                  { data: 'fecha_reg',
                    "render": function ( data, type, row ) {
                        if(type === 'display'){
                            // Asumiendo que viene como "yyyy-mm-dd"
                            let partes = row.fecha_reg.split('-'); // [yyyy, mm, dd]
                            return partes[2] + '/' + partes[1] + '/' + partes[0]; // dd/mm/yyyy
                        }else{
                            return data;
                        }
                    }
                   },
                  { data: 'hora_m',
                    "render": function ( data, type, row ) {
                        if(type === 'display'){
                            let partes = row.hora_m.split(':'); // ["18","45","20"]
                            let horas = partes[0].padStart(2, '0');
                            let minutos = partes[1].padStart(2, '0');
                            let segundos = partes[2] ? partes[2].padStart(2, '0') : '00';
                            return `${horas}:${minutos}:${segundos}`;
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
                                ${Ver ? `<a title="Mostrar" href="#${row.id_registro_m}" onclick="mostrarRegistroM(${row.id_registro_m})"><i class="fa-solid fa-eye fa-xl" style="color: #16ac19;"></i></a>` : ''}
                                ${Editar ? `<a title="Editar" class="Edit" href="EditarM.php?id=${row.id_registro_m}"><i class="fa-solid fa-pen-to-square fa-xl" style="color: #0a5ceb;"></i></a>` : ''}
                                ${Eliminar ? `<a title="Eliminar" class="Delete" href="#${row.id_registro_m}" onclick="eliminarRegistro(${row.id_registro_m})"><i class="fa-solid fa-trash fa-xl" style="color: #ca1212;"></i></a>` : ''}
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
                            { responsivePriority: 1, targets: 13 },
        <?php  } ?>
                            { responsivePriority: 3, targets: 12 },
                            { responsivePriority: 2, targets: 11 },
                            { responsivePriority: 3, targets: 10 },
                            { responsivePriority: 2, targets: 9 },
                            { responsivePriority: 2, targets: 8 },
                            { responsivePriority: 2, targets: 7 },
                            { responsivePriority: 3, targets: 6 },
                            { responsivePriority: 3, targets: 5 },
                            { responsivePriority: 3, targets: 4 },
                            { responsivePriority: 2, targets: 3 },
                            { responsivePriority: 2, targets: 2 },
                            { responsivePriority: 2, targets: 1 },
                            { responsivePriority: 1, targets: 0 }
                    ],
        fixedColumns: true,
        autoWidth: true,
        initComplete: function () {
            const api = this.api();

            // ===== Agregar inputs/selects en tfoot
            api.columns().every(function () {
                const column = this;
                const title = $(column.footer()).text().trim();

                if (title !== "Acciones") {
                    $(column.footer()).empty();

                    const selectColumns = [1, 2, 3, 4, 6]; // columnas con select
                    const dateColumns = [11]; // columna fecha

                    if (selectColumns.includes(column.index())) {
                        const select = $('<select><option value="">Todos</option></select>')
                            .appendTo($(column.footer()))
                            .on('change', function () {
                                const val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });

                        // 🔹 Llenar las opciones del select vía AJAX
                        $.ajax({
                            url: '../../Server_side/Merma/vuMerma.php', // tu endpoint PHP
                            type: 'POST',
                            data: { columna: column.index() }, // le mandas qué columna quieres
                            dataType: 'json',
                            success: function (data) {
                                data.forEach(function (item) {
                                    select.append('<option value="' + item + '">' + item + '</option>');
                                });
                            }
                        });

                    } else if (dateColumns.includes(column.index())) {
                        $('<input type="date" />')
                            .appendTo($(column.footer()))
                            .on('change', function () {
                                column.search(this.value).draw();
                            });

                    } else {
                        $('<input type="text" placeholder="Buscar ' + title + '" />')
                            .appendTo($(column.footer()))
                            .on('keyup change clear', function () {
                                if (column.search() !== this.value) {
                                    column.search(this.value).draw();
                                }
                            });
                    }
                }
            });

            // ===== Agregar botón de "Limpiar filtros" de forma segura
            $('.dt-buttons').append(
                '<button id="limpiarFiltros" class="btn btn-sm btn-outline-secondary ms-2" title="Quitar filtros">' +
                '<i class="fa-solid fa-filter-circle-xmark fa-xl"></i> Limpiar</button>'
            );

            // Evento para limpiar filtros
            $('#limpiarFiltros').on('click', function () {
                api.search('').draw();
                api.columns().every(function () {
                    this.search('');
                });
                $('#basic-datatables tfoot input, #basic-datatables tfoot select').each(function () {
                    $(this).val('').trigger('change');
                });

                api.draw();
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
            // {
            //     extend: 'excelHtml5',
            //     titleAttr: 'Exportar a Excel',
            //     footer: false,
            //     className: "btn btn-success btn-border btn-round",
            //     text: '<i class="fa-solid fa-file-excel fa-2xl" style="color: #1b7c0e;"></i>',
            //     exportOptions: {
            //         columns: ':not(.no-export)',
            //     },
            // },
            // {
            //     extend: 'pdfHtml5',
            //     pageSize: 'LEGAL',
            //     footer: false,
            //     orientation: 'landscape',
            //     className: "btn btn-danger btn-border btn-round",
            //     text: '<i class="fa-solid fa-file-pdf fa-2xl" style="color: #c60c0c;"></i>',
            //     titleAttr: 'Exportar a PDF',
            //     exportOptions: {
            //         columns: ':not(.no-export)',
            //         format: {
            //             body: function(data, row, column, node) {
            //                 return truncateText(data, 50);
            //             }
            //         },
            //     },    
            //     customize: function (doc) {
            //         doc.content.splice(0, 1);
            //         doc.defaultStyle.fontSize = 7;
            //         doc.styles.tableHeader.fontSize = 7;
            //         doc.styles.tableHeader.fillColor = '#1b7c0e';
            //         doc.styles.tableHeader.color = 'white';
            //         doc.content[0].table.widths = [
            //             '*',
            //             '5%', '5%', '4%', '4%', '7%', '3.7%', '4.5%', '4%', '*', 
            //             '5%', '4.8%', '7%', '5%', '5%', '4%',
            //             '*'
            //         ];

            //         if (doc.content[0] && doc.content[0].table) {
            //         var rowCount = doc.content[0].table.body.length;
            //         for (var i = 1; i < rowCount; i++) {
            //             var colCount = doc.content[0].table.body[i].length;
            //             for (var j = 0; j < colCount; j++) {
            //                 doc.content[0].table.body[i][j].alignment = 'center';
            //             }
            //         }
            //         doc.content.forEach(function(section) {
            //             if (section.table) {
            //             section.margin = [0, 0, 0, 0];
            //         }
            //         });

            //         var objLayout = {};
            //         objLayout['hLineWidth'] = function(i) { return 0.5; };
            //         objLayout['vLineWidth'] = function(i) { return 0.5; };
            //         objLayout['hLineColor'] = function(i) { return '#000000'; };
            //         objLayout['vLineColor'] = function(i) { return '#000000'; };
            //         objLayout['paddingLeft'] = function(i) { return 4; };
            //         objLayout['paddingRight'] = function(i) { return 4; };
            //         objLayout['paddingTop'] = function(i) { return 2; };
            //         objLayout['paddingBottom'] = function(i) { return 2; };
            //         doc.content[0].layout = objLayout;
            //     }
            //     // Define el encabezado con la imagen
            //     doc['header'] = function(currentPage, pageCount) {
            //         return {
            //             width: 930,
            //             height: 40,
            //             margin: [0, 10, 0, 40],
            //             alignment: 'center'
            //         };
            //     };
            //     doc.pageMargins = [50, 60, 50, 50];
            // }
            // },
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