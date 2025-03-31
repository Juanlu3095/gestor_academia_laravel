@extends('layouts.default')

@php
    $title = $incidence->titulo
@endphp

@section('title', $title)

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/incidencia.css') }}">
@endsection

@section('content')
<section id="title">
    <a href="{{ url()->previous() }}">← Volver atrás</a>
    <div class="text-center">
        <h1>{{ $incidence->titulo }}</h1>
        <h5>Incidencia</h5>
    </div>
</section>

<main id="principal">
    <section id="datosincidencia">
        <h4 class="title">Fecha</h4>
        <p class="content">{{ $incidence->fecha }}</p>

        <h4 class="title">Rol</h4>
        <p class="content">{{ $incidence->incidenceable_type }}</p>

        <h4 class="title">Nombre del {{ $incidence->incidenceable_type }} </h4>
        <p class="content">{{ $incidence->nombre }} {{ $incidence->apellidos }}</p>

        <h4 class="title">Descripción de la incidencia</h4>
        <p class="content">{{ $incidence->sumario }}</p>
    </section>

    <aside id="documento">
        @if ($incidence->document_id)
            @if (isset($_SERVER["HTTP_USER_AGENT"]) && !preg_match('/Mobile|Android|iPhone|iPad/i', $_SERVER['HTTP_USER_AGENT']))
                <embed src="{{ route('documento.get', $incidence->document_id) }}" type="application/pdf" width="100%" height="1100px"></embed>
            @else
                <button class="btn btn-primary download"><a href="{{ route('documento.download', $incidence->document_id) }}">Descargar documento</a></button>  
            @endif
        @else 
            <p>Esta incidencia no tiene ningún documento asignado.</p>
        @endif
    </aside>
</main>
@endsection

@section('scripts')
@endsection