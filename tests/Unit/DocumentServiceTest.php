<?php

namespace Tests\Unit;

use App\Models\Document;
use App\Services\DocumentService;
use Tests\TestCase; // Se usa esto para poder usar storagePath()
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentServiceTest extends TestCase
{
    /**
     * A test to assert the url of a stored document.
     */
    public function test_store_document(): void
    {
        Storage::fake('private');
        $file = UploadedFile::fake()->create('incidencias.pdf');

        $year = date("Y");
        $month = date("F");

        $service = new DocumentService();
        $idDocument = $service->storeDocument($file); // Guardamos el documento y se crea registro en BD
        $hex = strtoupper(bin2hex($idDocument)); // Obtenemos la id como HEX de cada carácter ASCII
        $document = Document::getDocument($hex); // Obtenemos los datos del documento
        $this->assertEquals($document->url, "$year/$month/$document->nombre"); // Comparamos con lo esperado
    }
}
