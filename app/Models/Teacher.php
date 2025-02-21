<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Teacher extends Model
{
    protected $fillable = ['nombre', 'apellidos', 'email', 'dni'];

    protected $hidden = [];

    public static function getTeachers ()
    {
        try {
            $teachers = DB::table('teachers')
                ->select('id', 'nombre', 'apellidos', 'email', 'dni')
                ->get();
            return $teachers;
        } catch (Exception $e) {
            return 'Error en la consulta. Código de error: ' . $e->getCode();
        }
    }

    public static function getTeacher (string $id)
    {
        try {
            $teacher = DB::table('teachers')
                ->select('id', 'nombre', 'apellidos', 'email', 'dni')
                ->where('id', $id)
                ->get();
            return $teacher;

        } catch (Exception $e) {
            if ($e->getCode() == 404) {
                return 'Profesor no encontrado. Código del error: ' . $e->getCode();
            } else {
                return 'Error en la consulta. Código del error: ' . $e->getCode();
            }
        }
    }

    public static function createTeacher (Request $request)
    {
        try {
            $teacher = DB::table('teachers')->insert([
                'nombre' => $request->nombre,
                'apellidos' => $request->apellidos,
                'email' => $request->email,
                'dni' => $request->dni,
                'updated_at' => now(),
                'created_at' => now()
            ]);

            return $teacher;

        } catch (Exception $e) {
            return 'Error en la consulta. Código de error: ' . $e->getCode();
        }
    }

    public static function updateTeacher (string $id, Request $request)
    {
        try {
            $teacher = DB::table('teachers')
                ->where('id', $id)
                ->update($request->except('_token', '_method'), ['updated_at' => now()]);
            return $teacher;

        } catch (Exception $e) {
            if ($e->getCode() == 404) {
                return 'No se ha encontrado al profesor. Código de error: ' . $e->getCode();
            } else {
                return 'Error en la consulta. Código de error: ' . $e->getCode();
            }
        }
    }

    public static function deleteTeacher (string $id)
    {
        try {
            $teacher = DB::table('teachers')
                ->where('id', $id)
                ->delete();
            
            return $teacher;
        } catch (Exception $e) {
            if ($e->getCode() == 404) {
                return 'No se ha encontrado al profesor. Código de error: ' . $e->getCode();
            } else {
                return 'Error en la consulta. Código de error: ' . $e->getCode();
            }
        }
    }
}
