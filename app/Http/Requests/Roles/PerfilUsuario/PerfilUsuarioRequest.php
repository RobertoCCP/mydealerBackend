<?php

namespace App\Http\Requests\Roles\PerfilUsuario;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class PerfilUsuarioRequest extends FormRequest
{
    public function rules()
    {
        return [
            'perfil' => ['required', 'string', 'max:255'],
            'nivel' => ['required', 'integer'],
            'tipoperfil' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'perfil.required' => 'El campo Perfil es obligatorio.',
            'perfil.string' => 'El campo Perfil debe ser una cadena de texto.',
            'perfil.max' => 'El campo Perfil no puede exceder los 255 caracteres.',

            'nivel.required' => 'El campo Nivel es obligatorio.',
            'nivel.integer' => 'El campo Nivel debe ser un número entero.',

            'tipoperfil.required' => 'El campo Tipo de Perfil es obligatorio.',
            'tipoperfil.string' => 'El campo Tipo de Perfil debe ser una cadena de texto.',
            'tipoperfil.max' => 'El campo Tipo de Perfil no puede exceder los 255 caracteres.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['error' => $validator->errors()], 400));
    }
}
