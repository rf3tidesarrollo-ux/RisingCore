$(document).ready(function () {
    $.ajax({
        url: '../../Server_side/Usuarios/obtener_permisos.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            const tbody = $('#tablaPermisos');
            const modulos = data.modulos;
            const permisos = data.permisos;

            console.log("Permisos seleccionados desde PHP:", permisosSeleccionados);

            tbody.empty();

            modulos.forEach(function (modulo) {
                const modulo_id = modulo.id_seccion;
                const nombre_modulo = modulo.nombre_seccion;

                let row = `<tr><td>${nombre_modulo}</td>`;

                permisos.forEach(function (permiso) {
                    const permiso_id = parseInt(permiso.id_permiso);
                    const permiso_nombre = permiso.nombre;

                    const moduloKey = String(modulo_id);

                    const checked = (permisosSeleccionados[moduloKey] &&
                                        permisosSeleccionados[moduloKey].includes(permiso_id))
                                    ? 'checked' : '';

                    row += `
                        <td data-label="${permiso_nombre}">
                            <input type="checkbox" name="permisos[${modulo_id}][]" value="${permiso_id}" ${checked}>
                        </td>
                    `;
                });

                row += `</tr>`;
                tbody.append(row);
            });
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar permisos:', error);
            console.log(xhr.responseText);
        }
    }); 
});