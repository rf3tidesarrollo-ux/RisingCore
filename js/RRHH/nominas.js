function mostrarDesglose(id_personal, tipo, fecha1, fecha2) {
  $.ajax({
    url: '../../Server_side/Nominas/desgloseIncentivos.php',
    type: 'GET',
    data: { id: id_personal, tipo: tipo, fecha1: fecha1, fecha2: fecha2 },
    success: function(response) {
      if (response.status === 'ok') {
        let texto = '';
        response.incentivos.forEach(item => {
          let fechaFormateada = item.fecha.split('-').reverse().join('/'); 
          texto += `${fechaFormateada} - ${item.motivo}: $${item.monto}\n`;
        });
        swal("Detalle de " + response.msg, texto, "info");
      } else if (response.status === 'empty') {
        swal("Detalle de " + response.msg, response.message, "info");
      } else {
        swal("Error", response.message, "error");
      }
    },
    error: function() {
      swal("Error", "No se pudo obtener el detalle.", "error");
    }
  });
}

