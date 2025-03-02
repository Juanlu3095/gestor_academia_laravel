<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    protected $fillable = ['nombre', 'apellidos', 'email', 'dni'];

    protected $hidden = [];

    public static function getStudents(array $options = [])
    {
        $busqueda = $options['busqueda'] ?? null; // Establecemos los parámetros que pueden ir dentro del array $options
        $paginacion = $options['paginacion'] ?? false;

        try {
            if($busqueda != null) {
                $students = DB::table('students')
                    ->select('id', 'nombre', 'apellidos', 'email', 'dni')
                    ->whereAny(['nombre', 'apellidos', 'email', 'dni'], 'like', "%$busqueda%")
                    ->paginate(5);

            } else {
                $students = DB::table('students')
                    ->select('id', 'nombre', 'apellidos', 'email', 'dni')
                    ->paginate(5);
            }
            
            return $students;
            
        } catch (Exception $e) {
            return 'Error en la consulta. Código de error: ' . $e->getCode();
        }
    }

    public static function getStudent (string $id)
    {
        try {
            $student = DB::table('students')
                ->select('id', 'nombre', 'apellidos', 'email', 'dni')
                ->where('id', $id)
                ->get(); // Con get obtenemos un array, en cambio si usamos first() o firstOrFail devuelve un objeto

            return $student;

        } catch (Exception $e) {
            return 'Error en la consulta. Código del error: ' . $e->getCode();
        }
    }

    public static function createStudent(Request $request)
    {
        try {
            $student = DB::table('students')->insert([
                'nombre' => $request->nombre,
                'apellidos' => $request->apellidos,
                'email' => $request->email,
                'dni' => $request->dni,
                'updated_at' => now(),
                'created_at' => now()
            ]);

            return $student;

        } catch (Exception $e) {
            return 'Error en la consulta. Código de error: ' . $e->getMessage();
        }
    }

    public static function updateStudent (string $id, Request $request)
    {
        try {
            $student = DB::table('students')
                ->where('id', $id)
                ->update($request->except('_token', '_method'), ['updated_at' => now()]);
            return $student;

        } catch (Exception $e) {
            return 'Error en la consulta. Código de error: ' . $e->getCode();
        }
    }

    public static function deleteStudent (string $id)
    {
        // Si no se encuentra usuario a eliminar, devolverá 0, y no un error
        try {
            $query = DB::table('students')
                ->where('id', $id)
                ->delete();
            
            return $query;
        } catch (Exception $e) {
            return 'Error en la consulta. Código de error: ' . $e->getCode();
        }
    }
}
