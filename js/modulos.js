
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

$(document).ready(function () {

    $('#campo_codigos').hide();

    $('#sede').on('change', function () {
        const tipo = $(this).val();

        if (tipo === '') {
            $('#codigos').html('<option value="">Seleccione una variedad primero</option>');
            return;
        }

        $.ajax({
            url: '/RisingCore/Modulos_empaque/Registro_empaque/get_codigos.php',
            method: 'GET',
            data: { tipo: tipo },
            success: function (data) {
                $('#codigos').html(data);
                $('#campo_codigos').show(); 
            },
            error: function (xhr, status, error) {
                console.error('Error en AJAX:', status, error);
            }
        });
    });
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

const pass = document.getElementById("2"), icon = document.querySelector("#eye");

// icon.addEventListener("click", () => {
//     if (pass.type === "password") {
//         pass.type = "text";
//         $('#eye').removeClass('fa-eye').addClass('fa-eye-slash');
//     }else{
//         pass.type = "password";
//         $('#eye').removeClass('fa-eye-slash').addClass('fa-eye');
//     }
// });

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

$(document).ready(function () {
    // Toggle submenu in mobile
    $('.submenu-parent > a').click(function (e) {
        if ($(window).width() < 768) {
            e.preventDefault();
            $(this).siblings('.submenu').slideToggle();
        }
    });
});


