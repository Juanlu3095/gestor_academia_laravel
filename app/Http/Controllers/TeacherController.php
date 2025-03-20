<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Error;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'busqueda' => 'string'
        ]);

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

    public function create(Request $request)
    {
        $request->validate([
            'nombre' => 'string|required',
            'apellidos' => 'string|required',
            'email' => 'email|required',
            'dni' => 'string|required',
        ]);

        $student = Teacher::createTeacher($request);

        if(!$student) {
            throw new Error('El profesor no ha sido creado.');
        }

        return redirect()->route('profesores.index');
    }

    public function update(Request $request, string $id)
    {
        self::show($id); // Llamamos a la función del controlador para comprobar si existe el profesor

        $request->validate([
            'nombre' => 'string|required',
            'apellidos' => 'string|required',
            'email' => 'email|required',
            'dni' => 'string|required',
        ]);
        Teacher::updateTeacher($id, $request);
        
        return response('Profesor actualizado.', 200);

    }

    public function delete (string $id)
    {
        self::show($id);

        $query = Teacher::deleteTeacher($id);
        return $query; // Devuelve 1 si se elimina y 0 si no lo hace
    }
}
