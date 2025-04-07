@extends('layouts.default')

@section('title', 'Editar incidencia')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/incidencia_editar.css') }}">
@endsection

@section('content')

<main>
    <a href="{{ url()->previous() }}">← Volver atrás</a>

    <section class="title text-center">
        <h1>Editar Incidencia</h1>
    </section>
    
    <section class="form-container">
        <form action="{{ route('incidencias.update', $incidence->id) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" value="{{ $incidence->titulo }}">
                @error('titulo')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" class="form-control" id="fecha" name="fecha" value="{{ $incidence->fecha }}">
                @error('fecha')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label for="rol" class="form-label">Rol</label>
                <select class="form-select" id="rol" aria-label="Selecciona un rol" name="rol">
                    <option disabled>Elija el rol de la persona</option>
                    <option value="Profesor" {{ $incidence->incidenceable_type == 'Profesor' ? 'selected' : '' }}>Profesor</option>
                    <option value="Alumno" {{ $incidence->incidenceable_type == 'Alumno' ? 'selected' : '' }}>Alumno</option>
                </select>
                @error('rol')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <!-- Los option vienen de Javascript -->
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
                <textarea class="form-control" id="sumario" rows="3" name="sumario">{{ $incidence->sumario }}</textarea>
                @error('sumario')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-3">
                <label for="formFile" class="form-label">Documento opcional</label>
                <input class="form-control" type="file" id="formFile" name="documento">
                @if ($incidence->document_id)
                    <p>Documento actual: {{ $incidence->documento }}</p>
                @endif
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
<script>
    // OBTIENE LAS PERSONAS NADA MÁS CARGAR LA PÁGINA CON LA PERSONA YA SELECCIONADA
    function getPersonas() {
        let rol = $("#rol").val();
        let partialUrl = ''
        
        if(rol == 'Profesor') {
            partialUrl = 'profesores'
        } else if (rol == 'Alumno') {
            partialUrl = 'alumnos'
        }

        fetch(`/${partialUrl}/lista`, {
            method: 'GET'
        })
        .then(respuesta => {
            if (respuesta.ok) {
                return respuesta.json()
            }
            throw new Error("Error " + respuesta.status + " al llamar al backend: " + respuesta.statusText);
        })
        .then (respuesta => {
            let defaultOption = '<option selected disabled>Elija la persona afectada por la incidencia</option>'

            $('#persona').empty() // Vaciamos las option dentro del select con id persona
            $('#persona').append(defaultOption); // Añadimos la opción por defecto

            respuesta.forEach(persona => {
                let options = '<option value="' + persona.id + '" ' + (persona.id == <?=$incidence->incidenceable_id?> ? 'selected' : '') + '>' + persona.nombre + ' ' +  persona.apellidos + '</option>'

            $('#persona').append(options); // Añadimos las personas (alumno o profesor) que nos vienen con el fetch
            })
        })
        .catch (error => {
            console.error(error)
        })
    }

    // SE EJECUTA AL CAMBIAR EL ROL EN EL INPUT, DEJANDO LA OPCIÓN POR DEFECTO COMO SELECCIONADA
    function getNuevasPersonas() {
        let rol = $("#rol").val();
        let partialUrl = ''
        
        if(rol == 'Profesor') {
            partialUrl = 'profesores'
        } else if (rol == 'Alumno') {
            partialUrl = 'alumnos'
        }

        fetch(`/${partialUrl}/lista`, {
            method: 'GET'
        })
        .then(respuesta => {
            if (respuesta.ok) {
                return respuesta.json()
            }
            throw new Error("Error " + respuesta.status + " al llamar al backend: " + respuesta.statusText);
        })
        .then (respuesta => {
            let defaultOption = '<option selected disabled>Elija la persona afectada por la incidencia</option>'

            $('#persona').empty() // Vaciamos las option dentro del select con id persona
            $('#persona').append(defaultOption); // Añadimos la opción por defecto

            respuesta.forEach(persona => {
                let options = '<option value="' + persona.id + '">' + persona.nombre + ' ' +  persona.apellidos + '</option>'

            $('#persona').append(options); // Añadimos las personas (alumno o profesor) que nos vienen con el fetch
            })
        })
        .catch (error => {
            console.error(error)
        })
    }

    // SE EJECUTA NADA MÁS CARGAR LA PÁGINA
    getPersonas()

    // ESTO SÓLO OBTIENE LAS PERSONAS CUANDO SE MODIFICA EL ROL, CUANDO SE CARGA LA PÁGINA POR 1a VEZ NO CARGAN
    $(document).on('change', '#rol', getNuevasPersonas); // Pasamos la función del handler sin (), sólo nombrándola

</script>
@endsection