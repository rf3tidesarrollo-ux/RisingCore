$(document).ready(function () {

    $('#campo_clasificacion').hide();

    $('#tipo_registro').on('change', function () {
        const tipo = $(this).val();

        if (tipo === '') {
            $('#clasificacion').html('<option value="">Seleccione un tipo de merma primero</option>');
            return;
        }

        $.ajax({
            url: '/RisingCore/Modulos/Server_side/get_clasificaciones.php',
            method: 'GET',
            data: { tipo: tipo },
            success: function (data) {
                $('#clasificacion').html(data);
                $('#campo_clasificacion').show();

                if (clasificacionSeleccionada !== "" && $('#clasificacion option[value="' + clasificacionSeleccionada + '"]').length > 0) {
                    $('#clasificacion').val(clasificacionSeleccionada).trigger('change');
                } else {
                    console.warn("La clasificación seleccionada no está entre las opciones");
                }
            },
            error: function (xhr, status, error) {
                console.error('Error en AJAX:', status, error);
            }
        });
    });
});

$(document).ready(function () {

    $('#campo_naves').hide();

    $('#sedes').on('change', function () {
        const tipo = $(this).val();

        if (tipo === '') {
            $('#naves').html('<option value="">Seleccione una nave primero</option>');
            return;
        }

        $.ajax({
            url: '/RisingCore/Modulos/Server_side/get_naves.php',
            method: 'GET',
            data: { tipo: tipo },
            success: function (data) {
                $('#naves').html(data);
                $('#campo_naves').show();

                if (naveSeleccionada !== "" && $('#naves option[value="' + naveSeleccionada + '"]').length > 0) {
                    $('#naves').val(naveSeleccionada).trigger('change');
                } else {
                    console.warn("La nave seleccionada no está entre las opciones");
                }
            },
            error: function (xhr, status, error) {
                console.error('Error en AJAX:', status, error);
            }
        });
    });
});

$(document).ready(function () {
    const sede = $('#sede').val(); // "RF1", "RF2", etc.

    if (sede && sede !== "0") {
        cargarVariedades(sede, variedadSeleccionada);
    }

    $('#sede').on('change', function () {
        const sede = $(this).val();
        cargarVariedades(sede, null);
    });

    function cargarVariedades(sede, seleccionada) {
        $.ajax({
            url: '../../Server_side/get_codigos.php',
            type: 'GET',
            data: { tipo: sede },
            success: function (res) {
                const select = $('#codigos');
                select.empty();
                select.append('<option value="0">Seleccione la variedad:</option>');

                if (res.status === 'ok') {
                    res.variedades.forEach(function (v) {
                        const selected = (v.id == seleccionada) ? 'selected' : '';
                        select.append(`<option value="${v.id}" ${selected}>${v.codigo}</option>`);
                    });
                } else {
                    select.append('<option value="0">No hay variedades disponibles</option>');
                }
            },
            error: function () {
                $('#codigos').html('<option value="0">Error al cargar variedades</option>');
            }
        });
    }
});

const inputs = document.querySelectorAll('.FAD .FAI');

inputs.forEach(input => {
    input.onfocus = ( ) =>{
        input.previousElementSibling.classList.add('Top');
        input.previousElementSibling.classList.add('Focus');
        input.parentNode.classList.add('Focus');
    }
    input.onblur = ( ) =>{
        input.value = input.value.trim();
        if (input.value.trim().length==0) {
            input.previousElementSibling.classList.remove('Top');
        }
        input.previousElementSibling.classList.remove('Focus');
        input.parentNode.classList.remove('Focus');
    }  
});

function validar() {
    inputs.forEach(input => {
        campo = input.value.trim();
        if (campo.length != 0) {
            input.previousElementSibling.classList.add('Top');
        }
    });
}

function mayus(e) {
    e.value = e.value.toUpperCase();
}

const icon = document.getElementById("eye");
const pass = document.getElementById("2");

if (icon && pass) {
    icon.addEventListener("click", () => {
        if (pass.type === "password") {
            pass.type = "text";
            $('#eye').removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            pass.type = "password";
            $('#eye').removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
}

function mostrarCampo() {
    const tipo = document.getElementById("tipo_registro").value;
    const campoC = document.getElementById("campo_codigos");

    if (tipo === "PRODUCCIÓN") {
        campoC.style.display = "block";
    } else {
        campoC.style.display = "none";
    }
}

function mostrarCampo2() {
    const tipo = document.getElementById("sedes").value;
    const campoN = document.getElementById("campo_naves");

    if (tipo === "1" || tipo === "2" || tipo === "3") {
        campoN.style.display = "block";
    } else {
        campoN.style.display = "none";
    }
}

$(document).ready(function () {
    // Toggle submenu in mobile
    $('.submenu-parent > a').click(function (e) {
        if ($(window).width() < 768) {
            e.preventDefault();
            $(this).siblings('.submenu').slideToggle();
        }
    });
});

// Este fragmento se asegura de volver a cargar las clasificaciones si ya hay una selección previa
$(document).ready(function () {
    const tipoInicial = $('#tipo_registro').val();
    if (tipoInicial !== "0" && tipoInicial !== "") {
        $('#tipo_registro').trigger('change');
    }
});

$(document).ready(function () {
    const tipoInicial = $('#sedes').val();
    if (tipoInicial !== "0" && tipoInicial !== "") {
        $('#sedes').trigger('change');
    }
});

