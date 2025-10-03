$(document).ready(function () {

    // Función que actualiza los href de los PDFs
    function actualizarHrefPDF() {
        let embarque = $('#embarques').val();
        if(!embarque || embarque == 0) embarque = 0;
        $('#PDFCFF').attr('href', `../../Plantillas/CamaraFria/pdf_cf.php?id=fill&embarque=${encodeURIComponent(embarque)}`);
    }

    // Función que carga los embarques según la sede
    function cargarEmbarques(sede, embarqueSeleccionado = null) {
        const selectEmbarques = $('#embarques');
        selectEmbarques.empty().append('<option value="0">Seleccione un embarque:</option>');

        if (!sede || sede === '0') {
            actualizarHrefPDF();
            return;
        }

        $.getJSON('get_embarques.php?sede=' + sede, function (data) {
            if (data.status === 'ok') {
                data.embarques.forEach(function (e) {
                    const selectedAttr = (e.id == embarqueSeleccionado) ? 'selected' : '';
                    selectEmbarques.append(`<option value="${e.id}" ${selectedAttr}>${e.embarque}</option>`);
                });
            } else {
                selectEmbarques.append('<option value="0">No hay embarques disponibles</option>');
            }

            // Inicializar Select2 si lo estás usando
            if (selectEmbarques.hasClass('select2-hidden-accessible')) {
                selectEmbarques.select2('destroy');
            }
            selectEmbarques.select2({ placeholder: "Seleccione un embarque", width: '100%' });

            // Actualizar href al terminar de cargar opciones
            actualizarHrefPDF();
        });
    }

    // Cuando cambia la sede
    $('#sede3').on('change', function () {
        const sede = $(this).val();
        cargarEmbarques(sede);
    });

    // Cuando el usuario selecciona un embarque
    $('#embarques').on('change', function(){
        actualizarHrefPDF();
    });

    // Inicializar al cargar la página
    const sedeInicial = $('#sede3').val();
    const embarqueInicial = $('#embarqueSeleccionado').val();
    if (sedeInicial && sedeInicial !== '0') {
        cargarEmbarques(sedeInicial, embarqueInicial);
    } else {
        actualizarHrefPDF();
    }

});


$(document).on('click', 'a.edit-row', function(e) {
    e.preventDefault();
    e.stopPropagation();
    e.stopImmediatePropagation();

    let id = $(this).data('id');
    let folio = $(this).data('folio');
    let presentacion = $(this).data('presentacion');
    let cajas = $(this).data('cajas');
    let mapeo = $(this).data('mapeo');
    let ubicacion = $(this).data('ubicacion');
    let valorMapeo = (mapeo != 0 && mapeo !== "0") ? mapeo : '';
    let valorUbicacion = (ubicacion != 0 && ubicacion !== "0") ? ubicacion : '';

    Swal.fire({
        title: `Pallet: ${folio}`,
        html: `
          <p><b>Presentación:</b> ${presentacion}</p>
          <p><b>Total de cajas:</b> ${cajas}</p>
          <input id="swal-mapeo" class="swal2-input" type="number" value="${valorMapeo}" placeholder="Mapeo">
          <input id="swal-ubicacion" class="swal2-input" type="text" value="${valorUbicacion}" placeholder="Ubicación">
        `,
        focusConfirm: false,
        showCloseButton: true,
        confirmButtonText: 'Guardar',
        customClass: {
            confirmButton: 'btn btn-success',
        },
        preConfirm: () => {
            let nuevoMapeo = parseInt(document.getElementById('swal-mapeo').value.trim(), 10);
            let nuevaUbicacion = parseInt(document.getElementById('swal-ubicacion').value.trim(), 10);

            // Validación de números válidos
            if (isNaN(nuevoMapeo) || nuevoMapeo <= 0 || nuevoMapeo > 70) {
                Swal.showValidationMessage("⚠️ El mapeo debe estar entre 1 y 70");
                return false;
            }
            if (isNaN(nuevaUbicacion) || nuevaUbicacion <= 0 || nuevaUbicacion > 30) {
                Swal.showValidationMessage("⚠️ La ubicación debe estar entre 1 y 30");
                return false;
            }

            // Validar duplicados en la tabla ya renderizada
            let duplicado = false;
            tablaPallets.rows().every(function () {
                let data = this.data();
                if (
                    (parseInt(data.mapeo) === nuevoMapeo && data.id_pallet != id) ||
                    (parseInt(data.ubicacion) === nuevaUbicacion && data.id_pallet != id)
                ) {
                    duplicado = true;
                }
            });

            if (duplicado) {
                Swal.showValidationMessage("⚠️ Ese mapeo o ubicación ya está ocupado");
                return false;
            }

            return {
                id: id,
                mapeo: nuevoMapeo,
                ubicacion: nuevaUbicacion
            };
        }

    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'update_pallet.php',
                type: 'POST',
                data: {
                    id: id,
                    mapeo: result.value.mapeo,
                    ubicacion: result.value.ubicacion
                },
                success: function(resp) {
                    Swal.fire({
                        title: "✅ Guardado",
                        text: "El pallet se actualizó correctamente",
                        icon: "success",
                        confirmButtonText: "OK",
                        customClass: {
                            confirmButton: 'btn btn-success'
                        },
                        buttonsStyling: false
                    });
                    tablaPallets.ajax.reload(null, false);
                },
                error: function() {
                    Swal.fire({
                        title: "❌ Error",
                        text: "No se pudo actualizar el pallet",
                        icon: "error",
                        confirmButtonText: "Cerrar",
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    });
                }
            });
        }
    });
});


