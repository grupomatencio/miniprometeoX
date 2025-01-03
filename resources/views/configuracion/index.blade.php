@extends('plantilla.plantilla')
@section('titulo', 'Delegations')
@section('contenido')

<div class="container">
    <meta name= "csrf-token" content="{{csrf_token()}}">


    <!-- Configuracion datos de disposicion -->

        @if(!$data['company'])

            <div class="col-8 offset-2 isla-list p-4 mt-2 mb-2 border border-primary">
                <h2 class="mb-3"> Configurar disposición</h2>
                <div class="form-floating">
                    <input type="text" name="company" class="form-control"
                        id="input_company" placeholder="Compañía">
                    <label for="company">Compañía</label>
                    <div class = "invalid-feedback" id="company_error_message">
                            TEXT ERROR
                    </div>
                </div>
                <div class="form-group mt-3 col-8 offset-2">
                    <button type="button" class="btn btn-primary w-100" id="button_company">Pedir datos de compañía </button>
                </div>
            </div>
        @endif



    <!-- configuracion de datos de conexiones -->

    @if($data['company'])
        <form action="{{ route('configuracion.update', $data['user_cambio']) }}" method="POST" autocomplete="off">
            @csrf
            @method('PUT')


            <div class="col-8 offset-2 isla-list p-4 mt-2 mb-2 border border-primary">
                <h2 class="mb-3"> Configurar disposición</h2>
                <h1 class='mb-2'>{{$data['company']}}</h1>

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
                                {{$data['name_delegation']}}
                            </td>
                            <td>
                                {{$data['name_zona']}}
                            </td>
                            <td>
                                @if (count($data['locales']) === 1 )
                                    {{$data['locales'][0] -> name }}
                                    <input type='hidden' name='locales' value = {{$data['locales'][0] -> id }}>
                                @else
                                    <select name="locales" class="form-control @error('locales') is-invalid @enderror">
                                        <option value =""> == Elije un Local ==</option>
                                        @foreach ($data['locales'] as $local)
                                            <option value = "{{$local -> id}}">{{$local -> name}} </option>
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
                    @if (session ('errorSerialNumber'))
                        <div class="text-danger fw-semibold text-center">{{ session ('errorSerialNumber') }} </div>
                    @endif
            </div>




            <div class="col-8 offset-2 isla-list p-4 mt-2 mb-2 border border-primary">
                <h2 class="mb-3"> Configurar conexión con servidor Prometeo</h2>

                <!-- IP -->
                <div class="form-floating pb-3">
                    <input type="text" name="ip_prometeo" class="form-control @error('ip_prometeo') is-invalid @enderror"
                        id="ip_prometeo" placeholder="IP"
                        @if (old('ip_prometeo'))
                            value="{{ old('ip_prometeo') }}"
                        @elseif ($data['user_prometeo']->ip)
                            value="{{$data['user_prometeo']->ip}}"
                        @endif>
                    <label for="ip_prometeo">IP</label>
                    @if ($errors->has('ip_prometeo'))
                        <div class="invalid-feedback"> {{ $errors->first('ip_prometeo') }} </div>
                    @endif
                </div>

                <!-- Puerto -->
                <div class="form-floating">
                    <input type="text" name="port_prometeo" class="form-control @error('port_prometeo') is-invalid @enderror"
                        id="port_prometeo" placeholder="Puerto"
                        @if (old('port_prometeo'))
                            value="{{ old('port_prometeo') }}"
                        @elseif ($data['user_prometeo']->port !== null)
                            value="{{$data['user_prometeo']->port}}"
                        @endif>
                    <label for="port_prometeo">Puerto</label>
                    @error('port_prometeo')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
            </div>



            <div class="col-8 offset-2 isla-list p-4 mt-2 mb-2 border border-primary">
                <h2 class="mb-3"> Configurar conexión con maquina cambio</h2>
                <!-- IP -->
                <div class="form-floating pb-3">
                    <input type="text" name="ip_cambio" class="form-control @error('ip_cambio') is-invalid @enderror"
                        id="ip_cambio" placeholder="IP"
                        @if (old('ip_cambio'))
                            value="{{ old('ip_cambio') }}"
                        @elseif ($data['user_cambio']->ip)
                            value="{{$data['user_cambio']->ip}}"
                        @endif>
                    <label for="ip_cambio">IP</label>
                    @if ($errors->has('ip_cambio'))
                        <div class="invalid-feedback"> {{ $errors->first('ip_cambio') }} </div>
                    @endif
                </div>

                <!-- Puerto -->
                <div class="form-floating">
                    <input type="text" name="port_cambio" class="form-control @error('port_cambio') is-invalid @enderror"
                        id="port_cambio" placeholder="Puerto"
                        @if (old('port_cambio'))
                            value="{{ old('port_cambio') }}"
                        @elseif ($data['user_cambio']->port)
                            value="{{$data['user_cambio']->port}}"
                        @endif>
                    <label for="port_cambio">Puerto</label>
                    @error('port_cambio')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
            </div>

            <div class="col-8 offset-2 isla-list p-4 mt-2 mb-2 border border-primary">
                <h2 class="mb-3"> Configurar ComDataHost</h2>

                <!-- IP -->
                <div class="form-floating pb-3">
                    <input type="text" name="ip_comdatahost" class="form-control col-4 @error('ip_comdatahost') is-invalid @enderror"
                        id="ip_comdatahost" placeholder="IP"
                        @if (old('ip_comdatahost'))
                            value="{{ old('ip_comdatahost') }}"
                        @elseif ($data['user_comDataHost']->ip)
                            value="{{$data['user_comDataHost']->ip}}"
                        @endif>
                    <label for="ip_comdatahost">IP</label>
                    @error('ip_comdatahost')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>

                <!-- Puerto -->
                <div class="form-floating">
                    <input type="text" name="port_comdatahost" class="form-control @error('port_comdatahost') is-invalid @enderror"
                        id="port_comdatahost" placeholder="Puerto"
                        @if (old('port_comdatahost'))
                            value="{{ old('port_comdatahost') }}"
                        @elseif ($data['user_comDataHost']->port)
                            value="{{$data['user_comDataHost']->port}}"
                        @endif>
                    <label for="port_comdatahost">Puerto</label>
                    @error('port_comdatahost')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>
            </div>

            <!-- Botón de Enviar -->
            <div class="form-group mt-3 col-4 offset-4">
                <button type="submit" class="btn btn-primary w-100">Guardar configuración</button>
            </div>
        </form>

        <!-- Bloque del botones -->
        <div class="d-flex">
            <a class="offset-4 col-4 pt-3 pb-3" href="{{ route('configuracion.buscar') }}">
                <button type="button" class="btn btn-primary w-100" >Obtener IP automaticámente</button>
            </a>
        </div>
        <div class="d-flex">
            <a class="offset-4 col-4 pt-3 pb-3" data-bs-toggle="modal"
                data-bs-target="#modalAccionesLocal{{ $data['user_cambio']->id }}">
                <button class="btn btn-danger w-100" >Borrar datos de configuración</button>
            </a>
        </div>

    @endif


    <!--MODAL ACCIONES-->
    <div class="modal fade" id="modalAccionesLocal{{ $data['user_cambio']->id }}"
        data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalAcciones" aria-hidden="true">
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
                    <form action="{{ route('configuracion.destroy', $data['user_cambio']) }}"
                        method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="submitt" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>


                                        <!--Modal eliminar-->
                                        <div class="modal fade" id="eliminarModal1"
                                            data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                            aria-labelledby="eliminarModal1" aria-hidden="true">
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
                                                        <form action="{{ route('configuracion.destroy', $data['user_cambio']) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submitt" class="btn btn-danger">Eliminar</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



<!--
<script>

    const buttonPedirCompany = document.getElementById('button_company');
    const blockDeError = document.getElementById('company_error_message');
    const PROMETEO_PRINCIPAL_IP = @json(session('PROMETEO_PRINCIPAL_IP'));

    if (buttonPedirCompany !==null) {

        buttonPedirCompany.addEventListener('click', function(event) {

                const company_name =  document.getElementById('input_company').value;

                console.log(company_name); // Verificar el valor de localId

                let $url = "http://"+PROMETEO_PRINCIPAL_IP+":8000/api/verify-company";

                    fetch($url, {
                        method: 'POST',
                        body: JSON.stringify({
                            company_name
                        }),
                        headers: {
                            'Content-Type': 'application/json'
                        }

                    })
                    .then(response => response.json())
                    .then(data => {
                        $company = data.company;
                        $ip_prometeo_propio = $company['ip'];   // IP prometeo de cliente
                        $port_prometeo_propio = $company['port']; // // Port prometeo de cliente

                        if (data.status === 'error') {
                            blockDeError.innerHTML = "Datos enviados son incorrectos";
                            blockDeError.classList.add('d-block');
                        } else {

                            fetch ('{{ route ('configuracion.save_company')}}', {   // guardamos datos de compania y ip
                                method:'POST',
                                headers: {'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                },
                                body: JSON.stringify({
                                    $company
                                })


                            })
                            .then (response => {

                                if (response.status !== 200) {
                                    blockDeError.innerHTML = "Error de guardar datos";
                                    blockDeError.classList.add('d-block');
                                } else {        // si datos guardados correctamento

                                    $url_prometeo_propio = "http://" + $ip_prometeo_propio + ":" + $port_prometeo_propio + "/api/send-data-company"  // url prometeo de cliente
                                    fetch($url_prometeo_propio, {    // preguntamos sobre informacion delegaciones, locales etc
                                        method: 'POST',
                                        body: JSON.stringify({
                                            company_name
                                        }),
                                        headers: {
                                            'Content-Type': 'application/json'
                                        }

                                    })

                                    .then(response => response.json())
                                    .then(data => {
                                        $companyInfo = data.company;

                                        if (data.status === 'error') {
                                            blockDeError.innerHTML = "No hay datos en servidor Prometeo";
                                            blockDeError.classList.add('d-block');
                                        } else {

                                            fetch ('{{ route ('configuracion.company')}}', {   // guardamos datos a BD
                                                method:'POST',
                                                headers: {'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                                },
                                                body: JSON.stringify({
                                                    $companyInfo
                                                })
                                            })
                                            .then (response => {
                                                window.location.href = '{{ route ('configuracion.index')}}';
                                                console.log ('finalizado!');
                                            })

                                            .catch(error => {
                                                 blockDeError.innerHTML = "Error de BD.";
                                                blockDeError.classList.add('d-block');
                                            });
                                        }
                                    })
                                    .catch(error => {
                                        blockDeError.innerHTML = "Error de servidor Prometeo.";
                                        blockDeError.classList.add('d-block');
                                    });

                                    }
                                })

                            .catch(error => {
                                    blockDeError.innerHTML = "Error de BD.";
                                    blockDeError.classList.add('d-block');
                            });
                        }

                    })
                    .catch(error => {
                        blockDeError.innerHTML = "Ocurrió un error al intentar enviar la solicitud.";
                        blockDeError.classList.add('d-block');
                    });
            });
    }
</script>

-->

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
                const companyResponse = await sendPostRequest(apiUrl, { company_name: companyName });   // Probamos nombre compania

                if (companyResponse.status === 'error') {
                    showErrorMessage("Datos enviados son incorrectos");
                    return;
                }

                const company = companyResponse.company;        // Obtenemos datos de compania, ip y  port de servidor de Prometeo de compania
                const ipPrometeoPropio = company.ip;
                const portPrometeoPropio = company.port;

                const saveCompanyResponse = await sendPostRequest('{{ route("configuracion.save_company") }}', company); //guardamos a BD datos de compania: nombre, ip, puerto

                console.log(saveCompanyResponse.message);

                if (saveCompanyResponse.message !== 'success') {
                    showErrorMessage("Error al guardar datos");
                    return;
                }

                const urlPrometeoPropio = `http://${ipPrometeoPropio}:${portPrometeoPropio}/api/send-data-company`;  // obtenemos datos de compania: locales etc
                const dataResponse = await sendPostRequest(urlPrometeoPropio, { company_name: companyName });

                if (dataResponse.status === 'error') {
                    showErrorMessage("No hay datos en servidor Prometeo");
                    return;
                }

                const companyInfo = dataResponse.company;
                const saveCompanyInfoResponse = await sendPostRequest('{{ route("configuracion.company") }}', companyInfo); // si tenemos datos de servidor -guardamos a BD


                if (saveCompanyInfoResponse.message === 'success') {
                    console.log("Proceso finalizado exitosamente!");
                    window.location.href = '{{ route ('configuracion.index')}}';   // si toto bien - reiniciamos pagina
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
        console.log (document.querySelector('meta[name="csrf-token"]').content);

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
</script>

@endsection
