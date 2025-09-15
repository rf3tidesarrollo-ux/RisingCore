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

function mostrarRegistroR(id){
  $.ajax({
    url: 'MostrarR.php',
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

function mostrarRegistroU(id){
  $.ajax({
    url: 'MostrarU.php',
    type: 'POST',
    async: true,
    data: {id:id},
    success: function(response){
      if (response != 'error') {
        var info = JSON.parse(response);
        var uName = info.un;
        var Sede = info.s;
        var cNombre = info.nc;
        var cargo = info.c;
        var depto = info.d;
        var rol = info.r;
        var state = info.e;
              if (state=="1") {
                state="ACTIVO";
              }else{
                state="INACTIVO";
              }

        swal("Información:", 
        "USUARIO: " + uName + "\n" +
        "SEDE: " + Sede + "\n" +
        "TITULAR: " + cNombre + "\n" +
        "CARGO: " + cargo + "\n" +
        "DEPARTAMENTO: " + depto + "\n" +
        "ROL: " + rol + "\n" +
        "ESTADO: " + state);
      }
    },
    error: function(error){
      swal("Error!", {
        icon: "error",
      });
    },
  });
}

function mostrarRegistroMz(id){
  $.ajax({
    url: 'MostrarMz.php',
    type: 'POST',
    async: true,
    data: {id:id},
    success: function(response){
      if (response != 'error') {
        var info = JSON.parse(response);
        var folio = info.f;
        var sede = info.s;
        var cNombre = info.nc;
        var cajas = info.c;
        var kilos = info.k;
        var F = info.fc;
        var fecha = F.split('-').reverse().join('/');
        var hora = info.h;
        var nReg = info.nr;

        swal("Información:", 
        "FOLIO: " + folio + "\n" +
        "SEDE: " + sede + "\n" +
        "CLIENTE: " + cNombre + "\n" +
        "CAJAS: " + cajas + "\n" +
        "KILOS: " + kilos + "\n" +
        "FECHA: " + fecha + "\n" +
        "HORA: " + hora + "\n" +
        "TITULAR: " + nReg);
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