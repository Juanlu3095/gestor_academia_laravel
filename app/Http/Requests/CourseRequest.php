<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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
        switch ($this->method()) {
            case 'GET':
                $rules = [
                    'busqueda' => 'string'
                ];
            break;

            case 'POST':
                $rules = [
                    'nombre_nuevo' => 'string|required',
                    'fecha_nuevo' => 'string|required',
                    'horas_nuevo' => 'integer|required',
                    'descripcion_nuevo' => 'string',
                    'profesor_nuevo' => 'integer|required'
                ];
            break;

            case 'PUT':
                $rules = [
                    'nombre' => 'required|string',
                    'fecha' => 'required|string',
                    'horas' => 'required|integer',
                    'descripcion' => 'required|string',
                    'profesor' => 'required|integer'
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
            'nombre.required' => 'El campo Nombre es requerido.',
            'nombre.string' => 'El campo Nombre debe ser un string.',
            'fecha.required' => 'El campo Fecha es requerido.',
            'fecha.string' => 'El campo Fecha debe ser un string.',
            'horas.required' => 'El campo Horas es requerido.',
            'horas.integer' => 'El campo Horas debe ser un entero.',
            'descripcion.required' => 'El campo Descripcion es requerido.',
            'descripcion.string' => 'El campo Descripcion debe ser un texto.',
            'profesor.required' => 'El campo Profesor es requerido.',
            'profesor.integer' => 'El campo Profesor debe ser un entero.',
            'nombre_nuevo.required' => 'El campo Nombre es requerido.',
            'nombre_nuevo.string' => 'El campo Nombre debe ser un string.',
            'fecha_nuevo.required' => 'El campo Fecha es requerido.',
            'fecha_nuevo.string' => 'El campo Fecha debe ser un string.',
            'horas_nuevo.required' => 'El campo Horas es requerido.',
            'horas_nuevo.integer' => 'El campo Horas debe ser un entero.',
            'descripcion_nuevo.required' => 'El campo Descripcion es requerido.',
            'descripcion_nuevo.string' => 'El campo Descripcion debe ser un texto.',
            'profesor_nuevo.required' => 'El campo Profesor es requerido.',
            'profesor_nuevo.integer' => 'El campo Profesor debe ser un entero.',
        ];
    }
}
