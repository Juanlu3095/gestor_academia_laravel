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
    /**
     * It returns a view with all courses. It also can return courses by search input
     * @param App\Http\Requests\CourseRequest $request
     * @return \Illuminate\View\View
     */
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

    /**
     * It returns an stdClass object from course model or 404 if not
     * @param string $id
     * @return stdClass
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function show(string $id)
    {
        $course = Course::getCourse($id);

        if(!$course) {
            abort(404, 'Curso no encontrado.');
        }

        return $course;
    }

    /**
    * It returns a view with course's info and the students enrolled
    * @param string $id
    * @param App\Http\Requests\CourseRequest $request
    * @return \Illuminate\View\View
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

    /**
     * It creates a course and redirects to cursos.index
     * @param CourseRequest $request
     * @return RedirectResponse
     * @throws Error
     */
    public function create(CourseRequest $request)
    {
        $course = Course::createCourse($request);

        if(!$course) {
            throw new Error('El curso no ha sido creado.');
        }

        return redirect()->route('cursos.index');
    }

    /**
     * It updates a course and redirects to cursos.index
     * @param CourseRequest $request
     * @return RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function update(CourseRequest $request, string $id)
    {
        $this->show($id);

        $updatedCourse = Course::updateCourse($request, $id);

        if($updatedCourse == 0) {
            abort(404, 'Curso no encontrado');
        }

        return redirect()->route('cursos.index');
    }

    /**
     * It deletes a course and redirects to cursos.index. If not returns 404.
     * @param string $id
     * @return RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException 
     */
    public function delete(string $id)
    {
        $this->show($id);

        $query = Course::deleteCourse($id);

        if($query == 0) {
            abort(404, 'Curso no encontrado.');
        }

        return redirect()->route('cursos.index');
    }
}
