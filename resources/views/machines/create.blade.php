@extends('plantilla.plantilla')

@section('contenido')
    <div class="container">
        <div class="col-8 offset-2 isla-list p-4 mt-5">
            <div class="ttl text-center mb-4">
                <h1>Crear máquina</h1>
            </div>
            <form action="{{ route('machines.store') }}" method="POST" autocomplete="off">
                @csrf

                <!-- Delegation ID (hidden) -->
                <input type="hidden" name="delegation_id" value="1">

                <!-- Nombre -->
                <div class="form-floating mb-3">
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        id="floatingName" placeholder="Nombre máquina" value="{{ old('name') }}">
                    <label for="floatingName">Nombre máquina</label>
                    @error('name')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>

                <!-- Alias -->
                <div class="form-floating mb-3">
                    <input type="text" name="alias" class="form-control @error('alias') is-invalid @enderror"
                        id="floatingAlias" placeholder="Alias máquina" value="{{ old('alias') }}">
                    <label for="floatingAlias">Alias máquina</label>
                    @error('alias')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>

                <!-- Modelo -->
                <div class="col-5 d-flex justify-content-between">
                    <div>
                        <p>Tipo modelo: </p>
                    </div>
                    @foreach (['A', 'B', 'C', 'X'] as $modelType)
                        <div class="form-check">
                            <input class="form-check-input @error('model') is-invalid @enderror" type="radio"
                                name="model" id="model{{ $modelType }}" value="{{ $modelType }}"
                                {{ old('model') == $modelType ? 'checked' : '' }}>
                            <label class="form-check-label" for="model{{ $modelType }}">{{ $modelType }}</label>
                        </div>
                    @endforeach
                </div>
                @error('model')
                    <div class="invalid-feedback d-block pb-4"> {{ $message }} </div>
                @enderror

                <!-- Código -->
                <div class="form-floating mb-3">
                    <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror"
                        id="floatingCodigo" placeholder="Código" value="{{ old('codigo') }}">
                    <label for="floatingCodigo">Código</label>
                    @error('codigo')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>

                <!-- Serie -->
                <div class="form-floating mb-3">
                    <input type="text" name="serie" class="form-control @error('serie') is-invalid @enderror"
                        id="floatingSerie" placeholder="Serie" value="{{ old('serie') }}">
                    <label for="floatingSerie">Serie</label>
                    @error('serie')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>

                <!-- Número -->
                <div class="form-floating mb-3">
                    <input type="text" name="numero" class="form-control @error('numero') is-invalid @enderror"
                        id="floatingNumero" placeholder="Número" value="{{ old('numero') }}">
                    <label for="floatingNumero">Número</label>
                    @error('numero')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror
                </div>

                <!-- Botón de Enviar -->
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">Crear máquina</button>
                    <button type="reset" class="btn btn-danger">Limpiar</button>
                </div>
            </form>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const radios = document.querySelectorAll('input[name="local"]');
                    const resumen = document.getElementById('resumenLocales');

                    function updateResumen() {
                        const selectedRadio = document.querySelector('input[name="local"]:checked');
                        if (selectedRadio) {
                            const label = document.querySelector(`label[for="${selectedRadio.id}"]`);
                            resumen.textContent = `Local seleccionado: ${label.textContent}`;
                        } else {
                            resumen.textContent = 'Local seleccionado: Taller';
                        }
                    }

                    // Escuchar cambios en los radios
                    radios.forEach(radio => {
                        radio.addEventListener('change', updateResumen);
                    });

                    // Actualizar resumen al cargar la página (en caso de que haya un valor preseleccionado)
                    updateResumen();
                });
            </script>
        </div>
    </div>
@endsection
