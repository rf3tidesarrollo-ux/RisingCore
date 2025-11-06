$(document).ready(function () {

    function cargarNombres(sede, dep, nombreSeleccionado = null) {
        const selectNombres = $('#nombres');
        selectNombres.empty().append('<option value="0">Seleccione el nombre:</option>');
        const contenedor = $('#campo_nombres');

        if (!sede || sede === '0' || dep === '0') {
            selectNombres.hide(); // ocultar si no hay sede
            return;
        }

        $.getJSON('../../Server_side/Personal/get_nombres.php?sede=' + sede +'&dep=' + dep, function (data) {
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

    function cargarMunicipios(sede, municipioSeleccionado = null) {
        const selectMunicipio = $('#municipio');
        selectMunicipio.empty().append('<option value="0">Seleccione el municipio:</option>');
        const contenedor = $('#campo_municipio');

        if (!sede || sede === '0') {
            selectMunicipio.hide(); // ocultar si no hay sede
            return;
        }

        $.getJSON('../../Server_side/Personal/get_municipios.php?sede=' + sede, function (data) {
            if (data.status === 'ok' && data.municipios.length > 0) {
                data.municipios.forEach(function (m) {
                    const selectedAttr = (m.id == municipioSeleccionado) ? 'selected' : '';
                    selectMunicipio.append(`<option value="${m.id}" ${selectedAttr}>${m.municipio}</option>`);
                });
                contenedor.show(); // mostrar solo después de cargar todos los nombres
            } else {
                selectNombres.append('<option value="0">No hay municipios disponibles</option>');
                contenedor.hide();
            }
        });
    }

    function cargarRutas(sede, rutaSeleccionada = null) {
        const selectRuta = $('#ruta');
        selectRuta.empty().append('<option value="0">Seleccione la ruta:</option>');
        const contenedor = $('#campo_ruta');

        if (!sede || sede === '0') {
            selectRuta.hide(); // ocultar si no hay sede
            return;
        }

        $.getJSON('../../Server_side/Personal/get_rutas.php?sede=' + sede, function (data) {
            if (data.status === 'ok' && data.rutas.length > 0) {
                data.rutas.forEach(function (r) {
                    const selectedAttr = (r.id == rutaSeleccionada) ? 'selected' : '';
                    selectRuta.append(`<option value="${r.id}" ${selectedAttr}>${r.ruta}</option>`);
                });
                contenedor.show(); // mostrar solo después de cargar todos los nombres
            } else {
                selectRuta.append('<option value="0">No hay rutas disponibles</option>');
                contenedor.hide();
            }
        });
    }

    // Al cambiar la sede
    $('#sede3').on('change', function () {
        const sede = $(this).val();
        $('#campo_departamento').show();
        $('#campo_municipio').show();
        $('#campo_ruta').show();

        cargarMunicipios(sede);
        cargarRutas(sede);
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
    const municipioInicial = $('#municipioSeleccionado').val();
    const rutaInicial = $('#rutaSeleccionada').val();

    if (sedeInicial && depInicial !== '0') {
        $('#campo_departamento').show();

        cargarNombres(sedeInicial, depInicial, nombreInicial);
    } else {
        $('#campo_departamento').hide();
    }

    if (sedeInicial !== '0') {
        $('#campo_municipio').show();
        $('#campo_ruta').show();

        cargarMunicipios(sedeInicial, municipioInicial);
        cargarRutas(sedeInicial, rutaInicial);
    } else {
        $('#campo_municipio').hide();
        $('#campo_ruta').hide();
    }

});
