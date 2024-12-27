<?php

namespace App\Http\Requests\Empresa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class UsuarioAdminRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'loginusuario' => ['required', 'string', 'min:6', 'max:12', Rule::unique('usuarioadmin', 'loginusuario')->ignore($this->route('loginusuario'), 'loginusuario')],
            'password' => ['required', 'string', 'min:6', 'max:12'],
            'nombre' => ['required', 'string', 'max:50'],
            'apellido' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:100'],
            'cargo' => ['required'],
            'codperfil' => ['required', 'integer', Rule::exists('perfil', 'srperfil')],
            'coddivision' => ['required', 'string', 'max:10', Rule::exists('division', 'coddivision')],
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['loginusuario'] = ['nullable', 'string', 'min:6', 'max:12', Rule::unique('usuarioadmin', 'loginusuario')->ignore($this->route('loginusuario'), 'loginusuario')];
            $rules['password'] = ['nullable', 'string', 'min:6', 'max:12'];
            $rules['nombre'] = ['nullable', 'string', 'max:50'];
            $rules['apellido'] = ['nullable', 'string', 'max:50'];
            $rules['email'] = ['nullable', 'email', 'max:100'];
            $rules['cargo'] = ['nullable'];
            $rules['codperfil'] = ['nullable', 'integer', Rule::exists('perfil', 'srperfil')];
            $rules['estado'] = ['nullable', 'string', 'max:1'];
            $rules['coddivision'] = ['nullable', 'string', 'max:10', Rule::exists('division', 'coddivision')];
        }

        return $rules;
    }


    public function messages()
    {
        return [
            'loginusuario.required' => 'El campo loginusuario es obligatorio.',
            'loginusuario.string' => 'El campo loginusuario debe ser una cadena de texto.',
            'loginusuario.min' => 'El campo loginusuario debe tener al menos :min caracteres.',
            'loginusuario.max' => 'El campo loginusuario no puede exceder :max caracteres.',
            'loginusuario.unique' => 'El campo loginusuario ya está en uso.',

            'password.required' => 'El campo Contraseña es obligatorio.',
            'password.string' => 'El campo Contraseña debe ser una cadena de texto.',
            'password.min' => 'El campo Contraseña debe tener al menos :min caracteres.',
            'password.max' => 'El campo Contraseña no puede exceder :max caracteres.',

            'nombre.required' => 'El campo Nombre es obligatorio.',
            'nombre.string' => 'El campo Nombre debe ser una cadena de texto.',
            'nombre.max' => 'El campo Nombre no puede exceder :max caracteres.',

            'email.required' => 'El campo Email es obligatorio.',
            'email.email' => 'El campo Email debe ser una dirección de correo electrónico válida.',
            'email.max' => 'El campo Email no puede exceder :max caracteres.',

            'codperfil.required' => 'El campo Código de Perfil es obligatorio.',
            'codperfil.integer' => 'El campo Código de Perfil debe ser un número entero.',
            'codperfil.exists' => 'El valor seleccionado para el campo Código de Perfil no es válido.',

            'coddivision.required' => 'El campo Código de División es obligatorio.',
            'coddivision.string' => 'El campo Código de División debe ser una cadena de texto.',
            'coddivision.max' => 'El campo Código de División no puede exceder :max caracteres.',
            'coddivision.exists' => 'El valor seleccionado para el campo Código de División no es válido.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['error' => $validator->errors()], 400));
    }
}
