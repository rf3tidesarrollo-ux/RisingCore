function mostrarIDL() {
    const loteID = $('#lotes').val();

    if (loteID !== "0") {
        mostrarRegistroL(loteID);
    } else {
        swal("Seleccione un lote válido.", {
            icon: "warning",
        });
    }
}

function mostrarRegistroL(id){
  $.ajax({
    url: 'MostrarL.php',
    type: 'POST',
    async: true,
    data: {id:id},
    success: function(response){
      if (response != 'error') {
        var info = JSON.parse(response);
        var nSerie = info.ns; 
        var vCodigo = info.cv;
        var Sede = info.s;
        var vNombre = info.nv;
        var pNombre = info.np;
        var nave = info.i;
        var cCajas = info.cc; 
        var tCaja = info.tc; 
        var pBruto = info.pb; 
        var pTaraje = info.pt;
        var pNeto = info.pn; 
        var F = info.fr;
        var fecha = F.split('-').reverse().join('/');
        var hora = info.hr;
        var rSemana = info.sr;
        var tCarro = info.tc;
        var nTarima = info.nt;
        var cTarima = info.ct;

        swal("Información:", 
        "NO. SERIE: " + nSerie + "\n" +
        "CÓDIGO: " + vCodigo + "\n" +
        "SEDE: " + Sede + "\n" +
        "VARIEDAD: " + vNombre + "\n" +
        "PRESENTACIÓN: " + pNombre + "\n" +
        "NAVE: " + nave + "\n" +
        "CANTIDAD DE CAJAS: " + cCajas + "\n" +
        "TIPO DE CAJA: " + tCaja + "\n" +
        "PESO BRUTO: " + pBruto + "\n" +
        "PESO TARAJE: " + pTaraje + "\n" +
        "PESO NETO: " + pNeto + "\n" +
        "FECHA DE REGISTRO: " + fecha + "\n" +
        "HORA: " + hora + "\n" +
        "SEMANA: " + rSemana + "\n" +
        "TIPO DE CARRO: " + tCarro + "\n" +
        "TIPO DE TARIMAS: " + nTarima + "\n" +
        "CANTIDAD DE TARIMAS: " + cTarima);
      }
    },
    error: function(error){
      swal("Error!", {
        icon: "error",
      });
    },
  });
}

window.addEventListener('pagehide', function(event) {
  navigator.sendBeacon('EliminarTodoTemp.php');
});

$('#lotes').on('change', function () {
    const loteId = $(this).val();

    if (loteId && loteId !== "0") {
        $.ajax({
            url: 'Fill_boxes.php',
            type: 'POST',
            dataType: 'json',
            data: { id_lote: loteId },
            success: function (res) {
                if (res.status === 'ok') {
                    $('#CajasD').val(res.cajas_dis);
                } else {
                    $('#CajasD').val('');
                    console.warn(res.message);
                }
            },
            error: function () {
                $('#CajasD').val('');
                console.error('Error al obtener cajas disponibles.');
            }
        });
    } else {
        $('#CajasD').val('');
    }
});

$('#verLoteBtn').on('click', function () {
    const loteID = $('#lotes').val();
    const loteText = $('#lotes option:selected').text();

    if (loteID !== "0") {
        $('#infoLote').html(`<strong>Lote seleccionado:</strong> ${loteText} (ID: ${loteID})`);
    } else {
        $('#infoLote').html(`<span style="color: yellow;">Debe seleccionar un lote.</span>`);
    }
});

$(document).ready(function() {
 $('#btnAgregarLote').on('click', function(e) {

    const loteID = $('#lotes').val();
    const variedadID = $('#variedad').val();
    const sede = $('#sede2').val();
    const cajasM = $('#CajasA').val();
    const cajasDis = $('#CajasD').val();

    if (
    loteID === "0" ||
    variedadID === "0" ||
    sede === "0" ||
    cajasM === "" ||
    isNaN(cajasM) ||
    cajasM <= 0 ||
    parseFloat(cajasM) > parseFloat(cajasDis)
    ) {
        swal("Por favor selecciona sede, variedad, lote y cajas. Las cajas no deben ser mayores que las existentes", { icon: "warning" });
        return;
    }

    $.ajax({
      url: 'AgregarLote.php',
      method: 'POST',
      data: {
        lote: loteID,
        cajas: cajasM,
        addLote: true
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
        $('#cajasT').val(res.total_cajas);
        $('#kilosT').val(parseFloat(res.total_kilos).toFixed(2));
        $('#variedad').val('0').trigger('change');
        $('#lotes').val('0').trigger('change');
        $('#CajasA').val('');
        $('#CajasD').val('');
        tablaLotes.ajax.reload(null, false);

        } else if (res.status === 'duplicate') {
            swal("Este lote ya fue agregado anteriormente", { icon: "warning" });

        } else {
            swal(res.message || "Error al agregar lote", { icon: "error" });
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
            swal("Lote eliminado correctamente", { icon: "success" });
            $('#cajasT').val(res.total_cajas);
            $('#kilosT').val(parseFloat(res.total_kilos).toFixed(2));
            tablaLotes.ajax.reload(null, false);
            tablaLotes.ajax.reload(function() {
            tablaLotes.responsive.recalc();
          }, false);
        } else {
            swal({
                title: "Error al eliminar lote:",
                text: "No puede quedar sin ningún lote el registro",
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