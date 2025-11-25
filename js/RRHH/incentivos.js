function aprobarRegistro(id){
    swal({
        title: "¿Realmente quiere aprobar el incentivo?",
        text: "",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          window.location.href = "CambiarIC.php?es=1&id=" + id;
          swal("Aprobación completada!", {
            icon: "success",
            button: false,
          });
        } else {
          swal("Se ha cancelado la acción");
        }
      });
}

function rechazarRegistro(id){
    swal({
        title: "¿Realmente quiere rechazar el incentivo?",
        text: "",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          window.location.href = "CambiarIC.php?es=2&id=" + id;
          swal("Rechazo completado!", {
            icon: "success",
            button: false,
          });
        } else {
          swal("Se ha cancelado la acción");
        }
      });
}