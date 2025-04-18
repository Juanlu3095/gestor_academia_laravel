<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Incidence extends Model
{
    /**
     * It returns all incidences with pagination.
     * @param string|null $busqueda
     * @return \Illuminate\Pagination\LengthAwarePaginator|string
     */
    public static function getIncidences (?string $busqueda = null)
    {
        try { // Seleccionar id, titulo, fecha, nombre de la persona y rol (alumno o profesor)
            $incidences = DB::table('incidences')
                ->select(DB::raw('HEX(incidences.id) as id'), 'incidences.titulo', 'incidences.fecha', 'incidences.incidenceable_type as rol', DB::raw('HEX(incidences.document_id) as document_id'), DB::raw('COALESCE(teachers.nombre, students.nombre) AS nombre, 
                    COALESCE(teachers.apellidos, students.apellidos) AS apellidos'))
                ->leftJoin('teachers', function (JoinClause $join) { 
                    $join->where('incidences.incidenceable_type', '=', DB::raw("'Profesor'"))->on('incidences.incidenceable_id', '=', 'teachers.id');
                }) // Las comparaciones con valores estáticos como 'Profesor' debe hacerse con DB::raw()
                ->leftJoin('students', function (JoinClause $join) {
                    $join->where('incidences.incidenceable_type', '=', DB::raw("'Alumno'"))->on('incidences.incidenceable_id', '=', 'students.id');
                });

                if($busqueda) {
                    $incidences = $incidences->whereAny(['incidences.titulo', 'students.nombre', 'students.apellidos', 'teachers.nombre', 'teachers.apellidos'], 'like', "%$busqueda%");
                }
                
            $incidences = $incidences->paginate(5);
                
                /* SELECT incidences.id, incidences.titulo, incidences.fecha, incidences.incidenceable_type, 
               COALESCE(teachers.nombre, students.nombre) AS nombre, 
               COALESCE(teachers.apellidos, students.apellidos) AS apellidos
               FROM `incidences`
               LEFT JOIN `teachers` ON incidences.incidenceable_type = 'Profesor' AND incidences.incidenceable_id = teachers.id
               LEFT JOIN `students` ON incidences.incidenceable_type = 'Alumno' AND incidences.incidenceable_id = students.id; */

            return $incidences;
        } catch (Exception $e) {
            return 'Error en la consulta. Código del error: ' . $e->getMessage();
        }
    }

    /**
     * It returns a specific incidence by $id as an stdClass Object.
     * @param string $id
     * @return stdClass|null|string If there is no result, returns null.
     */
    public static function getIncidence (string $id)
    {
        try { // PROBLEMA: SI NO HAY DOCUMENTO ASIGNADO ERROR 404
            $incidence = DB::table('incidences')
                ->select(DB::raw('HEX(incidences.id) as id'), 'titulo', 'fecha', 'sumario', 'incidenceable_id', 'incidenceable_type', DB::raw('HEX(document_id) as document_id'), DB::raw('COALESCE(teachers.nombre, students.nombre) AS nombre, 
                    COALESCE(teachers.apellidos, students.apellidos) AS apellidos'))
                ->leftJoin('teachers', function (JoinClause $join) { 
                    $join->where('incidences.incidenceable_type', '=', DB::raw("'Profesor'"))->on('incidences.incidenceable_id', '=', 'teachers.id');
                }) // Las comparaciones con valores estáticos como 'Profesor' debe hacerse con DB::raw()
                ->leftJoin('students', function (JoinClause $join) {
                    $join->where('incidences.incidenceable_type', '=', DB::raw("'Alumno'"))->on('incidences.incidenceable_id', '=', 'students.id');
                })
                ->where(DB::raw('HEX(incidences.id)'), '=', $id)
                ->first();

            if(!$incidence) { // Si no se encuentra la incidencia first() devuelve un error al intentar leer document_id que es null
                return null;  // con get() devuelve un array vacio []
            }

            if($incidence->document_id != null) {
                $incidence = DB::table('incidences')
                ->select(DB::raw('HEX(incidences.id) as id'), 'titulo', 'fecha', 'sumario', 'incidenceable_id', 'incidenceable_type', DB::raw('HEX(document_id) as document_id'), 'documents.nombre as documento', DB::raw('COALESCE(teachers.nombre, students.nombre) AS nombre, 
                    COALESCE(teachers.apellidos, students.apellidos) AS apellidos'))
                ->join('documents', 'incidences.document_id', '=', 'documents.id')
                ->leftJoin('teachers', function (JoinClause $join) { 
                    $join->where('incidences.incidenceable_type', '=', DB::raw("'Profesor'"))->on('incidences.incidenceable_id', '=', 'teachers.id');
                }) // Las comparaciones con valores estáticos como 'Profesor' debe hacerse con DB::raw()
                ->leftJoin('students', function (JoinClause $join) {
                    $join->where('incidences.incidenceable_type', '=', DB::raw("'Alumno'"))->on('incidences.incidenceable_id', '=', 'students.id');
                })
                ->where(DB::raw('HEX(incidences.id)'), '=', $id)
                ->first();
            }

            return $incidence;
        } catch (Exception $e) {
            return 'Error en la consulta. Código del error: ' . $e->getMessage();
        }
    }

    /**
     * It creates an incidence.
     * @param array $request
     * @return bool|string true if course created correctly, false if not.
     */
    public static function createIncidence (array $request)
    {
        try {
            $incidence = DB::table('incidences')
                ->insert([
                    'id' => Str::uuid(), // Puede ocurrir que esto de problemas para el personal_access_token. Ver url en marcadores
                    'titulo' => $request['titulo'],
                    'sumario' => $request['sumario'],
                    'fecha' => $request['fecha'],
                    'document_id' => $request['documento'],
                    'incidenceable_id' => $request['persona'],
                    'incidenceable_type' => $request['rol'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            
            return $incidence;
        } catch (Exception $e) {
            return 'Error en la consulta. Código del error: ' . $e->getMessage();
        }
    }

    /**
     * It updates a specific incidence by id.
     * @param string $id
     * @param array $request
     * @return int|string Number of updated rows. It is 0 if none is updated.
     */
    public static function updateIncidence (string $id, array $request)
    {
        try {
            $incidence = DB::table('incidences')
                ->where(DB::raw('HEX(id)'), '=', $id);

                if($request['documento']) {
                    $incidence = $incidence->update([
                        'titulo' => $request['titulo'],
                        'sumario' => $request['sumario'],
                        'fecha' => $request['fecha'],
                        'document_id' => $request['documento'],
                        'incidenceable_id' => $request['persona'],
                        'incidenceable_type' => $request['rol'],
                        'updated_at' => now()
                    ]);
                } else {
                    $incidence = $incidence->update([
                        'titulo' => $request['titulo'],
                        'sumario' => $request['sumario'],
                        'fecha' => $request['fecha'],
                        'incidenceable_id' => $request['persona'],
                        'incidenceable_type' => $request['rol'],
                        'updated_at' => now()
                    ]);
                }

            return $incidence;
        } catch (Exception $e) {
            return 'Error en la consulta. Código del error: ' . $e->getMessage();
        }
    }

    /**
     * It deletes an incidence by a specific id.
     * @param string $id
     * @return int|string Number of rows deleted. Returns 0 if none is deleted.
     */
    public static function deleteIncidence (string $id)
    {
        try {
            $query = DB::table('incidences')
                ->where(DB::raw('HEX(id)'), '=', $id)
                ->delete();
            
            return $query;
        } catch (Exception $e) {
            return 'Error en la consulta. Código del error: ' . $e->getCode();
        }
    }
}
