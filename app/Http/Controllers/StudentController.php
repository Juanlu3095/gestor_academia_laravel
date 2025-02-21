<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Error;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::getStudents();

        return view('alumnos', compact('students'));
    }

    public function show(string $id)
    {
        $student = Student::getStudent($id);

        if($student->count() < 1) {
            abort(404, 'Alumno no encontrado.'); // Para app web es mejor usar abort y no response()
        }

        return $student;
    }

    public function create (Request $request)
    {
        $request->validate([
            'nombre' => 'string|required',
            'apellidos' => 'string|required',
            'email' => 'email|required',
            'dni' => 'string|required',
        ]);

        $student = Student::createStudent($request);

        if(!$student) { // ¿Cuándo prodría ocurrir este error? ¿Cuándo se pierda la conexión a base de datos?
            throw new Error('El alumno no ha sido creado.');
        }

        return redirect()->route('alumnos.index');
    }

    public function update(Request $request, string $id)
    {
        $student = self::show($id); // Podemos llamar directamente a show del controlador y no al modelo directamente

        $request->validate([
            'nombre' => 'string|required',
            'apellidos' => 'string|required',
            'email' => 'email|required',
            'dni' => 'string|required',
        ]);
        Student::updateStudent($id, $request);

        // Devolvemos el alumno actualizado para mostrarlo con AJAX
        return $student; // ¿NO DEBERÍA DEVOLVER EL ALUMNO ANTES DE SER ACTUALIZADO POR USAR $student DE ANTES DEL UPDATE?

    }

    public function delete (string $id)
    {
        self::show($id);

        $query = Student::deleteStudent($id);
        return $query; // Devuelve 1 si se elimina y 0 si no lo hace
    }
}
