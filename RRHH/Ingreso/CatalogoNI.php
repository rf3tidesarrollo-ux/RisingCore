<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";
// $Pagina=basename(__FILE__);
// Historial($Pagina,$Con);
$Ver = TienePermiso($_SESSION['ID'], "RRHH/Ingreso", 1, $Con);
$Crear = TienePermiso($_SESSION['ID'], "RRHH/Ingreso", 2, $Con);
$Editar = TienePermiso($_SESSION['ID'], "RRHH/Ingreso", 3, $Con);
$Eliminar = TienePermiso($_SESSION['ID'], "RRHH/Ingreso", 4, $Con);

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
    <link rel="stylesheet" href="DesignNI.css">
    <title>Ingreso: Reporte</title>
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
                        🙎🏻 Nuevo Ingreso
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="#" style="color: #6c757d; text-decoration: none;">
                        📋 Reportes
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <strong style="color: #333;">👆🏻 Pendientes de huella</strong>
                </nav>
            </div>

            <div class="tabla">
            <?php if ($TipoRol=="ADMINISTRADOR" || $Crear==true) { ?> <a title="Agregar" href="RegistrarNI.php"><div id="wizard" class="btn-up"><i class="fa-solid fa-fingerprint fa-2xl" style="color: #ffffff;"></i></div></a> <?php } ?>
                    
            
            <table id="basic-datatables" class="display table table-striped table-hover responsive nowrap" style="width:95%">
                    <thead>
                        <tr>
                            <th>Sede</th>
                            <th>Badge</th>
                            <th>Nombre</th>
                            <th>Género</th>
                            <th>Tipo de empleado</th>
                            <th>Departamento</th>
                            <th>Fecha ingreso</th>
                            <th>Fecha registro</th>
                            <th>Registró</th>
                            <?php if ($TipoRol=="ADMINISTRADOR" || $Editar==true || $Eliminar==true) { ?> <th class="no-export">Acciones</th> <?php } ?> 
                        </tr>
                    </thead>
                    
                    <tfoot>
                        <tr>
                            <th>Sede</th>
                            <th>Badge</th>
                            <th>Nombre</th>
                            <th>Género</th>
                            <th>Tipo de empleado</th>
                            <th>Departamento</th>
                            <th>Fecha ingreso</th>
                            <th>Fecha registro</th>
                            <th>Registró</th>
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
            url: '../../Server_side/Personal/tabla_pendientes.php',
            type: 'POST',
        },
        columns: [
                  { data: 'codigo_s' },
                  { data: 'badge' },
                  { data: 'nombre_personal'}, 
                  { data: 'genero' },
                  { data: 'tipo_rh', },
                  { data: 'departamento', },
                  { data: 'fecha_ingreso',
                    "render": function ( data, type, row ) {
                        if(type === 'display'){
                            // Asumiendo que viene como "yyyy-mm-dd"
                            let partes = row.fecha_ingreso.split('-'); // [yyyy, mm, dd]
                            return partes[2] + '/' + partes[1] + '/' + partes[0]; // dd/mm/yyyy
                        }else{
                            return data;
                        }
                    }
                   },
                   { data: 'fecha_registro',
                    "render": function ( data, type, row ) {
                        if(type === 'display'){
                            // Asumiendo que viene como "yyyy-mm-dd"
                            let partes = row.fecha_registro.split('-'); // [yyyy, mm, dd]
                            return partes[2] + '/' + partes[1] + '/' + partes[0]; // dd/mm/yyyy
                        }else{
                            return data;
                        }
                    }
                   },
                  { data: 'nombre_completo' },
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
                                ${Ver ? `<a title="Mostrar" href="#${row.id_personal}" onclick="mostrarRegistroNI(${row.id_personal})"><i class="fa-solid fa-eye fa-xl" style="color: #16ac19;"></i></a>` : ''}
                                ${Editar ? `<a title="Editar" class="Edit" href="EditarNI.php?id=${row.id_personal}"><i class="fa-solid fa-pen-to-square fa-xl" style="color: #0a5ceb;"></i></a>` : ''}
                                ${Eliminar ? `<a title="Eliminar" class="Delete" href="#${row.id_personal}" onclick="eliminarRegistro(${row.id_personal})"><i class="fa-solid fa-trash fa-xl" style="color: #ca1212;"></i></a>` : ''}
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
                            { responsivePriority: 1, targets: 9 },
        <?php  } ?>
                            { responsivePriority: 3, targets: 8 },
                            { responsivePriority: 2, targets: 7 },
                            { responsivePriority: 3, targets: 6 },
                            { responsivePriority: 2, targets: 5 },
                            { responsivePriority: 2, targets: 4 },
                            { responsivePriority: 3, targets: 3 },
                            { responsivePriority: 2, targets: 2 },
                            { responsivePriority: 1, targets: 1 },
                            { responsivePriority: 2, targets: 0 }
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

                    const selectColumns = [0, 3, 4, 5, 8]; // columnas con select
                    const dateColumns = [6, 7]; // columna fecha

                    if (selectColumns.includes(column.index())) {
                        const select = $('<select><option value="">Todos</option></select>')
                            .appendTo($(column.footer()))
                            .on('change', function () {
                                const val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });

                        // 🔹 Llenar las opciones del select vía AJAX
                        $.ajax({
                            url: '../../Server_side/Personal/vuPendientes.php', // tu endpoint PHP
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

            $('.dt-buttons').append(
                '<button id="actualizar" class="btn btn-sm btn-outline-secondary ms-2" title="Actualizar registros">' +
                '<i class="fa-solid fa-arrows-rotate fa-xl"></i> Actualizar</button>'
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
                            api.ajax.reload(); // recarga la tabla después de actualizar
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