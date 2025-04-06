<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\Teacher;
use Error;

class CourseController extends Controller
{
    public function index(CourseRequest $request)
    {
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
    public function details (string $id, CourseRequest $request)
    {
        $course = $this->show($id);

        $busqueda = $request->query('busqueda');

        if($busqueda) {
            $students = CourseStudent::getStudentsByCourse($id, $busqueda);
        } else {
            $students = CourseStudent::getStudentsByCourse($id);
        }

        return view('cursodetalle', compact('course', 'students'));        
    }

    public function create(CourseRequest $request)
    {
        $course = Course::createCourse($request);

        if(!$course) {
            throw new Error('El curso no ha sido creado.');
        }

        return redirect()->route('cursos.index');
    }

    public function update(CourseRequest $request, string $id)
    {
        $this->show($id);

        Course::updateCourse($request, $id);
        return redirect()->route('cursos.index');
    }

    public function delete(string $id)
    {
        $this->show($id);

        $query = Course::deleteCourse($id);

        if(!$query) {
            abort(404, 'Curso no encontrado.');
        }

        return redirect()->route('cursos.index');
    }
}
