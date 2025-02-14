@extends('plantilla.plantilla')
@section('titulo', 'Máquinas')
@section('contenido')
    <div class="container">
        <div class="col-8 offset-2 isla-list p-4 mt-5">
            <div class="ttl text-center mb-4">
                <h1>Editar máquina</h1>
            </div>
            <form action="{{ route('machines.update', $machine->id) }}" method="POST" autocomplete="off">
                @csrf
                @method('PUT')

                <!-- Delegation ID (hidden) -->
                <input type="hidden" name="delegation_id" value="{{ old('delegation_id', $machine->delegation_id) }}">

                <!-- Nombre -->
                <div class="form-floating mb-3">
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="floatingName" placeholder="Nombre máquina" value="{{ old('name', $machine->name) }}">
                    <label for="floatingName">Nombre máquina</label>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Alias -->
                <div class="form-floating mb-3">
                    <input type="text" name="alias" class="form-control @error('alias') is-invalid @enderror" id="floatingAlias" placeholder="Alias máquina" value="{{ old('alias', $machine->alias) }}">
                    <label for="floatingAlias">Alias máquina</label>
                    @error('alias')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Modelo -->
                <div class="col-5 d-flex justify-content-between">
                    <div>
                        <p>Tipo modelo: </p>
                    </div>
                    @foreach(['A', 'B', 'C', 'X'] as $modelType)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="model" id="model{{ $modelType }}" value="{{ $modelType }}" {{ old('model', $mode) == $modelType ? 'checked' : '' }}>
                            <label class="form-check-label" for="model{{ $modelType }}">{{ $modelType }}</label>
                        </div>
                    @endforeach
                </div>
                @error('model')
                        <div class="invalid-feedback d-block pb-4">{{ $message }}</div>
                @enderror

                <!-- Código -->
                <div class="form-floating mb-3">
                    <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror" id="floatingCodigo" placeholder="Código" value="{{ old('codigo', $codigo) }}">
                    <label for="floatingCodigo">Código</label>
                    @error('codigo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Serie -->
                <div class="form-floating mb-3">
                    <input type="text" name="serie" class="form-control @error('serie') is-invalid @enderror" id="floatingSerie" placeholder="Serie" value="{{ old('serie', $serie) }}">
                    <label for="floatingSerie">Serie</label>
                    @error('serie')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Número -->
                <div class="form-floating mb-3">
                    <input type="text" name="numero" class="form-control @error('numero') is-invalid @enderror" id="floatingNumero" placeholder="Número" value="{{ old('numero', $numero) }}">
                    <label for="floatingNumero">Número</label>
                    @error('numero')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        // Obtener todos los inputs de tipo radio con las clases salon-radio y bar-radio
                        const salonRadios = document.querySelectorAll('.salon-radio');
                        const barRadios = document.querySelectorAll('.bar-radio');

                        // Obtener el elemento donde se mostrará el nombre del local seleccionado
                        const resumenLocales = document.getElementById('resumenLocales');

                        // Función para actualizar el resumen con el nombre del local seleccionado
                        function actualizarResumen() {
                            const seleccionado = document.querySelector('input[name="local"]:checked');
                            if (seleccionado) {
                                // Si hay un local seleccionado, mostrar su nombre en el resumen
                                let localSeleccionado = seleccionado.nextElementSibling.textContent;
                                resumenLocales.textContent = `Local seleccionado: ${localSeleccionado}`;
                            } else {
                                // Si no hay ningún local seleccionado, mostrar "Taller" por defecto
                                resumenLocales.textContent = 'Local seleccionado: Taller';
                            }
                        }

                        // Agregar evento a cada radio button para actualizar el resumen cuando se selecciona un local
                        salonRadios.forEach(radio => {
                            radio.addEventListener('change', actualizarResumen);
                        });

                        barRadios.forEach(radio => {
                            radio.addEventListener('change', actualizarResumen);
                        });

                        // Ejecutar la función al cargar la página para mostrar el local seleccionado por defecto
                        actualizarResumen();
                    });
                </script>

                <!-- Botón de Enviar -->
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">Editar máquina</button>
                </div>
            </form>
        </div>
    </div>
@endsection
