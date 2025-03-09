@extends('layouts.default')

@section('title', 'Cursos')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/tabladatos.css') }}">
@endsection

@section('content')
    <div class="datos-container">
        <div class="new-container d-flex justify-content-between">
            <form action="{{ route('cursos.index') }}" class="d-flex gap-3">
                <input type="text" class="form-control" name="busqueda" id="busqueda" placeholder="Palabra clave">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoModal">+ Añadir curso</button>
        </div>
        
        <!-- TABLA -->
        <div class="table-responsive">
            <table class="table table-striped table-responsive" id="tabla">
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Horas</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($courses as $course)
                        <tr>
                            <th scope="row" id="table-nombre">{{ $course->nombre }}</th>
                            <td id="table-apellidos">{{ $course->fecha }}</td>
                            <td id="table-email">{{ $course->horas }}</td>
                            <td>
                                <button type="button" class="btn btn-success"><a href="{{ route('cursos.details', $course->id) }}">Ver</a></button>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editarModal{{ $course->id }}" data-editar="{{ $course->id }}">Editar</button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#eliminarModal{{ $course->id }}"data-eliminarid="{{ $course->id }}" data-eliminarnombre="{{ $course->nombre }}">Eliminar</button>
                            </td>
                        </tr>

                        <!-- MODAL EDITAR CURSO -->
                        <div class="modal fade" id="editarModal{{ $course->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Editar curso</h5>
                                        <button type="button" class="close btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('cursos.update', $course->id) }}" method="POST" id="edit-form">
                                        <div class="modal-body">
                                            @method('PUT')
                                            @csrf
                                            <label for="nombre" class="form-label">Nombre</label>
                                            <input type="text" class="form-control w-100 mb-4" id="edit-nombre" name="nombre" value="{{ $course->nombre }}">

                                            <label for="fecha">Fecha</label>
                                            <input type="text" class="form-control w-100 mb-4" id="edit-fecha" name="fecha" value="{{ $course->fecha }}">

                                            <label for="horas">Horas</label>
                                            <input type="number" class="form-control w-100 mb-4" id="edit-horas" name="horas" value="{{ $course->horas }}">
                                            
                                            <label for="profesor">Profesor</label>
                                            <select class="form-control w-100 mb-4" id="edit-profesor" name="profesor" value="{{ $course->teacher_id }}">
                                                @foreach ($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}" @if ($teacher->id == $course->teacher_id) selected @endif>
                                                        {{ $teacher->nombre }} {{ $teacher->apellidos }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <label for="descripcion">Descripción</label>
                                            <textarea type="text" class="form-control w-100 mb-4" id="edit-descripcion" name="descripcion">{{ $course->descripcion }}
                                            </textarea>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" value="Cancelar">Cerrar</button>
                                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                        </div>
                                    </form>
                                    
                                </div>
                            </div>
                        </div>


                        <!-- MODAL ELIMINAR CURSO -->
                        <div class="modal fade" id="eliminarModal{{ $course->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Eliminar curso</h5>
                                        <button type="button" class="close btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <form action="{{ route('cursos.delete', $course->id) }}" method="POST" id="eliminar-form">
                                        @method('DELETE')
                                        @csrf
                                        <div class="modal-content px-4 py-4">
                                            <span>¿Estás seguro de que quieres eliminar el curso <a id="nombrecurso">{{ $course->nombre }}</a>?</span>
                                        </div>
                                        
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" value="Cancelar">Cerrar</button>
                                            <button type="submit" id="confirmarEliminar" class="btn btn-danger">Eliminar curso</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="paginacion">
            {{ $courses->links('layouts._partials.paginator') }}
        </div>
    </div>

    <!-- Modal nuevo curso -->
    <div class="modal fade" id="nuevoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Añadir curso</h5>
                    <button type="button" class="close btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('cursos.create') }}" method="POST" id="nuevo-form">
                    <div class="modal-body">
                        @method('POST')
                        @csrf
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control w-100 mb-4" id="nuevo-nombre" name="nombre">

                        <label for="fecha">Fecha</label>
                        <input type="text" class="form-control w-100 mb-4" id="nuevo-fecha" name="fecha">

                        <label for="horas">Horas</label>
                        <input type="number" class="form-control w-100 mb-4" id="nuevo-horas" name="horas">

                        <label for="profesor">Profesor</label>
                        <select class="form-control w-100 mb-4" id="nuevo-profesor" name="profesor">
                            <option value="" selected disabled hidden>
                                Selecciona un profesor
                            </option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}">
                                    {{ $teacher->nombre }} {{ $teacher->apellidos }}
                                </option>
                            @endforeach
                        </select>

                        <label for="descripcion">Descripción</label>
                        <textarea type="text" class="form-control w-100 mb-4" id="nuevo-descripcion" name="descripcion"></textarea>
                
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" value="Cancelar">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Crear curso</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
@endsection