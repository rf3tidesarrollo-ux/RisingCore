window.addEventListener('pagehide', function(e) {
  navigator.sendBeacon('EliminarTodoTemp.php');
});

$(document).ready(function () {
    // Función para cargar las mezclas por sede
    function cargarMezclas(sede) {
        $.ajax({
            url: '../../Server_side/Pallet/get_mezcla.php',
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

    function cargarLineas(sede) {
        $.ajax({
            url: '../../Server_side/Pallet/get_lineas.php',
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

    function cargarTarimas(sede, tarimaSeleccionada = null) {
        if (!sede || sede === '0') {
        $('#tarimas').empty().append('<option value="0">Seleccione la tarima:</option>');
        return;
        }

        $.getJSON('../../Server_side/Pallet/get_tarimas.php?sede=' + sede, function (data) {
        const selectTarimas = $('#tarimas');
        selectTarimas.empty().append('<option value="0">Seleccione la tarima:</option>');

        if (data.status === 'ok') {
            data.tarimas.forEach(function (t) {
            const selectedAttr = (t.id == tarimaSeleccionada) ? 'selected' : '';
            selectTarimas.append(`<option value="${t.id}" ${selectedAttr}>${t.tarima}</option>`);
            });
        } else {
            selectTarimas.append('<option value="0">No hay tarimas disponibles</option>');
        }
        });
    }

    function cargarEmbarques(sede, embarqueSeleccionado = null) {
        if (!sede || sede === '0') {
        $('#embarques').empty().append('<option value="0">Seleccione el embarque:</option>');
        return;
        }

        $.getJSON('../../Server_side/Pallet/get_embarques.php?sede=' + sede, function (data) {
        const selectEmbarques = $('#embarques');
        selectEmbarques.empty().append('<option value="0">Seleccione el embarque:</option>');

        if (data.status === 'ok') {
            data.embarques.forEach(function (e) {
            const selectedAttr = (e.id == embarqueSeleccionado) ? 'selected' : '';
            selectEmbarques.append(`<option value="${e.id}" ${selectedAttr}>${e.embarque}</option>`);
            });
        } else {
            selectEmbarques.append('<option value="0">No hay embarques disponibles</option>');
        }
        });
    }

    function cargarPresentaciones(sede, presentacionSeleccionada = null) {
        if (!sede || sede === '0') {
        $('#presentaciones').empty().append('<option value="0">Seleccione la presentación:</option>');
        return;
        }

        $.getJSON('../../Server_side/Pallet/get_presentaciones.php?sede=' + sede, function (data) {
        const selectPresentacion = $('#presentaciones');
        selectPresentacion.empty().append('<option value="0">Seleccione la presentación:</option>');

        if (data.status === 'ok') {
            data.presentaciones.forEach(function (p) {
            const selectedAttr = (p.id == presentacionSeleccionada) ? 'selected' : '';
            selectPresentacion.append(`<option value="${p.id}" ${selectedAttr}>${p.presentacion}</option>`);
            });
        } else {
            selectPresentacion.append('<option value="0">No hay presentaciones disponibles</option>');
        }
        });
    }

    $('#sede3').on('change', function () {
        const sede = $(this).val();
        cargarTarimas(sede);
        cargarEmbarques(sede);
        cargarPresentaciones(sede);
        cargarLineas(sede);
        cargarMezclas(sede);

        if (sede !== "0") {
            $('#datosMezcla').show();
        } else {
            $('#datosMezcla').hide();
        }
    });

    // Inicializar si ya hay datos cargados
    const sedeInicial = $('#sede3').val();
    const presentacionInicial = $('#presentacionSeleccionada').val();
    const tarimaInicial = $('#tarimaSeleccionada').val();
    const embarqueInicial = $('#embarqueSeleccionado').val();

    if (sedeInicial && sedeInicial !== '0') {
        cargarTarimas(sedeInicial, tarimaInicial);
        cargarEmbarques(sedeInicial, embarqueInicial);
        cargarPresentaciones(sedeInicial, presentacionInicial);
        cargarLineas(sedeInicial);
        cargarMezclas(sedeInicial);
        $('datosMezcla').show();
    } else {
        $('#datosMezcla').hide(); // Oculta datos si no hay selección inicial
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
    const lineas = $('#lineas').val();

    if (
    mezclaID === "0" ||
    sede === "0" ||
    cajasT === "" ||
    lineas === "0" ||
    isNaN(cajasT) ||
    cajasT <= 0
    ) {
        swal("Por favor selecciona sede, mezcla, cajas y linea.", { icon: "warning" });
        return;
    }

    $.ajax({
      url: 'AgregarMezcla.php',
      method: 'POST',
      data: {
        mezcla: mezclaID,
        cajas: cajasT,
        lineas : lineas,
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
        swal("Mezcla agregada correctamente", { icon: "success" });
        $('#mezclas').val('0').trigger('change');
        $('#cajasT').val('');
        $('#lineas').val('0').trigger('change');
        tablaMezclas.ajax.reload(null, false);

        } else if (res.status === 'duplicate') {
            swal("Esta mezcla ya fue agregada anteriormente", { icon: "warning" });

        } else {
            swal(res.message || "Error al agregar mezcla", { icon: "error" });
        }

        }
        });
    });

  // Manejador para eliminar mezcla temporal desde la tabla
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

document.addEventListener("DOMContentLoaded", function () {
    const linkPdf = document.getElementById("linkPdf");

    linkPdf.addEventListener("click", function (e) {
        e.preventDefault(); // Evita el href vacío

        // Obtén los valores de los campos
        const sede = document.getElementById("sede3").value;
        const folio = document.getElementById("folio").value;
        const presentacion = document.getElementById("presentaciones").value;
        const tarima = document.getElementById("tarimas").value;
        const fecha = document.getElementById("Fecha").value;
        const fechaE = document.getElementById("FechaE").value;

        // Validación simple
        if (sede === "0") {
            swal("Tiene que seleccionar una sede primero", {
            icon: "warning",
        });
        return;
        }

        // Construir URL
        const queryString = new URLSearchParams({
            Folio: folio,
            Sede: sede,
            Presentaciones: presentacion,
            Tarima: tarima,
            Fecha: fecha,
            FechaE: fechaE
        }).toString();

        const url = `../../Plantillas/Pallets/pdf_pallet.php?id=mostrar&${queryString}`;
        console.log("URL generada:", url);
        window.open(url, "_blank");
    });
});