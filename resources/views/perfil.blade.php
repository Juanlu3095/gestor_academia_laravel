@extends('layouts.default')

@section('title', 'Perfil')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/perfil.css') }}">
@endsection

@section('content')

    <!-- LOS FORMULARIOS HTML SÓLO ADMITEN GET Y POST, PARA EL RESTO USAR method -->
    <div class="container">
        <form action="{{ route('user.patch', $user[0]['id']) }}" method="POST" class="user-form">
            @method('PATCH')
            @csrf
            <h3>Nombre</h3>
            <input type="text" name="name" value="{{ $user[0]['name'] }}">
            @error('name')
                <p class="text-danger">{{ $message }}</p>
            @enderror

            <h3>Email</h3>
            <input type="text" name="email" value="{{ $user[0]['email'] }}">
            @error('email')
                <p class="text-danger">{{ $message }}</p>
            @enderror

            <button type="submit">Guardar cambios</button>
        </form>
    </div>
    
    <div class="container">
        <h3>Contraseña</h3>
        <form method="POST" action="{{ route('user.patch', $user[0]['id']) }}" class="pass-form">
            @method('PATCH')
            @csrf
            <input type="password" name="password">
            <button type="submit">Cambiar contraseña</button>
            @error('password')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </form>
    </div>

    <div class="container caution">
        <h3>Zona peligrosa</h3>
        <!-- Cerrar sesión requiere una solicitud POST y por ello usamos un formulario -->
        <form method="POST" action="{{ route('auth.logout') }}">
        @csrf
        <button type="submit">Cerrar sesión</button>
        </form>
    
        <!-- Cerrar sesión requiere una solicitud POST y por ello usamos un formulario -->
        <form method="POST" action="{{ route('user.delete', $user[0]['id']) }}">
        @method('DELETE')
        @csrf
        <button type="submit" class="btn-delete">Eliminar cuenta</button>
        </form>
    </div>
    
@endsection

