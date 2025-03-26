@extends('layouts.default')

@section('title', 'Incidencias')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/tabladatos.css') }}">
@endsection

@section('content')

<div class="datos-container">
        <div class="new-container d-flex justify-content-between">
            <form action="{{ route('incidencias.index') }}" class="d-flex gap-3">
                <input type="text" class="form-control" name="busqueda" id="busqueda" placeholder="Palabra clave">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
            <button class="btn btn-primary"><a href="{{ route('incidencias.new') }}">Nueva incidencia</a></button>
        </div>
        
        <!-- TABLA -->
        <div class="table-responsive">
            <table class="table table-striped table-responsive" id="tabla">
                <thead>
                    <tr>
                        <th scope="col">Título</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellidos</th>
                        <th scope="col">Rol</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($incidences as $incidence)
                        <tr>
                            <th scope="row" id="table-titulo">{{ $incidence->titulo }}</th>
                            <td id="table-nombre">{{ $incidence->nombre }}</td>
                            <td id="table-apellidos">{{ $incidence->apellidos }}</td>
                            <td id="table-rol">{{ $incidence->rol }}</td>
                            <td id="table-fecha">{{ $incidence->fecha }}</td>
                            <td>
                                <button type="button" class="btn btn-primary"><a href="{{ route('incidencias.edit', $incidence->id) }}">Editar</a></button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#eliminarModal"data-eliminarid="{{ $incidence->id }}" data-eliminartitulo="{{ $incidence->titulo }}" onclick="eliminarModal(this)">Eliminar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="paginacion">
            
        </div>
    </div>

    <!-- Modal eliminar incidencia -->
    <div class="modal fade" id="eliminarModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Eliminar incidencia</h5>
                    <button type="button" class="close btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="" method="POST" id="eliminar-form">
                    @method('DELETE')
                    @csrf
                    <div class="modal-content px-4 py-4">
                        <span>¿Estás seguro de que quieres eliminar la incidencia <a id="nombreincidencia"></a>?</span>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" value="Cancelar">Cerrar</button>
                        <button type="button" id="confirmarEliminar" class="btn btn-danger">Eliminar incidencia</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function eliminarModal(data) // El parámetro debe estar en el botón eliminar 
    {
        let id = data.getAttribute('data-eliminarid');
        let titulo = data.getAttribute('data-eliminartitulo');
        var form = $("#eliminar-form"); // Identificamos el formulario por su id. Podemos usar form.prop('action')
        var datos = form.serialize();  // Serializamos sus datos: method y _token, éste último nos lo pide para el delete

        document.getElementById('nombreincidencia').innerHTML = titulo;
        confirmar = $("#confirmarEliminar");

        // Se ejecuta cuando hacemos click para confirmar el delete. off('click') elimina todos los eventos de click
        confirmar.off('click').on('click', function() {
            $.ajax({
                type: 'DELETE',
                url: '/incidencias/' + id,
                data: datos,

                success: function(response) {
                    alert('Incidencia eliminada.')
                    $('#eliminarModal').modal('hide')
                        
                    // Se recargan correctamente los datos de la tabla al eliminar un registro
                    $( "#tabla" ).load( "/incidencias #tabla" );
                    //$(".paginacion").load("/profesores .paginacion")

                },
                error: function (error) {
                    alert(`${error.responseJSON.message} Código del error: ${error.status}.`)
                }

            })
        })

    }
</script>
@endsection