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

    /**
     * It returns all students with pagination and search input or not according to array $options.
     * @param array $options Can contains search input and boolean for pagination
     * @return \Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|string
     */
    public static function getStudents(array $options = [])
    {
        $busqueda = $options['busqueda'] ?? null; // Establecemos los parámetros que pueden ir dentro del array $options
        $paginacion = $options['paginacion'] ?? false;

        try {         
            $students = DB::table('students')
                    ->select('id', 'nombre', 'apellidos', 'email', 'dni');

            if($busqueda != null) {
                $students = $students->whereAny(['nombre', 'apellidos', 'email', 'dni'], 'like', "%$busqueda%");
            }

            if($paginacion) {
                $students = $students ->paginate(5);
            } else {
                $students = $students->get();
            }
            
            return $students;
            
        } catch (Exception $e) {
            return 'Error en la consulta. Código de error: ' . $e->getCode();
        }
    }

    /**
     * It returns a specific student by $id as a collection.
     * @param string $id
     * @return \Illuminate\Support\Collection|string If there is no result, returns null.
     */
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

    /**
     * It creates a student.
     * @param Request $request
     * @return bool|string true if course created correctly, false if not.
     */
    public static function createStudent(Request $request)
    {
        try {
            $student = DB::table('students')->insert([
                'nombre' => $request->nombre_nuevo,
                'apellidos' => $request->apellidos_nuevo,
                'email' => $request->email_nuevo,
                'dni' => $request->dni_nuevo,
                'updated_at' => now(),
                'created_at' => now()
            ]);

            return $student;

        } catch (Exception $e) {
            return 'Error en la consulta. Código de error: ' . $e->getMessage();
        }
    }

    /**
     * It updates a specific student by id.
     * @param string $id
     * @param Request $request
     * @return int|string Number of updated rows. It is 0 if none is updated.
     */
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

    /**
     * It deletes a student by a specific id.
     * @param string $id
     * @return int|string Number of rows deleted. Returns 0 if none is deleted.
     */
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
