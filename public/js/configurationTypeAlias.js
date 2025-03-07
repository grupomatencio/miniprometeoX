document.addEventListener('DOMContentLoaded', function () {
    let filaEnEdicion = null;
    let valoresOriginales = {}; // Objeto para almacenar los valores originales

    const editButtons = document.querySelectorAll('.edit');
    const volverButtons = document.querySelectorAll('.volver');

    editButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            if (filaEnEdicion) {
                console.log("Ya hay una fila en edición. No se puede editar otra.");
                return;
            }

            const rowId = button.getAttribute('data-row');
            const row = document.querySelector(`[data-row='${rowId}']`);

            if (!row) return;

            filaEnEdicion = row; // Guardamos la fila en edición

            // Deshabilitar edición y eliminación en otras filas
            editButtons.forEach(btn => btn.disabled = true);
            document.querySelectorAll('.btn-danger').forEach(btn => btn.disabled = true); // Deshabilitar los botones de eliminar

            // Ocultar botón de edición y mostrar los de guardar y volver
            button.classList.add('d-none');
            row.querySelector('.guardar').classList.remove('d-none');
            row.querySelector('.volver').classList.remove('d-none');

            // Obtener elementos de la fila
            const select = row.querySelector('.select-control');
            const checkbox = row.querySelector('.check-input');

            // Guardar los valores originales antes de editar
            valoresOriginales[rowId] = {
                select: select ? select.value : null,
                checkbox: checkbox ? checkbox.checked : null
            };

            // Habilitar los inputs
            if (select) select.removeAttribute('disabled');
            if (checkbox) checkbox.removeAttribute('disabled');

            // Obtener el tipo de ticket del botón (usando un data-attribute)
            const ticketType = button.getAttribute('data-ticket-type');

            // Actualizar el alias en los modales
            const selectedAlias = select.options[select.selectedIndex].text; // Obtener el texto del alias seleccionado
            document.getElementById(`selected-alias-${ticketType}`).innerText = selectedAlias;
            document.getElementById(`delete-alias-${ticketType}`).innerText = selectedAlias;

            // Abrir el modal
            const modal = new bootstrap.Modal(document.getElementById(`modalAccionesLocal${ticketType}`));
            modal.show();
        });
    });


    volverButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            const rowId = button.getAttribute('data-row');
            const row = document.querySelector(`[data-row='${rowId}']`);

            if (!row) return;

            filaEnEdicion = null; // Liberamos la fila en edición

            // Restaurar la edición de otras filas
            editButtons.forEach(btn => btn.disabled = false);
            document.querySelectorAll('.btn-danger').forEach(btn => btn.disabled = false); // Habilitar los botones de eliminar

            // Obtener elementos de la fila
            const select = row.querySelector('.select-control');
            const checkbox = row.querySelector('.check-input');

            // Restaurar los valores originales
            if (select && valoresOriginales[rowId]) select.value = valoresOriginales[rowId].select;
            if (checkbox && valoresOriginales[rowId]) checkbox.checked = valoresOriginales[rowId].checkbox;

            // Restaurar a solo lectura
            if (select) select.setAttribute('disabled', true);
            if (checkbox) checkbox.setAttribute('disabled', true);

            // Ocultar los botones de guardar y volver
            row.querySelector('.guardar').classList.add('d-none');
            row.querySelector('.volver').classList.add('d-none');

            // Mostrar el botón de edición
            row.querySelector('.edit').classList.remove('d-none');

            // Eliminar los valores almacenados después de restaurarlos
            delete valoresOriginales[rowId];
        });
    });
});
