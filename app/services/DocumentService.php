<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;

class DocumentService {

    /**
    * Store document and returns id of the document. It is no necessary to register because it has no contructor.
    * @param $document UploadedFile
    * @return string uuid format
    */
    public function storeDocument(UploadedFile $document)
    {
        $fileName = time() . '_' . $document->getClientOriginalName(); // No es necesario añadir extensión
        $year = date("Y");
        $month = date("F");
        Storage::disk('private')->putFileAs($year . '/' . $month, $document, $fileName);
        $url = "$year/$month/$fileName";

        $data = [
            'nombre' => $fileName,
            'url' => $url
        ];

        $document = Document::createDocument($data); // Devuelve la id del documento guardado

        return $document;
    }

    /**
     * Delete document from database and file storage.
     * @param string $id HEX(id)
     * @return int $query It returns the number of rows affected.
     */
    public function deleteDocument(string $id)
    {
        // LLAMAR AL MODELO PARA OBTENER EL DOCUMENTO POR HEX(ID)
        $document = Document::getDocument($id);
        // OBTENER EL NOMBRE DEL DOCUMENTO Y BUSCARLO EN EL DISCO
        $urlDocumento = $document->url;
        // BORRAR DOCUMENTO DEL DISCO
        Storage::disk('private')->delete($urlDocumento);
        // BORRAR DOCUMENTO DE LA BD
        $query = Document::deleteDocument($id);

        return $query;

        // El OnCascade de las migraciones da problemas si se elimina el documento de una incidencia y luego se trata
        // de eliminar una incidencia sin documento. Además si se comenta el código que elimina la incidencia y se elimina
        // el documento, automáticamente se elimina la incidencia
    }   
}

?>