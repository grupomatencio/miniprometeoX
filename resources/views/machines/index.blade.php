@extends('plantilla.plantilla')
@section('titulo', 'Máquinas')
@section('contenido')
    <div class="container d-none d-md-block">
        <div class="row">
            <div class="col-12 text-center d-flex justify-content-center mt-3 mb-3" id="headerAll">
                <div class="w-50 ttl">
                    <h1>Máquinas delegación Benidorm</h1>
                </div>
            </div>

            @include('plantilla.messages')

            <div class="row d-flex justify-content-center">
                <div class="col-12 isla-list">
                    <div class="row p-3">
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
                    </div>


                    <div class="d-flex justify-content-center gap-3 flex-wrap">
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
                                        <th scope="col">Auxiliar</th>
                                        <th scope="col">Anular pago manual</th>
                                        <th scope="col"><a class="btn btn-primary w-100 btn-ttl"
                                                href="{{ route('machines.create') }}">+</a></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($machines as $machine)
                                        <tr class="user-row">
                                            <form action="{{ route('machines.update', $machine->id) }}" method="POST"
                                                autocomplete="off">
                                                @csrf
                                                @method('PUT')
                                                <td>
                                                    <input type="text"
                                                        class="form-control w-100 @error('alias.' . $machine->id) is-invalid @enderror"
                                                        name="alias[{{ $machine->id }}]" id="{{ $machine->id }}"
                                                        value="{{ $machine->alias }}" disabled>
                                                    @error('alias.' . $machine->id)
                                                        <div class="invalid-feedback text-start"> {{ $message }} </div>
                                                    @enderror
                                                </td>
                                                <td>{{ $machine->identificador }}</td>
                                                <td class="position-relative">
                                                    <input type="number" min="0" max="{{ $auxCount }}"
                                                        class="form-control w-100 @error('r_auxiliar.' . $machine->id) is-invalid @enderror"
                                                        id="{{ $machine->id }}" name="r_auxiliar[{{ $machine->id }}]"
                                                        value="{{ $machine->r_auxiliar }}" disabled
                                                        oninput="validarAuxiliar(this, {{ $auxCount }})"
                                                        onchange="validarAuxiliar(this, {{ $auxCount }})">

                                                    <small id="error_{{ $machine->id }}"
                                                           class="text-danger d-none"
                                                           style="font-size: 12px;">
                                                        No hay más recargas auxiliares para asignar
                                                    </small>

                                                    @error('r_auxiliar.' . $machine->id)
                                                        <div class="invalid-feedback text-start">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <!-- Input hidden para enviar 0 cuando el checkbox está desmarcado -->
                                                        <input type="hidden" name="AnularPM[{{ $machine->id }}]"
                                                            value="0">

                                                        <!-- Checkbox con el mismo nombre para que sobrescriba el valor si se marca -->
                                                        <input type="checkbox" name="AnularPM[{{ $machine->id }}]"
                                                            id="AnularPM{{ $machine->id }}" value="1"
                                                            {{ $machine->AnularPM == 1 ? 'checked' : '' }} disabled>
                                                        <!-- 🔹 Añadimos "disabled" para que esté bloqueado al inicio -->
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        <button type="button" class="btn btn-warning w-100 btn-in edit"
                                                            id="{{ $machine->id }}"><i class="bi bi-pencil-square"
                                                                id="{{ $machine->id }}"></i></button>
                                                        <button type="submit"
                                                            class="btn btn-success w-100 btn-in d-none guardar"
                                                            id="{{ $machine->id }}"><i
                                                                class="bi bi-check-lg"></i></button>
                                                        <button type="button"
                                                            class="btn btn-secondary w-100 btn-in ms-2 d-none volver"
                                                            id="{{ $machine->id }}"><i
                                                                class="bi bi-x-circle"></i></button>
                                                        <a class="btn btn-danger w-100 btn-in ms-2 eliminar"
                                                            id="{{ $machine->id }}" data-bs-toggle="modal"
                                                            data-bs-target="#eliminarModal{{ $machine->id }}"><i
                                                                class="bi bi-trash3"></i></a>
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
                                    <a class="btn btn-primary w-100 btn-ttl">Auxiliares</a>
                                </div>
                            </div>
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th scope="col">Número de la auxiliar</th>
                                        <th scope="col">Nombre de la máquina</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($auxmoneys as $auxmoney)
                                        <tr>
                                            <td>{{ $auxmoney->TypeIsAux }}</td>
                                            <td>{{ $auxmoney->AuxName }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>



                    <div class="col-4 offset-4 pb-1 mt-3">
                        <div class="d-flex justify-content-center gap-3">
                            <a class="btn btn-warning px-4 py-2" href="{{ route('import.index') }}">
                                Importar <i class="bi bi-box-arrow-in-right"></i>
                            </a>
                            <a class="btn btn-warning px-4 py-2" href="{{ route('syncTypesTickets') }}">
                                Syncronizar tipos <i class="bi bi-ticket-perforated"></i>
                            </a>
                            <a class="btn btn-success px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalPdf">
                                Exportar <i class="bi bi-filetype-pdf"></i>
                            </a>
                            <button type="button" class="btn btn-warning px-4 py-2" data-bs-toggle="modal"
                                data-bs-target="#grabarAuxiliaresModal">
                                Grabar auxiliares <i class="bi bi-box-arrow-in-right"></i>
                            </button>
                        </div>
                    </div>


                    <!-- MODAL EXPORTAR PDF -->
                    <div class="modal fade" id="modalPdf" data-bs-backdrop="static" data-bs-keyboard="false"
                        tabindex="-1" aria-labelledby="modalPdfLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="modalPdfLabel">¿Qué máquinas quieres
                                        exportar?</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="text-center">
                                        <a class="btn btn-warning w-100 mb-2" href="#">Máquinas
                                            Salones</a>
                                        <a class="btn btn-warning w-100" href="#">Máquinas Bares</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal enviar auxiliares -->
                    <div class="modal fade" id="grabarAuxiliaresModal" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="grabarAuxiliaresModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="grabarAuxiliaresModalLabel">
                                        Grabar Auxiliares en archivo de texto remoto
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Cerrar"></button>
                                </div>
                                <form action="{{ route('sendAuxiliares') }}" method="POST" autocomplete="off">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Usuario remoto</label>
                                            <input type="text" class="form-control" id="username" name="username"
                                                autocomplete="off">
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Contraseña</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                autocomplete="new-password">
                                        </div>
                                        <div class="mb-3">
                                            <label for="ip_address" class="form-label">Dirección IP remota</label>
                                            <input type="text" class="form-control" id="ip_address" name="ip_address"
                                                required autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-warning">Confirmar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>





                    <div class="col-4 offset-8 pb-4">

                        <div class="d-flex justify-content-center mt-4">
                            @if (session('errorConfiguracion'))
                                <div class="text-danger fw-semibold text-center">
                                    {{ session('errorConfiguracion') }} </div>
                            @endif
                        </div>
                    </div>
                </div>


                <div class="d-flex justify-content-center mt-4">
                    <!-- pagination -->
                </div>
            </div>


        </div>
    </div>






    <div class="container d-block d-md-none text-center pt-5">
        <div class="ttl d-flex align-items-center p-2">
            <div>
                <a href="#" class="titleLink">
                    <i style="font-size: 30pt" class="bi bi-arrow-bar-left"></i>
                </a>
            </div>
            <div>
                <h1>Máquinas delegación Benidorm</h1>
            </div>
        </div>

        <div class="mt-5 p-3 isla-list">
            @if (count($machines) != 0)
                <div class="row p-2 mb-4">
                    <div class="col-12">
                        <a class="btn btn-primary w-100 btn-ttl">Máquinas</a>
                    </div>
                </div>
                <form action="#" method="GET" class="mb-4" autocomplete="off">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Buscar máquinas...">
                        <div class="input-group-append">
                            <button class="btn btn-warning" type="submit">Buscar</button>
                        </div>
                    </div>
                </form>
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Identificador</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($machines as $machine)
                            <tr class="user-row">
                                <td>{{ $machine->name }}</td>
                                <td>{{ $machine->identificador }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="col-4 offset-8 pb-4">
                    <a class="btn btn-primary w-100 btn-inf" data-bs-toggle="modal" data-bs-target="#modalPdfTlf"><i
                            class="bi bi-filetype-pdf"></i> Exportar</a>

                    <!-- MODAL EXPORTAR PDF -->
                    <div class="modal fade" id="modalPdfTlf" data-bs-backdrop="static" data-bs-keyboard="false"
                        tabindex="-1" aria-labelledby="modalPdfTlfLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="modalPdfLabel">¿Qué máquinas quieres
                                        exportar?</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="text-center">
                                        <a class="btn btn-warning w-100 mb-2" href="#">Máquinas
                                            Salones</a>
                                        <a class="btn btn-warning w-100" href="#">Máquinas
                                            Bares</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="d-flex justify-content-center mt-4">
                    Pagination <!--  { $machines->links('vendor.pagination.bootstrap-5') }} -->
                </div>
            @else
                <p>No existen máquinas!</p>
            @endif
        </div>
    </div>

    <script src="{{ asset('js/machines.js') }}"></script>


@endsection
