<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request by checking logged user. 
     * @return bool true is authorized
     */
    public function authorize(): bool
    {
        if(!$this->user()) {
            return false;
        }
        return true;
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
                    'nombre_nuevo' => 'required|string',
                    'apellidos_nuevo' => 'required|string',
                    'email_nuevo' => 'required|email',
                    'dni_nuevo' => 'required|string'
                ];
            break;

            case 'PUT':
                $rules = [
                    'nombre' => 'required|string',
                    'apellidos' => 'required|string',
                    'email' => 'required|email',
                    'dni' => 'required|string'
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
            'nombre_nuevo.string' => 'El campo Nombre no es un string.',
            'nombre_nuevo.required' => 'El campo Nombre es requerido.',
            'nombre.string' => 'El campo Nombre no es un string.',
            'nombre.required' => 'El campo Nombre es requerido.',
            'apellidos_nuevo.string' => 'El campo Apellidos no es un string.',
            'apellidos_nuevo.required' => 'El campo Apellidos es requerido.',
            'apellidos.string' => 'El campo Apellidos no es un string.',
            'apellidos.required' => 'El campo Apellidos es requerido.',
            'email_nuevo.email' => 'El campo Email no es un email válido.',
            'email_nuevo.required' => 'El campo Email es requerido.',
            'email.email' => 'El campo Email no es un email válido.',
            'email.required' => 'El campo Email es requerido.',
            'dni_nuevo.string' => 'El campo DNI no es un string.',
            'dni_nuevo.required' => 'El campo DNI es requerido.',
            'dni.string' => 'El campo DNI no es un string.',
            'dni.required' => 'El campo DNI es requerido.'
        ];
    }
}
