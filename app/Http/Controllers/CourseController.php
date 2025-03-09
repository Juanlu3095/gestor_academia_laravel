<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\Teacher;
use Error;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'busqueda' => 'string'
        ]);

        $keyword = $request->query('busqueda');

        if($keyword) {
            $courses = Course::getCourses($keyword);
        } else {
            $courses = Course::getCourses();
        }

        $teachers = Teacher::getTeachers(); // Intentar hacer esto en incidencias desde otra página en una modal pasando los datos
        
        return view('cursos', compact('courses', 'teachers'));
    }

    public function show(string $id)
    {
        $course = Course::getCourse($id);

        if(!$course) {
            abort(404, 'Curso no encontrado.');
        }

        return $course;
    }

    /*
    * It returns a view with course's info and the students enrolled
    */
    public function details (string $id, Request $request)
    {
        $course = self::show($id);
        $request->validate([
            'busqueda' => 'string'
        ]);
        $busqueda = $request->query('busqueda');

        if($busqueda) {
            $students = CourseStudent::getStudentsByCourse($id, $busqueda);
        } else {
            $students = CourseStudent::getStudentsByCourse($id);
        }

        return view('cursodetalle', compact('course', 'students'));        
    }

    public function create(Request $request)
    {
        $request->validate([
            'nombre' => 'string|required',
            'fecha' => 'string|required',
            'horas' => 'integer|required',
            'descripcion' => 'string',
            'profesor' => 'integer|required'
        ]);

        $course = Course::createCourse($request);

        if(!$course) {
            throw new Error('El curso no ha sido creado.');
        }

        return redirect()->route('cursos.index');
    }

    public function update(Request $request, string $id)
    {
        self::show($id);

        $request->validate([
            'nombre' => 'string|required',
            'fecha' => 'string|required',
            'horas' => 'integer|required',
            'descripcion' => 'string|required',
            'profesor' => 'integer'
        ]);

        Course::updateCourse($request, $id);
        return redirect()->route('cursos.index');
    }

    public function delete(string $id)
    {
        self::show($id);

        $query = Course::deleteCourse($id);

        if(!$query) {
            abort(404, 'Curso no encontrado.');
        }

        return redirect()->route('cursos.index');
    }
}
