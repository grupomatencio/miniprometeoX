document.addEventListener('DOMContentLoaded', function () {
    let filaEnEdicion = null;
    let valoresOriginales = {}; // Objeto para almacenar los valores originales

    const editButtons = document.querySelectorAll('.edit');
    const volverButtons = document.querySelectorAll('.volver');
    const crearButtons = document.querySelectorAll('.crear');

    editButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            console.log("Botón de edición presionado"); // Log al presionar editar

            if (filaEnEdicion) {
                console.log("Ya hay una fila en edición. No se puede editar otra.");
                return;
            }

            const rowId = button.getAttribute('data-row');
            const row = document.querySelector(`[data-row='${rowId}']`);

            if (!row) {
                console.error(`Fila no encontrada para ID: ${rowId}`); // Log de error
                return;
            }

            filaEnEdicion = row; // Guardamos la fila en edición
            console.log("Fila en edición:", filaEnEdicion);

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
            console.log("Valores originales guardados:", valoresOriginales[rowId]);

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
            console.log("Botón de volver presionado"); // Log al presionar volver
            const rowId = button.getAttribute('data-row');
            const row = document.querySelector(`[data-row='${rowId}']`);

            if (!row) {
                console.error(`Fila no encontrada para ID: ${rowId}`); // Log de error
                return;
            }

            filaEnEdicion = null; // Liberamos la fila en edición
            console.log("Fila en edición liberada");

            // Restaurar la edición de otras filas
            editButtons.forEach(btn => btn.disabled = false);
            document.querySelectorAll('.btn-danger').forEach(btn => btn.disabled = false); // Habilitar los botones de eliminar

            const select = row.querySelector('.select-control');

            // Restaurar los valores originales
            if (select && valoresOriginales[rowId]) {
                select.value = valoresOriginales[rowId].select;
                console.log("Valores restaurados:", valoresOriginales[rowId]);
            }

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

    document.querySelectorAll('.guardar').forEach(button => {
        button.addEventListener("click", function () {
            let ticketNumber = this.getAttribute("data-row"); // Número de ticket
            console.log("Botón de guardar presionado para ticket:", ticketNumber);

            // Obtener el select del alias
            let aliasSelect = document.querySelector(`tr[data-row="${ticketNumber}"] select[name="alias"]`);
            let selectedAlias = aliasSelect.options[aliasSelect.selectedIndex].text; // Obtener el alias (texto visible)

            // Buscar el input hidden dentro del modal
            let modal = document.querySelector(`#modalAccionesLocal${ticketNumber}`);
            let aliasInput = modal ? modal.querySelector(`input[name="alias"]`) : null;

            if (aliasInput) {
                aliasInput.value = selectedAlias; // Asignar el alias real al input hidden
            } else {
                console.error(`No se encontró input[name="alias"] en el formulario del modal para el ticket ${ticketNumber}`); // Log de error
            }

            // Actualizar el texto visible en el modal
            let aliasText = document.querySelector(`#selected-alias-${ticketNumber}`);
            if (aliasText) {
                aliasText.innerText = selectedAlias ? selectedAlias : 'Sin alias';
            }
        });
    });


    document.querySelectorAll('.crear').forEach(button => {
        button.addEventListener('click', function () {
            let ticketNumber = this.getAttribute('data-row');
            let tipo = this.getAttribute('data-tipo');
            let idMachine = this.getAttribute('data-maquina-id');
            let alias = this.getAttribute('data-alias');

            let modal = document.querySelector(`#modalCrearTipoAlias${ticketNumber}`);
            if (modal) {
                modal.querySelector('#nuevoTipo').value = tipo;
                modal.querySelector('#nuevoAlias').value = alias;
                modal.querySelector('#idMachine').value = idMachine;
                modal.querySelector('#tipoMostrar').innerText = tipo;
                modal.querySelector('#aliasMostrar').innerText = alias ? alias : 'Sin alias';
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

    function validarSeleccionAlias(ticketNumber, button) {
        const select = document.querySelector(`tr[data-row="${ticketNumber}"] select[name="alias"]`);
        const errorElement = document.getElementById(`error_${ticketNumber}`);
        const tipoMaquinaElement = document.querySelector(`tr[data-row="${ticketNumber}"] td input[type="text"]`); // Suponiendo que el tipo de máquina está en un input dentro de un td

        if (select) {
            const selectedValue = select.value; // Esto devuelve el ID
            const selectedText = select.options[select.selectedIndex].text; // Esto devuelve el texto visible
            const tipoMaquina = tipoMaquinaElement ? tipoMaquinaElement.value : ''; // Obtener el tipo de máquina

            console.log(`Validando selección de alias: ID=${selectedValue}, Texto=${selectedText}, Tipo=${tipoMaquina}`); // Log de validación

            if (!selectedValue) {
                errorElement.classList.remove('d-none'); // Muestra el mensaje de error
            } else {
                errorElement.classList.add('d-none'); // Oculta el mensaje de error

                // Aquí llamas a prepararModalCrear con los datos correctos
                prepararModalCrear(tipoMaquina, selectedValue, selectedText, ticketNumber);
            }
        } else {
            console.error('Select no encontrado para el ticket:', ticketNumber); // Log de error
        }
    }



    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function () {
            document.body.classList.remove('modal-open');
            document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
        });
    });




});












function prepararModalCrear(tipo, idMachine, alias, ticketNumber) {
    // Asignar valores a los inputs ocultos
    document.getElementById('nuevoTipo').value = tipo;
    document.getElementById('nuevoAlias').value = alias;
    document.getElementById('idMachine').value = idMachine;

    // Mostrar valores en el modal para referencia
    document.getElementById('tipoMostrar').innerText = tipo;
    document.getElementById('aliasMostrar').innerText = alias;

    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById(`modalCrearTipoAlias${ticketNumber}`));
    modal.show();
}




function enviarDatosCrear(ticketNumber) {
    // Obtener los datos del formulario
    var tipo = document.getElementById('nuevoTipo').value;
    var alias = document.getElementById('nuevoAlias').value;
    var maquinaId = document.getElementById('idMachine').value; // Capturar el id_machine

    console.log(`Enviando datos crear: tipo=${tipo}, alias=${alias}, maquinaId=${maquinaId}`); // Log de envío

    // Crear un formulario para enviar los datos
    var form = document.createElement('form');
    form.action = "{{ route('configurationTypeAlias.store') }}"; // Cambia a la ruta correspondiente
    form.method = "POST";

    // Agregar el token CSRF
    var csrfInput = document.createElement('input');
    csrfInput.type = "hidden";
    csrfInput.name = "_token";
    csrfInput.value = "{{ csrf_token() }}"; // Asumiendo que tienes el token disponible
    form.appendChild(csrfInput);

    // Agregar los datos al formulario
    var tipoInput = document.createElement('input');
    tipoInput.type = "hidden";
    tipoInput.name = "type";
    tipoInput.value = tipo;
    form.appendChild(tipoInput);

    var aliasInput = document.createElement('input');
    aliasInput.type = "hidden";
    aliasInput.name = "alias";
    aliasInput.value = alias;
    form.appendChild(aliasInput);

    // Agregar el ID de la máquina
    var maquinaIdInput = document.createElement('input');
    maquinaIdInput.type = "hidden";
    maquinaIdInput.name = "id_machine";
    maquinaIdInput.value = maquinaId; // Usar el id_machine capturado
    form.appendChild(maquinaIdInput);

    // Agregar el formulario al body y enviarlo
    document.body.appendChild(form);
    form.submit();
}
