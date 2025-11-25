function mostrarIDA() {
    const artID = $('#articulos').val();

    if (artID !== "0") {
        mostrarRegistroP(artID);
    } else {
        swal("Seleccione un producto válido.", {
            icon: "warning",
        });
    }
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
        const tipo = info.tp || 'N/A';
        const producto = info.p || 'N/A';
        const unidad = info.u || 'N/A';
        const existencias = info.ex || 0;
        // Fechas
        const UE = info.ue;
        const US = info.us;

        const uEntrada = UE ? UE.split('-').reverse().join('/') : 'N/A';
        const uSalida  = US ? US.split('-').reverse().join('/') : 'N/A';

        // Otros datos
        const uProve = info.up || 'N/A';
        const uPrecio = info.pr || 'N/A';

        swal("Información:", 
        "TIPO DE PRODUCTO: " + tipo + "\n" +
        "PRODUCTO: " + producto + "\n" +
        "UNIDAD: " + unidad + "\n" +
        "EXISTENCIAS: " + existencias + "\n" +
        "ULTIMA ENTRADA: " + uEntrada + "\n" +
        "ULTIMA SALIDA: " + uSalida + "\n" +
        "ULTIMO PROVEEDOR: " + uProve + "\n" +
        "ULTIMO PRECIO: " + uPrecio);
      }
    },
    error: function(error){
      swal("Error!", {
        icon: "error",
      });
    },
  });
}

function mostrarRegistroPA(id){
  $.ajax({
    url: 'MostrarPA.php',
    type: 'POST',
    async: true,
    data: {id:id},
    success: function(response){
      if (response != 'error') {
        var info = JSON.parse(response);
        const folio = info.f || 'N/A';
        const producto = info.p || 'N/A';
        const prioridad = info.pr || 'N/A';
        const cantidad = info.c || 'N/A';
        const unidad = info.u || 'N/A';
        const existencias = info.e || 0;
        const fechaReq = info.fr ? info.fr.split('-').reverse().join('/') : 'N/A';
        const estado = info.ed || 'N/A';
        const justificacion = info.j || 'N/A';

        swal("Información:", 
        "FOLIO: " + folio + "\n" +
        "PRODUCTO: " + producto + "\n" +
        "PRIORIDAD: " + prioridad + "\n" +
        "CANTIDAD: " + cantidad + " " + unidad + "\n" +
        "EXISTENCIAS: " + existencias + "\n" +
        "FECHA REQUERIDA: " + fechaReq + "\n" +
        "JUSTIFICACIÓN: " + justificacion + "\n" +
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

window.addEventListener('pagehide', function(event) {
  navigator.sendBeacon('EliminarTodoTemp.php');
});

$(document).ready(function () {
  function cargarAreas(depto, areaSeleccionada = null) {
    if (!depto || depto === '0') {
      $('#areas').empty().append('<option value="0">Seleccione el área</option>');
      $('#datosArticulos').hide(); // Oculta los datos también
      return;
    }

    $.getJSON('../../Server_side/Requisiciones/get_areas.php?tipo=' + depto, function (data) {
      const selectAreas = $('#areas');

      if (data.status === 'ok') {
        const totalAreas = data.areas.length;

        if (totalAreas != 1) {
          selectAreas.empty().append('<option value="0">Seleccione el área</option>');
        }

        data.areas.forEach(function (area) {
          const selectedAttr = (area.id == areaSeleccionada) ? 'selected' : '';
          selectAreas.append(`<option value="${area.id}" data-nombre="${area.sub}" ${selectedAttr}>${area.area}</option>`);
        });

        if (areaSeleccionada && areaSeleccionada !== '0') {
          $('#datosArticulos').show();
          selectAreas.val(areaSeleccionada).trigger('change');
          //generarFolio();
        } else {
          $('#datosArticulos').hide();
        }
      } else {
        selectAreas.append('<option value="0">No hay áreas disponibles</option>');
        $('#datosArticulos').hide();
      }
    });
  }

  // Evento cambio en sede para cargar departamentos
  $('#depto').on('change', function () {
    const dep = $(this).val();
    cargarAreas(dep);
  });

  // Evento cambio en departamentos para mostrar datos y generar folio
  $('#areas').on('change', function () {
    const areaVal = $(this).val();
    if (areaVal && areaVal !== '0') {
      $('#datosArticulos').show();
      generarFolioR();
    } else {
      $('#datosArticulos').hide();
    }
  });

  // Inicializar si ya hay datos cargados
  const sedeInicial = $('#sede3').val();
  const departamentoInicial = $('#depto').val();
  const areaInicial = $('#areaSeleccionada').val();
  if (sedeInicial && sedeInicial !== '0' && departamentoInicial !== '0' && areaInicial !== '0') {
    cargarAreas(departamentoInicial, areaInicial);
  } else {
    $('#datosArticulos').hide(); // Oculta datos si no hay selección inicial
  }
});

function obtenerCorrelativo(base) {
  return fetch(`../../Server_side/Requisiciones/get_correlativo.php?base=${base}`)
    .then(response => response.json())
    .then(data => data.correlativo || '01')
    .catch(() => '01');
}

function generarFolioR() {
  const sedeSelect = document.getElementById('sede3');
  const areaSelect = document.getElementById('areas');
  const folioInput = document.querySelector('input[name="Folio"]');

  if (!sedeSelect || !areaSelect || !folioInput) return;

  const sede = sedeSelect.value;
  const areaId = areaSelect.value;
  const areaOption = areaSelect.options[areaSelect.selectedIndex];
  const areaCodigo = areaOption.getAttribute('data-nombre');

  if (sede !== "0" && areaId !== "0" && areaCodigo) {
    const fecha = new Date();

    // Calcular semana
    const getWeekNumber = (d) => {
      d = new Date(Date.UTC(d.getFullYear(), d.getMonth(), d.getDate()));
      const dayNum = d.getUTCDay() || 7;
      d.setUTCDate(d.getUTCDate() + 4 - dayNum);
      const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
      const weekNo = Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
      return weekNo;
    };

    const weekNumber = getWeekNumber(fecha);
    const year = fecha.getFullYear().toString().slice(-2);
    const folioBase = `${sede}-${areaCodigo}-${weekNumber}${year}`;

    obtenerCorrelativo(folioBase).then(correlativo => {
      const folioFinal = `${folioBase}-${correlativo}`;
      folioInput.value = folioFinal;

      // const linkRequi = document.getElementById('linkRequi');
      //   if (linkRequi) {
      //   linkRequi.href = `../../Plantillas/Requis/pdf_requi.php?id=mostrar&folio=${encodeURIComponent(folioFinal)}`;
      //   }
    });

  } else {
    folioInput.value = '';
  }
}

$(document).ready(function () {
    $('#tipo').on('change', function () {
        const tipo = $(this).val();

        if (tipo !== "0") {
            cargarArticulos(tipo);
        } else {
            $('#articulos').html('<option value="0">Seleccione el producto:</option>');
        }
    });

    function cargarArticulos(tipo) {
        $.ajax({
            url: '../../Server_side/Requisiciones/get_art.php',
            type: 'GET',
            data: {
                tipo: tipo
            },
            success: function (res) {
                const select = $('#articulos');
                select.empty();
                select.append('<option value="0">Seleccione el producto:</option>');

                if (res.status === 'ok') {
                    res.productos.forEach(function (p) {
                        select.append(`<option value="${p.id}">${p.producto}</option>`);
                    });
                } else {
                    select.append('<option value="0">No hay productos disponibles</option>');
                }
            },
            error: function () {
                $('#lotes').html('<option value="0">Error al cargar productos</option>');
            }
        });
    }
});

$('#verArtBtn').on('click', function () {
    const artId = $('#articulos').val();
    const artText = $('#articulos option:selected').text();

    if (artId !== "0") {
        $('#infoArt').html(`<strong>Producto seleccionado:</strong> ${artText} (ID: ${artId})`);
    } else {
        $('#infoArt').html(`<span style="color: yellow;">Debe seleccionar un producto.</span>`);
    }
});

$(document).ready(function() {
 $('#btnAgregarArt').on('click', function(e) {
    const sede = $('#sede3').val();
    const tipoID = $('#tipo').val();
    const artID = $('#articulos').val();
    const cantidad = $('#cantidad').val();
    const fecha = $('#requerido').val();
    const prioridad = $('#prioridad').val();
    const justificacion = $('#justificacion').val();

    if (tipoID === "0") { swal("Tiene que seleccionar un tipo de producto", { icon: "warning", }); return; }
    if (artID === "0") { swal("Tiene que seleccionar un producto", { icon: "warning", }); return; }
    if (cantidad === "") { swal("Tiene que ingresar una cantidad", { icon: "warning", }); return; }
    if (fecha === "") { swal("Tiene que ingresar una fecha requerida", { icon: "warning", }); return; }
    if (prioridad === "0") { swal("Tiene que seleccionar una prioridad", { icon: "warning", }); return; }
    if (justificacion.trim() === "") { swal("Tiene que ingresar una justificación", { icon: "warning", }); return; }

    $.ajax({
      url: 'AgregarArt.php',
      method: 'POST',
      data: {
        sede : sede,
        tipoID: tipoID,
        artID: artID,
        cantidad: cantidad,
        fecha: fecha,
        prioridad: prioridad,
        justificacion: justificacion,
        addArt: true
      },
      success: function(response) {
      let res;
      console.log("Respuesta cruda del servidor:", response); 
        try {
            res = typeof response === 'string' ? JSON.parse(response) : response;
        } catch (e) {
            console.error("Error al parsear JSON:", e);
            swal("Respuesta no válida del servidor", { icon: "error" });
            return;
        }

        if (res.status === 'ok') {
        swal("Producto agregado correctamente", { icon: "success" });
        const hoy = new Date().toISOString().split('T')[0];
        $('#producto').val(res.total);
        $('#tipo').val('0').trigger('change');
        $('#articulos').val('0').trigger('change');
        $('#cantidad').val('');
        $('#requerido').val(hoy);
        $('#prioridad').val('0').trigger('change');
        $('#justificacion').val('');
        tablaArticulos.ajax.reload(null, false);

        } else if (res.status === 'duplicate') {
            swal("Este producto ya esta agregado", { icon: "warning" });

        } else {
            swal(res.message || "Error al agregar producto", { icon: "error" });
        }

        }
        });
    });

  // Manejador para eliminar producto temporal desde la tabla
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
            swal("Producto eliminado correctamente", { icon: "success" });
            $('#producto').val(res.total);
            tablaArticulos.ajax.reload(null, false);
            tablaArticulos.ajax.reload(function() {
            tablaArticulos.responsive.recalc();
          }, false);
        } else {
            swal({
                title: "Error al eliminar producto:",
                text: "No puede quedar sin ningún producto la requisición",
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

document.addEventListener("DOMContentLoaded", function () {
    const linkRequi = document.getElementById("linkRequi");

    linkRequi.addEventListener("click", function (e) {
        e.preventDefault(); // Evita el href vacío

        // Obtén los valores de los campos
        const sede = document.getElementById("sede3").value;
        const depto = document.getElementById("depto").value;
        const area = document.getElementById("areas").value;
        const folio = document.getElementById("folio").value;

        // Validación simple
        if (sede === "0" || depto === "0" || area === "0" || folio.trim() === "") {
            swal("Tiene que seleccionar una sede, departamento y área", {
            icon: "warning",
        });
        return;
        }

        // Construir URL
        const queryString = new URLSearchParams({
            Sede: sede,
            Depto: depto,
            Area: area,
            Folio: folio
        }).toString();

        const url = `../../Plantillas/requis/pdf_requi.php?id=mostrar&${queryString}`;
        console.log("URL generada:", url);
        window.open(url, "_blank");
    });
});