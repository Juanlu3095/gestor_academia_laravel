<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Course extends Model
{
    protected $fillable = ['nombre', 'fecha', 'horas', 'descripcion'];

    protected $table = 'courses'; // La tabla asignada al modelo

    public static function getCourses (string $keyword = null)
    {
        try {
            if ($keyword != null) {
                $courses = DB::table('courses')
                    ->join('teachers', 'courses.teacher_id', '=' , 'teachers.id')
                    ->select('courses.id', 'courses.nombre', 'courses.fecha', 'courses.horas', 'courses.descripcion', 'courses.teacher_id', 'teachers.nombre as nombre_profesor', 'teachers.apellidos as apellidos_profesor')
                    ->whereAny(['courses.nombre'], 'like', "%$keyword%")
                    ->orderBy('nombre')
                    ->paginate(5);

                    // Esto es lo mismo que:
                    // SELECT courses.id, courses.nombre, courses.fecha, courses.horas, courses.descripcion, teachers.nombre as profesor FROM `courses`
                    // INNER JOIN `teachers` ON courses.teacher_id = teachers.id;
            } else {
                $courses = DB::table('courses')
                    ->join('teachers', 'courses.teacher_id', '=' , 'teachers.id')
                    ->select('courses.id', 'courses.nombre', 'courses.fecha', 'courses.horas', 'courses.descripcion', 'courses.teacher_id', 'teachers.nombre as nombre_profesor', 'teachers.apellidos as apellidos_profesor')
                    ->orderBy('nombre')
                    ->paginate(5);
            }
            return $courses;

        } catch (Exception $e) {
            return 'Error en la consulta. Código del error: ' . $e->getCode();
        }
    }

    public static function getCourse(string $id)
    {
        try {
            $course = DB::table('courses')
                ->join('teachers', 'courses.teacher_id', '=' , 'teachers.id')
                ->select('courses.id', 'courses.nombre', 'courses.fecha', 'courses.horas', 'courses.descripcion', 'teachers.nombre as nombre_profesor', 'teachers.apellidos as apellidos_profesor')
                ->where('courses.id', $id)
                ->first(); // Devuelve un sólo elemento y no una colección

            return $course;
        } catch (Exception $e) {
            return 'Error en la consulta. Código del error: ' . $e->getCode();
        }
    }

    public static function createCourse(Request $request)
    {
        try {
            $course = DB::table('courses')
                ->insert([
                    'nombre' => $request->nombre,
                    'fecha' => $request->fecha,
                    'horas' => $request->horas,
                    'descripcion' => $request->descripcion,
                    'teacher_id' => $request->profesor,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

            return $course;

        } catch (Exception $e) {
            return 'Error en la consulta. Código del error: ' . $e->getCode();
        }
    }

    public static function updateCourse(Request $request, string $id)
    {
        try {
            $query = DB::table('courses')
                ->where('id', $id)
                ->update([
                    'nombre' => $request->nombre,
                    'fecha' => $request->fecha,
                    'horas' => $request->horas,
                    'teacher_id' => $request->profesor,
                    'descripcion' => $request->descripcion,
                    'updated_at' => now()
                ]);

            return $query;
        } catch (Exception $e) {
            return 'Error en la consulta. Código del error: ' . $e->getCode();
        }
    }

    public static function deleteCourse(string $id)
    {
        try {
            $query = DB::table('courses')
                ->where('id', $id)
                ->delete();
            
            return $query;
        } catch (Exception $e) {
            return 'Error en la consulta. Código del error: ' . $e->getCode();
        }
    }
}
