$(document).ready(function () {

    function cargarNombres(sede, dep, nombreSeleccionado = null) {
        const selectNombres = $('#nombres');
        selectNombres.empty().append('<option value="0">Seleccione el nombre:</option>');
        const contenedor = $('#campo_nombres');

        if (!sede || sede === '0' || dep === '0') {
            selectNombres.hide(); // ocultar si no hay sede
            return;
        }

        $.getJSON('../../Server_side/Incidencia/get_nombres.php?sede=' + sede +'&dep=' + dep, function (data) {
            if (data.status === 'ok' && data.nombres.length > 0) {
                data.nombres.forEach(function (n) {
                    const selectedAttr = (n.id == nombreSeleccionado) ? 'selected' : '';
                    selectNombres.append(`<option value="${n.id}" ${selectedAttr}>${n.nombre}</option>`);
                });
                contenedor.show(); // mostrar solo después de cargar todos los nombres
            } else {
                selectNombres.append('<option value="0">No hay nombres disponibles</option>');
                contenedor.hide();
            }
        });
    }

    // Al cambiar la sede
    $('#sede3').on('change', function () {
        $('#campo_departamento').show();
    });

    $('#campo_departamento').on('change', function () {
        const dep = $('#departamentos').val();
        const sede = $('#sede3').val();

        cargarNombres(sede,dep);
    });

    // Inicializar si ya hay datos cargados
    const sedeInicial = $('#sede3').val();
    const depInicial = $('#departamentos').val();
    const nombreInicial = $('#nombreSeleccionado').val();

    if (sedeInicial && depInicial !== '0') {
        $('#campo_departamento').show();
        cargarNombres(sedeInicial, depInicial, nombreInicial);
    } else {
        $('#campo_departamento').hide(); // ocultar si no hay selección inicial
    }

});
