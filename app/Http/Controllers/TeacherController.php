<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeacherRequest;
use App\Models\Teacher;
use Error;

class TeacherController extends Controller
{
    public function index(TeacherRequest $request)
    {
        $busqueda = $request->query('busqueda');

        if($busqueda) {
            $teachers = Teacher::getTeachers($busqueda, true);
        } else {
            $teachers = Teacher::getTeachers(null, true);
        }

        return view('profesores', compact('teachers'));
    }

    public function list()
    {
        return Teacher::getTeachers();
    }

    public function show (string $id)
    {
        $teacher = Teacher::getTeacher($id);

        if($teacher->count() < 1) {
            abort(404, 'Profesor no encontrado.');
        }

        return $teacher;
    }

    public function create(TeacherRequest $request)
    {
        $student = Teacher::createTeacher($request);

        if(!$student) {
            throw new Error('El profesor no ha sido creado.');
        }

        return redirect()->route('profesores.index');
    }

    public function update(TeacherRequest $request, string $id)
    {
        $this->show($id); // Llamamos a la función del controlador para comprobar si existe el profesor

        Teacher::updateTeacher($id, $request);
        
        return response('Profesor actualizado.', 200);

    }

    public function delete (string $id)
    {
        $this->show($id);

        $query = Teacher::deleteTeacher($id);
        return $query; // Devuelve 1 si se elimina y 0 si no lo hace
    }
}
