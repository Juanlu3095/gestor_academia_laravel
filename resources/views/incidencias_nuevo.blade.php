@extends('layouts.default')

@section('title', 'Nueva incidencia')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/incidencia.css') }}">
@endsection

@section('content')

<main id="principal">
    <a href="{{ url()->previous() }}">← Volver atrás</a>

    <section class="title text-center">
        <h1>Nueva Incidencia</h1>
    </section>
    
    <section class="form-container">
        <form action="{{ route('incidencias.create') }}" method="POST" enctype="multipart/form-data">
            @method('POST')
            @csrf
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo">
                @error('titulo')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" class="form-control" id="fecha" name="fecha">
                @error('fecha')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label for="rol" class="form-label">Rol</label>
                <select class="form-select" id="rol" aria-label="Selecciona un rol" name="rol">
                    <option selected disabled>Elija el rol de la persona</option>
                    <option value="Profesor">Profesor</option>
                    <option value="Alumno">Alumno</option>
                </select>
                @error('rol')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <!-- Los option viene de Javascript -->
            <div class="mb-3">
                <label for="persona" class="form-label">Persona afectada</label>
                <select class="form-select" id="persona" aria-label="Selecciona una persona" name="persona">
                    <option selected>Elija la persona afectada por la incidencia</option>   
                </select>
                @error('persona')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label for="sumario" class="form-label">Sumario</label>
                <textarea class="form-control" id="sumario" rows="3" name="sumario"></textarea>
                @error('sumario')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label for="formFile" class="form-label">Documento opcional</label>
                <input class="form-control" type="file" id="formFile" name="documento">
                @error('documento')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary my-4">Guardar incidencia</button>
        </form>
    </section>
</main>

@endsection()

@section('scripts')
    <script src="{{ asset('assets/js/incidencias_nuevo.js') }}"></script>
@endsection