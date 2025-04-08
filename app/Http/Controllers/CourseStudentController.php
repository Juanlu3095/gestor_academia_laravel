<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseStudentRequest;
use App\Models\Course;
use App\Models\CourseStudent;
use Error;

class CourseStudentController extends Controller
{
    /**
    * It allows to find a resource from database. This function will be used in others before doing anything.
    * @param String $id id for CourseStudent
    * @return \Illuminate\Support\Collection
    * @throws \Symfony\Component\HttpKernel\Exception\HttpException 
    */
    public function show(string $id)
    {
        $courseStudent = CourseStudent::getCourseStudent($id);

        if($courseStudent->count() < 1) {
            abort(404, 'Recurso no encontrado.');
        }

        return $courseStudent;
    }

    /**
    * It returns available students to enroll in a specific course.
    * @param String $idCourse id for course
    * @param CourseStudentRequest Contains busqueda input
    * @return \Illuminate\Support\Collection
    * @throws \Symfony\Component\HttpKernel\Exception\HttpException 
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

    /**
    * It allows to add a student to specific course. If all correct it returns bool, if not a string with error.
    * @param CourseStudentRequest
    * @return bool|string
    * @throws Error
    */
    public function addStudentToCourse (CourseStudentRequest $request)
    {
        $courseStudent = CourseStudent::createCourseStudent($request);

        if(!$courseStudent) {
            throw new Error('No se ha podido inscribir al alumno en el curso.');
        }

        return $courseStudent;

    }

    /**
    * It deletes a student from a specific course by id of CourseStudent. It returns 1 if all OK, 0 if error.
    * @param String $id
    * @return int|string String only if Exception
    */
    public function deleteStudentFromCourse(string $id)
    {
        $this->show($id);

        $query = CourseStudent::deleteCourseStudent($id);
        return $query;
    }
    
}
