<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";
// $Pagina=basename(__FILE__);
// Historial($Pagina,$Con);
$Ver = TienePermiso($_SESSION['ID'], "RRHH/Asistencia", 1, $Con);
$Crear = TienePermiso($_SESSION['ID'], "RRHH/Asistencia", 2, $Con);
$Editar = TienePermiso($_SESSION['ID'], "RRHH/Asistencia", 3, $Con);
$Eliminar = TienePermiso($_SESSION['ID'], "RRHH/Asistencia", 4, $Con);

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
    <link rel="stylesheet" href="DesignA.css">
    <title>Asistencias: Reporte</title>
</head>

<body>
        <?php
        $basePath = "../";
        $Logo = "../../../";
        $modulo = 'RRHH';
        include('../../../Complementos/Header.php');
        ?>

        <main>
            <div style="background: #f9f9f9; padding: 12px 25px; border-bottom: 1px solid #ccc; font-size: 16px;">
                <nav style="display: flex; flex-wrap: wrap; gap: 5px; align-items: center;">
                    <a href="/RisingCore/Modulos/Empaque/index.php" style="color: #6c757d; text-decoration: none;">
                        👥 Recursos Humanos
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="/RisingCore/Modulos/Empaque/Merma/index.php" style="color: #6c757d; text-decoration: none;">
                        🙋🏻 Asistencias
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="#" style="color: #6c757d; text-decoration: none;">
                        📋 Reportes
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <strong style="color: #333;">🗒️ Listas de asistencia</strong>
                </nav>
            </div>

            <div class="tabla">
            <?php if ($TipoRol=="ADMINISTRADOR" || $Crear==true) { ?> <a title="Agregar" href="RegistrarNI.php"><div id="wizard" class="btn-up"><i class="fa-solid fa-fingerprint fa-2xl" style="color: #ffffff;"></i></div></a> <?php } ?>
                    
            
            <table id="basic-datatables" class="display table table-striped table-hover responsive nowrap" style="width:95%">
                    <thead>
                        <tr>
                            <th>Sede</th>
                            <th>Empleado</th>
                            <th>Nombre</th>
                            <th>Departamento</th>
                            <th>Lunes</th>
                            <th>Martes</th>
                            <th>Miércoles</th>
                            <th>Jueves</th>
                            <th>Viernes</th>
                            <th>Sabado</th>
                            <th>Domingo</th> 
                        </tr>
                    </thead>
                    
                    <tfoot>
                        <tr>
                            <th>Sede</th>
                            <th>Empleado</th>
                            <th>Nombre</th>
                            <th>Departamento</th>
                            <th>Lunes</th>
                            <th>Martes</th>
                            <th>Miércoles</th>
                            <th>Jueves</th>
                            <th>Viernes</th>
                            <th>Sabado</th>
                            <th>Domingo</th> 
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
    var table = $('#basic-datatables').DataTable({
        serverSide: true,
        ajax: {
            url: '../../Server_side/Personal/tabla_asistencia.php',
            type: 'POST',
            data: function(d) {
                d.ano = $('#filtroAno').val();
                d.semana = $('#filtroSemana').val();
                d.departamento = $('#filtroDepto').val();
                d.tipo = $('#filtroTipo').val();
            }
        },
        columns: [
                  { data: 'codigo_s' },
                  { data: 'empleado' },
                  { data: 'nombre_completo'}, 
                  { data: 'departamento' },
                  { data: 'lunes', },
                  { data: 'martes', },
                  { data: 'miercoles'},
                  { data: 'jueves' },
                  { data: 'viernes' },
                  { data: 'sabado' },
                  { data: 'domingo' },
                ],
        pageLength: 25,
        lengthMenu: [[10,25,50,100,500],[10,25,50,100,500]],
        stateSave: true,
        responsive: true,
        columnDefs: [
                        { responsivePriority: 2, targets: 10 },
                        { responsivePriority: 2, targets: 9 },
                        { responsivePriority: 2, targets: 8 },
                        { responsivePriority: 2, targets: 7 },
                        { responsivePriority: 2, targets: 6 },
                        { responsivePriority: 2, targets: 5 },
                        { responsivePriority: 2, targets: 4 },
                        { responsivePriority: 3, targets: 3 },
                        { responsivePriority: 1, targets: 2 },
                        { responsivePriority: 1, targets: 1 },
                        { responsivePriority: 3, targets: 0 }
                    ],
        fixedColumns: true,
        autoWidth: true,
        initComplete: function () {
            const api = this.api();

            // ===== Agregar inputs/selects en tfoot
            // api.columns().every(function () {
            //     const column = this;
            //     const title = $(column.footer()).text().trim();

            //     if (title !== "Acciones") {
            //         $(column.footer()).empty();

            //         const selectColumns = [0, 3]; // columnas con select
            //         const dateColumns = []; // columna fecha

            //         if (selectColumns.includes(column.index())) {
            //             const select = $('<select><option value="">Todos</option></select>')
            //                 .appendTo($(column.footer()))
            //                 .on('change', function () {
            //                     const val = $.fn.dataTable.util.escapeRegex($(this).val());
            //                     column.search(val ? '^' + val + '$' : '', true, false).draw();
            //                 });

            //             // Llenar las opciones del select vía AJAX
            //             $.ajax({
            //                 url: '../../Server_side/Personal/vuAsistencia.php', // tu endpoint PHP
            //                 type: 'POST',
            //                 data: { columna: column.index() }, // le mandas qué columna quieres
            //                 dataType: 'json',
            //                 success: function (data) {
            //                     data.forEach(function (item) {
            //                         select.append('<option value="' + item + '">' + item + '</option>');
            //                     });
            //                 }
            //             });

            //         } else if (dateColumns.includes(column.index())) {
            //             $('<input type="date" />')
            //                 .appendTo($(column.footer()))
            //                 .on('change', function () {
            //                     column.search(this.value).draw();
            //                 });

            //         } else {
            //             $('<input type="text" placeholder="Buscar ' + title + '" />')
            //                 .appendTo($(column.footer()))
            //                 .on('keyup change clear', function () {
            //                     if (column.search() !== this.value) {
            //                         column.search(this.value).draw();
            //                     }
            //                 });
            //         }
            //     }
            // });

            // ===== Agregar botón de "Limpiar filtros" de forma segura
            $('.dt-buttons').append(
                '<select id="filtroAno" class="filtro form-select form-select-sm ms-2" style="width:auto;">' +
                    '<option value="">Año</option>' +
                '</select>' +
                '<select id="filtroSemana" class="filtro form-select form-select-sm ms-2" style="width:auto;">' +
                    '<option value="">Semana</option>' +
                '</select>' +
                '<select id="filtroDepto" class="filtro form-select form-select-sm ms-2" style="width:auto;">' +
                    '<option value="">Departamento</option>' +
                '</select>' +
                '<select id="filtroTipo" class="filtro form-select form-select-sm ms-2" style="width:auto;">' +
                    '<option value="">Tipo empleado</option>' +
                '</select>' +
                '<button id="limpiarFiltros" class="btn btn-sm btn-outline-secondary ms-2" title="Quitar filtros">' +
                    '<i class="fa-solid fa-filter-circle-xmark fa-xl"></i> Limpiar' +
                '</button>'
            );

            $('.dt-buttons').append(
                '<button id="actualizar" class="btn btn-sm btn-outline-secondary ms-2" title="Actualizar registros">' +
                '<i class="fa-solid fa-arrows-rotate fa-xl"></i> Actualizar</button>'
            );

            $('.dt-buttons').append(`
                <button id="exportarPDF" class="btn btn-sm btn-outline-secondary ms-2">
                    <i class="fa-solid fa-file-pdf"></i> PDF
                </button>
            `);

            $('#exportarPDF').on('click', function() {
                let filtros = {
                    ano: $('#filtroAno').val(),
                    semana: $('#filtroSemana').val(),
                    departamento: $('#filtroDepto').val(),
                    tipo: $('#filtroTipo').val()
                };

                // Abrir en nueva pestaña (o podrías hacer window.location.href)
                let url = '../../Plantillas/RRHH/pdf_la.php?' + $.param(filtros);
                window.open(url, '_blank');
            });

            // 🔹 Llenar Año
            $.getJSON('../../Server_side/Personal/filtrosAsistencia.php?filtro=anos', function(data) {
                data.forEach(row => {
                    $('#filtroAno').append(`<option value="${row.ano}">${row.ano}</option>`);
                });
            });

            // 🔹 Llenar Semanas
            $.getJSON('../../Server_side/Personal/filtrosAsistencia.php?filtro=semanas', function(data) {
                data.forEach(row => {
                    $('#filtroSemana').append(`<option value="${row.semana}">${row.semana}</option>`);
                });
            });

            // 🔹 Llenar Departamentos
            $.getJSON('../../Server_side/Personal/filtrosAsistencia.php?filtro=departamentos', function(data) {
                data.forEach(row => {
                    $('#filtroDepto').append(`<option value="${row.departamento}">${row.departamento}</option>`);
                });
            });

            // 🔹 Llenar Tipos empleados
            $.getJSON('../../Server_side/Personal/filtrosAsistencia.php?filtro=tipos', function(data) {
                data.forEach(row => {
                    $('#filtroTipo').append(`<option value="${row.prefijo_te}">${row.tipo_rh}</option>`);
                });
            });

            $('#filtroAno, #filtroSemana, #filtroDepto, #filtroTipo').on('change', function() {
                api.ajax.reload(); // <-- Recarga la tabla usando los filtros actuales
            });

            // 🔹 Evento de limpiar filtros
            $('#limpiarFiltros').on('click', function () {
                $('#filtroAno, #filtroSemana, #filtroDepto, #filtroTipo').val('');
                table.ajax.reload();
            });

            $('#actualizar').on('click', function () {
                // Opcional: mostrar un mensaje mientras se procesa
                swal("Actualizando...", "Por favor espera mientras se actualizan los registros.", "info");

                $.ajax({
                    url: '../../Server_side/Personal/actualizarRH.php', // ruta a tu script PHP
                    type: 'POST',
                    dataType: 'json', // si tu PHP devuelve JSON
                    success: function(response) {
                        if (response.success) {
                            swal("¡Listo!", "Registros actualizados correctamente.", "success");
                            table.ajax.reload(null, false); // recarga la tabla después de actualizar
                        } else {
                            swal("Error", response.message || "Ocurrió un error al actualizar.", "error");
                        }
                    },
                    error: function(xhr, status, error) {
                        swal("Error", "No se pudo conectar con el servidor.", "error");
                    }
                });
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
                        extend: 'colvis',
                        text: ['Mostrar/Ocultar'],
                    },
                ]
            }
        },
    });
});

</script>

<?php } else { header("Location: Inicio.php"); }?>