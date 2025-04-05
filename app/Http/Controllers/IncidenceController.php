<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Incidence;
use App\Services\DocumentService;
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

    public function index(Request $request)
    {
        $busqueda = $request->query('busqueda');

        if($busqueda) {
            $incidences = Incidence::getIncidences($busqueda);
        } else {
            $incidences = Incidence::getIncidences();
        }
        
        return view('incidencias', compact('incidences'));
    }

    /**
    * @param string $id Hex(id)
    * @return stdClass
    * It lets to get all data for a specific incidence by id.
    * It is used by other functions, for example to assert an incidence exists.
    */
    public function show(string $id) // REVISAR EL FIRST() EN TODAS LAS FUNCIONES, puede dar error antes de entrar en el if con abort
    {
        $incidence = Incidence::getIncidence($id);

        if(!$incidence) {
            abort(404, 'Incidencia no encontrada.');
        }

        return $incidence;
    }

    /**
    * It shows a page with all information about a specific incidence.
    * @param string $id HEX(id)
    * @return view
    */
    public function details(string $id)
    {
        $incidence = $this->show($id);  

        return view('incidencia', compact('incidence'));
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
            'documento' => ['nullable', File::types(['pdf', 'odt'])->max('10mb')],
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
        $incidence = $this->show($id);

        return view('incidencias_editar', compact('incidence'));
    }

    public function update(string $id, Request $request)
    {
        // ESTA FUNCIÓN REALIZA LAS SIGUIENTES ACCIONES:
        // COMPROBACIÓN DE LA EXISTENCIA DE LA INCIDENCIA Y VALIDACIÓN DE LA REQUEST
        // COMPROBACIÓN DE LA EXISTENCIA DE UN DOCUMENTO, EN CASO AFIRMATIVO ENVIAR LOS DATOS AL SERVICIO
        // EL SERVICIO GUARDA EL DOCUMENTO
        // ACTUALIZACIÓN DE LA INCIDENCIA CON SU MODELO, SI HAY idDocumento SE LA ENVIAMOS. ACTUALIZAR DOCUMENTO EN LA INCIDENCIA
        // REDIRECCIÓN

        $this->show($id);

        $request->validate([
            'titulo' => 'required|string',
            'sumario' => 'required|string',
            'fecha' => 'required|date',
            'documento' => ['nullable', File::types(['pdf', 'odt'])->max('10mb')],
            'persona' => 'required|numeric',
            'rol' => 'required|in:Alumno,Profesor',
        ]);

        // Delegamos el procesado del archivo al Servicio y obtenemos la id de ese documento
        if($request->hasFile('documento')) {
            $idDocument = $this->documentService->storeDocument($request->documento);
        }

        // Enviamos los datos de la incidencia a actualizar al modelo. Documento puede ser nulo y se actua en consecuencia en modelo
        $incidenceRequest = [
            'titulo' => $request->titulo,
            'sumario' => $request->sumario,
            'fecha' => $request->fecha,
            'documento' => $idDocument ?? NULL,
            'persona' => $request->persona,
            'rol' => $request->rol,
        ];

        $incidence = Incidence::updateIncidence($id, $incidenceRequest);

        if(!$incidence) {
            throw new Error('La incidencia no ha sido actualizada');
        }

        return redirect()->route('incidencias.index');
        
    }

    public function delete(string $id)
    {
        $incidence = $this->show($id);
        if($incidence->document_id != null) {
            $this->documentService->deleteDocument($incidence->document_id);
        }

        Incidence::deleteIncidence($id);
    }
}
