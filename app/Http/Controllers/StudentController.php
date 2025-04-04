<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Models\Student;
use Error;

class StudentController extends Controller
{
    public function index(StudentRequest $request)
    {
        $busqueda = $request->query('busqueda'); // Obtenemos el parámetro de consulta del form con GET

        if($busqueda) {
            $students = Student::getStudents(['busqueda' => $busqueda, 'paginacion' => 'true']);
        } else {
            $students = Student::getStudents(['paginacion' => 'true']);
        }

        return view('alumnos', compact('students'));
    }

    public function list()
    {
        return Student::getStudents();
    }

    public function show(string $id)
    {
        $student = Student::getStudent($id);

        if($student->count() < 1) {
            abort(404, 'Alumno no encontrado.'); // Para app web es mejor usar abort y no response()
        }

        return $student;
    }

    public function create (StudentRequest $request)
    {
        $student = Student::createStudent($request);

        if(!$student) { // ¿Cuándo prodría ocurrir este error? ¿Cuándo se pierda la conexión a base de datos?
            throw new Error('El alumno no ha sido creado.');
        }

        return redirect()->route('alumnos.index');
    }

    public function update(StudentRequest $request, string $id)
    {
        self::show($id); // Podemos llamar directamente a show del controlador y no al modelo directamente

        Student::updateStudent($id, $request);

        // ESTÁ DEVOLVIENDO EL ANTIGUO, PERO AJAX CARGA LOS DATOS DE TODOS LOS ALUMNOS Y POR ESO SALE BIEN
        // return $student;
        
        return response('Alumno actualizado.', 200);

    }

    public function delete (string $id)
    {
        self::show($id);

        $query = Student::deleteStudent($id);
        return $query; // Devuelve 1 si se elimina y 0 si no lo hace
    }
}
