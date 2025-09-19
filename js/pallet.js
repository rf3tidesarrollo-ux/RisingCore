$(document).ready(function () {
    $('#sede3').on('change', function () {
        const sede = $('#sede3').val();

        if (sede !== "0") {
            cargarMezclas(sede);
            cargarLineas(sede);
            cargarTarimas(sede);
            cargarPresentaciones(sede);
            $('#datosMezcla').show();
        } else {
            $('#mezclas').html('<option value="0">Seleccione la mezcla:</option>');
            $('#datosMezcla').hide();
        }
    });

    // Función para cargar las mezclas por sede
    function cargarMezclas(sede) {
        $.ajax({
            url: '../../Server_side/get_mezcla.php',
            type: 'GET',
            data: {
                sede: sede,
            },
            success: function (res) {
                const select = $('#mezclas');
                select.empty();
                select.append('<option value="0">Seleccione la mezcla:</option>');

                if (res.status === 'ok') {
                    res.mezclas.forEach(function (m) {
                        select.append(`<option value="${m.id}">${m.mezcla}</option>`);
                    });
                } else {
                    select.append('<option value="0">No hay mezclas disponibles</option>');
                }
            },
            error: function () {
                $('#mezclas').html('<option value="0">Error al cargar mezclas</option>');
            }
        });
    }

    // Función para cargar las líneas por sede
    function cargarLineas(sede) {
        $.ajax({
            url: '../../Server_side/get_lineas.php',
            type: 'GET',
            data: {
                sede: sede,
            },
            success: function (res) {
                const select = $('#lineas');
                select.empty();
                select.append('<option value="0">Seleccione la línea:</option>');

                if (res.status === 'ok') {
                    res.lineas.forEach(function (l) {
                        select.append(`<option value="${l.id}">${l.linea}</option>`);
                    });
                } else {
                    select.append('<option value="0">No hay líneas disponibles</option>');
                }
            },
            error: function () {
                $('#lineas').html('<option value="0">Error al cargar líneas</option>');
            }
        });
    }

    // Función para cargar las mezclas por sede
    function cargarTarimas(sede) {
        $.ajax({
            url: '../../Server_side/get_tarimas.php',
            type: 'GET',
            data: {
                sede: sede,
            },
            success: function (res) {
                const select = $('#tarimas');
                select.empty();
                select.append('<option value="0">Seleccione la tarima:</option>');

                if (res.status === 'ok') {
                    res.tarimas.forEach(function (t) {
                        select.append(`<option value="${t.id}">${t.tarima}</option>`);
                    });
                } else {
                    select.append('<option value="0">No hay tarimas disponibles</option>');
                }
            },
            error: function () {
                $('#tarimas').html('<option value="0">Error al cargar tarimas</option>');
            }
        });
    }

    // Función para cargar las mezclas por sede
    function cargarPresentaciones(sede) {
        $.ajax({
            url: '../../Server_side/get_presentaciones.php',
            type: 'GET',
            data: {
                sede: sede,
            },
            success: function (res) {
                const select = $('#presentaciones');
                select.empty();
                select.append('<option value="0">Seleccione la presentacion:</option>');

                if (res.status === 'ok') {
                    res.presentaciones.forEach(function (t) {
                        select.append(`<option value="${t.id}">${t.presentacion}</option>`);
                    });
                } else {
                    select.append('<option value="0">No hay presentaciones disponibles</option>');
                }
            },
            error: function () {
                $('#presentaciones').html('<option value="0">Error al cargar presentaciones</option>');
            }
        });
    }
});

$('#verMezclaBtn').on('click', function () {
    const mezclaID = $('#mezclas').val();
    const mezclaText = $('#mezclas option:selected').text();

    if (mezclaID !== "0") {
        $('#infoMezcla').html(`<strong>Mezcla seleccionada:</strong> ${mezclaID} (ID: ${mezclaText})`);
    } else {
        $('#infoMezcla').html(`<span style="color: yellow;">Debe seleccionar una mezcla.</span>`);
    }
});

$(document).ready(function() {
 $('#btnAgregarMezcla').on('click', function(e) {

    const mezclaID = $('#mezclas').val();
    const sede = $('#sede3').val();
    const cajasT = $('#cajasT').val();

    if (
    mezclaID === "0" ||
    sede === "0" ||
    cajasT === "" ||
    isNaN(cajasT) ||
    cajasT <= 0
    ) {
        swal("Por favor selecciona sede, mezcla y cajas.", { icon: "warning" });
        return;
    }

    $.ajax({
      url: 'AgregarMezcla.php',
      method: 'POST',
      data: {
        mezcla: mezclaID,
        cajas: cajasT,
        addMezcla: true
      },
      success: function(response) {
      let res;
        try {
            res = typeof response === 'string' ? JSON.parse(response) : response;
        } catch (e) {
            console.error("Error al parsear JSON:", e);
            swal("Respuesta no válida del servidor", { icon: "error" });
            return;
        }

        if (res.status === 'ok') {
        swal("Lote agregado correctamente", { icon: "success" });
        $('#mezclas').val('0').trigger('change');
        $('#cajasT').val('');
        tablaMezclas.ajax.reload(null, false);

        } else if (res.status === 'duplicate') {
            swal("Esta mezcla ya fue agregada anteriormente", { icon: "warning" });

        } else {
            swal(res.message || "Error al agregar mezcla", { icon: "error" });
        }

        }
        });
    });

  // Manejador para eliminar lote temporal desde la tabla
  $('#basic-datatables').on('click', '.Delete', function(e) {
    e.preventDefault();
    const idTemp = $(this).data('id');
    $.ajax({
      url: 'EliminarTemp.php',
      method: 'POST',
      data: { id: idTemp },
      success: function(response) {
        let res;
        try {
            res = typeof response === 'string' ? JSON.parse(response) : response;
        } catch (e) {
            console.error("Error al parsear JSON:", e);
            swal("Respuesta no válida del servidor", { icon: "error" });
            return;
        }

        if (res.status === 'ok') {
            swal("Mezcla eliminada correctamente", { icon: "success" });
            tablaMezclas.ajax.reload(null, false);
            tablaMezclas.ajax.reload(function() {
            tablaMezclas.responsive.recalc();
          }, false);
        } else {
            swal({
                title: "Error al eliminar mezcla:",
                text: "No puede quedar sin ninguna mezcla el registro",
                icon: "error"
            });
        }
        },
      error: function() {
        swal("Error en la eliminación", { icon: "error" });
      }
    });
  });

});

function mostrarIDM() {
    const mezclaID = $('#mezclas').val();

    if (mezclaID !== "0") {
        mostrarRegistroL(mezclaID);
    } else {
        swal("Seleccione una mezcla válido.", {
            icon: "warning",
        });
    }
}

function mostrarRegistroL(id){
  $.ajax({
    url: 'MostrarM.php',
    type: 'POST',
    async: true,
    data: {id:id},
    success: function(response){
      if (response != 'error') {
        var info = JSON.parse(response);
        var mFolio = info.fm;
        var Sede = info.s;
        var cNombre = info.nc;
        var tCajas = info.ct; 
        var tKilos = info.kt;
        var F = info.f;
        var fecha = F.split('-').reverse().join('/');
        var hora = info.h;

        swal("Información:", 
        "FOLIO: " + mFolio + "\n" +
        "SEDE: " + Sede + "\n" +
        "CLIENTE: " + cNombre + "\n" +
        "CAJAS: " + tCajas + "\n" +
        "KILOS: " + tKilos + "\n" +
        "FECHA DE REGISTRO: " + fecha + "\n" +
        "HORA  DE REGISTRO: " + hora);
      }
    },
    error: function(error){
      swal("Error!", {
        icon: "error",
      });
    },
  });
}
