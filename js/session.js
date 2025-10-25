setInterval(function() {
    $.ajax({
        url: '../../../Login/validar_sesion.php',
        type: 'GET',
        data: { check_session: 1 },
        dataType: 'json',
        success: function(response) {
            if (response.expired) {
                location.href = '../../../index.php';
            }
        },
        error: function() {
            console.warn('No se pudo verificar la sesión.');
        }
    });
}, 60000);