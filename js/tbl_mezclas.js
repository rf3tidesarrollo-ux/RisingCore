$(document).ready(function () {
  // Mostrar formulario
  $('#btnAgregarMezcla').on('click', function () {
    $('#formMezcla').show();
    $('#mezclaID').val('0');
    $('#cajasSolicitadas').val('');
  });

  // Cancelar
  $('#cancelarMezcla').on('click', function () {
    $('#formMezcla').hide();
  });

  // Guardar mezcla
  $('#guardarMezcla').on('click', function () {
    const mezclaID = $('#mezclaID').val();
    const cajas = $('#cajasSolicitadas').val();

    if (mezclaID === '0' || !cajas || parseInt(cajas) <= 0) {
      alert('Selecciona una mezcla y una cantidad válida');
      return;
    }

    // Obtener datos de la mezcla desde el servidor
    $.ajax({
      url: '../../Server_side/get_lotes.php',
      type: 'GET',
      data: { id: mezclaID },
      dataType: 'json',
      success: function (res) {
        if (res.status === 'ok') {
          const mezcla = res.mezcla;

          const fila = `
            <tr>
              <td>${mezcla.id}</td>
              <td>${mezcla.nombre}</td>
              <td>${mezcla.tipo}</td>
              <td>${cajas}</td>
              <td>
                <button type="button" class="eliminarFila">Eliminar</button>
              </td>

              <!-- Campos ocultos para enviar al backend -->
              <input type="hidden" name="mezclas[]" value="${mezcla.id}">
              <input type="hidden" name="cajas_solicitadas[]" value="${cajas}">
            </tr>
          `;

          $('#tablaMezclas tbody').append(fila);
          $('#formMezcla').hide();
        } else {
          alert('Error al obtener datos de la mezcla.');
        }
      },
      error: function () {
        alert('Error de conexión al cargar datos de mezcla.');
      }
    });
  });

  // Eliminar fila
  $('#tablaMezclas').on('click', '.eliminarFila', function () {
    $(this).closest('tr').remove();
  });
});
