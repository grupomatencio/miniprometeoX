document.addEventListener("DOMContentLoaded", function() {
    console.log("Script cargado correctamente.");

    const editButtons = document.querySelectorAll('.edit');
    const guardarButtons = document.querySelectorAll('.guardar');
    const volverButtons = document.querySelectorAll('.volver');
    const eliminarButtons = document.querySelectorAll('.eliminar');

    const aliasInputs = document.querySelectorAll('.alias-input');
    const placaInputs = document.querySelectorAll('.placa-input');
    const selects = document.querySelectorAll('.select-control');

    const radioSingle = document.getElementById("single");
    const radioAll = document.getElementById("all");
    const saveAllBtn = document.getElementById("saveAll");

    let filaEnEdicion = null; // Variable para almacenar la fila que está en edición

    function actualizarEstado() {
        console.log("Actualizando estado de botones y inputs...");

        const isAllChecked = radioAll?.checked;
        if (saveAllBtn) saveAllBtn.disabled = !isAllChecked;

        aliasInputs.forEach(input => input.readOnly = !isAllChecked);
        placaInputs.forEach(input => input.readOnly = !isAllChecked);
        selects.forEach(select => select.disabled = !isAllChecked);

        editButtons.forEach(btn => btn.disabled = isAllChecked);
        guardarButtons.forEach(btn => btn.disabled = isAllChecked);
        volverButtons.forEach(btn => btn.disabled = isAllChecked);
        eliminarButtons.forEach(btn => btn.disabled = isAllChecked);
    }

    if (radioSingle) radioSingle.addEventListener("change", actualizarEstado);
    if (radioAll) radioAll.addEventListener("change", actualizarEstado);

    editButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            if (filaEnEdicion) {
                console.log("Ya hay una fila en edición. No se puede editar otra.");
                return;
            }

            console.log("Botón de edición presionado.");

            const buttonElement = event.target.closest('button');
            if (!buttonElement) {
                console.log("No se encontró el botón.");
                return;
            }

            const rowId = buttonElement.getAttribute('data-row');
            console.log("Editando fila con ID:", rowId);

            if (!rowId) {
                console.log("El botón no tiene data-row.");
                return;
            }

            const row = document.querySelector(`[data-row='${rowId}']`)?.closest('tr');

            if (!row) {
                console.log("No se encontró la fila correspondiente.");
                return;
            }

            filaEnEdicion = row; // Guardamos la fila en edición

            // Deshabilitar la edición de otras filas
            editButtons.forEach(btn => btn.disabled = true);
            eliminarButtons.forEach(btn => btn.disabled = true);

            // Ocultar botón de edición y eliminación en la fila actual
            buttonElement.classList.add('d-none');
            row.querySelector('.eliminar')?.classList.add('d-none');

            // Mostrar los botones de guardar y volver
            row.querySelector('.guardar')?.classList.remove('d-none');
            row.querySelector('.volver')?.classList.remove('d-none');

            // Habilitar edición (quitar readonly y disabled)
            row.querySelector('.alias-input')?.removeAttribute('readonly');
            row.querySelector('.placa-input')?.removeAttribute('readonly');
            row.querySelector('.select-control')?.removeAttribute('disabled');
        });
    });

    volverButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            console.log("Botón de volver presionado.");

            const buttonElement = event.target.closest('button');
            if (!buttonElement) {
                console.log("No se encontró el botón.");
                return;
            }

            const rowId = buttonElement.dataset.row;
            console.log("Restaurando fila con ID:", rowId);

            const row = document.querySelector(`[data-row='${rowId}']`)?.closest('tr');

            if (!row) {
                console.log("No se encontró la fila correspondiente.");
                return;
            }

            filaEnEdicion = null; // Liberamos la fila en edición

            // Restaurar la edición de otras filas
            editButtons.forEach(btn => btn.disabled = false);
            eliminarButtons.forEach(btn => btn.disabled = false);

            // Restaurar inputs a solo lectura
            row.querySelector('.alias-input')?.setAttribute('readonly', true);
            row.querySelector('.placa-input')?.setAttribute('readonly', true);
            row.querySelector('.select-control')?.setAttribute('disabled', true);

            // Ocultar los botones de guardar y volver
            row.querySelector('.guardar')?.classList.add('d-none');
            row.querySelector('.volver')?.classList.add('d-none');

            // Mostrar los botones de edición y eliminación
            row.querySelector('.edit')?.classList.remove('d-none');
            row.querySelector('.eliminar')?.classList.remove('d-none');
        });
    });

    actualizarEstado();


    console.log("Estado inicial de saveAllBtn:", saveAllBtn?.disabled);

    if (saveAllBtn) {

        // Método para el modal de confirmación
        function mostrarModalConfirmacion(mensaje, callback) {
            let modal = new bootstrap.Modal(document.getElementById("confirmModal"));
            document.getElementById("confirmModalMessage").textContent = mensaje;

            // Cuando el usuario hace clic en "Aceptar"
            document.getElementById("confirmModalBtn").onclick = function() {
                callback(true);
                modal.hide();
            };

            modal.show();
        }

        // Para mostrar alertas con el resultado del servidor
        function mostrarAlerta(tipo, mensaje) {
            let alertContainer = document.querySelector(".d-flex.justify-content-center.my-3");
            if (!alertContainer) {
                console.error("No se encontró el contenedor de alertas en la vista.");
                return;
            }

            let alertDiv = document.createElement("div");
            alertDiv.className =
                `alert alert-${tipo === "success" ? "success" : "danger"} alert-dismissible fade show text-center`;
            alertDiv.role = "alert";
            alertDiv.innerHTML =
                `${mensaje}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;

            alertContainer.appendChild(alertDiv);

            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }

        saveAllBtn.addEventListener('click', function() {
            console.log("Guardando todos los datos...");

            let rows = document.querySelectorAll('tbody tr');
            let data = [];
            let hasNumPlacaZero = false;

            rows.forEach(row => {
                let machineId = row.querySelector('input[name="machine_id"]')?.value;
                let numPlaca = row.querySelector('select[name^="numPlaca"]')?.value;
                let alias = row.querySelector('input[name^="alias"]')?.value;
                let selected = row.querySelector('input[type="checkbox"]')?.checked ? 1 : 0;

                if (machineId && numPlaca && alias) {
                    if (numPlaca === "0") {
                        hasNumPlacaZero = true;
                    }
                    data.push({
                        machine_id: machineId,
                        numPlaca,
                        alias,
                        selected
                    });
                }
            });

            console.log("Datos a enviar:", JSON.stringify(data, null, 2));

            if (data.length === 0) {
                mostrarModalConfirmacion(
                    "No hay datos para guardar. ¿Quieres eliminar todas las asociaciones?",
                    function(confirmado) {
                        if (confirmado) {
                            fetch(`${window.location.origin}/configurationAccountants/clearAll`, {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "X-CSRF-TOKEN": document.querySelector(
                                            'meta[name="csrf-token"]').content
                                    },
                                    body: JSON.stringify({
                                        clear_all: true
                                    })
                                })
                                .then(response => response.json())
                                .then(result => {
                                    window.location.reload();
                                })
                                .catch(error => {
                                    console.error("Error al eliminar asociaciones:", error);
                                });
                        }
                    });
                return;
            }

            if (hasNumPlacaZero) {
                mostrarModalConfirmacion(
                    "Se eliminarán/editarán el número de placa y sus contadores. ¿Deseas continuar?",
                    function(confirmado) {
                        if (confirmado) {
                            enviarDatos(data);
                        }
                    });
                return;
            }

            enviarDatos(data);
        });

        function enviarDatos(data) {
            let csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!csrfToken) {
                alert("Error: No se encontró el token CSRF.");
                return;
            }

            fetch(`${window.location.origin}/configurationAccountants/storeAll`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify({
                        machines: data
                    })
                })
                .then(response => response.json().catch(() => {
                    throw new Error("Respuesta del servidor no es un JSON válido.");
                }))
                .then(result => {
                    console.log("Respuesta del servidor:", result);
                    mostrarAlerta(result.success ? "success" : "error", result.message);
                })
                .catch(error => {
                    console.error("Error en la solicitud:", error);
                });
        }
    }
});
