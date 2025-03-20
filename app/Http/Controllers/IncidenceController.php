<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Incidence;
use Error;
use Illuminate\Http\Request;

class IncidenceController extends Controller
{
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
            'documento' => '', // FALTA GESTIONAR EL DOCUMENTO
            'persona' => 'required|numeric',
            'rol' => 'required|in:Alumno,Profesor',
        ]);
        // VER infocontroller pruebaslaravel para guardado de archivos
        $incidence = Incidence::createIncidence($request);

        if(!$incidence) {
            throw new Error('La incidencia no ha sido guardada');
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
