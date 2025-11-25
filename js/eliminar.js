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

function actualizarRegistro(id){
    swal({
        title: "¿Realmente quiere reactivar al personal?",
        text: "",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          window.location.href = "Eliminar.php?id=" + id;
          swal("Reactivación completada!", {
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
        var tCaja = info.c; 
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
        "TIPO DE CARRO: " + tCarro + "\n" +
        "TIPO DE TARIMAS: " + nTarima + "\n" +
        "CANTIDAD DE TARIMAS: " + cTarima + "\n" +
        "TIPO DE CAJA: " + tCaja + "\n" +
        "CANTIDAD DE CAJAS: " + cCajas + "\n" +
        "PESO BRUTO: " + pBruto + "\n" +
        "PESO TARAJE: " + pTaraje + "\n" +
        "PESO NETO: " + pNeto + "\n" +
        "FECHA DE REGISTRO: " + fecha + "\n" +
        "HORA: " + hora + "\n" +
        "SEMANA: " + rSemana );
      }
    },
    error: function(error){
      swal("Error!", {
        icon: "error",
      });
    },
  });
}

function mostrarRegistroM(id){
  $.ajax({
    url: 'MostrarM.php',
    type: 'POST',
    async: true,
    data: {id:id},
    success: function(response){
      if (response != 'error') {
        var info = JSON.parse(response);
        var nSerie = info.ns;
        var cNombre = info.tm;
        var motivo = info.m;
        var cCajas = info.cc; 
        var tCaja = info.c; 
        var pBruto = info.pb; 
        var pTaraje = info.pt;
        var pNeto = info.pn; 
        var F = info.fr;
        var fecha = F.split('-').reverse().join('/');
        var hora = info.hr;
        var mSemana = info.sr;
        var tCarro = info.tc;
        var nTarima = info.nt;
        var cTarima = info.ct;

        swal("Información:", 
        "NO. SERIE: " + nSerie + "\n" +
        "CLASIFICACIÓN: " + cNombre + "\n" +
        "MOTIVO: " + motivo + "\n" +
        "TRAILA: " + tCarro + "\n" +
        "TIPO DE TARIMA: " + nTarima + "\n" +
        "CANTIDAD DE TARIMAS: " + cTarima + "\n" +
        "TIPO DE CAJA: " + tCaja + "\n" +
        "CANTIDAD DE CAJAS: " + cCajas + "\n" +
        "PESO BRUTO: " + pBruto + "\n" +
        "PESO TARAJE: " + pTaraje + "\n" +
        "PESO NETO: " + pNeto + "\n" +
        "SEMANA: " + mSemana + "\n" +
        "FECHA DE REGISTRO: " + fecha + "\n" +
        "HORA: " + hora);
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
        var folio = info.f;
        var sede = info.s;
        var pNombre = info.np;
        var cNombre = info.nc;
        var cajas = info.c;
        var tipo = info.t;
        var F = info.fc;
        var fecha = F.split('-').reverse().join('/');
        var hora = info.h;
        var nReg = info.nr;

        swal("Información:", 
        "FOLIO: " + folio + "\n" +
        "SEDE: " + sede + "\n" +
        "PRESENTACIÓN: " + pNombre + "\n" +
        "CLIENTE: " + cNombre + "\n" +
        "CAJAS: " + cajas + "\n" +
        "EMBARQUE: " + tipo + "\n" +
        "FECHA: " + fecha + "\n" +
        "HORA: " + hora + "\n" +
        "REGISTRÓ: " + nReg);
      }
    },
    error: function(error){
      swal("Error!", {
        icon: "error",
      });
    },
  });
}

function mostrarRegistroEm(id){
  $.ajax({
    url: 'MostrarE.php',
    type: 'POST',
    async: true,
    data: {id:id},
    success: function(response){
      if (response != 'error') {
        var info = JSON.parse(response);
        var sede = info.fs;
        var folio = info.fe;
        var po = info.po;
        var destino = info.de;
        var cajasE = info.ce;
        var kilosE = info.ke;
        var cajasT = info.ct;
        var kilosT = info.kt;
        var F = info.fe;
        var fecha = F.split('-').reverse().join('/');
        var semana = info.se;
        var cNmbre = info.nc;
        var estado = info.ee;

        swal("Información:",
        "SEDE: " + sede + "\n" +
        "FOLIO: " + folio + "\n" +
        "PO: " + po + "\n" +
        "DESTINO: " + destino + "\n" +
        "CAJAS REQUERIDAS: " + cajasE + "\n" +
        "KILOS REQUERIDAS: " + kilosE + "\n" +
        "CAJAS EMBARCADAS: " + cajasT + "\n" +
        "KILOS EMBARCADOS: " + kilosT + "\n" +
        "FECHA DE ENVIÓ: " + fecha + "\n" +
        "SEMANA: " + semana + "\n" +
        "REGISTRÓ: " + cNmbre + "\n" +
        "ESTADO: " + estado);
      }
    },
    error: function(error){
      swal("Error!", {
        icon: "error",
      });
    },
  });
}

function mostrarRegistroNI(id){
  $.ajax({
    url: 'MostrarNI.php',
    type: 'POST',
    async: true,
    data: {id:id},
    success: function(response){
      if (response != 'error') {
        var info = JSON.parse(response);
        var sede = info.s;
        var badge = info.b;
        var personal = info.np;
        var genero = info.g;
        var tipo = info.te;
        var depto = info.d;
        var tPago = info.tp;
        var tipo2 = info.th;
        var FI = info.fi;
        var fechaI = FI.split('-').reverse().join('/');
        var FR = info.fr;
        var fechaR = FR.split('-').reverse().join('/');
        var cNmbre = info.nc;

        swal("Información:",
        "SEDE: " + sede + "\n" +
        "BADGE: " + badge + "\n" +
        "NOMBRE: " + personal + "\n" +
        "GENÉRO: " + genero + "\n" +
        "TIPO DE EMPLEADO: " + tipo + "\n" +
        "DEPARTAMENTO: " + depto + "\n" +
        "TIPO DE PAGO: " + tPago + "\n" +
        "TIPO DE HORARIO: " + tipo2 + "\n" +
        "FECHA DE INGRESO: " + fechaI + "\n" +
        "FECHA DE REGISTRO: " + fechaR + "\n" +
        "REGISTRÓ: " + cNmbre);
      }
    },
    error: function(error){
      swal("Error!", {
        icon: "error",
      });
    },
  });
}

function mostrarRegistroPI(id){
  $.ajax({
    url: 'MostrarPI.php',
    type: 'POST',
    async: true,
    data: {id:id},
    success: function(response){
      if (response != 'error') {
        var info = JSON.parse(response);
        var sede = info.s;
        var badge = info.b;
        var personal = info.np;
        var genero = info.g;
        var tipo = info.te;
        var depto = info.d;
        var tPago = info.tp;
        var tipo2 = info.th;
        var FI = info.fi;
        var fechaI = FI.split('-').reverse().join('/');
        var FR = info.fr;
        var fechaR = FR.split('-').reverse().join('/');
        var cNmbre = info.nc;

        swal("Información:",
        "SEDE: " + sede + "\n" +
        "BADGE: " + badge + "\n" +
        "NOMBRE: " + personal + "\n" +
        "GENÉRO: " + genero + "\n" +
        "TIPO DE EMPLEADO: " + tipo + "\n" +
        "DEPARTAMENTO: " + depto + "\n" +
        "TIPO DE PAGO: " + tPago + "\n" +
        "TIPO DE HORARIO: " + tipo2 + "\n" +
        "FECHA DE INGRESO: " + fechaI + "\n" +
        "FECHA DE REGISTRO: " + fechaR + "\n" +
        "REGISTRÓ: " + cNmbre);
      }
    },
    error: function(error){
      swal("Error!", {
        icon: "error",
      });
    },
  });
}

function mostrarRegistroH(id){
  $.ajax({
    url: 'MostrarH.php',
    type: 'POST',
    async: true,
    data: {id:id},
    success: function(response){
      if (response != 'error') {
        var info = JSON.parse(response);
        var sede = info.s;
        var nombre = info.n;
        var horaE = info.he;
        var horaS = info.hs;
        var horaSE = info.se;
        var horaSS = info.ss;
        var horaDE = info.de;
        var horaDS = info.ds;

        swal("Información:",
        "SEDE: " + sede + "\n" +
        "HORARIO: " + nombre + "\n" +
        "ENTRADA: " + horaE + "\n" +
        "SALIDA: " + horaS + "\n" +
        "ENTRADA DEL SÁBADO: " + horaSE + "\n" +
        "SALIDA DEL SÁBADO: " + horaSS + "\n" +
        "ENTRADA DEL DOMINGO: " + horaDE + "\n" +
        "SALIDA DEL DOMINGO: " + horaDS);
      }
    },
    error: function(error){
      swal("Error!", {
        icon: "error",
      });
    },
  });
}

function mostrarRegistroLI(id){
  $.ajax({
    url: 'MostrarLI.php',
    type: 'POST',
    async: true,
    data: {id:id},
    success: function(response){
      if (response != 'error') {
        var info = JSON.parse(response);
        var sede = info.s;
        var nombre = info.np;
        var depto = info.d;
        var permiso = info.tp;
        var F = info.fp;
        var fecha = F.split('-').reverse().join('/');

        swal("Información:",
        "SEDE: " + sede + "\n" +
        "NOMBRE: " + nombre + "\n" +
        "DEPARTAMENTO: " + depto + "\n" +
        "TIPO DE PERMISO: " + permiso + "\n" +
        "FECHA DE PERMISO: " + fecha);
      }
    },
    error: function(error){
      swal("Error!", {
        icon: "error",
      });
    },
  });
}

function mostrarRegistroRI(id){
  $.ajax({
    url: 'MostrarRI.php',
    type: 'POST',
    async: true,
    data: {id:id},
    success: function(response){
      if (response != 'error') {
        var info = JSON.parse(response);
        var sede = info.s;
        var badge = info.b;
        var personal = info.np;
        var genero = info.g;
        var tipo = info.te;
        var depto = info.d;
        var tPago = info.tp;
        var tipo2 = info.th;
        var FI = info.fi;
        var fechaI = FI.split('-').reverse().join('/');
        var FR = info.fr;
        var fechaR = FR.split('-').reverse().join('/');
        var cNmbre = info.nc;

        swal("Información:",
        "SEDE: " + sede + "\n" +
        "BADGE: " + badge + "\n" +
        "NOMBRE: " + personal + "\n" +
        "GENÉRO: " + genero + "\n" +
        "TIPO DE EMPLEADO: " + tipo + "\n" +
        "DEPARTAMENTO: " + depto + "\n" +
        "TIPO DE PAGO: " + tPago + "\n" +
        "TIPO DE HORARIO: " + tipo2 + "\n" +
        "FECHA DE INGRESO: " + fechaI + "\n" +
        "FECHA DE REGISTRO: " + fechaR + "\n" +
        "REGISTRÓ: " + cNmbre);
      }
    },
    error: function(error){
      swal("Error!", {
        icon: "error",
      });
    },
  });
}

function mostrarRegistroPR(id){
  $.ajax({
    url: 'MostrarPR.php',
    type: 'POST',
    async: true,
    data: {id:id},
    success: function(response){
      if (response != 'error') {
        var info = JSON.parse(response);
        var sede = info.s;
        var badge = info.b;
        var personal = info.np;
        var genero = info.g;
        var tipo = info.te;
        var depto = info.d;
        var tPago = info.tp;
        var tipo2 = info.th;
        var FI = info.fi;
        var fechaI = FI.split('-').reverse().join('/');
        var FR = info.fr;
        var fechaR = FR.split('-').reverse().join('/');
        var cNmbre = info.nc;

        swal("Información:",
        "SEDE: " + sede + "\n" +
        "BADGE: " + badge + "\n" +
        "NOMBRE: " + personal + "\n" +
        "GENÉRO: " + genero + "\n" +
        "TIPO DE EMPLEADO: " + tipo + "\n" +
        "DEPARTAMENTO: " + depto + "\n" +
        "TIPO DE PAGO: " + tPago + "\n" +
        "TIPO DE HORARIO: " + tipo2 + "\n" +
        "FECHA DE INGRESO: " + fechaI + "\n" +
        "FECHA DE REGISTRO: " + fechaR + "\n" +
        "REGISTRÓ: " + cNmbre);
      }
    },
    error: function(error){
      swal("Error!", {
        icon: "error",
      });
    },
  });
}

function mostrarRegistroRQ(id){
  $.ajax({
    url: 'MostrarRQ.php',
    type: 'POST',
    async: true,
    data: {id:id},
    success: function(response){
      if (response != 'error') {
        var info = JSON.parse(response);
        const sede = info.s || 'N/A';
        const folio = info.f || 'N/A';
        const departamento = info.d || 'N/A';
        const area = info.a || 'N/A';
        const solicitante = info.p || 'N/A';
        const pTotal = info.t || 0;
        const fechaReq = info.fr ? info.fr.split('-').reverse().join('/') : 'N/A';
        const estado = info.e || 'N/A';
        const usuario = info.u || 'N/A';

        swal("Información:",
        "SEDE: " +sede + "\n" +
        "FOLIO: " + folio + "\n" +
        "DEPARTAMENTO: " + departamento + "\n" +
        "ÁREA: " + area + "\n" +
        "TOTAL DE PRODUCTOS: " + pTotal + "\n" +
        "SOLICITANTE: " + solicitante + "\n" +
        "FECHA DE REQUESICIÓN: " + fechaReq + "\n" +
        "ESTADO: " + estado + "\n" +
        "USUARIO: " + usuario);
      }
    },
    error: function(error){
      swal("Error!", {
        icon: "error",
      });
    },
  });
}

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

function mostrarRegistroIC(id){
  $.ajax({
    url: 'MostrarIC.php',
    type: 'POST',
    async: true,
    data: {id:id},
    success: function(response){
      if (response != 'error') {
        var info = JSON.parse(response);
        var sede = info.s;
        var nombre = info.np;
        var depto = info.d;
        var incentivo = info.ti;
        var F = info.f;
        var fecha = F.split('-').reverse().join('/');
        var cant = info.c;
        var motivo = info.m;
        var estado = info.e;
        var user = info.u;

        swal("Información:",
        "SEDE: " + sede + "\n" +
        "NOMBRE: " + nombre + "\n" +
        "DEPARTAMENTO: " + depto + "\n" +
        "TIPO DE INCENTIVO: " + incentivo + "\n" +
        "FECHA DEL INCENTIVO: " + fecha + "\n" +
        "CANTIDAD: " + cant + "\n" +
        "MOTIVO: " + motivo + "\n" +
        "Estado: " + estado + "\n" +
        "USUARIO: " + user);
      }
    },
    error: function(error){
      swal("Error!", {
        icon: "error",
      });
    },
  });
}