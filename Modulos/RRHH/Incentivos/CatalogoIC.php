<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";
// $Pagina=basename(__FILE__);
// Historial($Pagina,$Con);
$Ver = TienePermiso($_SESSION['ID'], "RRHH/Incentivos", 1, $Con);
$Crear = TienePermiso($_SESSION['ID'], "RRHH/Incentivos", 2, $Con);
$Editar = TienePermiso($_SESSION['ID'], "RRHH/Incentivos", 3, $Con);
$Eliminar = TienePermiso($_SESSION['ID'], "RRHH/Incentivos", 4, $Con);

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
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.colVis.min.js"></script>
    <script src="https://kit.fontawesome.com/367278d2a4.js" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="../../../js/script.js"></script>
    <script src="../../../js/eliminar.js"></script>
    <script src="../../../js/RRHH/incentivos.js"></script>
    <script src="../../../js/session.js"></script>
    <link rel="stylesheet" href="DesignIC.css">
    <title>Incentivos: Reporte</title>
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
                    <a href="/RisingCore/Modulos/RRHH/inicio.php" style="color: #6c757d; text-decoration: none;">
                        👥 Recursos Humanos
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="/RisingCore/Modulos/RRHH/Ingreso/Inicio.php" style="color: #6c757d; text-decoration: none;">
                        🪙 Incentivos
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="#" style="color: #6c757d; text-decoration: none;">
                        📋 Reportes
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <strong style="color: #333;">📊 Lista de incentivos</strong>
                </nav>
            </div>
            <div style="background: #f9f9f9; padding: 12px 25px; border-bottom: 1px solid #ccc; font-size: 16px;">
                <nav style="display: flex; flex-wrap: wrap; gap: 5px; align-items: center;">
                    <strong style="color: #333;">🪧 La edición y eliminación de incentivos solo se puede realizar la semana en que se registran</strong>
                </nav>
            </div>

            <div class="tabla">
            <?php if ($TipoRol=="ADMINISTRADOR" || $Crear==true) { ?> <a title="Agregar" href="RegistrarIC.php"><div id="wizard" class="btn-up"><i class="fa-solid fa-fingerprint fa-2xl" style="color: #ffffff;"></i></div></a> <?php } ?>
                    
            
            <table id="basic-datatables" class="display table table-striped table-hover responsive nowrap" style="width:95%">
                    <thead>
                        <tr>
                            <th>Sede</th>
                            <th>Nombre</th>
                            <th>Departamento</th>
                            <th>Tipo de incentivo</th>
                            <th>Fecha</th>
                            <th>Cantidad</th>
                            <th>Motivo</th>
                            <th>Estado</th>
                            <th>Registro</th>
                            <?php if ($TipoRol=="ADMINISTRADOR" || $Editar==true || $Eliminar==true) { ?> <th class="no-export">Acciones</th> <?php } ?> 
                        </tr>
                    </thead>
                    
                    <tfoot>
                        <tr>
                            <th>Sede</th>
                            <th>Nombre</th>
                            <th>Departamento</th>
                            <th>Tipo de incentivo</th>
                            <th>Fecha</th>
                            <th>Cantidad</th>
                            <th>Motivo</th>
                            <th>Estado</th>
                            <th>Registro</th>
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
            url: '../../Server_side/Incentivo/tabla_incentivos.php',
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
                  { data: 'codigo_s' },
                  { data: 'nombre_personal'},
                  { data: 'departamento' },
                  { data: 'tipo_incentivo' },
                  { data: 'fecha_i',
                    "render": function ( data, type, row ) {
                        if(type === 'display'){
                            // Asumiendo que viene como "yyyy-mm-dd"
                            let partes = row.fecha_i.split('-'); // [yyyy, mm, dd]
                            return partes[2] + '/' + partes[1] + '/' + partes[0]; // dd/mm/yyyy
                        }else{
                            return data;
                        }
                    }
                  },
                  { data: 'cantidad'},
                  { data: 'motivo' },
                  { data: 'estado_texto' },
                  { data: 'username' },
                  { 
                    data: null,
                    "render": function (data, type, row) {
                        var Rol = <?php echo json_encode($TipoRol); ?>;
                        var Ver = <?php echo json_encode($Ver); ?>;
                        var Editar = <?php echo json_encode($Editar); ?>;
                        var Eliminar = <?php echo json_encode($Eliminar); ?>;
                        var Especial = true;

                        // ===== Validar semana y año =====
                        const partes = row.fecha_i.split('-');
                        const fecha = new Date(partes[0], partes[1] - 1, partes[2]); // Año, mes, día (correcto)

                        const hoy = new Date();

                       function semanaMiercoles(d) {
                            let f = new Date(d.getFullYear(), d.getMonth(), d.getDate());
                            let diaSemana = f.getDay(); // 0=Domingo, 1=Lunes ... 3=Miércoles

                            // Calcular el miércoles más reciente
                            // Si hoy es miércoles => índice = 0
                            // Si hoy es jueves => retroceder 1
                            // Si hoy es martes => retroceder 6
                            let indice = (diaSemana - 3 + 7) % 7;
                            let inicioSemana = new Date(f);
                            inicioSemana.setDate(f.getDate() - indice);

                            // Ahora obtenemos un número de semana basado en miércoles del año
                            // Sin usar ISO ni semanas desde enero — solo contando MIÉRCOLES
                            let primerMiercoles = new Date(inicioSemana.getFullYear(), 0, 1);
                            while (primerMiercoles.getDay() !== 3) { // 3 → miércoles
                                primerMiercoles.setDate(primerMiercoles.getDate() + 1);
                            }

                            let numeroSemana = Math.floor((inicioSemana - primerMiercoles) / (7 * 86400000)) + 1;

                            return {
                                año: inicioSemana.getFullYear(),
                                semana: numeroSemana
                            };
                        }
                        
                        // Calcular inicio (miércoles) y fin (martes) de semana laboral
                        let inicioSemanaHoy = new Date(hoy);
                        inicioSemanaHoy.setDate(hoy.getDate() - ((hoy.getDay() - 3 + 7) % 7));

                        let finSemanaHoy = new Date(inicioSemanaHoy);
                        finSemanaHoy.setDate(inicioSemanaHoy.getDate() + 6);

                        // Eliminar horas para comparación correcta
                        function soloFecha(d) {
                            return new Date(d.getFullYear(), d.getMonth(), d.getDate());
                        }

                        const estadoAprobado = 2;
                        console.log(row.estado_i, estadoAprobado);
                        const habilitado = soloFecha(fecha) >= soloFecha(inicioSemanaHoy) && soloFecha(fecha) <= soloFecha(finSemanaHoy) && row.estado_i != estadoAprobado;

                        if (!habilitado) {
                            Editar = false;
                            Eliminar = false;
                        }

                        if (Rol === "ADMINISTRADOR") {
                            Ver = true;
                            Editar = true;
                            Eliminar = true;
                        }

                        if (Rol==="CORDINADOR") {
                            Especial = false;
                        }

                        if ((Ver || Editar || Eliminar) && Especial) {
                            return `
                                ${Ver ? `<a title="Mostrar" href="#${row.id_incentivo}" onclick="mostrarRegistroIC(${row.id_incentivo})"><i class="fa-solid fa-eye fa-xl" style="color: #16ac19;"></i></a>` : ''}
                                ${Editar ? `<a title="Editar" class="Edit" href="EditarIC.php?id=${row.id_incentivo}"><i class="fa-solid fa-pen-to-square fa-xl" style="color: #0a5ceb;"></i></a>` : ''}
                                ${Eliminar ? `<a title="Eliminar" class="Delete" href="#${row.id_incentivo}" onclick="eliminarRegistro(${row.id_incentivo})"><i class="fa-solid fa-trash fa-xl" style="color: #ca1212;"></i></a>` : ''}
                            `;
                        } else {
                            return `
                                ${Ver ? `<a title="Mostrar" href="#${row.id_incentivo}" onclick="mostrarRegistroIC(${row.id_incentivo})"><i class="fa-solid fa-eye fa-xl" style="color: #2baaffff;"></i></a>` : ''}
                                ${Editar ? `<a title="Aprobar" class="Edit" href="#${row.id_incentivo}" onclick="aprobarRegistro(${row.id_incentivo})"><i class="fa-solid fa-stamp fa-xl" style="color: #16ac19;"></i></a>` : ''}
                                ${Eliminar ? `<a title="Rechazar" class="Delete" href="#${row.id_incentivo}" onclick="rechazarRegistro(${row.id_incentivo})"><i class="fa-solid fa-stamp fa-xl" style="color: #ca1212;"></i></a>` : ''}
                            `;
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
                            { responsivePriority: 2, targets: 8 },
                            { responsivePriority: 2, targets: 7 },
                            { responsivePriority: 2, targets: 6 },
                            { responsivePriority: 3, targets: 5 },
                            { responsivePriority: 2, targets: 4 },
                            { responsivePriority: 2, targets: 3 },
                            { responsivePriority: 3, targets: 2 },
                            { responsivePriority: 1, targets: 1 },
                            { responsivePriority: 3, targets: 0 }
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

                    const selectColumns = [0, 2, 3, 7]; // columnas con select
                    const dateColumns = [4]; // columna fecha

                    if (selectColumns.includes(column.index())) {
                        const select = $('<select><option value="">Todos</option></select>')
                            .appendTo($(column.footer()))
                            .on('change', function () {
                                const val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });

                        // 🔹 Llenar las opciones del select vía AJAX
                        $.ajax({
                            url: '../../Server_side/Incentivo/vuIncentivos.php', // tu endpoint PHP
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