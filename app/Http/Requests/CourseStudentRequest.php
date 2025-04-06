<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseStudentRequest extends FormRequest
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
                    'curso' => 'required|integer',
                    'alumno' => 'required|integer'
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
            'curso.required' => 'El campo Curso es requerido.',
            'curso.integer' => 'El campo Curso debe ser un entero.',
            'alumno.required' => 'El campo Alumno es requerido.',
            'alumno.integer' => 'El campo Alumno debe ser un entero.',
        ];
    }
}
