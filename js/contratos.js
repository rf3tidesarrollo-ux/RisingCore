$(document).ready(function () {

    function cargarNombres(sede, dep, nombreSeleccionado = null) {
        const selectNombres = $('#nombres');
        selectNombres.empty().append('<option value="0">Seleccione el nombre:</option>');
        const contenedor = $('#campo_nombres');

        if (!sede || sede === '0' || dep === '0') {
            selectNombres.hide(); // ocultar si no hay sede
            return;
        }

        $.getJSON('../../Server_side/Contrato/get_personal.php?sede=' + sede +'&dep=' + dep, function (data) {
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
    }

});


document.addEventListener("DOMContentLoaded", function () {
    const linkWord = document.getElementById("linkWord");

    linkWord.addEventListener("click", function (e) {
        e.preventDefault(); // Evita el href vacío

        // Obtén los valores de los campos
        const sede = document.getElementById("sede3").value;
        const dep = document.getElementById("departamentos").value;
        const badge = document.getElementById("nombres").value;
        const contrato = document.getElementById("contrato").value;

        // Validación simple
        if (sede === "0") {
            swal("Tiene que seleccionar una sede primero", {
            icon: "warning",
        });
        return;
        }

        if (dep === "0") {
            swal("Tiene que seleccionar un departamento", {
            icon: "warning",
        });
        return;
        }

        if (badge === "0") {
            swal("Tiene que seleccionar un nombre", {
            icon: "warning",
        });
        return;
        }

        if (contrato === "0") {
            swal("Tiene que seleccionar un tipo de contrato", {
            icon: "warning",
        });
        return;
        }

        // Construir URL
        const queryString = new URLSearchParams({
            id: badge,
            tc: contrato,
        }).toString();

        const url = `../../Plantillas/Contratos/word_contrato.php?${queryString}`;
        console.log("URL generada:", url);
        window.open(url, "_blank");
    });
});