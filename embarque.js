$(document).ready(function () {
    function cargarDestinos(sede, destinoSeleccionado = null) {
        if (!sede || sede === '0') {
        $('#destino').empty().append('<option value="0">Seleccione el destino:</option>');
        return;
        }

        $.getJSON('../../Server_side/Embarque/get_destinos.php?sede=' + sede, function (data) {
        const selectDestinos = $('#destino');
        selectDestinos.empty().append('<option value="0">Seleccione el destino:</option>');

        if (data.status === 'ok') {
            data.destinos.forEach(function (d) {
            const selectedAttr = (d.id == destinoSeleccionado) ? 'selected' : '';
            selectDestinos.append(`<option value="${d.id}" ${selectedAttr}>${d.destino}</option>`);
            });
        } else {
            selectDestinos.append('<option value="0">No hay destinos disponibles</option>');
        }
        });
    }

    $('#sede3').on('change', function () {
        const sede = $(this).val();
        cargarDestinos(sede);

        if (sede !== "0") {
            $('#campo_destino').show();
        } else {
            $('#campo_destino').hide();
        }
    });

    // Inicializar si ya hay datos cargados
    const sedeInicial = $('#sede3').val();
    const destinoInicial = $('#destinoSeleccionado').val();

    if (sedeInicial && sedeInicial !== '0') {
        cargarDestinos(sedeInicial, destinoInicial);

        $('campo_destino').show();
    } else {
        $('#campo_destino').hide(); // Oculta datos si no hay selección inicial
    }
});