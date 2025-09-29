// Mapeo de CF
document.querySelectorAll('.celda').forEach(celda => {
  celda.addEventListener('click', function() {
    document.getElementById('celdaSeleccionada').value = this.id;
    abrirModal();
  });
});

  const modal = document.getElementById("modal");
  const formPallet = document.getElementById("formPallet");
  const celdaSeleccionada = document.getElementById("celdaSeleccionada");

  // Celdas de CF
  const celdas = document.querySelectorAll("[id^='dato_']");
  celdas.forEach(celda => {
    celda.style.cursor = "pointer";
    celda.onclick = () => abrirModal(celda.id);
  });

  function abrirModal(id) {
    modal.style.display = "flex";
    celdaSeleccionada.value = id;
  }

  function cerrarModal() {
    modal.style.display = "none";
    formPallet.reset();
  }

  formPallet.onsubmit = function(e) {
    e.preventDefault();

    const palletSelect = document.getElementById("palletSelect");
    const palletId = palletSelect.value;
    const palletNombre = palletSelect.options[palletSelect.selectedIndex].dataset.nombre;
    const palletFolio = palletSelect.options[palletSelect.selectedIndex].dataset.folio;
    const palletPresentacion = palletSelect.options[palletSelect.selectedIndex].dataset.presentacion;

    const ubicacion = document.getElementById("ubicacionCamion").value;
    const idCelda = celdaSeleccionada.value;

    // Pintar en el cuadro
    const celda = document.getElementById(idCelda);
    celda.style.background = "#4caf50";
    celda.style.color = "white";
    celda.style.fontSize = "11px";

    celda.innerHTML = `
      <div><strong>${palletNombre}</strong></div>
      <div>Folio: ${palletFolio}</div>
      <div>${palletPresentacion}</div>
      <div>Ubicación: ${ubicacion}</div>
    `;

    // Guardar en BD vía AJAX
    fetch("guardar_mapeo_cf.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `pallet_id=${palletId}&celda=${idCelda}&ubicacion=${ubicacion}`
    }).then(res => res.json()).then(data => {
      console.log("Guardado:", data);
    });

    cerrarModal();
  }
