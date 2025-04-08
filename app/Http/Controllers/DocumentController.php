<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
    * It gets the url of a specific document by id. It returns 404 if error.
    * @param String $id HEX(id)
    * @return \Symfony\Component\HttpFoundation\BinaryFileResponse HTTP Response with file
    * @throws \Symfony\Component\HttpKernel\Exception\HttpException 
    */
    public function getUrlDocument(string $id)
    {
        $document = Document::getDocument($id);

        if(!$document) {
            abort(404, 'Documento no encontrado.'); 
        }

        if(Storage::disk('private')->exists($document->url)) {
            $file = Storage::disk('private')->path($document->url);
            return response()->file($file, ['Content-Type' => 'application/pdf']);
        } else {
            abort(404, 'Documento no encontrado.');
        }
    }

    /**
    * It gets the url of a specific document by id. It returns 404 if error.
    * @param String $id HEX(id)
    * @return \Symfony\Component\HttpFoundation\BinaryFileResponse HTTP Response to download file
    */
    public function downloadDocument(string $id)
    {
        $document = Document::getDocument($id);

        if(Storage::disk('private')->exists($document->url)) {
            $file = Storage::disk('private')->path($document->url);
            return response()->download($file);
        }
    }
} 
