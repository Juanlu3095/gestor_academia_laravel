@extends('layouts.default')

@section('title', 'Profesores')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/tabladatos.css') }}">
@endsection

@section('content')

    <div class="datos-container">
        <div class="new-container d-flex justify-content-between">
            <form action="{{ route('profesores.index') }}" class="d-flex gap-3">
                <input type="text" class="form-control" name="busqueda" id="busqueda" placeholder="Palabra clave">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoModal">+ Añadir profesor</button>
        </div>
        
        <!-- TABLA -->
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
                    @foreach ($teachers as $teacher)
                        <tr>
                            <th scope="row" id="table-nombre">{{ $teacher->nombre }}</th>
                            <td id="table-apellidos">{{ $teacher->apellidos }}</td>
                            <td id="table-email">{{ $teacher->email }}</td>
                            <td id="table-dni">{{ $teacher->dni }}</td>
                            <td>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editarModal" data-editar="{{ $teacher->id }}" onclick="verModal(this)">Editar</button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#eliminarModal"data-eliminarid="{{ $teacher->id }}" data-eliminarnombre="{{ $teacher->nombre }}" onclick="eliminarModal(this)">Eliminar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="paginacion">
            {{ $teachers->links('layouts._partials.paginator') }}
        </div>
    </div>

    <!-- MODALES -->
    <!-- Modal nuevo profesor -->
    <div class="modal fade" id="nuevoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Añadir profesor</h5>
                    <button type="button" class="close btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('profesores.create') }}" method="POST" id="nuevo-form">
                    <div class="modal-body">
                        @method('POST')
                        @csrf
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control w-100 mb-4" id="nuevo-nombre" name="nombre">

                        <label for="apellidos">Apellidos</label>
                        <input type="text" class="form-control w-100 mb-4" id="nuevo-apellidos" name="apellidos">

                        <label for="email">Email</label>
                        <input type="email" class="form-control w-100 mb-4" id="nuevo-email" name="email">

                        <label for="dni">DNI</label>
                        <input type="text" class="form-control w-100 mb-4" id="nuevo-dni" name="dni">
                
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" value="Cancelar">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Crear profesor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal editar profesor -->
    <div class="modal fade" id="editarModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar profesor</h5>
                    <button type="button" class="close btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST" id="editar-form">
                    <div class="modal-body">
                        @method('PUT')
                        @csrf
                        <input type="hidden" class="form-control w-100 mb-4" id="editar-id" name="id">

                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control w-100 mb-4" id="editar-nombre" name="nombre">

                        <label for="apellidos">Apellidos</label>
                        <input type="text" class="form-control w-100 mb-4" id="editar-apellidos" name="apellidos">

                        <label for="email">Email</label>
                        <input type="email" class="form-control w-100 mb-4" id="editar-email" name="email">

                        <label for="dni">DNI</label>
                        <input type="text" class="form-control w-100 mb-4" id="editar-dni" name="dni">
                
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" value="Cancelar">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="editarModal()">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal eliminar profesor -->
    <div class="modal fade" id="eliminarModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Eliminar profesor</h5>
                    <button type="button" class="close btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="" method="POST" id="eliminar-form">
                    @method('DELETE')
                    @csrf
                    <div class="modal-content px-4 py-4">
                        <span>¿Estás seguro de que quieres eliminar el profesor <a id="nombreprofesor"></a>?</span>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" value="Cancelar">Cerrar</button>
                        <button type="button" id="confirmarEliminar" class="btn btn-danger">Eliminar profesor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
@endsection

@section('scripts')

<script>

// Al abrir modal de editar alumno
    function verModal(data) {
        let id = data.getAttribute('data-editar');

        fetch(`/profesores/${id}`)
            .then(response => response.json())
                .then(datos => {
                    $("#editar-id").val(datos[0].id);
                    $("#editar-nombre").val(datos[0].nombre);
                    $("#editar-apellidos").val(datos[0].apellidos);
                    $("#editar-email").val(datos[0].email);
                    $("#editar-dni").val(datos[0].dni);
                })
            .catch(error => {
                console.error(error)
            })
    }

    // Al confirmar la edición del alumno
    function editarModal() {
        var form = $("#editar-form"); //Identificamos el formulario por su id. Podemos usar form.prop('action')
        var datos = form.serialize();  //Serializamos sus datos: method, _token y values de los input
        var datosArray = form.serializeArray(); // Devolvería los datos en array

        fetch('/profesores/' + $("#editar-id").val(), {
            method: 'PUT',
            headers: {
                'Content-type': 'application/json', // Lo que se envía desde JS
                'Accept': 'text/plain', // Lo que se recibe como respuesta del fetch
                'X-CSRF-TOKEN': datosArray.filter(element => element.name == '_token')[0].value // necesitamos pasarle el token para POST, PUT y DELETE
            },
            body: JSON.stringify({
                // FILTER vs FIND para buscar elementos en un array
                // Filter devuelve un array (todos lo elementos que cumplen la condición => element.name == 'nombre')
                // Find devuelve un único objeto (el primero que cumple la condición)

                nombre: datosArray.filter(element => element.name == 'nombre')[0].value, 
                apellidos: datosArray.filter(element => element.name == 'apellidos')[0].value,
                email: datosArray.find(element => element.name == 'email').value,
                dni: datosArray.find(element => element.name == 'dni').value
            })
        })
        .then(respuesta => {
            if (respuesta.ok) {
                return respuesta.text()
            }
            throw new Error("Error " + respuesta.status + " al llamar al backend: " + respuesta.statusText);
        })
        .then(datos => {
            $( "#tabla" ).load( "/profesores #tabla" )
            alert(datos)
            $('#editarModal').modal('hide')
        })
        .catch(error => {
            console.error(error)
        })
    }

    // Al abrir modal para eliminar
    function eliminarModal(data) // El parámetro debe estar en el botón eliminar 
        {
            let id = data.getAttribute('data-eliminarid');
            let nombre = data.getAttribute('data-eliminarnombre');
            var form = $("#eliminar-form"); // Identificamos el formulario por su id. Podemos usar form.prop('action')
            var datos = form.serializeArray();  // Serializamos sus datos: method y _token, éste último nos lo pide para el delete

            document.getElementById('nombreprofesor').innerHTML = nombre;
            confirmar = document.getElementById("confirmarEliminar");

            // Se ejecuta cuando hacemos click para confirmar el delete
            confirmar.addEventListener('click', function() {
                fetch(`/profesores/${id}`, {
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
                    alert('Profesor eliminado.')
                    $('#eliminarModal').modal('hide')
                        
                    // Se recargan correctamente los datos de la tabla al eliminar un registro
                    $( "#tabla" ).load( "/profesores #tabla" );
                    $(".paginacion").load("/profesores .paginacion")
                })
                .catch (error => {
                    console.error(error)
                })
            })
        }
</script>
            
@endsection()
