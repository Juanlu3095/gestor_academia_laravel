<?php

namespace App\services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;

class DocumentService {

    /**
    * Store document and returns id of the document. It is no necessary to register because it has no contructor.
    * @param $document UploadedFile
    * @return $id string
    */
    public function storeDocument(UploadedFile $document)
    {
        $fileName = time() . '_' . $document->getClientOriginalName(); // No es necesario añadir extensión
        $year = date("Y");
        $month = date("F");
        Storage::disk('private')->putFileAs($year . '/' . $month, $document, $fileName);
        $url = Storage::url("app/private/$year/$month/$fileName");

        $data = [
            'nombre' => $fileName,
            'url' => $url
        ];

        $document = Document::createDocument($data); // Devuelve la id del documento guardado

        return $document;
    }

    public function deleteDocument()
    {

    }
}

?>