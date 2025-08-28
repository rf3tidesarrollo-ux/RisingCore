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

icon.addEventListener("click", () => {
    if (pass.type === "password") {
        pass.type = "text";
        $('#eye').removeClass('fa-eye').addClass('fa-eye-slash');
    }else{
        pass.type = "password";
        $('#eye').removeClass('fa-eye-slash').addClass('fa-eye');
    }
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

$(document).ready(function () {
  $('#tipo_registro').on('change', function () {
    console.log("Cambio detectado - tipo:", $(this).val());
  });
});

$(document).ready(function () {
    $('#campo_clasificacion').hide(); // Ocultar al inicio

    $('#tipo_registro').on('change', function () {
        const tipo = $(this).val();

        if (tipo !== '') {
            // Mostrar el campo de clasificación
            $('#campo_clasificacion').show();

            // Llamada AJAX
            $.ajax({
                url: 'get_clasificaciones.php',
                type: 'POST', // <-- Usa POST aquí
                data: { tipo: tipo },
                success: function (data) {
                    try {
                        const clasificaciones = JSON.parse(data);
                        let opciones = '<option value="">Seleccione la clasificación</option>';

                        clasificaciones.forEach(function (item) {
                            opciones += `<option value="${item.id_clasificacion}">${item.motivo}</option>`;
                        });

                        $('#clasificacion').html(opciones);
                    } catch (e) {
                        console.error("Respuesta no es JSON:", data);
                        $('#clasificacion').html('<option value="">Error al cargar clasificaciones</option>');
                    }
                },
                error: function () {
                    alert('Error al obtener las clasificaciones.');
                }
            });
        } else {
            // Ocultar y resetear el select
            $('#campo_clasificacion').hide();
            $('#clasificacion').html('<option value="">Seleccione un tipo de merma primero</option>');
        }
    });
});


