<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>
            Errores
        </title>
        <link href="{{ asset('css/login.css') }}" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link rel="icon" href="{{ asset('img/sin fondo - copia_favicon.png') }}" type="image/x-icon">
    </head>

    <body>
        <div id="caja">
            @yield('content')
            <img src="{{ asset('img/sin fondo - copia.png') }}">
            <div>
                <!--dependiendo si esta logueado redirija a delegaciones dependiendo del rol-->
                <a class="btn btn-dark" href="javascript:history.back()">Volver</a>
            </div>
        </div>
    </body>

</html>
