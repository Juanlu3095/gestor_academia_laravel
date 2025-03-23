<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Document extends Model
{
    /**
     * Create a row in documents table.
     * @param array $data It contains 'nombre' and 'url' for the document.
     * @return string $id It returns id in uuid format.
     */
    public static function createDocument (array $data)
    {
        try {
            $id = Str::uuid(); // Generamos uuid antes de la consulta para luego devolverlo

            DB::table('documents')
                ->insert([
                    'id' => $id,
                    'nombre' => $data['nombre'],
                    'url' => $data['url'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            
            return $id;
        } catch (Exception $e) {
            return 'Error en la consulta. Código del error: ' . $e->getCode();
        }
    }

    /**
     * Delete a specific row from documents by id.
     * @param string $id
     * @return int $query 1 means ok, 0 means attempt failed
     */
    public static function deleteDocument(string $id)
    {
        try {
            $query = DB::table('documents')
                ->where(DB::raw('HEX(id)'), '=', $id)
                ->delete();

            return $query;
        } catch (Exception $e) {
            return 'Error en la consulta. Código del error: ' . $e->getCode();
        }
    }
}
