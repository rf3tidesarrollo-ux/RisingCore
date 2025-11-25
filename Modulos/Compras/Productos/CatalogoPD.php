<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";
// $Pagina=basename(__FILE__);
// Historial($Pagina,$Con);
$Ver = TienePermiso($_SESSION['ID'], "Compras/Productos", 1, $Con);
$Crear = TienePermiso($_SESSION['ID'], "Compras/Productos", 2, $Con);
$Editar = TienePermiso($_SESSION['ID'], "Compras/Productos", 3, $Con);
$Eliminar = TienePermiso($_SESSION['ID'], "Compras/Productos", 4, $Con);

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
    <script src="../../../js/session.js"></script>
    <link rel="stylesheet" href="DesignPD.css">
    <title>Ingresos: Reporte</title>
</head>

<body>
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

                    <a href="/RisingCore/Modulos/Compras/Productos/Inicio.php" style="color: #6c757d; text-decoration: none;">
                        📦🆕 Productos
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <a href="#" style="color: #6c757d; text-decoration: none;">
                        📋 Reportes
                    </a>
                    <span style="color: #6c757d;">&raquo;</span>

                    <strong style="color: #333;">📊 Catálogo de productos</strong>
                </nav>
            </div>

            <div class="tabla">
            
            <table id="basic-datatables" class="display table table-striped table-hover responsive nowrap" style="width:95%">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Unidad</th>
                            <th>Tipo</th>
                            <th>Existencias</th>
                            <th>Ultima entrada</th>
                            <th>Ultima salida</th>
                            <th>Ultimo proveedor</th>
                            <th>Ultimo precio</th>
                            <th>Agregado</th>
                            <th>Usuario</th>
                            <?php if ($TipoRol=="ADMINISTRADOR" || $Ver=true || $Editar==true || $Eliminar==true) { ?> <th class="no-export">Acciones</th> <?php } ?> 
                        </tr>
                    </thead>
                    
                    <tfoot>
                        <tr>
                            <th>Producto</th>
                            <th>Unidad</th>
                            <th>Tipo</th>
                            <th>Existencias</th>
                            <th>Ultima entrada</th>
                            <th>Ultima salida</th>
                            <th>Ultimo proveedor</th>
                            <th>Ultimo precio</th>
                            <th>Agregado</th>
                            <th>Usuario</th>
                            <?php if ($TipoRol=="ADMINISTRADOR" || $Ver=true || $Editar==true || $Eliminar==true) { ?> <th class="no-export">Acciones</th> <?php } ?> 
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
            url: '../../Server_side/Productos/tabla_productos.php',
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
                  { data: 'producto'},
                  { data: 'unidad' },
                  { data: 'tipo_producto'},
                  { data: 'existencias'}, 
                  { data: 'u_entrada',
                    render: function (data, type, row) {
                        if (!row.u_entrada || row.u_entrada === null || row.u_entrada === "0000-00-00") {
                            return "N/A";
                        }

                        if(type === 'display'){
                            // Asumiendo que viene como "yyyy-mm-dd"
                            let partes = row.u_entrada.split('-'); // [yyyy, mm, dd]
                            return partes[2] + '/' + partes[1] + '/' + partes[0]; // dd/mm/yyyy
                        }else{
                            return data;
                        }
                    } 
                  },
                  { data: 'u_salida', 
                    render: function (data, type, row) {
                        if (!row.u_salida || row.u_salida === null || row.u_salida === "0000-00-00") {
                            return "N/A";
                        }

                        if(type === 'display'){
                            // Asumiendo que viene como "yyyy-mm-dd"
                            let partes = row.u_salida.split('-'); // [yyyy, mm, dd]
                            return partes[2] + '/' + partes[1] + '/' + partes[0]; // dd/mm/yyyy
                        }else{
                            return data;
                        }
                    } 
                  },
                  { data: 'u_proveedor', },
                  { data: 'u_precio',
                    render: function (data, type, row) {
                        // Detectar precio vacío o inválido
                        if (!row.u_precio || row.u_precio === "0" || row.u_precio === 0) {
                            return "N/A";
                        }
                        // Mostrar con signo $
                        if (type === 'display') {
                            return '$' + parseFloat(row.u_precio).toFixed(2); 
                        }
                        return row.u_precio;
                    } 
                  },
                  { data: 'fecha_a',
                    "render": function ( data, type, row ) {
                        if (!row.fecha_a || row.fecha_a === null || row.fecha_a === "0000-00-00") {
                            return "N/A";
                        }

                        if(type === 'display'){
                            // Asumiendo que viene como "yyyy-mm-dd"
                            let partes = row.fecha_a.split('-'); // [yyyy, mm, dd]
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
                                ${Ver ? `<a title="Mostrar" href="#${row.id_producto}" onclick="mostrarProducto(${row.id_producto})"><i class="fa-solid fa-eye fa-xl" style="color: #16ac19;"></i></a>` : ''}
                                ${Editar ? `<a title="Editar" class="Edit" href="EditarPD.php?id=${row.id_producto}"><i class="fa-solid fa-pen-to-square fa-xl" style="color: #0a5ceb;"></i></a>` : ''}
                                ${Eliminar ? `<a title="Eliminar" class="Delete" href="#${row.id_producto}" onclick="eliminarRegistro(${row.id_producto})"><i class="fa-solid fa-trash fa-xl" style="color: #ca1212;"></i></a>` : ''}
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
                            { responsivePriority: 1, targets: 10 },
        <?php  } ?>
                            { responsivePriority: 3, targets: 9 },
                            { responsivePriority: 3, targets: 8 },
                            { responsivePriority: 2, targets: 7 },
                            { responsivePriority: 2, targets: 6 },
                            { responsivePriority: 1, targets: 5 },
                            { responsivePriority: 1, targets: 4 },
                            { responsivePriority: 2, targets: 3 },
                            { responsivePriority: 2, targets: 2 },
                            { responsivePriority: 1, targets: 1 },
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

                    const selectColumns = [1, 2, 9]; // columnas con select
                    const dateColumns = [4, 5, 8]; // columna fecha

                    if (selectColumns.includes(column.index())) {
                        const select = $('<select><option value="">Todos</option></select>')
                            .appendTo($(column.footer()))
                            .on('change', function () {
                                const val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });

                        // 🔹 Llenar las opciones del select vía AJAX
                        $.ajax({
                            url: '../../Server_side/Productos/vuProductos.php', // tu endpoint PHP
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