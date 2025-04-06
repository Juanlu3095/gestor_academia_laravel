<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseStudentRequest;
use App\Models\Course;
use App\Models\CourseStudent;
use Error;

class CourseStudentController extends Controller
{
    /*
    * It allows to find a resource from database. This function will be used in others before doing anything.
    */
    public function show(string $id)
    {
        $courseStudent = CourseStudent::getCourseStudent($id);

        if($courseStudent->count() < 1) {
            abort(404, 'Recurso no encontrado.');
        }

        return $courseStudent;
    }

    /*
    * It returns available students to enroll in a specific course.
    */
    public function getAvailableStudents(string $idCourse, CourseStudentRequest $request)
    {
        $course = Course::getCourse($idCourse); // Comprobamos que el curso exista

        if(!$course) {
            abort(404, 'El curso no existe.');
        }

        $keyword = $request->query('busqueda');

        if($keyword) {
            return CourseStudent::getAvailableStudents($idCourse, $keyword);
        } else {
            return CourseStudent::getAvailableStudents($idCourse);
        }

    }

    /*
    * It allows to add a student to specific course.
    */
    public function addStudentToCourse (CourseStudentRequest $request)
    {
        $courseStudent = CourseStudent::createCourseStudent($request);

        if(!$courseStudent) {
            throw new Error('No se ha podido inscribir al alumno en el curso.');
        }

        return $courseStudent;

    }

    public function deleteStudentFromCourse(string $id)
    {
        $this->show($id);

        $query = CourseStudent::deleteCourseStudent($id);
        return $query;
    }
    
}
