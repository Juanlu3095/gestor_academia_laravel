<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Incidence;
use App\services\DocumentService;
use Error;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;

class IncidenceController extends Controller
{
    private $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    public function index()
    {
        $incidences = Incidence::getIncidences();
        return view('incidencias', compact('incidences'));
    }

    /*
    * It lets to get all data for a specific incidence by id.
    * It is used by other functions, for example to assert an incidence exists.
    */
    public function show(string $id) // REVISAR EL FIRST() EN TODAS LAS FUNCIONES
    {
        $incidence = Incidence::getIncidence($id);

        if(!$incidence) {
            abort(404, 'Incidencia no encontrada.');
        }

        return $incidence;
    }

    /*
    * It shows a page with all information about a specific incidence.
    */
    public function details(string $id)
    {
        $incidence = self::show($id);

        return view('', compact('incidence'));
    }

    public function new()
    {
        return view('incidencias_nuevo');
    }

    public function create(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string',
            'sumario' => 'required|string',
            'fecha' => 'required|date',
            'documento' => ['nullable', File::types(['pdf', 'odt'])->max('10mb')], // FALTA GESTIONAR EL DOCUMENTO
            'persona' => 'required|numeric',
            'rol' => 'required|in:Alumno,Profesor',
        ]);

        // Delegamos el procesado del archivo al Servicio y obtenemos la id de ese documento
        if($request->hasFile('documento')) {
            $idDocument = $this->documentService->storeDocument($request->documento);
        }

        $incidenceRequest = [
            'titulo' => $request->titulo,
            'sumario' => $request->sumario,
            'fecha' => $request->fecha,
            'documento' => $idDocument ?? NULL,
            'persona' => $request->persona,
            'rol' => $request->rol,
        ];
        
        // Guardamos la incidencia pasando el array con el id del documento
        $incidence = Incidence::createIncidence($incidenceRequest);

        if(!$incidence) {
            throw new Error('La incidencia no ha sido guardada.');
        }
        
        return redirect()->route('incidencias.index')->with('Success', 'Incidencia creada.');
    }

    public function edit (string $id)
    {
        $incidence = self::show($id);

        return view('incidencias_editar', compact('incidence'));
    }

    public function update(string $id, Request $request)
    {
        self::show($id);

        $request->validate([
            'titulo' => 'required|string',
            'sumario' => 'required|string',
            'fecha' => 'required|date',
            'documento' => '', // FALTA GESTIONAR EL DOCUMENTO
            'persona' => 'required|numeric',
            'rol' => 'required|in:Alumno,Profesor',
        ]);

        $incidence = Incidence::updateIncidence($id, $request);

        if(!$incidence) {
            throw new Error('La incidencia no ha sido actualizada');
        }

        return redirect()->route('incidencias.index');
        
    }

    public function delete(string $id)
    {
        self::show($id);

        Incidence::deleteIncidence($id);
    }
}
