<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseStudent extends Model
{
    /*
    * It returns the resource by id. Only to use in controller to prove its existence.
    */
    public static function getCourseStudent(string $id)
    {
        try {
            $coursestudent = DB::table('course_students')
                ->select('id', 'course_id', 'student_id')
                ->where('id', $id)
                ->get();

            return $coursestudent;
        } catch (Exception $e) {
            return 'Error en la consulta. Código del error: ' . $e->getCode();
        }
    }

    /*
    * It returns students enrolled to a given course.
    */
    public static function getStudentsByCourse (string $courseId, string $busqueda = null)
    {
        try {
            $students = DB::table('students')
                ->select('course_students.id as idRegistro', 'students.id', 'students.nombre', 'students.apellidos', 'students.email', 'students.dni')
                ->join('course_students', 'students.id' , '=', 'course_students.student_id')
                ->join('courses', 'course_students.course_id' , '=', 'courses.id')
                ->where('courses.id', $courseId);

            if($busqueda != null) {
                $students = $students->whereAny(['students.nombre', 'students.apellidos', 'students.email', 'students.dni'], 'like', "%$busqueda%");
            }

            $students = $students->paginate(5);
            return $students;

            // Esto es lo mismo que:
            // SELECT students.nombre, students.apellidos, students.email FROM `students`
            // INNER JOIN `course_students`
            // ON students.id = course_students.student_id
            // INNER JOIN `courses`
            // ON course_students.course_id = courses.id
            // WHERE courses.id = 6;
        } catch (Exception $e) {
            return 'Error en la consulta. Código del error: ' . $e->getCode();
        }
    }

    /*
    * It returns students that are not enrolled to a specific course.
    */
    public static function getAvailableStudents(string $idCourse)
    {
        try {
            $query = DB::table('students')
                ->select('id', 'nombre', 'apellidos', 'email')
                ->whereNotExists(function(Builder $consulta) use ($idCourse) {
                    $consulta->select('student_id')
                        ->from('course_students')
                        ->where('course_id', $idCourse)
                        ->whereColumn('students.id', 'course_students.student_id'); // Cuando se comparan por tablas usar esto
                        // Esto último es necesario para relacionar students y course_students
                })
                ->orderBy('apellidos')
                ->paginate(5);
            return $query;
            /* CONSULTA: SELECT * FROM `students`
            WHERE NOT EXISTS(
                SELECT student_id FROM `course_students`
                WHERE students.id = course_students.student_id
                AND course_id = 6); */
        } catch (Exception $e) {
            return 'Error en la consulta. Código del error: ' . $e->getMessage();
        }
    }

    /*
    * It adds a student to a given course.
    */
    public static function createCourseStudent(Request $request)
    {
        try {
            $studentCourse = DB::table('course_students')
                ->insert([
                    'course_id' => $request->curso,
                    'student_id' => $request->alumno,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            return $studentCourse;
        } catch (Exception $e) {
            return 'Error en la consulta. Código del error: ' . $e->getMessage();
        }
    }

    public static function deleteCourseStudent (string $id)
    {
        try {
            $query = DB::table('course_students')
                ->where('id', $id)
                ->delete();
            return $query;
        } catch (Exception $e) {
            return 'Error en la consulta. Código del error: ' . $e->getCode();
        }
    }
}
