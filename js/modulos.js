$(document).ready(function () {

    $('#campo_clasificacion').hide();

    $('#tipo_registro').on('change', function () {
        const tipo = $(this).val();

        if (tipo === '') {
            $('#clasificacion').html('<option value="">Seleccione un tipo de merma primero</option>');
            return;
        }

        $.ajax({
            url: '/RisingCore/Modulos/Server_side/get_clasificaciones.php',
            method: 'GET',
            data: { tipo: tipo },
            success: function (data) {
                $('#clasificacion').html(data);
                $('#campo_clasificacion').show();

                if (clasificacionSeleccionada !== "" && $('#clasificacion option[value="' + clasificacionSeleccionada + '"]').length > 0) {
                    $('#clasificacion').val(clasificacionSeleccionada).trigger('change');
                } else {
                    console.warn("La clasificación seleccionada no está entre las opciones");
                }
            },
            error: function (xhr, status, error) {
                console.error('Error en AJAX:', status, error);
            }
        });
    });
});

$(document).ready(function () {

    $('#campo_naves').hide();

    $('#sedes').on('change', function () {
        const tipo = $(this).val();

        if (tipo === '') {
            $('#naves').html('<option value="">Seleccione una nave primero</option>');
            return;
        }

        $.ajax({
            url: '/RisingCore/Modulos/Server_side/get_naves.php',
            method: 'GET',
            data: { tipo: tipo },
            success: function (data) {
                $('#naves').html(data);
                $('#campo_naves').show();

                if (naveSeleccionada !== "" && $('#naves option[value="' + naveSeleccionada + '"]').length > 0) {
                    $('#naves').val(naveSeleccionada).trigger('change');
                } else {
                    console.warn("La nave seleccionada no está entre las opciones");
                }
            },
            error: function (xhr, status, error) {
                console.error('Error en AJAX:', status, error);
            }
        });
    });
});

$(document).ready(function () {
    const sede = $('#sede').val(); // "RF1", "RF2", etc.

    if (sede && sede !== "0") {
        cargarVariedades(sede, variedadSeleccionada);
    }

    $('#sede').on('change', function () {
        const sede = $(this).val();
        cargarVariedades(sede, null);
    });

    function cargarVariedades(sede, seleccionada) {
        $.ajax({
            url: '../../Server_side/get_codigos.php',
            type: 'GET',
            data: { tipo: sede },
            success: function (res) {
                const select = $('#codigos');
                select.empty();
                select.append('<option value="0">Seleccione la variedad:</option>');

                if (res.status === 'ok') {
                    res.variedades.forEach(function (v) {
                        const selected = (v.id == seleccionada) ? 'selected' : '';
                        select.append(`<option value="${v.id}" ${selected}>${v.codigo}</option>`);
                    });
                } else {
                    select.append('<option value="0">No hay variedades disponibles</option>');
                }
            },
            error: function () {
                $('#codigos').html('<option value="0">Error al cargar variedades</option>');
            }
        });
    }
});

$(document).ready(function () {
  // Función para cargar clientes según la sede y seleccionar un cliente opcional
  function cargarClientes(sede, clienteSeleccionado = null) {
    if (!sede || sede === '0') {
      $('#clientes').empty().append('<option value="0">Seleccione el cliente</option>');
      $('#datosCliente').hide(); // Oculta los datos también
      return;
    }

    $.getJSON('../../Server_side/get_clientes.php?tipo=' + sede, function (data) {
      const selectClientes = $('#clientes');
      selectClientes.empty().append('<option value="0">Seleccione el cliente</option>');

      if (data.status === 'ok') {
        data.clientes.forEach(function (cliente) {
          const selectedAttr = (cliente.id == clienteSeleccionado) ? 'selected' : '';
          selectClientes.append(`<option value="${cliente.id}" data-nombre="${cliente.sub}" ${selectedAttr}>${cliente.cliente}</option>`);
        });

        // Mostrar datos si el cliente ya está seleccionado
        if (clienteSeleccionado && clienteSeleccionado !== '0') {
          $('#datosCliente').show();
          //generarFolio();
        } else {
          $('#datosCliente').hide();
        }
      } else {
        selectClientes.append('<option value="0">No hay clientes disponibles</option>');
        $('#datosCliente').hide();
      }
    });
  }

  // Evento cambio en sede para cargar clientes
  $('#sede2').on('change', function () {
    const sede = $(this).val();
    cargarClientes(sede);
  });

  // Evento cambio en clientes para mostrar datos y generar folio
  $('#clientes').on('change', function () {
    const clienteVal = $(this).val();
    if (clienteVal && clienteVal !== '0') {
      $('#datosCliente').show();
      generarFolio();
    } else {
      $('#datosCliente').hide();
    }
  });

  // Inicializar si ya hay datos cargados
  const sedeInicial = $('#sede2').val();
  const clienteInicial = $('#clienteSeleccionado').val();
  if (sedeInicial && sedeInicial !== '0') {
    cargarClientes(sedeInicial, clienteInicial);
  } else {
    $('#datosCliente').hide(); // Oculta datos si no hay selección inicial
  }
});

$(document).ready(function () {
    // Evento cuando cambia la variedad
    $('#variedad').on('change', function () {
        const variedad = $(this).val();
        const sede = $('#sede2').val(); // ← desde el select de sede

        if (sede !== "0" && variedad !== "0") {
            cargarLotes(sede, variedad);
        } else {
            $('#lotes').html('<option value="0">Seleccione el lote</option>');
        }
    });

    // Evento cuando cambia la sede, limpia los lotes
    $('#sede2').on('change', function () {
        $('#lotes').html('<option value="0">Seleccione el lote</option>');
    });

    // Función para cargar los lotes con sede y variedad
    function cargarLotes(sede, variedad) {
        $.ajax({
            url: '../../Server_side/get_lote.php',
            type: 'GET',
            data: {
                sede: sede,
                variedad: variedad
            },
            success: function (res) {
                const select = $('#lotes');
                select.empty();
                select.append('<option value="0">Seleccione el lote:</option>');

                if (res.status === 'ok') {
                    res.variedades.forEach(function (l) {
                        select.append(`<option value="${l.id}">${l.lote}</option>`);
                    });
                } else {
                    select.append('<option value="0">No hay lotes disponibles</option>');
                }
            },
            error: function () {
                $('#lotes').html('<option value="0">Error al cargar lotes</option>');
            }
        });
    }
});

const inputs = document.querySelectorAll('.FAD .FAI');

inputs.forEach(input => {
    input.onfocus = ( ) =>{
        input.previousElementSibling.classList.add('Top');
        input.previousElementSibling.classList.add('Focus');
        input.parentNode.classList.add('Focus');
    }
    input.onblur = ( ) =>{
        input.value = input.value.trim();
        if (input.value.trim().length==0) {
            input.previousElementSibling.classList.remove('Top');
        }
        input.previousElementSibling.classList.remove('Focus');
        input.parentNode.classList.remove('Focus');
    }  
});

function validar() {
    inputs.forEach(input => {
        campo = input.value.trim();
        if (campo.length != 0) {
            input.previousElementSibling.classList.add('Top');
        }
    });
}

function mayus(e) {
    e.value = e.value.toUpperCase();
}

const icon = document.getElementById("eye");
const pass = document.getElementById("2");

if (icon && pass) {
    icon.addEventListener("click", () => {
        if (pass.type === "password") {
            pass.type = "text";
            $('#eye').removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            pass.type = "password";
            $('#eye').removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
}

function mostrarCampo() {
    const tipo = document.getElementById("tipo_registro").value;
    const campoC = document.getElementById("campo_codigos");
    const campoP = document.getElementById("campo_presentacion");

    if (tipo === "PRODUCCIÓN") {
        campoC.style.display = "block";
        campoP.style.display = "block";
    } else {
        campoC.style.display = "none";
        campoP.style.display = "none";
    }
}

function mostrarCampo2() {
    const tipo = document.getElementById("sedes").value;
    const campoN = document.getElementById("campo_naves");

    if (tipo === "1" || tipo === "2" || tipo === "3") {
        campoN.style.display = "block";
    } else {
        campoN.style.display = "none";
    }
}

function mostrarAgregarLote() {
    const tipo = document.getElementById("cliente").value;
    const lotes = document.getElementById("lotes");

    if (tipo !== "" ) {
        lotes.style.display = "block";
    } else {
        lotes.style.display = "none";
    }
}

$(document).ready(function () {
    // Toggle submenu in mobile
    $('.submenu-parent > a').click(function (e) {
        if ($(window).width() < 768) {
            e.preventDefault();
            $(this).siblings('.submenu').slideToggle();
        }
    });
});

// Este fragmento se asegura de volver a cargar las clasificaciones si ya hay una selección previa
$(document).ready(function () {
    const tipoInicial = $('#tipo_registro').val();
    if (tipoInicial !== "0" && tipoInicial !== "") {
        $('#tipo_registro').trigger('change');
    }
});

$(document).ready(function () {
    const tipoInicial = $('#sedes').val();
    if (tipoInicial !== "0" && tipoInicial !== "") {
        $('#sedes').trigger('change');
    }
});

function obtenerCorrelativo(base) {
  return fetch(`../../Server_side/get_correlativo.php?base=${base}`)
    .then(response => response.json())
    .then(data => data.correlativo || '01')
    .catch(() => '01');
}

function generarFolio() {
  const sedeSelect = document.getElementById('sede2');
  const clienteSelect = document.getElementById('clientes');
  const folioInput = document.querySelector('input[name="Folio"]');

  if (!sedeSelect || !clienteSelect || !folioInput) return; // Evita errores si algo falta

  const sede = sedeSelect.value;
  const clienteId = clienteSelect.value;
  const clienteOption = clienteSelect.options[clienteSelect.selectedIndex];
  const clienteCodigo = clienteOption.getAttribute('data-nombre');

  if (sede !== "0" && clienteId !== "0" && clienteCodigo) {
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
    const dias = ['AX', 'AO', 'AU', 'AE', 'AH', 'AR', 'AA'];
    const nomenclatura = dias[fecha.getDay()];
    const folioBase = `${sede}-${clienteCodigo}-${nomenclatura}${weekNumber}${year}`;

    obtenerCorrelativo(folioBase).then(correlativo => {
      const folioFinal = `${folioBase}-${correlativo}`;
      folioInput.value = folioFinal;

      const linkPdf = document.getElementById('linkPdf');
        if (linkPdf) {
        linkPdf.href = `../../Plantillas/Mezclas/pdf_mezcla.php?id=mostrar&folio=${encodeURIComponent(folioFinal)}`;
        }
    });

  } else {
    folioInput.value = '';
  }
}

const bttn1 = document.querySelector('#C1');
const cmb1 = document.querySelector('#F1');
var P1 = false;

if (bttn1 && cmb1) {
    bttn1.addEventListener('click', () => {
        cmb1.classList.toggle("Close");
        cmb1.classList.toggle("Open");
        
        const arrow = document.querySelector('#Arrow1');
        if (arrow) {
            if (P1 === false) {
                arrow.classList.remove('fa-circle-down');
                arrow.classList.add('fa-circle-up');
                P1 = true;
            } else {
                arrow.classList.remove('fa-circle-up');
                arrow.classList.add('fa-circle-down');
                P1 = false;
            }
        }
    });
}
