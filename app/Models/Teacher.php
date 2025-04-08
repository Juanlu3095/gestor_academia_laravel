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

    /**
     * It returns all teachers with pagination and search input or not according to array $options.
     * @param string|null $busqueda Can contains search input.
     * @param bool $paginate Bool that indicates if needs pagination or not.
     * @return \Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|string
     */
    public static function getTeachers (?string $busqueda = null, bool $paginate = false)
    {
        try {
            $teachers = DB::table('teachers')
                ->select('id', 'nombre', 'apellidos', 'email', 'dni');

            if($busqueda != null) {
                $teachers = $teachers->whereAny(['nombre', 'apellidos', 'email', 'dni'], 'like', "%$busqueda%");
            }

            if($paginate) {
                $teachers = $teachers->paginate(5);
            } else {
                $teachers = $teachers->get();
            }

            return $teachers;

        } catch (Exception $e) {
            return 'Error en la consulta. Código de error: ' . $e->getCode();
        }
    }

    /**
     * It returns a specific teacher by $id as a collection.
     * @param string $id
     * @return \Illuminate\Support\Collection|string If there is no result, returns null.
     */
    public static function getTeacher (string $id)
    {
        try {
            $teacher = DB::table('teachers')
                ->select('id', 'nombre', 'apellidos', 'email', 'dni')
                ->where('id', $id)
                ->get();
            return $teacher;

        } catch (Exception $e) {
            return 'Error en la consulta. Código del error: ' . $e->getCode();
        }
    }

    /**
     * It creates a teacher.
     * @param Request $request
     * @return bool|string true if course created correctly, false if not.
     */
    public static function createTeacher (Request $request)
    {
        try {
            $teacher = DB::table('teachers')->insert([
                'nombre' => $request->nombre_nuevo,
                'apellidos' => $request->apellidos_nuevo,
                'email' => $request->email_nuevo,
                'dni' => $request->dni_nuevo,
                'updated_at' => now(),
                'created_at' => now()
            ]);

            return $teacher;

        } catch (Exception $e) {
            return 'Error en la consulta. Código de error: ' . $e->getCode();
        }
    }

    /**
     * It updates a specific teacher by id.
     * @param string $id
     * @param Request $request
     * @return int|string Number of updated rows. It is 0 if none is updated.
     */
    public static function updateTeacher (string $id, Request $request)
    {
        try {
            $teacher = DB::table('teachers')
                ->where('id', $id)
                ->update($request->except('_token', '_method'), ['updated_at' => now()]);
            return $teacher;

        } catch (Exception $e) {
            return 'Error en la consulta. Código del error: ' . $e->getCode();
        }
    }

    /**
     * It deletes a teacher by a specific id.
     * @param string $id
     * @return int|string Number of rows deleted. Returns 0 if none is deleted.
     */
    public static function deleteTeacher (string $id)
    {
        try {
            $query = DB::table('teachers')
                ->where('id', $id)
                ->delete();
            
            return $query;
        } catch (Exception $e) {
            return 'Error en la consulta. Código del error: ' . $e->getCode();
        }
    }
}
