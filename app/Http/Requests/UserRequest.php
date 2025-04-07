<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            case 'PATCH':
                $rules = [
                    'name' => 'string',
                    'email' => 'email',
                    'password' => 'string'
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
            'name.string' => 'El campo Nombre debe ser un string.',
            'email.email' => 'El campo Email no es un email válido.',
            'password.string' => 'El campo Contraseña no es válido.'
        ];
    }
}
