@extends('layouts.default')

@section('title', 'Alumnos')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/tabladatos.css') }}">
@endsection

@section('content')

    <div class="datos-container">
        <div class="text-center my-4">
            <h1>Alumnos</h1>
        </div>

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
        <div class="paginacion">
            {{ $students->links('layouts._partials.paginator') }}
        </div>
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
                        <input type="text" class="form-control w-100 mb-2" id="editar-nombre" name="nombre" value="{{ old('nombre') }}">

                        <label for="apellidos">Apellidos</label>
                        <input type="text" class="form-control w-100 mb-2" id="editar-apellidos" name="apellidos" value="{{ old('apellidos') }}">

                        <label for="email">Email</label>
                        <input type="email" class="form-control w-100 mb-2" id="editar-email" name="email" value="{{ old('email') }}">

                        <label for="dni">DNI</label>
                        <input type="text" class="form-control w-100 mb-2" id="editar-dni" name="dni" value="{{ old('dni') }}">
                
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

    <!-- Para abrir el modal de nuevo alumno si la validacion de los input da error -->
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
    <script src="{{ asset('assets/js/alumnos.js') }}"></script>
@endsection