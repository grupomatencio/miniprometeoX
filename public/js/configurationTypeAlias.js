document.addEventListener('DOMContentLoaded', function () {
    let filaEnEdicion = null;
    let valoresOriginales = {}; // Objeto para almacenar los valores originales

    const editButtons = document.querySelectorAll('.edit');
    const volverButtons = document.querySelectorAll('.volver');
    const crearButtons = document.querySelectorAll('.crear');

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

            // Ocultar botón de edición y mostrar los de guardar, crear y volver
            button.classList.add('d-none');
            row.querySelector('.guardar').classList.remove('d-none');
            row.querySelector('.volver').classList.remove('d-none');

            const select = row.querySelector('.select-control');

            // Guardar los valores originales antes de editar
            valoresOriginales[rowId] = {
                select: select ? select.value : null
            };

            // Habilitar los inputs
            if (select) select.removeAttribute('disabled');

            // Mostrar el botón de "Crear" o "Guardar"
            if (select.value) {
                row.querySelector('.guardar').classList.remove('d-none');
                row.querySelector('.crear').classList.add('d-none');
            } else {
                row.querySelector('.crear').classList.remove('d-none');
                row.querySelector('.guardar').classList.add('d-none');
            }
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

            const select = row.querySelector('.select-control');

            // Restaurar los valores originales
            if (select && valoresOriginales[rowId]) select.value = valoresOriginales[rowId].select;

            // Restaurar a solo lectura
            if (select) select.setAttribute('disabled', true);

            // Ocultar los botones de guardar y volver
            row.querySelector('.guardar').classList.add('d-none');
            row.querySelector('.volver').classList.add('d-none');
            row.querySelector('.crear').classList.add('d-none');


            // Mostrar el botón de edición
            row.querySelector('.edit').classList.remove('d-none');

            // Eliminar los valores almacenados después de restaurarlos
            delete valoresOriginales[rowId];
        });
    });


    // Maneja el evento del botón de guardar
    const saveButtons = document.querySelectorAll('.guardar');
    saveButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            const rowId = button.getAttribute('data-row');
            const row = document.querySelector(`[data-row='${rowId}']`);
            const select = row.querySelector('.select-control');

            // Obtener el alias seleccionado
            const selectedAlias = select.options[select.selectedIndex].text;

            // Actualizar el alias en el modal de confirmación de edición
            document.getElementById(`selected-alias-${rowId}`).innerText = selectedAlias;
            document.getElementById(`delete-alias-${rowId}`).innerText = selectedAlias;
        });
    });




    document.querySelectorAll('.guardar').forEach(button => {
        button.addEventListener("click", function () {
            let ticketNumber = this.getAttribute("data-row"); // Número de ticket

            // Obtener el select del alias
            let aliasSelect = document.querySelector(`tr[data-row="${ticketNumber}"] select[name="alias"]`);
            let selectedAlias = aliasSelect.options[aliasSelect.selectedIndex].text; // Obtener el alias (texto visible)
            let selectedMachineId = aliasSelect.value; // Obtener el id de la máquina seleccionada

            // Buscar el modal
            let modal = document.querySelector(`#modalAccionesLocal${ticketNumber}`);

            // Asignar el alias al input oculto
            let aliasInput = modal ? modal.querySelector(`input[name="alias"]`) : null;
            if (aliasInput) {
                aliasInput.value = selectedAlias; // Asignar el alias real al input hidden
            } else {
                console.error(`No se encontró input[name="alias"] en el formulario del modal para el ticket ${ticketNumber}`);
            }

            // Asignar el id_machine al input oculto
            let idMachineInput = modal ? modal.querySelector(`input[name="id_machine"]`) : null;
            if (idMachineInput) {
                idMachineInput.value = selectedMachineId; // Asignar el id_machine al input hidden
            } else {
                console.error(`No se encontró input[name="id_machine"] en el formulario del modal para el ticket ${ticketNumber}`);
            }

            // Actualizar el texto visible en el modal
            let aliasText = document.querySelector(`#selected-alias-${ticketNumber}`);
            if (aliasText) {
                aliasText.innerText = selectedAlias ? selectedAlias : 'Sin alias';
            }
        });
    });




























    crearButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            const ticketNumber = this.getAttribute('data-row'); // Obtener el TicketNumber del botón
            console.log(`Botón de crear presionado para ticket: ${ticketNumber}`); // Log del ticket number
            validarSeleccionAlias(ticketNumber, this); // Llamar a la función de validación
        });
    });
});

function validarSeleccionAlias(ticketNumber, button) {
    // Tu lógica para validar el alias seleccionado
    const select = document.querySelector(`tr[data-row="${ticketNumber}"] select[name="alias"]`);
    const errorElement = document.getElementById(`error_${ticketNumber}`);

    if (select) {
        const selectedValue = select.value;
        if (!selectedValue) {
            errorElement.classList.remove('d-none'); // Muestra el mensaje de error
        } else {
            errorElement.classList.add('d-none'); // Oculta el mensaje de error
            // Continúa con otras acciones
        }
    } else {
        console.error('Select no encontrado para el ticket:', ticketNumber);
    }
}
