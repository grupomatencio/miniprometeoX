@extends('plantilla.plantilla')
@section('titulo', 'Configuración')
@section('contenido')

    <div class="container">
        <meta name= "csrf-token" content="{{ csrf_token() }}">

        <div class="row">
            <div class="col-12 text-center d-flex justify-content-center mt-3 mb-3" id="headerAll">
                <div class="w-50 ttl">
                    <h1>Configuraciónes de Miniprometeo</h1>
                </div>
            </div>

            @include('plantilla.messages')

            <!-- Configuracion datos de disposicion -->

            @if (!$data['company'])
                <div class="col-8 offset-2 isla-list p-4 mt-2 mb-2 border border-secondary">
                    <h2 class="mb-3"> Configurar disposición</h2>
                    <div class="form-floating">
                        <input type="text" name="company" class="form-control" id="input_company" placeholder="Compañía">
                        <label for="company">Compañía</label>
                        <div class = "invalid-feedback" id="company_error_message">
                            TEXT ERROR
                        </div>
                    </div>
                    <div class="form-group mt-3 col-8 offset-2">
                        <button type="button" class="btn btn-warning w-100" id="button_company">Pedir datos de compañía
                        </button>
                    </div>
                </div>
            @endif

            <!-- configuracion de datos de conexiones -->

            @if ($data['company'])
                <form action="{{ route('configuration.update', $data['user_cambio']) }}" method="POST" autocomplete="off">
                    @csrf
                    @method('PUT')

                    <div class="col-8 offset-2 isla-list p-4 mt-2 mb-2 border border-secondary">
                        <div class="row mb-2">
                            <div class="col-12 d-flex justify-content-center">
                                <a class="btn btn-primary p-1 m-1 btn-ttl">Configurar compañia {{ $data['company'] }}</a>
                            </div>
                        </div>

                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th scope="col">Delegación</th>
                                    <th scope="col">Zona</th>
                                    <th scope="col">Local</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        {{ $data['name_delegation'] }}
                                    </td>
                                    <td>
                                        {{ $data['name_zona'] }}
                                    </td>
                                    <td>
                                        @if (count($data['locales']) === 1)
                                            {{ $data['locales'][0]->name }}
                                            <input type='hidden' name='locales' value={{ $data['locales'][0]->id }}>
                                        @else
                                            <select name="locales"
                                                class="form-control @error('locales') is-invalid @enderror">
                                                <option value =""> == Elije un Local ==</option>
                                                @foreach ($data['locales'] as $local)
                                                    <option value = "{{ $local->id }}">{{ $local->name }} </option>
                                                @endforeach
                                            </select>
                                            @error('locales')
                                                <div class="invalid-feedback"> {{ $message }} </div>
                                            @enderror
                                        @endif
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        @if (session('errorSerialNumber'))
                            <div class="text-danger fw-semibold text-center">{{ session('errorSerialNumber') }} </div>
                        @endif
                    </div>



                    <div class="col-8 offset-2 isla-list p-4 mt-2 mb-2 border border-success">
                        <div class="row mb-2">
                            <div class="col-12 d-flex justify-content-center">
                                @php
                                    // Verificar si hay clientes asignados
                                    $hasClients =
                                        isset($data['users']) &&
                                        $data['users']->isNotEmpty() &&
                                        $data['users']->some(fn($user) => $user->clients->isNotEmpty());
                                @endphp

                                @if ($hasClients)
                                    <!-- Mostrar "Cliente Asignado" por cada usuario con clientes -->
                                    @foreach ($data['users'] as $user)
                                        @if ($user->clients->isNotEmpty())
                                            <a class="btn btn-primary p-1 m-1 btn-ttl mb-2">Cliente Asignado para
                                                {{ $user->name }}</a>
                                        @endif
                                    @endforeach
                                @else
                                    <!-- Mostrar "Obtener y Guardar Datos del Cliente" si no hay clientes -->
                                    <a class="btn btn-primary p-1 m-1 btn-ttl">Obtener y Guardar Datos del Cliente</a>
                                @endif
                            </div>
                        </div>
                        @if (isset($data['users']) && $data['users']->isNotEmpty())
                            <!-- Mostrar clientes asignados si existen -->
                            @foreach ($data['users'] as $user)
                                @if ($user->clients->isNotEmpty())
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Cliente</th>
                                                <th>Creado en</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($user->clients as $client)
                                                <tr>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $client->name }}</td>
                                                    <td>{{ $client->created_at->format('Y-m-d H:i') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            @endforeach
                        @else
                            <!-- Mensaje si no hay usuarios -->
                            <div class="alert alert-danger">
                                No hay usuarios disponibles con un correo válido.
                            </div>
                        @endif

                        <!-- Seleccionar usuario con email válido si no hay clientes en ninguno -->
                        @if (isset($data['users']) && $data['users']->every(fn($user) => $user->clients->isEmpty()))
                            <div class="form-floating pb-3">
                                <select name="user_id" class="form-control @error('user_id') is-invalid @enderror"
                                    id="user_id">
                                    <option value="" disabled selected>Seleccione un usuario</option>
                                    @foreach ($data['users'] as $user)
                                        @if ($user->email)
                                            <option value="{{ $user->id }}" data-email="{{ $user->email }}"
                                                @if (old('user_id') == $user->id) selected @endif>
                                                {{ $user->name }} ({{ $user->email }})

                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <label for="user_id">Usuario</label>
                                @if ($errors->has('user_id'))
                                    <div class="invalid-feedback"> {{ $errors->first('user_id') }} </div>
                                @endif
                            </div>

                            <!-- Contraseña -->
                            <div class="form-floating pb-3">
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" id="password"
                                    placeholder="Contraseña">
                                <label for="password">Contraseña</label>
                                @if ($errors->has('password'))
                                    <div class="invalid-feedback"> {{ $errors->first('password') }} </div>
                                @endif
                            </div>

                            <!-- Botón para enviar la petición -->
                            <button type="button" class="btn btn-warning" id="getClientData">Obtener Cliente</button>
                        @endif

                        <!-- Mensaje de resultado -->
                        <div id="resultMessage" class="mt-3"></div>
                    </div>

                    <div class="col-8 offset-2 isla-list p-4 mt-2 mb-2 border border-secondary">
                        <div class="row mb-2">
                            <div class="col-12 d-flex justify-content-center">
                                <a class="btn btn-primary p-1 m-1 btn-ttl">Configuración con el servidor principal</a>
                            </div>
                        </div>
                        <div class="row">
                            <!-- IP -->
                            <div class="col-12 col-md-6">
                                <div class="form-floating pb-3 pb-md-0">
                                    <input type="text" name="ip_prometeo"
                                        class="form-control @error('ip_prometeo') is-invalid @enderror" id="ip_prometeo"
                                        placeholder="IP"
                                        @if (old('ip_prometeo')) value="{{ old('ip_prometeo') }}"
                                        @elseif ($data['user_prometeo']->ip)
                                        value="{{ $data['user_prometeo']->ip }}" @endif>
                                    <label for="ip_prometeo">IP</label>
                                    @if ($errors->has('ip_prometeo'))
                                        <div class="invalid-feedback"> {{ $errors->first('ip_prometeo') }} </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Puerto -->
                            <div class="col-12 col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="port_prometeo"
                                        class="form-control @error('port_prometeo') is-invalid @enderror" id="port_prometeo"
                                        placeholder="Puerto"
                                        @if (old('port_prometeo')) value="{{ old('port_prometeo') }}"
                                        @elseif ($data['user_prometeo']->port !== null)
                                        value="{{ $data['user_prometeo']->port }}" @endif>
                                    <label for="port_prometeo">Puerto</label>
                                    @if ($errors->has('port_prometeo'))
                                        <div class="invalid-feedback"> {{ $errors->first('port_prometeo') }} </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-8 offset-2 isla-list p-4 mt-2 mb-2 border border-secondary">
                        <div class="row mb-2">
                            <div class="col-12 d-flex justify-content-center">
                                <a class="btn btn-primary p-1 m-1 btn-ttl">Configuración con el servidor Ticketserver</a>
                            </div>
                        </div>
                        <div class="row">
                            <!-- IP -->
                            <div class="col-12 col-md-6">
                                <div class="form-floating pb-3 pb-md-0">
                                    <input type="text" name="ip_cambio"
                                        class="form-control @error('ip_cambio') is-invalid @enderror" id="ip_cambio"
                                        placeholder="IP"
                                        @if (old('ip_cambio')) value="{{ old('ip_cambio') }}"
                                        @elseif ($data['user_cambio']->ip)
                                        value="{{ $data['user_cambio']->ip }}" @endif>
                                    <label for="ip_cambio">IP</label>
                                    @if ($errors->has('ip_cambio'))
                                        <div class="invalid-feedback"> {{ $errors->first('ip_cambio') }} </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Puerto -->
                            <div class="col-12 col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="port_cambio"
                                        class="form-control @error('port_cambio') is-invalid @enderror" id="port_cambio"
                                        placeholder="Puerto"
                                        @if (old('port_cambio')) value="{{ old('port_cambio') }}"
                                        @elseif ($data['user_cambio']->port)
                                        value="{{ $data['user_cambio']->port }}" @endif>
                                    <label for="port_cambio">Puerto</label>
                                    @if ($errors->has('port_cambio'))
                                        <div class="invalid-feedback"> {{ $errors->first('port_cambio') }} </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-8 offset-2 isla-list p-4 mt-2 mb-2 border border-secondary">
                        <div class="row mb-2">
                            <div class="col-12 d-flex justify-content-center">
                                <a class="btn btn-primary p-1 m-1 btn-ttl">Configuración con el servidor de contadores</a>
                            </div>
                        </div>
                        <div class="row">
                            <!-- IP -->
                            <div class="col-12 col-md-6">
                                <div class="form-floating pb-3 pb-md-0">
                                    <input type="text" name="ip_comdatahost"
                                        class="form-control @error('ip_comdatahost') is-invalid @enderror"
                                        id="ip_comdatahost" placeholder="IP"
                                        @if (old('ip_comdatahost')) value="{{ old('ip_comdatahost') }}"
                                        @elseif ($data['user_comDataHost']->ip)
                                        value="{{ $data['user_comDataHost']->ip }}" @endif>
                                    <label for="ip_comdatahost">IP</label>
                                    @if ($errors->has('ip_comdatahost'))
                                        <div class="invalid-feedback"> {{ $errors->first('ip_comdatahost') }} </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Puerto -->
                            <div class="col-12 col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="port_comdatahost"
                                        class="form-control @error('port_comdatahost') is-invalid @enderror"
                                        id="port_comdatahost" placeholder="Puerto"
                                        @if (old('port_comdatahost')) value="{{ old('port_comdatahost') }}"
                                        @elseif ($data['user_comDataHost']->port)
                                        value="{{ $data['user_comDataHost']->port }}" @endif>
                                    <label for="port_comdatahost">Puerto</label>
                                    @if ($errors->has('port_comdatahost'))
                                        <div class="invalid-feedback"> {{ $errors->first('port_comdatahost') }} </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Contenedor flexible para alinear los botones en el centro -->
                    <div class="d-flex justify-content-center gap-3 mt-3">
                        <!-- Botón de Enviar dentro del formulario -->
                        <button type="submit" class="btn btn-warning px-4">Guardar</button>

                        <!-- Botón "Obtener datos contadores" -->
                        <a href="{{ route('configuration.buscar') }}">
                            <button type="button" class="btn btn-warning px-4">Obtener datos contadores</button>
                        </a>

                        <!-- Botón "Borrar datos" con modal -->
                        <a data-bs-toggle="modal" data-bs-target="#modalAccionesLocal{{ $data['user_cambio']->id }}">
                            <button class="btn btn-danger px-4">Borrar</button>
                        </a>
                    </div>

                </form>

                <!-- Bloque del botones
                            <div class="d-flex">
                                <a class="offset-4 col-4 pt-3 pb-3" href="{{ route('configuration.buscar') }}">
                                    <button type="button" class="btn btn-warning w-100">Obtener datos contadores</button>
                                </a>
                            </div>
                            <div class="d-flex">
                                <a class="offset-4 col-4 " data-bs-toggle="modal"
                                    data-bs-target="#modalAccionesLocal{{ $data['user_cambio']->id }}">
                                    <button class="btn btn-danger w-100">Borrar datos</button>
                                </a>
                            </div>-->

            @endif


            <!--MODAL ACCIONES-->
            <div class="modal fade" id="modalAccionesLocal{{ $data['user_cambio']->id }}" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalAcciones" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">!Eliminar
                                configuración!</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ¿Estas seguro que quieres eliminar datos del configuración?
                        </div>
                        <div class="modal-footer">
                            <form action="{{ route('configuration.destroy', $data['user_cambio']) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submitt" class="btn btn-danger">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <!--Modal eliminar-->
    <div class="modal fade" id="eliminarModal1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="eliminarModal1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">!Eliminar
                        configuración!</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estas seguro que quieres eliminar datos del configuración?
                </div>
                <div class="modal-footer">
                    <form action="{{ route('configuration.destroy', $data['user_cambio']) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="submitt" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>





    <!--Modal para reiniciar session-->
    <div class="modal fade" id="reiniciarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="eliminarModal1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">!Reiniciar session!</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Quieres reiniciar el sesión?
                </div>
                <div class="modal-footer">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf

                        <button type="submitt" class="btn btn-danger">Reiniciarr</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    @if (session('reiniciar') === true)
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var myModal = new bootstrap.Modal(document.getElementById('reiniciarModal'), {
                    keyboard: false
                });
                myModal.show();
            });
        </script>
    @endif




    <script>
        const buttonPedirCompany = document.getElementById('button_company');
        const blockDeError = document.getElementById('company_error_message');
        const PROMETEO_PRINCIPAL_IP = @json(session('PROMETEO_PRINCIPAL_IP'));
        const PROMETEO_PRINCIPAL_PORT = @json(session('PROMETEO_PRINCIPAL_PORT'));

        if (buttonPedirCompany !== null) {
            buttonPedirCompany.addEventListener('click', async () => {
                const companyName = document.getElementById('input_company').value;
                const apiUrl = `http://${PROMETEO_PRINCIPAL_IP}:${PROMETEO_PRINCIPAL_PORT}/api/verify-company`;

                try {
                    const companyResponse = await sendPostRequest(apiUrl, {
                        company_name: companyName
                    }); // Probamos nombre compania

                    if (companyResponse.status === 'error') {
                        showErrorMessage("Datos enviados son incorrectos");
                        return;
                    }

                    const company = companyResponse
                        .company; // Obtenemos datos de compania, ip y  port de servidor de Prometeo de compania
                    const ipPrometeoPropio = company.ip;
                    const portPrometeoPropio = company.port;

                    const saveCompanyResponse = await sendPostRequest(
                        '{{ route('configuration.save_company') }}', company
                    ); //guardamos a BD datos de compania: nombre, ip, puerto

                    console.log(saveCompanyResponse.message);

                    if (saveCompanyResponse.message !== 'success') {
                        showErrorMessage("Error al guardar datos");
                        return;
                    }

                    const urlPrometeoPropio =
                        `http://${ipPrometeoPropio}:${portPrometeoPropio}/api/send-data-company`; // obtenemos datos de compania: locales etc
                    const dataResponse = await sendPostRequest(urlPrometeoPropio, {
                        company_name: companyName
                    });

                    if (dataResponse.status === 'error') {
                        showErrorMessage("No hay datos en servidor Prometeo");
                        return;
                    }

                    const companyInfo = dataResponse.company;
                    const saveCompanyInfoResponse = await sendPostRequest(
                        '{{ route('configuration.company') }}', companyInfo
                    ); // si tenemos datos de servidor -guardamos a BD


                    if (saveCompanyInfoResponse.message === 'success') {
                        console.log("Proceso finalizado exitosamente!");
                        window.location.href =
                            '{{ route('configuration.index') }}'; // si toto bien - reiniciamos pagina
                    } else {
                        showErrorMessage("Error al guardar datos en la BD");
                    }
                } catch (error) {
                    showErrorMessage("Ocurrió un error inesperado.");
                    console.error(error);
                }
            });
        }

        /**
         * Realiza una solicitud POST.
         * @param {string} url - URL a la que se enviará la solicitud.
         * @param {object} data - Datos a enviar en el cuerpo de la solicitud.
         * @returns {Promise<Response|object>} - Respuesta o error parseado como JSON.
         */
        async function sendPostRequest(url, data) {
            const headers = {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            };
            console.log(document.querySelector('meta[name="csrf-token"]').content);

            const response = await fetch(url, {
                method: 'POST',
                headers,
                body: JSON.stringify(data),
            });

            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }

            return response.json();
        }

        /**
         * Muestra un mensaje de error en el bloque de errores.
         * @param {string} message - Mensaje a mostrar.
         */
        function showErrorMessage(message) {
            blockDeError.textContent = message;
            blockDeError.classList.add('d-block');
        }

        document.getElementById('getClientData').addEventListener('click', function() {
            const userSelect = document.getElementById('user_id');
            const selectedOption = userSelect.options[userSelect.selectedIndex];
            const userText = selectedOption.text;
            const match = userText.match(/\((.*?)\)/);

            if (!match || !match[1]) {
                document.getElementById('resultMessage').innerHTML =
                    '<div class="alert alert-danger">No se pudo obtener el email del usuario seleccionado. Por favor, revise las opciones.</div>';
                return;
            }

            const userEmail = match[1];
            const password = document.getElementById('password').value;

            if (!password) {
                document.getElementById('resultMessage').innerHTML =
                    '<div class="alert alert-danger">Por favor, complete la contraseña.</div>';
                return;
            }

            const prometeIp = '{{ session()->get('PROMETEO_PRINCIPAL_IP') }}';
            const prometePort = '{{ session()->get('PROMETEO_PRINCIPAL_PORT') }}';
            const url = `http://${prometeIp}:${prometePort}/api/getDataClient`;

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                    },
                    body: JSON.stringify({
                        email: userEmail,
                        password: password,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.client) {
                        document.getElementById('resultMessage').innerHTML =
                            `<div class="alert alert-success">
                        Cliente obtenido correctamente: <br>
                        <strong>Nombre:</strong> ${data.client.name}<br>
                    </div>`;

                        // Guardar los datos en la base de datos
                        return fetch('/saveClientData', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                            },
                            body: JSON.stringify({
                                email: match[1], // campo nuevo para el email
                                id: data.client.id,
                                user_id: data.client.user_id,
                                name: data.client.name,
                                client_secret: data.client.client_secret,
                            }),
                        });
                    } else {
                        document.getElementById('resultMessage').innerHTML =
                            '<div class="alert alert-danger">Error: No se pudo obtener el cliente.</div>';
                        return Promise.reject('Cliente no encontrado.');
                    }
                })
                .then(response => response.json())
                .then(saveResponse => {
                    if (saveResponse.message) {
                        document.getElementById('resultMessage').innerHTML +=
                            `<div class="alert alert-success">${saveResponse.message}</div>`;
                    } else {
                        document.getElementById('resultMessage').innerHTML +=
                            `<div class="alert alert-danger">${saveResponse.error}</div>`;
                    }
                })
                .catch(error => {
                    document.getElementById('resultMessage').innerHTML =
                        '<div class="alert alert-danger">Error en la solicitud: ' + error.message + '</div>';
                });
        });
    </script>

@endsection
