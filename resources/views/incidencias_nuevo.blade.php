@extends('layouts.default')

@section('title', 'Nueva incidencia')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/incidencia.css') }}">
@endsection

@section('content')

<main>
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
            </div>

            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" class="form-control" id="fecha" name="fecha">
            </div>

            <div class="mb-3">
                <label for="rol" class="form-label">Rol</label>
                <select class="form-select" id="rol" aria-label="Selecciona un rol" name="rol">
                    <option selected disabled>Elija el rol de la persona</option>
                    <option value="Profesor">Profesor</option>
                    <option value="Alumno">Alumno</option>
                </select>
            </div>

            <!-- Los option viene de Javascript -->
            <div class="mb-3">
                <label for="persona" class="form-label">Persona afectada</label>
                <select class="form-select" id="persona" aria-label="Selecciona una persona" name="persona">
                    <option selected>Elija la persona afectada por la incidencia</option>   
                </select>
            </div>

            <div class="mb-3">
                <label for="sumario" class="form-label">Sumario</label>
                <textarea class="form-control" id="sumario" rows="3" name="sumario"></textarea>
            </div>

            <div class="mb-3">
                <label for="formFile" class="form-label">Documento opcional</label>
                <input class="form-control" type="file" id="formFile" name="documento">
            </div>

            <button type="submit" class="btn btn-primary my-4">Guardar incidencia</button>
        </form>
    </section>
</main>

@endsection()

@section('scripts')
<script>
    $(document).on('change', '#rol', function(event) {
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
    });

</script>
@endsection