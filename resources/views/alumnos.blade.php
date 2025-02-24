@extends('layouts.default')

@section('title', 'Alumnos')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/alumnos.css') }}">
@endsection

@section('content')

    <div class="alumnos-container">
        <div class="new-container d-flex justify-content-between">
            <form action="{{ route('alumnos.index') }}" class="d-flex gap-3">
                <input type="text" class="form-control" name="busqueda" id="busqueda" placeholder="Palabra clave">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoModal">+ Añadir alumno</button>
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
                    @foreach ($students as $student)
                        <tr>
                            <th scope="row" id="table-nombre">{{ $student->nombre }}</th>
                            <td id="table-apellidos">{{ $student->apellidos }}</td>
                            <td id="table-email">{{ $student->email }}</td>
                            <td id="table-dni">{{ $student->dni }}</td>
                            <td>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editarModal" data-editar="{{ $student->id }}" onclick="verModal(this)">Editar</button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#eliminarModal"data-eliminarid="{{ $student->id }}" data-eliminarnombre="{{ $student->nombre }}" onclick="eliminarModal(this)">Eliminar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $students->links('layouts._partials.paginator') }}
    </div>

    <!-- MODALES -->
    <!-- Modal nuevo alumno -->
    <div class="modal fade" id="nuevoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Añadir alumno</h5>
                    <button type="button" class="close btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('alumnos.create') }}" method="POST" id="nuevo-form">
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
                        <button type="submit" class="btn btn-primary">Crear alumno</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal editar alumno -->
    <div class="modal fade" id="editarModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar alumno</h5>
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

    <!-- Modal eliminar alumno -->
    <div class="modal fade" id="eliminarModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Eliminar alumno</h5>
                    <button type="button" class="close btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="" method="POST" id="eliminar-form">
                    @method('DELETE')
                    @csrf
                    <div class="modal-content px-4 py-4">
                        <span>¿Estás seguro de que quieres eliminar el alumno <a id="nombrealumno"></a>?</span>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" value="Cancelar">Cerrar</button>
                        <button type="button" id="confirmarEliminar" class="btn btn-danger">Eliminar alumno</button>
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

            $.ajax({
                type: 'GET',
                url: '/alumnos/' + id,

                success: function (response) {
                    
                    $("#editar-id").val(response[0].id);
                    $("#editar-nombre").val(response[0].nombre);
                    $("#editar-apellidos").val(response[0].apellidos);
                    $("#editar-email").val(response[0].email);
                    $("#editar-dni").val(response[0].dni); 
                    
                    //$("#editar-form").attr('action', nuevaURL); // Solo crea /alumnos/6 MAL muy mal ChatGPT
                }
            })
        }

        // Al confirmar la edición del alumno
        function editarModal() {
            var form =$("#editar-form"); //Identificamos el formulario por su id. Podemos usar form.prop('action')
            var datos = form.serialize();  //Serializamos sus datos: method, _token y values de los input
            // var datosArray = form.serializeArray(); // Devolvería los datos en array

            $.ajax({
                type: 'PUT',
                url: '/alumnos/' + $("#editar-id").val(), // con val() obtenemos el value del input
                data: datos,

                success: function (response) {
                    if(response) {
                        
                        // Esto sólo edita el primer registro y modifica éste al intentar actualizar cualquier otro registro
                        /* $("#table-nombre").text(response[0].nombre); // con text() obtenemos el valor del <td>
                        $("#table-apellidos").text(response[0].apellidos);
                        $("#table-email").text(response[0].email);
                        $("#table-dni").text(response[0].dni);  */
                        
                        // Ésta es la forma correcta, se recarga sólo la tabla con los datos actualizados
                        $( "#tabla" ).load( "/alumnos #tabla" );

                        alert('Datos del alumno actualizados.')
                        $('#editarModal').modal('hide')
                    }
                },
                error: function (error) {
                    alert(`${error.responseJSON.message} Código del error: ${error.status}`)
                }
            })
        }

        // Al abrir modal para eliminar
        function eliminarModal(data) // El parámetro debe estar en el botón eliminar 
        {
            let id = data.getAttribute('data-eliminarid');
            let nombre = data.getAttribute('data-eliminarnombre');
            var form = $("#eliminar-form"); // Identificamos el formulario por su id. Podemos usar form.prop('action')
            var datos = form.serialize();  // Serializamos sus datos: method y _token, éste último nos lo pide para el delete

            document.getElementById('nombrealumno').innerHTML = nombre;
            confirmar = document.getElementById("confirmarEliminar");

            // Se ejecuta cuando hacemos click para confirmar el delete
            confirmar.addEventListener('click', function() {
                $.ajax({
                    type: 'DELETE',
                    url: '/alumnos/' + id,
                    data: datos,

                    success: function(response) {
                        alert('Alumno eliminado.')
                        $('#eliminarModal').modal('hide')
                        
                        // Se recargan correctamente los datos de la tabla al eliminar un registro
                        $( "#tabla" ).load( "/alumnos #tabla" );

                    },
                    error: function (error) {
                        alert(`${error.responseJSON.message} Código del error: ${error.status}.`)
                    }

                })
            })

        }
    </script>
@endsection