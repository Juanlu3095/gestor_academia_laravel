@extends('layouts.default')

@section('title', 'Inicio')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/welcome.css') }}">
@endsection

@section('content')
    
    <div class="bienvenida">
        <h1>Bienvenido/a</h1>
        <h3>Te damos la bienvenida a nuestra sistema de gestión para academias.</h3>
    </div>
  
@endsection

@section('scripts')
    
@endsection
