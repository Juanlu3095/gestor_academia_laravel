<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeacherRequest;
use App\Models\Teacher;
use Error;

class TeacherController extends Controller
{
    /**
     * It returns a view with all teachers. It also can return teachers by search input.
     * @param TeachertRequest $request
     * @return \Illuminate\View\View
     */
    public function index(TeacherRequest $request)
    {
        $busqueda = $request->query('busqueda');

        if($busqueda) {
            $teachers = Teacher::getTeachers($busqueda, true);
        } else {
            $teachers = Teacher::getTeachers(null, true);
        }

        return view('profesores', compact('teachers'));
    }

    /**
     * It returns a list of all teachers.
     * @return \Illuminate\Support\Collection
     */
    public function list()
    {
        return Teacher::getTeachers();
    }

    /**
     * It returns a collection from Teacher model or 404 if not.
     * @param string $id
     * @return \Illuminate\Support\Collection
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException 
     */
    public function show (string $id)
    {
        $teacher = Teacher::getTeacher($id);

        if($teacher->count() < 1) {
            abort(404, 'Profesor no encontrado.');
        }

        return $teacher;
    }

    /**
     * It creates a teacher and redirects to profesores.index.
     * @param TeacherRequest $request
     * @return RedirectResponse
     * @throws Error
     */
    public function create(TeacherRequest $request)
    {
        $student = Teacher::createTeacher($request);

        if(!$student) {
            throw new Error('El profesor no ha sido creado.');
        }

        return redirect()->route('profesores.index');
    }

    /**
     * It updates a teacher and sends a response.
     * @param TeacherRequest $request
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function update(TeacherRequest $request, string $id)
    {
        $this->show($id); // Llamamos a la función del controlador para comprobar si existe el profesor

        Teacher::updateTeacher($id, $request);
        
        return response('Profesor actualizado.', 200);

    }

    /**
     * It deletes a teacher.
     * @param string $id
     * @return int 1 means correct 0 means error
     */
    public function delete (string $id)
    {
        $this->show($id);

        $query = Teacher::deleteTeacher($id);
        return $query; // Devuelve 1 si se elimina y 0 si no lo hace
    }
}
