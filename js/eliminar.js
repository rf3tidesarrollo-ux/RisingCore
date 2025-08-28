function eliminarRegistro(id){
    swal({
        title: "¿Realmente quiere eliminar el registro?",
        text: "",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          window.location.href = "Eliminar.php?id=" + id;
          swal("Eliminación completada!", {
            icon: "success",
            button: false,
          });
        } else {
          swal("Se ha cancelado la acción");
        }
      });
}

function eliminarRegistroC(id,catalogo){
  swal({
      title: "¿Realmente quiere eliminar el registro?",
      text: "",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          url: 'Eliminar.php',
          type: 'POST',
          async: true,
          data: {catalogo:catalogo,id:id},
          success: function(response){
            if (response != 'error') {
              swal("Eliminación completada!", {
                icon: "success",
                button: false,
              });
              location.href ="CatalogoC.php";
            }
          },
          error: function(error){
            swal("Error!", {
              icon: "error",
            });
          },
        });
      } else {
        swal("Se ha cancelado la acción");
      }
    });
}

function mostrarRegistro(id_Inventario){
    $.ajax({
      url: 'MostrarI.php',
      type: 'POST',
      async: true,
      data: {id:id_Inventario},
      success: function(response){
        if (response != 'error') {
          location.reload();
        }
      },
      error: function(error){
        swal("Error!", {
          icon: "error",
        });
      },
    });
}

function mostrarRegistroR(id){
  var nombre = 'nombre';
  var cargo = 'cargo';
    $.ajax({
      url: 'MostrarR.php',
      type: 'POST',
      async: true,
      data: {nombre:nombre,cargo:cargo,id:id},
      success: function(response){
        if (response != 'error') {
          var info = JSON.parse(response);
          nombre = info.nombre+" "+info.apellido_paterno+" "+info.apellido_materno;
          cargo = info.cargo;
          
          swal("Información:", 
          "RESPONSABLE: " + nombre + "\n"+
          "CARGO: " + cargo);
        }
      },
      error: function(error){
        swal("Error!", {
          icon: "error",
        });
      },
    });
}

function mostrarRegistroM(id) {
  $.ajax({
      url: 'MostrarM.php',
      type: 'POST',
      async: true,
      data: { id: id },
      success: function(response) {
          if (response != 'error') {
              var info = JSON.parse(response);
              var folio = info.art; 
              var nombre_ant = info.na + ' ' + info.ap + ' ' + info.am;
              var nombre_act = info.nc + ' ' + info.pc + ' ' + info.mc;
              var edificio_ant = info.edificio_ant;
              var edificio_act = info.edificio_act;
              var area_ant = info.area_ant;
              var area_act = info.area_act;
              var F = info.fecha_mov;
              var fecha = F.split('-').reverse().join('/');
              var estado = info.estado;
                if (estado=="1") {
                  estado = "PENDIENTE";
                }else if(estado=="2"){
                  estado = "ACEPTADO";
                }else{
                  estado = "FINALIZADO";
                }
              var creador = info.creador;
              // switch (estado) {
              //   case "1":
              //     estado = "PENDIENTE";
              //     break;
              //   case "2":
              //     estado = "ACEPTADO";
              //     break;
              //   case "3":
              //     estado = "FINALIZADO";
              //     break;
              // }

              swal("Información:", 
              "FOLIO: " + folio + "\n" +
              "RESPONSABLE ANTERIOR: " + nombre_ant + "\n" +
              "RESPONSABLE ACTUAL: " + nombre_act + "\n" +
              "EDIFICIO ANTERIOR: " + edificio_ant + "\n" +
              "EDIFICIO ACTUAL: " + edificio_act + "\n" +
              "ÁREA ANTERIOR: " + area_ant + "\n" +
              "ÁREA ACTUAL: " + area_act + "\n" +
              "FECHA DEL MOVIMIENTO: " + fecha + "\n" +
              "ESTADO: " + estado + "\n" +
              "CREADOR: " + creador);

          }
      },
      error: function(error) {
          swal("Error!", "Ha ocurrido un error al obtener la información.", "error");
      },
  });
}

function mostrarRegistroA(id) {
  $.ajax({
      url: 'MostrarA.php',
      type: 'POST',
      async: true,
      data: { id: id },
      success: function(response) {
          if (response != 'error') {
              var info = JSON.parse(response);
              var edificio = info.nombre_edificio;
              var responsable = info.nombre + ' ' + info.apellido_paterno + ' ' + info.apellido_materno;
              var area = info.nombre_area;

              swal("Información:",
              "EDIFICIO: " + edificio + "\n" +
              "RESPONSABLE: " + responsable + "\n" +
              "NOMBRE DEL ÁREA: " + area + "\n"
          );

          }
      },
      error: function(error) {
          swal("Error!", "Ha ocurrido un error al obtener la información.", "error");
      },
  });
}

function mostrarRegistroE(id){
    $.ajax({
      url: 'MostrarE.php',
      type: 'POST',
      async: true,
      data: {id:id},
      success: function(response){
        if (response != 'error') {
          var info = JSON.parse(response);
          var nombre = info.nombre_edificio;
          var abreviatura = info.abreviatura_edificio;
          
          swal("Información:", 
          "NOMBRE DEL EDIFICIO: "+ nombre +"\n" + 
          "ABREVIATURA: "+abreviatura);
        }
      },
      error: function(error){
        swal("Error!", {
          icon: "error",
        });
      },
    });
}

function mostrarRegistroP(id){
  $.ajax({
    url: 'MostrarP.php',
    type: 'POST',
    async: true,
    data: {id:id},
    success: function(response){
      if (response != 'error') {
        var info = JSON.parse(response);
        var nombre = info.nombre_proveedor;
        var telefono = info.telefono_proveedor;
        var correo = info.correo_proveedor;
        var estado = info.estado_proveedor;
        var ciudad = info.ciudad_proveedor;
        var calle = info.calle_proveedor;
        var numero = info.numero_proveedor;
        var cp = info.cp_proveedor;
        if (telefono == null) {
          telefono = "SIN TELÉFONO";
        }
        if (correo == null) {
          correo = "SIN CORREO";
        }
        if (numero == 0) {
          numero = "SIN NÚMERO";
        }
        if (cp == 0) {
          cp = "SIN CP";
        }
        swal("Información:", 
        "NOMBRE DEL PROVEEDOR: "+ nombre +"\n" + 
        "TELÉFONO: "+ telefono +"\n" + 
        "CORREO: "+ correo +"\n" + 
        "ESTADO: "+ estado +"\n" + 
        "CIUDAD: "+ ciudad +"\n" + 
        "CALLE: "+ calle +"\n" + 
        "NÚMERO: "+ numero +"\n" + 
        "CÓDIGO POSTAL: "+cp);
      }
    },
    error: function(error){
      swal("Error!", {
        icon: "error",
      });
    },
  });
}

function mostrarRegistroPT(id){
  $.ajax({
    url: 'MostrarPT.php',
    type: 'POST',
    async: true,
    data: {id:id},
    success: function(response){
      if (response != 'error') {
        var info = JSON.parse(response);
        var folio = info.art; 
        var prestador = info.na + ' ' + info.ap + ' ' + info.am;
        var prestatario = info.nc + ' ' + info.pc + ' ' + info.mc;
        var F = info.fecha_pres;
        var fecha = F.split('-').reverse().join('/');
        var FF = info.fecha_fin;
          if (FF!=null) {
            var fechaF = FF.split('-').reverse().join('/');
          }else{
            var fechaF="SIN FINALIZAR";
          }
        var estado = info.estado;
          if (estado=="1") {
            estado = "PENDIENTE";
          }else if(estado=="2"){
            estado = "ACEPTADO";
          }else{
            estado = "FINALIZADO";
          }
        var creador = info.creador;

        swal("Información:", 
        "FOLIO: " + folio + "\n" +
        "PRESTADOR: " + prestador + "\n" +
        "PRESTATARIO: " + prestatario + "\n" +
        "FECHA INICIO: " + fecha + "\n" +
        "FECHA FINALIZADO: " + fechaF + "\n" +
        "Estado: " + estado + "\n" +
        "CREADOR: " + creador);
      }
    },
    error: function(error){
      swal("Error!", {
        icon: "error",
      });
    },
  });
}

function mostrarRegistroCC(id){
    var catalogo = '1';
    $.ajax({
      url: 'MostrarC.php',
      type: 'POST',
      async: true,
      data: {catalogo:catalogo,id:id},
      success: function(response){
        if (response != 'error') {
          var info = JSON.parse(response);
          var condicion = info.condicion;
          
          swal("Información:", "CONDICIÓN: " + condicion);
        }
      },
      error: function(error){
        swal("Error!", {
          icon: "error",
        });
      },
    });
}

function mostrarRegistroCF(id){
  var catalogo = '2';
  $.ajax({
    url: 'MostrarC.php',
    type: 'POST',
    async: true,
    data: {catalogo:catalogo,id:id},
    success: function(response){
      if (response != 'error') {
        var info = JSON.parse(response);
        var familia = info.nombre_familia;
        
        swal("Información:", "FAMILIA: " + familia);
      }
    },
    error: function(error){
      swal("Error!", {
        icon: "error",
      });
    },
  });
}

function mostrarRegistroCM(id){
  var catalogo = '3';
  $.ajax({
    url: 'MostrarC.php',
    type: 'POST',
    async: true,
    data: {catalogo:catalogo,id:id},
    success: function(response){
      if (response != 'error') {
        var info = JSON.parse(response);
        var marca = info.nombre_marca;
        
        swal("Información:", "MARCA: " + marca);
      }
    },
    error: function(error){
      swal("Error!", {
        icon: "error",
      });
    },
  });
}

function mostrarRegistroU(id) {
  $.ajax({
      url: 'MostrarU.php',
      type: 'POST',
      async: true,
      data: { id: id },
      success: function(response) {
          if (response != 'error') {
              var info = JSON.parse(response);
              var user = info.user;
              var rol = info.rol;
              var area = info.nombre_area;
              var status = info.estado;
              if (status=="0") {
                status="INACTIVO";
              }else{
                status="ACTIVO";
              }
              swal("Información:",
              "USUARIO: " + user + "\n" +
              "ROL: " + rol + "\n" +
              "NOMBRE DEL ÁREA: " + area + "\n" +
              "STATUS: " + status);

          }
      },
      error: function(error) {
          swal("Error!", "Ha ocurrido un error al obtener la información.", "error");
      },
  });
}