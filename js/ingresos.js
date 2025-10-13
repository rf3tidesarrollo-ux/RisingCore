$(document).ready(function () {

    function cargarHorarios(sede, horarioSeleccionado = null) {
        const selectHorarios = $('#horarios');
        selectHorarios.empty().append('<option value="0">Seleccione el tipo de horario:</option>');
        const contenedor = $('#campo_horarios');

        if (!sede || sede === '0') {
            selectHorarios.hide(); // ocultar si no hay sede
            return;
        }

        $.getJSON('../../Server_side/Personal/get_horarios.php?sede=' + sede, function (data) {
            if (data.status === 'ok' && data.horarios.length > 0) {
                data.horarios.forEach(function (h) {
                    const selectedAttr = (h.id == horarioSeleccionado) ? 'selected' : '';
                    selectHorarios.append(`<option value="${h.id}" ${selectedAttr}>${h.horario}</option>`);
                });
                contenedor.show(); // mostrar solo después de cargar todos los horarios
            } else {
                selectHorarios.append('<option value="0">No hay horarios disponibles</option>');
                contenedor.hide();
            }
        });
    }

    // Al cambiar la sede
    $('#sede3').on('change', function () {
        const sede = $(this).val();
        cargarHorarios(sede);
    });

    // Inicializar si ya hay datos cargados
    const sedeInicial = $('#sede3').val();
    const horarioInicial = $('#horarioSeleccionado').val();

    if (sedeInicial && sedeInicial !== '0') {
        cargarHorarios(sedeInicial, horarioInicial);
    } else {
        $('#horarios').hide(); // ocultar si no hay selección inicial
    }

});
