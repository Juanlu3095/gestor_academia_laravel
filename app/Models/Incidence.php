<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Incidence extends Model
{
    public static function getIncidences (?string $busqueda = null)
    {
        try { // Seleccionar id, titulo, fecha, nombre de la persona y rol (alumno o profesor)
            $incidences = DB::table('incidences')
                ->select(DB::raw('HEX(incidences.id) as id'), 'incidences.titulo', 'incidences.fecha', 'incidences.incidenceable_type as rol', DB::raw('COALESCE(teachers.nombre, students.nombre) AS nombre, 
                                                                            COALESCE(teachers.apellidos, students.apellidos) AS apellidos'))
                ->leftJoin('teachers', function (JoinClause $join) {
                    $join->where('incidences.incidenceable_type', '=', DB::raw("'Profesor'"))->on('incidences.incidenceable_id', '=', 'teachers.id');
                })
                ->leftJoin('students', function (JoinClause $join) {
                    $join->where('incidences.incidenceable_type', '=', DB::raw("'Alumno'"))->on('incidences.incidenceable_id', '=', 'students.id');
                })
                ->get();
                // Las comparaciones con valores estáticos como 'Profesor' debe hacerse con DB::raw()

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

    public static function getIncidence (string $id)
    {
        try {
            $incidence = DB::table('incidences')
                ->select(DB::raw('HEX(id) as id'), 'titulo', 'fecha', 'sumario', 'incidenceable_id', 'incidenceable_type', 'document_id')
                ->where(DB::raw('HEX(id)'), '=', $id)
                ->first();

            return $incidence; // Esta consulta habrá que modificarla para obtener el documento en sí y no su id
        } catch (Exception $e) {
            return 'Error en la consulta. Código del error: ' . $e->getMessage();
        }
    }

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

    public static function updateIncidence (string $id, Request $request)
    {
        try {
            $incidence = DB::table('incidences')
                ->where(DB::raw('HEX(id)'), '=', $id)
                ->update([
                    'titulo' => $request->titulo,
                    'sumario' => $request->sumario,
                    'fecha' => $request->fecha,
                    'document_id' => $request->documento,
                    'incidenceable_id' => $request->persona,
                    'incidenceable_type' => $request->rol,
                    'updated_at' => now()
                ]);

            return $incidence;
        } catch (Exception $e) {
            return 'Error en la consulta. Código del error: ' . $e->getMessage();
        }
    }

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
