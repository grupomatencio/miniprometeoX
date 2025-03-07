@extends('plantilla.plantilla')
@section('titulo', 'Configuración máquina de cambio')
@section('contenido')
    <div class="container d-none d-md-block">
        <div class="row">
            <div class="col-12 text-center d-flex justify-content-center mt-3 mb-3" id="headerAll">
                <div class="w-50 ttl">
                    <h1>Asignar tipos de tickets con alias de las máquinas {{ $local->name }}</h1>
                </div>
            </div>
            @include('plantilla.messages')

            <div class="col-12  mt-5">
                <div class="row">
                    <div class="col-12  isla-list">
                        <div class="p-4 pb-0">
                            <div class="row p-2">
                                <div class="col-12">
                                    <a class="btn btn-primary w-100 btn-ttl">Asignar tipo con alias</a>
                                </div>
                                <div class="d-flex justify-content-center gap-3 mt-3 mb-3">

                                    <table class="table table-bordered text-center">
                                        <thead>
                                            <tr>
                                                <th scope="col">Tipo</th>
                                                <th scope="col">Comentario</th>
                                                <th scope="col">Alias</th>
                                                <th scope="col">Acciones</th>
                                            </tr>
                                        </thead>
                                        <!--<tbody>
                                                                                                                            @foreach ($uniqueTickets as $ticket)
    <tr data-row="{{ $ticket->TicketNumber }}">
                                                                                                                                    <form action="{{ route('configurationTypeAlias.store') }}"
                                                                                                                                        method="POST" class="d-flex align-items-center"
                                                                                                                                        id="form-{{ $ticket->TicketNumber }}">
                                                                                                                                        @csrf

                                                                                                                                        <td>
                                                                                                                                            <input type="text" class="form-control"
                                                                                                                                                value="{{ $ticket->Type }}" readonly>
                                                                                                                                        </td>
                                                                                                                                        <td>
                                                                                                                                            <input type="text" class="form-control"
                                                                                                                                                value="{{ $ticket->Comment }}" readonly>
                                                                                                                                        </td>

                                                                                                                                        <td>
                                                                                                                                            <input type="hidden" name="type"
                                                                                                                                                value="{{ $ticket->Type }}">
                                                                                                                                            <select name="alias" class="form-control select-control"
                                                                                                                                                disabled>
                                                                                                                                                <option value="">Seleccione un alias</option>
                                                                                                                                                @foreach ($machines as $machine)
    <option value="{{ $machine->id }}"
                                                                                                                                                        {{ isset($typeAlias[$ticket->Type]) && $typeAlias[$ticket->Type]->id_machine == $machine->id ? 'selected' : '' }}>
                                                                                                                                                        {{ $machine->alias }}
                                                                                                                                                    </option>
    @endforeach
                                                                                                                                            </select>
                                                                                                                                            <small id="error_{{ $ticket->TicketNumber }}"
                                                                                                                                                class="text-danger d-none" style="font-size: 12px;">
                                                                                                                                                Debes seleccionar primero un Alias antes de guardar
                                                                                                                                            </small>
                                                                                                                                        </td>
                                                                                                                                        <td>
                                                                                                                                            <div class="d-flex">
                                                                                                                                                <button type="button"
                                                                                                                                                    class="btn btn-warning w-100 btn-in edit"
                                                                                                                                                    data-row="{{ $ticket->TicketNumber }}"
                                                                                                                                                    data-alias="{{ isset($typeAlias[$ticket->TicketNumber]) ? $typeAlias[$ticket->TicketNumber]->alias : 'Sin alias' }}">
                                                                                                                                                    <i class="bi bi-pencil-square"></i>
                                                                                                                                                </button>

                                                                                                                                                <button type="button"
                                                                                                                                                    class="btn btn-success w-100 btn-in d-none guardar"
                                                                                                                                                    data-row="{{ $ticket->TicketNumber }}"
                                                                                                                                                    data-bs-toggle="modal"
                                                                                                                                                    data-bs-target="#modalAccionesLocal{{ $ticket->TicketNumber }}">
                                                                                                                                                    <i class="bi bi-check-lg"></i>
                                                                                                                                                </button>

                                                                                                                                                <button type="button"
                                                                                                                                                    class="btn btn-warning w-100 btn-in ms-2 d-none crear"
                                                                                                                                                    data-row="{{ $ticket->TicketNumber }}"
                                                                                                                                                    data-tipo="{{ $ticket->Type }}"
                                                                                                                                                    data-maquina-id="{{ isset($typeAlias[$ticket->TicketNumber]) ? $typeAlias[$ticket->TicketNumber]->id_machine : '' }}"
                                                                                                                                                    onclick="validarSeleccionAlias('{{ $ticket->TicketNumber }}', this)">
                                                                                                                                                    <i class="bi bi-floppy"></i>
                                                                                                                                                </button>

                                                                                                                                                <button type="button"
                                                                                                                                                    class="btn btn-secondary w-100 btn-in ms-2 d-none volver"
                                                                                                                                                    data-row="{{ $ticket->TicketNumber }}">
                                                                                                                                                    <i class="bi bi-x-circle"></i>
                                                                                                                                                </button>

                                                                                                                                                <button type="button"
                                                                                                                                                    class="btn btn-danger w-100 btn-in ms-2"
                                                                                                                                                    data-bs-toggle="modal"
                                                                                                                                                    data-bs-target="#eliminarModal{{ $ticket->TicketNumber }}">
                                                                                                                                                    <i class="bi bi-trash"></i>
                                                                                                                                                </button>
                                                                                                                                            </div>
                                                                                                                                        </td>
                                                                                                                                    </form>
                                                                                                                                </tr>

                                                                                                                                <div class="modal fade" id="modalCrearTipoAlias" data-bs-backdrop="static"
                                                                                                                                    data-bs-keyboard="false" tabindex="-1"
                                                                                                                                    aria-labelledby="modalCrearLabel" aria-hidden="true">
                                                                                                                                    <div class="modal-dialog">
                                                                                                                                        <div class="modal-content">
                                                                                                                                            <div class="modal-header">
                                                                                                                                                <h1 class="modal-title fs-5" id="modalCrearLabel">Confirmar
                                                                                                                                                    creación del tipo y su alias</h1>
                                                                                                                                                <button type="button" class="btn-close"
                                                                                                                                                    data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                                                                                                            </div>
                                                                                                                                            <div class="modal-body">
                                                                                                                                                <form id="crearTipoAliasForm">
                                                                                                                                                    @csrf
                                                                                                                                                    <div class="mb-3">
                                                                                                                                                        <label for="nuevoTipo"
                                                                                                                                                            class="form-label">Tipo</label>
                                                                                                                                                        <input type="text" class="form-control"
                                                                                                                                                            id="nuevoTipo" name="nuevoTipo" required
                                                                                                                                                            readonly>
                                                                                                                                                    </div>
                                                                                                                                                    <div class="mb-3">
                                                                                                                                                        <label for="nuevoAlias"
                                                                                                                                                            class="form-label">Alias</label>
                                                                                                                                                        <input type="text" class="form-control"
                                                                                                                                                            id="nuevoAlias" name="nuevoAlias" required
                                                                                                                                                            readonly>
                                                                                                                                                    </div>
                                                                                                                                                    <p>¿Estás seguro que quieres crear el nuevo tipo <strong
                                                                                                                                                            id="tipoCreado"></strong> y su alias <strong
                                                                                                                                                            id="aliasCreado"></strong>?</p>
                                                                                                                                                </form>
                                                                                                                                            </div>
                                                                                                                                            <div class="modal-footer">
                                                                                                                                                <button type="button" class="btn btn-secondary"
                                                                                                                                                    data-bs-dismiss="modal">Cancelar</button>
                                                                                                                                                <button type="button" class="btn btn-primary"
                                                                                                                                                    id="confirmarCrear">Crear</button>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                </div>

                                                                                                                                <div class="modal fade"
                                                                                                                                    id="modalAccionesLocal{{ $ticket->TicketNumber }}"
                                                                                                                                    data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                                                                                                                    aria-labelledby="modalAccionesLabel" aria-hidden="true">
                                                                                                                                    <div class="modal-dialog">
                                                                                                                                        <div class="modal-content">
                                                                                                                                            <div class="modal-header">
                                                                                                                                                <h1 class="modal-title fs-5" id="modalAccionesLabel">
                                                                                                                                                    Confirmar edición del tipo y su alias</h1>
                                                                                                                                                <button type="button" class="btn-close"
                                                                                                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                                                                            </div>
                                                                                                                                            <div class="modal-body">
                                                                                                                                                ¿Estás seguro que quieres guardar los cambios para el
                                                                                                                                                <strong>{{ $ticket->Type }}</strong> y su alias <strong
                                                                                                                                                    id="selected-alias-{{ $ticket->TicketNumber }}">
                                                                                                                                                    {{ isset($typeAlias[$ticket->TicketNumber]) ? $typeAlias[$ticket->TicketNumber]->alias : 'Sin alias' }}
                                                                                                                                                </strong>?
                                                                                                                                            </div>
                                                                                                                                            <div class="modal-footer">
                                                                                                                                                <form
                                                                                                                                                    action="{{ route('configurationTypeAlias.update', $ticket->Type) }}"
                                                                                                                                                    method="POST"
                                                                                                                                                    id="update-form-{{ $ticket->TicketNumber }}">
                                                                                                                                                    @csrf
                                                                                                                                                    @method('PUT')
                                                                                                                                                    <input type="hidden" name="type" value="{{ $ticket->Type }}">
                                                                                                                                                    <input type="hidden" name="id_machine" value="">
                                                                                                                                                    <input type="hidden" name="alias"
                                                                                                                                                        value="{{ isset($typeAlias[$ticket->TicketNumber]) ? $typeAlias[$ticket->TicketNumber]->alias : '' }}">

                                                                                                                                                    <button type="submit" class="btn btn-warning">Guardar
                                                                                                                                                        cambios</button>
                                                                                                                                                </form>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                </div>

                                                                                                                                <div class="modal fade" id="eliminarModal{{ $ticket->TicketNumber }}"
                                                                                                                                    data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                                                                                                                    aria-labelledby="eliminarModal{{ $ticket->TicketNumber }}"
                                                                                                                                    aria-hidden="true">
                                                                                                                                    <div class="modal-dialog">
                                                                                                                                        <div class="modal-content">
                                                                                                                                            <div class="modal-header">
                                                                                                                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">
                                                                                                                                                    ¡Eliminar el tipo {{ $ticket->Type }} de el alias <span
                                                                                                                                                        id="delete-alias-{{ $ticket->TicketNumber }}">{{ isset($typeAlias[$ticket->TicketNumber]) ? $typeAlias[$ticket->TicketNumber]->alias : 'Sin alias' }}</span>!
                                                                                                                                                </h1>
                                                                                                                                                <button type="button" class="btn-close"
                                                                                                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                                                                            </div>
                                                                                                                                            <div class="modal-body">
                                                                                                                                                ¿Estás seguro que quieres eliminar la asociación del
                                                                                                                                                <strong>{{ $ticket->Type }}</strong> y su alias <strong
                                                                                                                                                    id="delete-alias-{{ $ticket->TicketNumber }}">
                                                                                                                                                    {{ isset($typeAlias[$ticket->Type]) ? $typeAlias[$ticket->Type]->alias : 'Sin alias' }}
                                                                                                                                                </strong>?
                                                                                                                                            </div>
                                                                                                                                            <div class="modal-footer">
                                                                                                                                                <form
                                                                                                                                                    action="{{ route('configurationTypeAlias.destroy', $ticket->Type) }}"
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
                                                                                                                        </tbody>-->

                                        <tbody>
                                            @foreach ($uniqueTickets as $ticket)
                                                <tr data-row="{{ $ticket->TicketNumber }}">
                                                    <form action="{{ route('configurationTypeAlias.store') }}"
                                                        method="POST" class="d-flex align-items-center"
                                                        id="form-{{ $ticket->TicketNumber }}">
                                                        @csrf <!-- Token CSRF para seguridad -->

                                                        <td>
                                                            <input type="text" class="form-control"
                                                                value="{{ $ticket->Type }}" readonly>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control"
                                                                value="{{ $ticket->Comment }}" readonly>
                                                        </td>

                                                        <td>
                                                            <input type="hidden" name="type"
                                                                value="{{ $ticket->Type }}">
                                                            <select name="alias" class="form-control select-control"
                                                                disabled>
                                                                <option value="">Seleccione un alias</option>
                                                                @foreach ($machines as $machine)
                                                                    <option value="{{ $machine->id }}"
                                                                        {{ isset($typeAlias[$ticket->Type]) && $typeAlias[$ticket->Type]->id_machine == $machine->id ? 'selected' : '' }}>
                                                                        {{ $machine->alias }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <small id="error_{{ $ticket->TicketNumber }}"
                                                                class="text-danger d-none" style="font-size: 12px;">
                                                                Debes seleccionar primero un Alias antes de guardar
                                                            </small>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <button type="button"
                                                                    class="btn btn-warning w-100 btn-in edit"
                                                                    data-row="{{ $ticket->TicketNumber }}"
                                                                    data-alias="{{ isset($typeAlias[$ticket->TicketNumber]) ? $typeAlias[$ticket->TicketNumber]->alias : 'Sin alias' }}">
                                                                    <i class="bi bi-pencil-square"></i>
                                                                </button>

                                                                <button type="button"
                                                                    class="btn btn-success w-100 btn-in d-none guardar"
                                                                    data-row="{{ $ticket->TicketNumber }}"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#modalAccionesLocal{{ $ticket->TicketNumber }}">
                                                                    <i class="bi bi-check-lg"></i>
                                                                </button>





                                                                <!-- Botón para abrir el modal -->
                                                                <button type="button"
                                                                    class="btn btn-warning w-100 btn-in d-none ms-2 crear"
                                                                    data-row="{{ $ticket->TicketNumber }}"
                                                                    data-tipo="{{ $ticket->Type }}"
                                                                    data-maquina-id="{{ isset($typeAlias[$ticket->TicketNumber]) ? $typeAlias[$ticket->TicketNumber]->id_machine : '' }}"
                                                                    data-alias="{{ isset($typeAlias[$ticket->TicketNumber]) ? $typeAlias[$ticket->TicketNumber]->alias : '' }}"
                                                                    onclick="prepararModalCrear('{{ $ticket->Type }}', '{{ isset($typeAlias[$ticket->TicketNumber]) ? $typeAlias[$ticket->TicketNumber]->id_machine : '' }}', '{{ isset($typeAlias[$ticket->TicketNumber]) ? $typeAlias[$ticket->TicketNumber]->alias : '' }}', '{{ $ticket->TicketNumber }}')">
                                                                    <i class="bi bi-floppy"></i>
                                                                </button>







                                                                <button type="button"
                                                                    class="btn btn-secondary w-100 btn-in ms-2 d-none volver"
                                                                    data-row="{{ $ticket->TicketNumber }}">
                                                                    <i class="bi bi-x-circle"></i>
                                                                </button>

                                                                <!-- Botón para abrir el modal de eliminación -->
                                                                <button type="button"
                                                                    class="btn btn-danger w-100 btn-in ms-2"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#eliminarModal{{ $ticket->TicketNumber }}">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </form>
                                                </tr>


                                                <!-- Modal de acciones para confirmar la creación -->
                                                <div class="modal fade"
                                                    id="modalCrearTipoAlias{{ $ticket->TicketNumber }}"
                                                    data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                                    aria-labelledby="modalCrearTipoAliasLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <form id="formCrearAlias" method="POST"
                                                                action="{{ route('configurationTypeAlias.store') }}">
                                                                @csrf <!-- Incluir el token CSRF -->
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Crear la asignación de un tipo
                                                                        de ticket a un alias</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>

                                                                </div>
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="type" id="nuevoTipo">
                                                                    <input type="hidden" name="alias" id="nuevoAlias">
                                                                    <input type="hidden" name="id_machine"
                                                                        id="idMachine">

                                                                    <p>¿Estás seguro que quieres asociar este
                                                                        tipo de ticket a este alias de una máquina?
                                                                    </p>
                                                                    <p>Tipo: <strong
                                                                            id="tipoMostrar">{{ $ticket->Type }}</strong>
                                                                    </p> <!-- Mostrar el tipo -->
                                                                    <p>Alias: <strong
                                                                            id="aliasMostrar">{{ isset($typeAlias[$ticket->TicketNumber]) ? $typeAlias[$ticket->TicketNumber]->alias : '' }}</strong>
                                                                    </p> <!-- Mostrar el alias -->
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Guardar</button>
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Cerrar</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>































































                                                <!-- Modal de acciones para confirmar la edición -->
                                                <div class="modal fade"
                                                    id="modalAccionesLocal{{ $ticket->TicketNumber }}"
                                                    data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                                    aria-labelledby="modalAccionesLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="modalAccionesLabel">
                                                                    Confirmar edición del tipo y su alias</h1>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                ¿Estás seguro que quieres guardar los cambios para el
                                                                <strong>{{ $ticket->Type }}</strong> y su alias <strong
                                                                    id="selected-alias-{{ $ticket->TicketNumber }}">
                                                                    {{ isset($typeAlias[$ticket->TicketNumber]) ? $typeAlias[$ticket->TicketNumber]->alias : 'Sin alias' }}
                                                                </strong>?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form
                                                                    action="{{ route('configurationTypeAlias.update', $ticket->Type) }}"
                                                                    method="POST"
                                                                    id="update-form-{{ $ticket->TicketNumber }}">
                                                                    @csrf
                                                                    @method('PUT') <!-- Método para actualizar -->
                                                                    <!-- Inputs ocultos para enviar los datos -->
                                                                    <input type="hidden" name="type"
                                                                        value="{{ $ticket->Type }}">
                                                                    <input type="hidden" name="id_machine"
                                                                        value="">
                                                                    <input type="hidden" name="alias"
                                                                        value="{{ isset($typeAlias[$ticket->TicketNumber]) ? $typeAlias[$ticket->TicketNumber]->alias : '' }}">
                                                                    <button type="submit" class="btn btn-warning">Guardar
                                                                        cambios</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Modal para confirmar la eliminación -->
                                                <div class="modal fade" id="eliminarModal{{ $ticket->TicketNumber }}"
                                                    data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                                    aria-labelledby="eliminarModal{{ $ticket->TicketNumber }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">
                                                                    ¡Eliminar el tipo {{ $ticket->Type }} de el alias <span
                                                                        id="delete-alias-{{ $ticket->TicketNumber }}">{{ isset($typeAlias[$ticket->TicketNumber]) ? $typeAlias[$ticket->TicketNumber]->alias : 'Sin alias' }}</span>?
                                                                </h1>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Una vez eliminado, no podrás recuperar este tipo ni su
                                                                alias.
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form
                                                                    action="{{ route('configurationTypeAlias.destroy', $ticket->Type) }}"
                                                                    method="POST"
                                                                    id="delete-form-{{ $ticket->TicketNumber }}">
                                                                    @csrf
                                                                    @method('DELETE') <!-- Método para eliminar -->
                                                                    <button type="submit"
                                                                        class="btn btn-danger">Eliminar</button>
                                                                </form>
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Cancelar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </tbody>


                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container d-block d-md-none text-center pt-5">
        <div class="ttl d-flex align-items-center p-2">
            <div>
                <a href="/" class="titleLink">
                    <i style="font-size: 20pt" class="bi bi-arrow-bar-left"></i>
                </a>
            </div>
            <div>
                <h1>Configuración de la máquina de cambio</h1>
            </div>
        </div>

        <div class="mt-5 p-3 isla-list">
            <div class="row p-2">
                <div class="col-12">
                    <a class="btn btn-primary w-100 btn-ttl">Sincronizar máquina de cambio con prometeo</a>
                </div>
                <div class="d-flex flex-column flex-md-row justify-content-center gap-3 mt-3 mb-3 w-75 mx-auto">
                    <a href="{{ route('sync.auxiliares') }}" class="btn btn-warning">Sync auxiliares</a>
                    <a href="{{ route('sync.config') }}" class="btn btn-warning">Sync configuración</a>
                    <a href="{{ route('sync.hcinfo') }}" class="btn btn-warning">Sync HC info...</a>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/configurationTypeAlias.js') }}"></script>


@endsection
