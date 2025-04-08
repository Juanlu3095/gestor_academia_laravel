<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\IncidenceRequest;
use App\Models\Incidence;
use App\Services\DocumentService;
use Error;

class IncidenceController extends Controller
{
    private $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    /**
     * It shows all incidences.
     * @param IncidenceRequest $request It can contain search input
     * @return \Illuminate\View\View
     */
    public function index(IncidenceRequest $request)
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
    * It lets to get all data for a specific incidence by id.
    * It is used by other functions, for example to assert an incidence exists.
    * @param string $id Hex(id)
    * @return stdClass
    * @throws \Symfony\Component\HttpKernel\Exception\HttpException 
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
    * @return \Illuminate\View\View
    */
    public function details(string $id)
    {
        $incidence = $this->show($id);  

        return view('incidencia', compact('incidence'));
    }

    /**
     * It returns a view to create incidences.
     * @return \Illuminate\View\View
     */
    public function new()
    {
        return view('incidencias_nuevo');
    }

    /**
     * It allows to create a incidence.
     * @param IncidenceRequest $request
     * @return RedirectResponse
     * @throws Error
     */
    public function create(IncidenceRequest $request)
    {
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

    /**
     * It returns a view to edit a specific incidence by id.
     * @param string $id
     * @return \Illuminate\View\View
     */
    public function edit (string $id)
    {
        $incidence = $this->show($id);

        return view('incidencias_editar', compact('incidence'));
    }

    /**
     * It allows to update a specific incidence by id. It uses a services for file processing.
     * @param string $id
     * @param IncidenceRequest $request
     * @return RedirectResponse
     * @throws Error
     */
    public function update(string $id, IncidenceRequest $request)
    {
        // Comprobamos que exista la incidencia
        $this->show($id);

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

        // Actualizamos la incidencia
        $incidence = Incidence::updateIncidence($id, $incidenceRequest);

        if(!$incidence) {
            throw new Error('La incidencia no ha sido actualizada');
        }

        return redirect()->route('incidencias.index');
        
    }

    /**
     * It allows to delete a specific incidence by id. DocumentService searchs document to delete.
     * @param string $id
     */
    public function delete(string $id)
    {
        $incidence = $this->show($id);
        if($incidence->document_id != null) {
            $this->documentService->deleteDocument($incidence->document_id);
        }

        Incidence::deleteIncidence($id);
    }
}
