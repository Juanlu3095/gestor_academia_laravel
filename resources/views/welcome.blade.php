<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
</head>
<body>
    <!-- Cerrar sesión requiere una solicitud POST y por ello usamos un formulario -->
    <form method="POST" action="{{ route('auth.logout') }}">
    @csrf
    <button type="submit">Cerrar sesión</button>
    </form>
    <h1>Hola</h1>
</body>
</html>