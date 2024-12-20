@extends('plantilla.plantilla')

@section('contenido')

@if (session('error'))

RTYRTRYTURUIIOUIIO!!!!

@endif

<div class="container d-flex justify-content-between pb-0">
    <div>
        <div class="row">
            <h1>Permissions</h1>
        </div>
        <div>
            <p>Error de autorización. Pedir ayuda online</p>
        </div>

        <div>


            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

                <a href ="{{ route('pedir.ayuda') }}">
                    <button id="pedirAyuda" class="btn btn-primary">
                        Pedir ayuda online
                    </button>
                </a>




            @if (session('success'))
            <!-- form action="{{ route('home') }}" method="GET" style="d-inline-block">
                @csrf
                <button type="submit" class="btn btn-primary ms-5">
                    Comprobar de nuevo
                </button>
            </-form> -->
        </div>

        @endif
        @if (session('warning'))
            <div class="alert alert-warning">
                {{ session('warning')}}
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success mt-5">
                {{ session('success')}}
            </div>
        @endif
        {{ session('local')}}
    </div>
</div>

@endsection
