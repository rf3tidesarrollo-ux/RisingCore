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
