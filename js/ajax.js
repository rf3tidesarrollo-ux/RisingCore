$(document).ready(function () {

    $('#campo_clasificacion').hide();

    $('#tipo_registro').on('change', function () {
        const tipo = $(this).val();

        if (tipo === '') {
            $('#clasificacion').html('<option value="">Seleccione un tipo de merma primero</option>');
            return;
        }

        $.ajax({
            url: '/RisingCore/Modulos_empaque/Registro_empaque/get_clasificaciones.php',
            method: 'GET',
            data: { tipo: tipo },
            success: function (data) {
                $('#clasificacion').html(data);
                $('#campo_clasificacion').show(); 
            },
            error: function (xhr, status, error) {
                console.error('Error en AJAX:', status, error);
            }
        });
    });
});

$(document).ready(function () {

    $('#campo_naves').hide();

    $('#tipo_registro').on('change', function () {
        const tipo = $(this).val();

        if (tipo === '') {
            $('#naves').html('<option value="">Seleccione una nave primero</option>');
            return;
        }

        $.ajax({
            url: '/RisingCore/Modulos_empaque/Registro_empaque/get_naves.php',
            method: 'GET',
            data: { tipo: tipo },
            success: function (data) {
                $('#naves').html(data);
                $('#campo_naves').show(); 
            },
            error: function (xhr, status, error) {
                console.error('Error en AJAX:', status, error);
            }
        });
    });
});

function mostrarCampo() {
    const tipo = document.getElementById("tipo_registro").value;
    const campoC = document.getElementById("campo_codigo");

    if (tipo === "A") {
        campoC.style.display = "block";
    } else {
        campoC.style.display = "none";
    }
}

function mostrarCampo2() {
    const tipo = document.getElementById("tipo_registro").value;
    const campoN = document.getElementById("campo_naves");

    if (tipo === "1" || tipo === "2" || tipo === "3") {
        campoN.style.display = "block";
    } else {
        campoN.style.display = "none";
    }
}