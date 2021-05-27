<!doctype html>
<html lang="es">
@php
    $datetime = new \DateTime();
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Notificacion de {{$evento}}</title>
</head>
<body>
    <p style="color:red;font-size:20px;"><span>&#9888;</span> ¡Atención! Se ha reportado evento de {{$evento}} reportado a {{ $datetime->format('H:i:s') }}.<span>&#9888;</span></p>
    <p>Emitido por: {{$name}}</p>
    <p>Estos son los detalles:</p>
    <ul>
        <li>Evento: {{$evento}}</li>
        <li>Tipo: {{$tipo}}</li>
        <li>Resultado: {{$resultado}}</li>
    </ul>
    <p>{{$mensaje}}</p>
    <img src="{{URL::asset('/images/logo.png')}}" alt="profile Pic" height="50" width="200">
</body>
</html>