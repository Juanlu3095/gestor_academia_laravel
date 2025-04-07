<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class IncidenceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if($this->user()) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        switch($this->method()) {
            case 'GET':
                $rules = [
                    'busqueda' => 'string'
                ];
            break;
            
            case 'POST':
                $rules = [
                    'titulo' => 'required|string',
                    'sumario' => 'required|string',
                    'fecha' => 'required|date',
                    'documento' => ['nullable', File::types(['pdf', 'odt'])->max('10mb')],
                    'persona' => 'required|integer',
                    'rol' => 'required|in:Alumno,Profesor',
                ];
            break;

            case 'PUT':
                $rules = [
                    'titulo' => 'required|string',
                    'sumario' => 'required|string',
                    'fecha' => 'required|date',
                    'documento' => ['nullable', File::types(['pdf', 'odt'])->max('10mb')],
                    'persona' => 'required|integer',
                    'rol' => 'required|in:Alumno,Profesor',
                ];
            break;
        }

        return $rules;
    }

    /**
     * It allows to show custom messages for validation errors
     */
    public function messages()
    {
        return [
            'busqueda.string' => 'El campo de búsqueda no es un string.',
            'titulo.required' => 'El campo Título es requerido.',
            'titulo.string' => 'El campo Título debe ser un string.',
            'sumario.required' => 'El campo Sumario es requerido.',
            'sumario.string' => 'El campo Sumario debe ser un texto.',
            'fecha.required' => 'El campo Fecha es requerido.',
            'fecha.date' => 'El campo Fecha debe ser una fecha.',
            'documento.file' => 'El documento debe ser un archivo válido.',
            'documento.mimes' => 'El documento debe ser un archivo .pdf o .odt.',
            'documento.max' => 'El documento no debe ocupar más de 10MB.',
            'persona.required' => 'El campo Persona es requerido.',
            'persona.integer' => 'El campo Persona debe ser un entero.',
            'rol.required' => 'El campo Rol es requerido.', // COMPROBAR ESTO
            'rol.in' => 'El campo Rol debe ser "Alumno" o "Profesor".', // COMPROBAR ESTO
        ];
    }
}
