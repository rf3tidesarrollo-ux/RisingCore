<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";
// $Pagina=basename(__FILE__);
// Historial($Pagina,$Con);
$Ver = TienePermiso($_SESSION['ID'], "RRHH/Nominas", 1, $Con);
$Crear = TienePermiso($_SESSION['ID'], "RRHH/Nominas", 2, $Con);
$Editar = TienePermiso($_SESSION['ID'], "RRHH/Nominas", 3, $Con);
$Eliminar = TienePermiso($_SESSION['ID'], "RRHH/Nominas", 4, $Con);

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
    <script src="../../../js/RRHH/nominas.js"></script>
    <script src="../../../js/session.js"></script>
    <link rel="stylesheet" href="DesignNS.css">
    <title>Nominas: Reporte</title>
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
                    <a href="/RisingCore/Modulos/RRHH/Inicio.php" style="color: #6c757d; text-decoration: none;">
                        👥 Recursos Humanos
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="/RisingCore/Modulos/RRHH/Nominas/Inicio.php" style="color: #6c757d; text-decoration: none;">
                        💴 Nominas
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="#" style="color: #6c757d; text-decoration: none;">
                        📋 Reportes
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <strong style="color: #333;">📅 Nomina semanal</strong>
                </nav>
            </div>

            <div class="tabla">
            <?php if ($TipoRol=="ADMINISTRADOR" || $Crear==true) { ?> <a title="Agregar" href="RegistrarNS.php"><div id="wizard" class="btn-up"><i class="fa-solid fa-fingerprint fa-2xl" style="color: #ffffff;"></i></div></a> <?php } ?>
                    
            
            <table id="basic-datatables" class="display table table-striped table-hover responsive nowrap" style="width:95%">
                    <thead>
                        <tr>
                            <th>Sede</th>
                            <th>Empleado</th>
                            <th>Nombre</th>
                            <th>Área</th>
                            <th>Asistencias</th>
                            <th>S/D</th>
                            <th>Horas Extra</th>
                            <th>Horas Trabajadas</th>
                            <th>Bonos</th>
                            <th>Destajos</th>
                            <th>Compensaciones</th>
                        </tr>
                    </thead>
                    
                    <tfoot>
                        <tr>
                            <th>Sede</th>
                            <th>Empleado</th>
                            <th>Nombre</th>
                            <th>Área</th>
                            <th>Asistencias</th>
                            <th>S/D</th>
                            <th>Horas Extra</th>
                            <th>Horas Trabajadas</th>
                            <th>Bonos</th>
                            <th>Destajos</th>
                            <th>Compensaciones</th>
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
            url: '../../Server_side/Nominas/tabla_nominas.php',
            type: 'POST',
            data: function(d) {
                d.ano = $('#filtroAno').val();
                d.semana = $('#filtroSemana').val();
                d.departamento = $('#filtroDepto').val();
                d.tipo = $('#filtroTipo').val();
                d.sede = $('#filtroSede').val();
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
                  { data: 'sede' },
                  { data: 'clave' },
                  { data: 'nombre_completo'}, 
                  { data: 'area' },
                  { data: 'total_dias', orderable: false, searchable: false },
                  {
                    data: 'vino_domingo',
                    render: function (data) {
                        return data == 1
                            ? '<span style="color: red; font-weight: bold;">SI</span>'
                            : 'NO';
                    },
                    orderable: false,
                    searchable: false
                  },
                  {
                    data: 'total_horas_extra',
                    render: function (data) {
                        return parseFloat(data) > 0.5
                            ? '<span style="color: red; font-weight: bold;">' + data + '</span>'
                            : data;
                    },
                    orderable: false,
                    searchable: false
                  },
                  { data: 'total_horas_trabajadas', orderable: false, searchable: false },
                  {
                    data: 'total_bonos',
                    render: function(data, type, row) {
                        let tipo = 1; // 1 = bonos
                        let fecha1 = row.miercoles || '';
                        let fecha2= row.martes || '';

                        let style = (parseFloat(data) > 0)
                                    ? 'color: red; font-weight: bold; cursor: pointer;'
                                    : 'cursor: pointer;';

                        return `<span style="${style}" ondblclick="mostrarDesglose(${row.id_personal}, ${tipo}, '${fecha1}', '${fecha2}')">${data}</span>`;
                    },
                    orderable: false,
                    searchable: false
                  },
                  {
                    data: 'total_destajos',
                    render: function(data, type, row) {
                        let tipo = 2; // 2 = destajos
                        let fecha1 = row.miercoles || '';
                        let fecha2= row.martes || '';

                        let style = (parseFloat(data) > 0)
                                    ? 'color: red; font-weight: bold; cursor: pointer;'
                                    : 'cursor: pointer;';

                        return `<span style="${style}" ondblclick="mostrarDesglose(${row.id_personal}, ${tipo}, '${fecha1}', '${fecha2}')">${data}</span>`;
                    },
                    orderable: false,
                    searchable: false
                  },
                  {
                    data: 'total_compensaciones',
                    render: function(data, type, row) {
                        let tipo = 3; // 3 = compensaciones
                        let fecha1 = row.miercoles || '';
                        let fecha2= row.martes || '';

                        let style = (parseFloat(data) > 0)
                                    ? 'color: red; font-weight: bold; cursor: pointer;'
                                    : 'cursor: pointer;';

                        return `<span style="${style}" ondblclick="mostrarDesglose(${row.id_personal}, ${tipo}, '${fecha1}', '${fecha2}')">${data}</span>`;
                    },
                    orderable: false,
                    searchable: false
                  },
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

            // ===== Agregar botón de "Limpiar filtros" de forma segura
            $('.dt-buttons').append(
                '<select id="filtroAno" class="filtro form-select form-select-sm ms-2" style="width:auto;">' +
                    '<option value="">Año</option>' +
                '</select>' +
                '<select id="filtroSemana" class="filtro form-select form-select-sm ms-2" style="width:auto;">' +
                    '<option value="">Semana</option>' +
                '</select>' +
                '<select id="filtroSede" class="filtro form-select form-select-sm ms-2" style="width:auto;">' +
                    '<option value="">Sede</option>' +
                '</select>' +
                '<select id="filtroDepto" class="filtro form-select form-select-sm ms-2" style="width:auto;">' +
                    '<option value="">Área</option>' +
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
                <button id="exportarPDF" class="btn btn-sm btn-outline-secondary ms-2" title="PDF Nóminas">
                    <i class="fa-solid fa-file-pdf"></i> PDF
                </button>
            `);

            $('.dt-buttons').append(`
                <button id="exportarExcel" class="btn btn-sm btn-outline-secondary ms-2" title="Excel Nóminas">
                    <i class="fa-solid fa-file-excel"></i> Excel
                </button>
            `);

            $('#exportarPDF').on('click', function() {
                let filtros = {
                    ano: $('#filtroAno').val(),
                    semana: $('#filtroSemana').val(),
                    sede : $('#filtroSede'),
                    departamento: $('#filtroDepto').val(),
                    tipo: $('#filtroTipo').val()
                };

                // Abrir en nueva pestaña (o podrías hacer window.location.href)
                let url = '../../Plantillas/RRHH/pdf_ns.php?' + $.param(filtros);
                window.open(url, '_blank');
            });

            $('#exportarExcel').on('click', function() {
                let filtros = {
                    ano: $('#filtroAno').val(),
                    semana: $('#filtroSemana').val(),
                    sede : $('#filtroSede'),
                    departamento: $('#filtroDepto').val(),
                    tipo: $('#filtroTipo').val()
                };

                // Abrir en nueva pestaña (o podrías hacer window.location.href)
                let url = '../../Plantillas/RRHH/excel_ns.php?' + $.param(filtros);
                window.open(url, '_blank');
            });

            // 🔹 Llenar Año
            $.getJSON('../../Server_side/Nominas/filtrosNominas.php?filtro=anos', function(data) {
                data.forEach(row => {
                    $('#filtroAno').append(`<option value="${row.ano}">${row.ano}</option>`);
                });
            });

            // 🔹 Llenar Semanas
            $.getJSON('../../Server_side/Nominas/filtrosNominas.php?filtro=semanas', function(data) {
                data.forEach(row => {
                    $('#filtroSemana').append(`<option value="${row.semana}">${row.semana}</option>`);
                });
            });

            // 🔹 Llenar Sedes
            $.getJSON('../../Server_side/Nominas/filtrosNominas.php?filtro=sedes', function(data) {
                data.forEach(row => {
                    $('#filtroSede').append(`<option value="${row.id_sede}">${row.sede}</option>`);
                });
            });

            // 🔹 Llenar Departamentos
            $.getJSON('../../Server_side/Nominas/filtrosNominas.php?filtro=departamentos', function(data) {
                data.forEach(row => {
                    $('#filtroDepto').append(`<option value="${row.departamento}">${row.departamento}</option>`);
                });
            });

            // 🔹 Llenar Tipos empleados
            $.getJSON('../../Server_side/Nominas/filtrosNominas.php?filtro=tipos', function(data) {
                data.forEach(row => {
                    $('#filtroTipo').append(`<option value="${row.prefijo_te}">${row.tipo_rh}</option>`);
                });
            });

            $('#filtroAno, #filtroSemana, #filtroSemana, #filtroDepto, #filtroTipo').on('change', function() {
                api.ajax.reload(); // <-- Recarga la tabla usando los filtros actuales
            });

            // 🔹 Evento de limpiar filtros
            $('#limpiarFiltros').on('click', function () {
                $('#filtroAno, #filtroSemana, #filtroSede, #filtroDepto, #filtroTipo').val('');
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
        "drawCallback": function () {
            let api = this.api();
            if (api.data().count() === 0) {
                let colspan = api.columns().count();
                $('#tabla_incentivos tbody tr td.dataTables_empty')
                    .attr('colspan', colspan);
            }
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