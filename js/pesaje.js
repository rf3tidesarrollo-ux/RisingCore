$(document).ready(function () {
    // Función para cargar las mezclas por sede
    function cargarTrailas(sede, trailaSeleccionada = null) {
        if (!sede || sede === '0') {
        $('#trailas').empty().append('<option value="0">Seleccione la traila:</option>');
        return;
        }

        $.getJSON('../../Server_side/Pesaje/get_trailas.php?sede=' + sede, function (data) {
        const selectTrailas = $('#trailas');
        selectTrailas.empty().append('<option value="0">Seleccione la traila:</option>');

        if (data.status === 'ok') {
            data.carros.forEach(function (c) {
            const selectedAttr = (c.id == trailaSeleccionada) ? 'selected' : '';
            selectTrailas.append(`<option value="${c.id}" ${selectedAttr}>${c.traila}</option>`);
            });
        } else {
            selectTrailas.append('<option value="0">No hay trailas disponibles</option>');
        }
        });
    }

    function cargarTarimas(sede, tarimaSeleccionada = null) {
        if (!sede || sede === '0') {
        $('#tarimas').empty().append('<option value="0">Seleccione la tarima:</option>');
        return;
        }

        $.getJSON('../../Server_side/Pallet/get_tarimas.php?sede=' + sede, function (data) {
        const selectTarimas = $('#tarimas');
        selectTarimas.empty().append('<option value="0">Seleccione la tarima:</option>');

        if (data.status === 'ok') {
            data.tarimas.forEach(function (t) {
            const selectedAttr = (t.id == tarimaSeleccionada) ? 'selected' : '';
            selectTarimas.append(`<option value="${t.id}" ${selectedAttr}>${t.tarima}</option>`);
            });
        } else {
            selectTarimas.append('<option value="0">No hay tarimas disponibles</option>');
        }
        });
    }

    function cargarCajas(sede, cajaSeleccionada = null) {
        if (!sede || sede === '0') {
        $('#cajas').empty().append('<option value="0">Seleccione la caja:</option>');
        return;
        }

        $.getJSON('../../Server_side/Pesaje/get_cajas.php?sede=' + sede, function (data) {
        const selectCajas = $('#cajas');
        selectCajas.empty().append('<option value="0">Seleccione la caja:</option>');

        if (data.status === 'ok') {
            data.cajas.forEach(function (c) {
            const selectedAttr = (c.id == cajaSeleccionada) ? 'selected' : '';
            selectCajas.append(`<option value="${c.id}" ${selectedAttr}>${c.caja}</option>`);
            });
        } else {
            selectCajas.append('<option value="0">No hay cajas disponibles</option>');
        }
        });
    }

    $('#sede').on('change', function () {
        const sede = $(this).val();
        cargarTrailas(sede);
        cargarTarimas(sede);
        cargarCajas(sede);
    });

    // Inicializar si ya hay datos cargados
    const sedeInicial = $('#sede').val();
    const trailaInicial = $('#trailaSeleccionada').val();
    const tarimaInicial = $('#tarimaSeleccionada').val();
    const cajaInicial = $('#cajaSeleccionada').val();

    if (sedeInicial && sedeInicial !== '0') {
        cargarTrailas(sedeInicial, trailaInicial);
        cargarTarimas(sedeInicial, tarimaInicial);
        cargarCajas(sedeInicial, cajaInicial);
    }
});
