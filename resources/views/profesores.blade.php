@extends('layouts.default')

@section('title', 'Profesores')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/tabladatos.css') }}">
@endsection

@section('content')

    <div class="datos-container">
        <div class="text-center my-4">
            <h1>Profesores</h1>
        </div>
        
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
                        <input type="text" class="form-control w-100 mb-2" id="nuevo-nombre" name="nombre_nuevo" value="{{ old('nombre_nuevo') }}">
                        @error('nombre_nuevo')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror

                        <label for="apellidos">Apellidos</label>
                        <input type="text" class="form-control w-100 mb-2" id="nuevo-apellidos" name="apellidos_nuevo" value="{{ old('apellidos_nuevo') }}">
                        @error('apellidos_nuevo')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror

                        <label for="email">Email</label>
                        <input type="email" class="form-control w-100 mb-2" id="nuevo-email" name="email_nuevo" value="{{ old('email_nuevo') }}">
                        @error('email_nuevo')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror

                        <label for="dni">DNI</label>
                        <input type="text" class="form-control w-100 mb-2" id="nuevo-dni" name="dni_nuevo" value="{{ old('dni_nuevo') }}">
                        @error('dni_nuevo')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                
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
                        <input type="hidden" class="form-control w-100 mb-2" id="editar-id" name="id">

                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control w-100 mb-2" id="editar-nombre" name="nombre">
                        <div id="error-nombre"></div>

                        <label for="apellidos">Apellidos</label>
                        <input type="text" class="form-control w-100 mb-2" id="editar-apellidos" name="apellidos">
                        <div id="error-apellidos"></div>

                        <label for="email">Email</label>
                        <input type="email" class="form-control w-100 mb-2" id="editar-email" name="email">
                        <div id="error-email"></div>

                        <label for="dni">DNI</label>
                        <input type="text" class="form-control w-100 mb-2" id="editar-dni" name="dni">
                        <div id="error-dni"></div>
                        
                
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

    <!-- Para abrir el modal de nuevo profesor si la validacion de los input da error -->
    @if ($errors->has('nombre_nuevo') || $errors->has('apellidos_nuevo') || $errors->has('email_nuevo') || $errors->has('dni_nuevo'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var nuevoModal = new bootstrap.Modal(document.getElementById('nuevoModal'));
                nuevoModal.show();
            });
        </script>
    @endif
    
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/profesores.js') }}"></script>
@endsection()
