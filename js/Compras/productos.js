function mostrarProducto(id){
  $.ajax({
    url: 'MostrarPD.php',
    type: 'POST',
    async: true,
    data: {id:id},
    success: function(response){
      if (response != 'error') {
        var info = JSON.parse(response);
        const tipo = info.tp || 'N/A';
        const producto = info.p || 'N/A';
        const unidad = info.u || 'N/A';
        const existencias = info.e || 0;
        const UE = info.ue;
        const US = info.us;
        const uEntrada = UE ? UE.split('-').reverse().join('/') : 'N/A';
        const uSalida  = US ? US.split('-').reverse().join('/') : 'N/A';
        const uProve = info.up || 'N/A';
        const uPrecio = (info.ud != null && info.ud !== "" && info.ud != 0)
        ? '$' + info.ud
        : 'N/A';
        const F = info.fa;
        const fecha = F ? F.split('-').reverse().join('/') : 'N/A';

        swal("Información:", 
        "PRODUCTO: " + producto + "\n" +
        "UNIDAD: " + unidad + "\n" +
         "TIPO DE PRODUCTO: " + tipo + "\n" +
        "EXISTENCIAS: " + existencias + "\n" +
        "ULTIMA ENTRADA: " + uEntrada + "\n" +
        "ULTIMA SALIDA: " + uSalida + "\n" +
        "ULTIMO PROVEEDOR: " + uProve + "\n" +
        "ULTIMO PRECIO: " + uPrecio + "\n" +
        "FECHA:" + fecha);
      }
    },
    error: function(error){
      swal("Error!", {
        icon: "error",
      });
    },
  });
}