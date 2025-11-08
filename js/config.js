function kickUser(idUsuario) {
  swal({
    title: "¿Cerrar sesión del usuario?",
    text: "El usuario será desconectado inmediatamente.",
    icon: "warning",
    buttons: ["Cancelar", "Sí, cerrar sesión"],
    dangerMode: true,
  }).then((willKick) => {
    if (willKick) {
      fetch("KickUser.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `id_usuario=${idUsuario}`,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.ok) {
            swal({
              title: "Sesión finalizada",
              text: "El usuario fue desconectado correctamente.",
              icon: "success",
              buttons: false,
              timer: 2000,
            });
            // refrescar tabla automáticamente
            $('#basic-datatables').DataTable().ajax.reload();
          } else {
            swal({
              title: "Error",
              text: data.msg ?? "No se pudo cerrar la sesión.",
              icon: "error",
            });
          }
        })
        .catch((err) => {
          console.error(err);
          swal({
            title: "Error de conexión",
            text: "No se pudo contactar con el servidor.",
            icon: "error",
          });
        });
    } else {
      swal("Acción cancelada", {
        icon: "info",
        timer: 1500,
        buttons: false,
      });
    }
  });
}
