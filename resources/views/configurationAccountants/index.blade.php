@extends('plantilla.plantilla')
@section('titulo', 'Configuración')
@section('contenido')

    <div class="container d-none d-md-block">
        <div class="row">
            <div class="col-12 text-center d-flex justify-content-center mt-3 mb-3" id="headerAll">
                <div class="w-50 ttl">
                    <h1>Configuración de contadores</h1>
                </div>
            </div>

            @include('plantilla.messages')

            <div id="alert-container" class="position-fixed top-0 end-0 p-3"></div>



            <div class="row d-flex justify-content-center">
                <div class="col-12 isla-list">
                    <!--<div class="row p-3">
                                                                                                                                                                    <div class="col-12">
                                                                                                                                                                        <form action="{{ route('machines.search') }}" method="GET" class="mb-4" autocomplete="off">
                                                                                                                                                                            <div class="input-group">
                                                                                                                                                                                <input type="text" name="search" class="form-control"
                                                                                                                                                                                    placeholder="Buscar máquinas...">
                                                                                                                                                                                <div class="input-group-append">
                                                                                                                                                                                    <button class="btn btn-warning" type="submit">Buscar</button>
                                                                                                                                                                                </div>
                                                                                                                                                                            </div>
                                                                                                                                                                        </form>
                                                                                                                                                                    </div>
                                                                                                                                                                </div>-->

                    <div class="row p-3 justify-content-center text-center">
                        <div class="col-12">
                            <h2 class="titleSecondaryMoney">¿Cómo quieres configurar los contadores?</h2>
                            <div class="d-flex justify-content-center align-items-center gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="storeOption" id="single"
                                        value="single" checked>
                                    <label class="form-check-label" for="single">
                                        Individualmente
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="storeOption" id="all"
                                        value="all">
                                    <label class="form-check-label" for="all">
                                        Todos
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center gap-3 flex-wrap mt-3">
                        <div class="mx-auto">
                            <div class="row p-1">
                                <div class="col-12">
                                    <a class="btn btn-primary w-100 btn-ttl">Máquinas</a>
                                </div>
                            </div>
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th scope="col">Alias</th>
                                        <th scope="col">Identificador</th>
                                        <th scope="col">Asignar Número de placa</th>
                                        <th scope="col">
                                            <form id="saveAllForm" action="{{ route('configurationAccountants.storeAll') }}"
                                                method="POST">
                                                @csrf
                                                <button type="button" id="saveAll" class="btn btn-warning">Guardar
                                                    Todo</button>
                                            </form>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($machines as $machine)
                                        <tr class="user-row">
                                            <form action="{{ route('configurationAccountants.store') }}" method="POST"
                                                autocomplete="off">
                                                @csrf
                                                <input type="hidden" name="machine_id" value="{{ $machine->id }}">
                                                <td>
                                                    <input type="text"
                                                        class="form-control w-100 @error('alias.' . $machine->id) is-invalid @enderror"
                                                        name="alias[{{ $machine->id }}]" id="alias-{{ $machine->id }}"
                                                        data-row="{{ $machine->id }}" value="{{ $machine->alias }}"
                                                        readonly>
                                                    @error('alias.' . $machine->id)
                                                        <div class="invalid-feedback text-start"> {{ $message }} </div>
                                                    @enderror
                                                </td>
                                                <td>{{ $machine->identificador }}</td>
                                                <td>
                                                    <select name="numPlaca[{{ $machine->id }}]"
                                                        class="w-100 form-control select-control"
                                                        data-row="{{ $machine->id }}" readonly>

                                                        <option value="0">Seleccione placa</option>

                                                        @foreach ($numPlacas as $numPlaca)
                                                            <option value="{{ $numPlaca }}"
                                                                {{ isset($acumuladosLocales[$machine->id]) && $acumuladosLocales[$machine->id] == $numPlaca ? 'selected' : '' }}>
                                                                {{ $numPlaca }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                </td>

                                                <td>
                                                    <div class="d-flex">
                                                        <button type="button" class="btn btn-warning w-100 btn-in edit"
                                                            id="{{ $machine->id }}" data-row="{{ $machine->id }}">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
                                                        <button type="submit"
                                                            class="btn btn-success w-100 btn-in d-none guardar"
                                                            id="{{ $machine->id }}" data-row="{{ $machine->id }}">
                                                            <i class="bi bi-check-lg"></i>
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-secondary w-100 btn-in ms-2 d-none volver"
                                                            id="{{ $machine->id }}" data-row="{{ $machine->id }}">
                                                            <i class="bi bi-x-circle"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </form>
                                        </tr>

                                        <!--MODAL ACCIONES-->
                                        <div class="modal fade" id="modalAccionesLocal{{ $machine->id }}"
                                            data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                            aria-labelledby="modalAcciones" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="modalAccionesLabel">Acciones
                                                            para
                                                            la
                                                            máquina {{ $machine->alias }}</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="text-center">
                                                            <a class="btn btn-danger" data-bs-toggle="modal"
                                                                data-bs-target="#eliminarModal{{ $machine->id }}">
                                                                Eliminar
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!--Modal eliminar-->
                                        <div class="modal fade" id="eliminarModal{{ $machine->id }}"
                                            data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                            aria-labelledby="eliminarModal{{ $machine->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">!Eliminar
                                                            {{ $machine->alias }}!</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        ¿Estas seguro que quieres eliminar la máquina
                                                        {{ $machine->alias }}?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form action="{{ route('machines.destroy', $machine->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit"
                                                                class="btn btn-danger">Eliminar</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mx-auto">
                            <div class="row p-1">
                                <div class="col-12">
                                    <a class="btn btn-primary w-100 btn-ttl">Contadores</a>
                                </div>
                            </div>
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th scope="col">Número placa</th>
                                        <th scope="col">Nombre de la placa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($acumulados as $acumulado)
                                        <tr>
                                            <td>{{ $acumulado->NumPlaca }}</td>
                                            <td>{{ $acumulado->nombre }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <a class="btn btn-secondary m-3" href="{{ route('home') }}">Volver</a>
                    </div>

                </div>

                <!-- MODAL DE CONFIRMACIÓN -->
                <div class="modal fade" id="confirmModal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="confirmModalLabel">Confirmación de los cambios</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <p id="confirmModalMessage"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-warning" id="confirmModalBtn">Aceptar</button>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="d-flex justify-content-center mt-4">
                    <!-- pagination -->
                </div>
            </div>


        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            console.log("Script cargado correctamente."); // Verificar que el script se está ejecutando

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

            function actualizarEstado() {
                console.log("Actualizando estado de botones y inputs...");

                const isAllChecked = radioAll?.checked;
                if (saveAllBtn) saveAllBtn.disabled = !isAllChecked;

                aliasInputs.forEach(input => input.readOnly = !isAllChecked);
                placaInputs.forEach(input => input.readOnly = !isAllChecked);
                selects.forEach(select => select.disabled = !isAllChecked); // Los selects sí deben usar disabled

                editButtons.forEach(btn => btn.disabled = isAllChecked);
                guardarButtons.forEach(btn => btn.disabled = isAllChecked);
                volverButtons.forEach(btn => btn.disabled = isAllChecked);
                eliminarButtons.forEach(btn => btn.disabled = isAllChecked);
            }

            if (radioSingle) radioSingle.addEventListener("change", actualizarEstado);
            if (radioAll) radioAll.addEventListener("change", actualizarEstado);

            editButtons.forEach(button => {
                button.addEventListener('click', function(event) {
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

                    // Ocultar botón de edición y eliminación en la fila actual
                    buttonElement.classList.add('d-none');
                    row.querySelector('.eliminar')?.classList.add('d-none');

                    // Mostrar los botones de guardar y volver
                    row.querySelector('.guardar')?.classList.remove('d-none');
                    row.querySelector('.volver')?.classList.remove('d-none');

                    // Habilitar edición (quitar readonly)
                    row.querySelector('.alias-input')?.removeAttribute('readonly');
                    row.querySelector('.placa-input')?.removeAttribute('readonly');
                    row.querySelector('.select-control')?.removeAttribute(
                        'disabled'); // Solo para selects
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

                    // Restaurar inputs a solo lectura
                    row.querySelector('.alias-input')?.setAttribute('readonly', true);
                    row.querySelector('.placa-input')?.setAttribute('readonly', true);
                    row.querySelector('.select-control')?.setAttribute('disabled',
                        true); // Solo para selects

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
                        mostrarModalConfirmacion("Se eliminarán/editarán el número de placa y sus contadores. ¿Deseas continuar?",
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
    </script>



@endsection
