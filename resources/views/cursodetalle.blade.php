@extends('layouts.default')

@php
    $title = $course->nombre
@endphp

@section('title', $title)

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/tabladatos.css') }}">
@endsection

@section('content')

    <div class="datos-container">
        <h1 class="text-center">{{ $course->nombre }}</h1>
        
        <h4 class="title">Fecha</h4>
        <p class="content">{{ $course->fecha }}</p>

        <h4 class="title">Número de horas</h4>
        <p class="content">{{ $course->horas }}</p>

        <h4 class="title">Profesor</h4>
        <p class="content">{{ $course->nombre_profesor }} {{ $course->apellidos_profesor }}</p>

        <h4 class="title">Descripción del curso</h4>
        <p class="content">{{ $course->descripcion }}</p>
    

        <div class="new-container d-flex justify-content-between my-5">
            <form action="" class="d-flex gap-3">
                <input type="text" class="form-control" name="busqueda" id="busqueda" placeholder="Palabra clave">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoAlumnoModal" onclick="nuevoAlumnoModal('{{ $course->id }}')">+ Añadir alumno</button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-responsive" id="tabla">
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellidos</th>
                        <th scope="col">Email</th>
                        <th scope="col">DNI</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        <tr>
                            <th scope="row" id="table-nombre">{{ $student->nombre }}</th>
                            <td id="table-apellidos">{{ $student->apellidos }}</td>
                            <td id="table-email">{{ $student->email }}</td>
                            <td id="table-dni">{{ $student->dni }}</td>
                            <td>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#eliminarModal" data-eliminarid="{{ $student->idRegistro }}" data-eliminarnombre="{{ $student->nombre }}" data-course="{{ $course->id }}" onclick="eliminarModal(this)">Eliminar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="paginacion">
            {{ $students->links('layouts._partials.paginator') }}
        </div>
    </div>

    <!-- Modal añadir alumnos disponibles al curso -->
    <div class="modal fade" id="nuevoAlumnoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-alumnoscurso" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Añadir alumnos al curso</h5>
                    <button type="button" class="close btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="search-container d-flex justify-content-between m-4">
                    <form action="" class="d-flex gap-3" id="buscarAlumnoModal">
                        <input type="text" class="form-control" name="busqueda" id="busqueda" placeholder="Palabra clave">
                        <button type="button" onclick="nuevoAlumnoModal('{{ $course->id }}')" class="btn btn-primary">Buscar</button>
                    </form>
                </div>

                <div class="table-responsive tabla-alumnos">
                    <table class="table table-striped table-responsive" id="tabla-alumnos-disponibles">
                        <thead>
                            <tr>
                                <th scope="col">Nombre</th>
                                <th scope="col">Apellidos</th>
                                <th scope="col">Email</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="content-students">
                            
                        </tbody>
                    </table>
                    <div class="modal-paginacion d-flex justify-content-center gap-4">
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <!-- Modal eliminar alumno del curso -->
    <div class="modal fade" id="eliminarModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Eliminar alumno del curso</h5>
                    <button type="button" class="close btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="" method="POST" id="eliminar-form">
                    @method('DELETE')
                    @csrf
                    <div class="modal-content px-4 py-4">
                        <span>¿Estás seguro de que quieres eliminar al alumno <a id="nombrealumno"></a> del curso {{ $course->nombre }}?</span>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" value="Cancelar">Cancelar</button>
                        <button type="button" id="confirmarEliminar" class="btn btn-danger">Eliminar alumno</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>

    // Modal para añadir alumnos al curso
    function nuevoAlumnoModal(idCourse)
    {
        let form = $("#buscarAlumnoModal"); //Identificamos el formulario de búsqueda por su id.
        let datosArray = form.serializeArray(); // Devolvería los datos del form en un array
        let keyword = datosArray.find(element => element.name == 'busqueda').value

        // Necesitamos comprobar que se haya introducido una keyword en el input de búsqueda antes para que si no hay nada
        // no de error al usar la url con 'busqueda' vacía
        let url = keyword != '' ? `/cursoalumno/${idCourse}/alumnos?busqueda=${keyword}` : `/cursoalumno/${idCourse}/alumnos`

        fetch(url, {
            method: 'GET'
        })
        .then(respuesta => {
            if (respuesta.ok) {
                return respuesta.json()
            }
            throw new Error("Error " + respuesta.status + " al llamar al backend: " + respuesta.statusText);
        })
        .then(respuesta => {
            // AL ABRIR Y CERRAR LA MODAL, LOS REGISTROS ANTERIORES SE MANTIENEN. DEBEMOS ELIMINAR LO QUE HABÍA ANTES
            $('#content-students').empty() // empty() elimina todo lo que haya dentro del elemento
            $('.modal-paginacion').empty() // text() SÓLO elimina texto

            respuesta.data.forEach(student => {
                let row = '<tr>' + 
                    '<td>' + student.nombre + '</td>' +
                    '<td>' + student.apellidos + '</td>' +
                    '<td>' + student.email + '</td>' +
                    '<td><form method="POST" id="inscripcion-form">@csrf' +
                    '<button type="button" class="btn btn-primary" onclick="inscripcion(' + "[" + student.id + "," + idCourse + "]" + ')">Añadir</button></td></tr>' +
                    '</form>';

                $('#content-students').append(row);
                // VER CÓMO AUMENTAR TAMAÑO VENTANA MODAL Y LUEGO BOTÓN PARA AÑADIR ALUMNO
            });

            let before = respuesta.prev_page_url ? `href="#" onclick="event.preventDefault(); getPageAlumnos('${respuesta.prev_page_url}', ${idCourse})"` : ''
            let after = respuesta.next_page_url ? `href="#" onclick="event.preventDefault(); getPageAlumnos('${respuesta.next_page_url}', ${idCourse})"` : ''
            let actualPage = respuesta.current_page
            let lastPage = respuesta.last_page
            let paginacion = `<a ${before}>Anterior</a><span>Página ${actualPage} de ${lastPage}</span><a ${after}>Siguiente</a>` // Esta mal

            $('.modal-paginacion').append(paginacion);

        })
        .catch(error => {
            console.error(error)
        })
    }

    // Función para obtener los alumnos disponibles procedentes de la paginación
    function getPageAlumnos(url, idCourse) {

        fetch(url, {
            method: 'GET'
        })
        .then(respuesta => {
            if (respuesta.ok) {
                return respuesta.json()
            }
            throw new Error("Error " + respuesta.status + " al llamar al backend: " + respuesta.statusText);
        })
        .then(respuesta => {
            // Eliminamos los datos que pudieran haber para que no se solapen cuando se muestren nuevos registros
            $('#content-students').empty()
            $('.modal-paginacion').empty()

            respuesta.data.forEach(student => {
                let row = '<tr>' + 
                    '<td>' + student.nombre + '</td>' +
                    '<td>' + student.apellidos + '</td>' +
                    '<td>' + student.email + '</td>' +
                    '<td><form method="POST" id="inscripcion-form">@csrf' +
                    '<button type="button" class="btn btn-primary" onclick="inscripcion(' + "[" + student.id + "," + idCourse + "]" + ')">Añadir</button></td></tr>' +
                    '</form>';

                $('#content-students').append(row);
                // VER CÓMO AUMENTAR TAMAÑO VENTANA MODAL Y LUEGO BOTÓN PARA AÑADIR ALUMNO
            });

            let before = respuesta.prev_page_url ? `href="#" onclick="event.preventDefault(); getPageAlumnos('${respuesta.prev_page_url}', ${idCourse})"` : ''
            let after = respuesta.next_page_url ? `href="#" onclick="event.preventDefault(); getPageAlumnos('${respuesta.next_page_url}', ${idCourse})"` : ''
            let actualPage = respuesta.current_page
            let lastPage = respuesta.last_page
            let paginacion = `<a ${before}>Anterior</a><span>Página ${actualPage} de ${lastPage}</span><a ${after}>Siguiente</a>` // Esta mal

            $('.modal-paginacion').append(paginacion);
        })
        .catch(error => {
            console.error(error)
        })
    }

    function inscripcion(data) {
        let form = $("#inscripcion-form");
        let datos = form.serializeArray();
        let token = datos.find(input => input.name == '_token').value
        
        fetch('/cursoalumno', {
            method: 'POST',
            headers: {
                'Content-type': 'application/json', // Lo que se envía desde JS
                'Accept': 'application/json', // Lo que se recibe como respuesta del fetch
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                alumno: data[0],
                curso: data[1]
            })
        })
        .then(respuesta => {
            if (respuesta.ok) {
                return respuesta.json()
            }
            throw new Error("Error " + respuesta.status + " al llamar al backend: " + respuesta.statusText);
        })
        .then (respuesta => {
            nuevoAlumnoModal(data[1]) // Como el contenido se introduce con fetch, tenemos que llamar a la función y no load como tal
            $('#tabla').load(`/cursos/${data[1]} #tabla`)
        })
        .catch (error => {
            console.error(error)
        })
    }

    // Al abrir modal para eliminar
    function eliminarModal(data) // El parámetro debe estar en el botón eliminar 
    {
        let idcourse = data.getAttribute('data-course'); // ID del curso
        let id = data.getAttribute('data-eliminarid'); // ID de course_students
        let nombre = data.getAttribute('data-eliminarnombre');
       
        document.getElementById('nombrealumno').innerHTML = nombre;
        let confirmar = document.getElementById("confirmarEliminar"); // El botón que confirma la eliminación del registro

        // Limpiamos eventos previos para que no se ejecuten cuando intentamos borrar otro registro
        confirmar.replaceWith(confirmar.cloneNode(true));
        confirmar = document.getElementById("confirmarEliminar");

            // Se ejecuta cuando hacemos click para confirmar el delete
            confirmar.addEventListener('click', function() {
                fetch(`/cursoalumno/${id}`, {
                    method: 'DELETE',
                    headers: {
                        // Otra forma de seleccionar el valor del token del form. Si no especificamos la id del formulario,
                        // seleccionará el primer formulario que encuentre con el input indicado del html
                        'X-CSRF-TOKEN': document.querySelector('#eliminar-form input[name=_token]').value
                    }
                })
                .then(respuesta => {
                    if (respuesta.ok) {
                        return respuesta.json()
                    }
                    throw new Error("Error " + respuesta.status + " al llamar al backend: " + respuesta.statusText);
                })
                .then (respuesta => {
                    alert('Alumno eliminado del curso.')
                    $('#eliminarModal').modal('hide')
                        
                    // Se recargan correctamente los datos de la tabla al eliminar un registro
                    $( "#tabla" ).load( `/cursos/${idcourse} #tabla` );
                    $(".paginacion").load(`/cursos/${idcourse} .paginacion`);
                })
                .catch (error => {
                    console.error(error)
                })
            })
        }

    </script>
@endsection