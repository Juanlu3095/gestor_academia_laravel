<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
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

    public function downloadDocument(string $id)
    {
        $document = Document::getDocument($id);

        if(Storage::disk('private')->exists($document->url)) {
            $file = Storage::disk('private')->path($document->url);
            return response()->download($file);
        }
    }
} 
