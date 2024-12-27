<?php

namespace App\Http\Requests\Empresa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreEmpresaRequest extends FormRequest
{
    public function rules()
    {
        return [
            'codempresa' => 'numeric',
            'nombre' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:16',
            'pais' => 'nullable|string|max:60',
            'provincia' => 'nullable|string|max:60',
            'ciudad' => 'nullable|string|max:60',
            'nombretienda' => 'nullable|string|max:50',
            'emailsoporte' => 'nullable|string|email|max:100',
            'emailorden' => 'nullable|string|email|max:100',
            'usuarioadmin' => 'nullable|string|max:8',
            'claveadmin' => 'nullable|string|max:40',
            'mensaje' => 'nullable|string|max:200',
            'cargo1' => 'nullable|string|max:50',
            'nombre1' => 'nullable|string|max:60',
            'email1' => 'nullable|string|email|max:100',
            'foto1' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cargo2' => 'nullable|string|max:50',
            'nombre2' => 'nullable|string|max:60',
            'email2' => 'nullable|string|email|max:100',
            'foto2' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cargo3' => 'nullable|string|max:50',
            'nombre3' => 'nullable|string|max:60',
            'email3' => 'nullable|string|email|max:100',
            'foto3' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cargo4' => 'nullable|string|max:50',
            'nombre4' => 'nullable|string|max:60',
            'email4' => 'nullable|string|email|max:100',
            'foto4' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'fax' => 'nullable|string|max:15',
            'logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_cabecera' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'codempresa.numeric' => 'El campo codempresa debe ser un número.',
            
            'nombre.string' => 'El campo nombre debe ser una cadena de texto.',
            'nombre.max' => 'El campo nombre no debe exceder los 50 caracteres.',

            'direccion.string' => 'El campo direccion debe ser una cadena de texto.',
            'direccion.max' => 'El campo direccion no debe exceder los 255 caracteres.',

            'telefono.string' => 'El campo telefono debe ser una cadena de texto.',
            'telefono.max' => 'El campo telefono no debe exceder los 16 caracteres.',

            'pais.string' => 'El campo pais debe ser una cadena de texto.',
            'pais.max' => 'El campo pais no debe exceder los 60 caracteres.',

            'provincia.string' => 'El campo provincia debe ser una cadena de texto.',
            'provincia.max' => 'El campo provincia no debe exceder los 60 caracteres.',

            'ciudad.string' => 'El campo ciudad debe ser una cadena de texto.',
            'ciudad.max' => 'El campo ciudad no debe exceder los 60 caracteres.',

            'nombretienda.string' => 'El campo nombretienda debe ser una cadena de texto.',
            'nombretienda.max' => 'El campo nombretienda no debe exceder los 50 caracteres.',

            'emailsoporte.string' => 'El campo emailsoporte debe ser una cadena de texto.',
            'emailsoporte.email' => 'El campo emailsoporte debe ser una dirección de correo electrónico válida.',
            'emailsoporte.max' => 'El campo emailsoporte no debe exceder los 100 caracteres.',

            'emailorden.string' => 'El campo emailorden debe ser una cadena de texto.',
            'emailorden.email' => 'El campo emailorden debe ser una dirección de correo electrónico válida.',
            'emailorden.max' => 'El campo emailorden no debe exceder los 100 caracteres.',

            'usuarioadmin.string' => 'El campo usuarioadmin debe ser una cadena de texto.',
            'usuarioadmin.max' => 'El campo usuarioadmin no debe exceder los 8 caracteres.',

            'claveadmin.string' => 'El campo claveadmin debe ser una cadena de texto.',
            'claveadmin.max' => 'El campo claveadmin no debe exceder los 40 caracteres.',

            'mensaje.string' => 'El campo mensaje debe ser una cadena de texto.',
            'mensaje.max' => 'El campo mensaje no debe exceder los 200 caracteres.',

            'cargo1.string' => 'El campo cargo1 debe ser una cadena de texto.',
            'cargo1.max' => 'El campo cargo1 no debe exceder los 50 caracteres.',

            'nombre1.string' => 'El campo nombre1 debe ser una cadena de texto.',
            'nombre1.max' => 'El campo nombre1 no debe exceder los 60 caracteres.',

            'email1.string' => 'El campo email1 debe ser una cadena de texto.',
            'email1.email' => 'El campo email1 debe ser una dirección de correo electrónico válida.',
            'email1.max' => 'El campo email1 no debe exceder los 100 caracteres.',

            'foto1.file' => 'El campo foto1 debe ser un archivo.',
            'foto1.mimes' => 'El campo foto1 debe ser un archivo de tipo: jpeg, png, jpg, gif, svg.',
            'foto1.max' => 'El campo foto1 no debe exceder los 2048 kilobytes.',

            'cargo2.string' => 'El campo cargo2 debe ser una cadena de texto.',
            'cargo2.max' => 'El campo cargo2 no debe exceder los 50 caracteres.',

            'nombre2.string' => 'El campo nombre2 debe ser una cadena de texto.',
            'nombre2.max' => 'El campo nombre2 no debe exceder los 60 caracteres.',

            'email2.string' => 'El campo email2 debe ser una cadena de texto.',
            'email2.email' => 'El campo email2 debe ser una dirección de correo electrónico válida.',
            'email2.max' => 'El campo email2 no debe exceder los 100 caracteres.',

            'foto2.file' => 'El campo foto2 debe ser un archivo.',
            'foto2.mimes' => 'El campo foto2 debe ser un archivo de tipo: jpeg, png, jpg, gif, svg.',
            'foto2.max' => 'El campo foto2 no debe exceder los 2048 kilobytes.',

            'cargo3.string' => 'El campo cargo3 debe ser una cadena de texto.',
            'cargo3.max' => 'El campo cargo3 no debe exceder los 50 caracteres.',

            'nombre3.string' => 'El campo nombre3 debe ser una cadena de texto.',
            'nombre3.max' => 'El campo nombre3 no debe exceder los 60 caracteres.',

            'email3.string' => 'El campo email3 debe ser una cadena de texto.',
            'email3.email' => 'El campo email3 debe ser una dirección de correo electrónico válida.',
            'email3.max' => 'El campo email3 no debe exceder los 100 caracteres.',

            'foto3.file' => 'El campo foto3 debe ser un archivo.',
            'foto3.mimes' => 'El campo foto3 debe ser un archivo de tipo: jpeg, png, jpg, gif, svg.',
            'foto3.max' => 'El campo foto3 no debe exceder los 2048 kilobytes.',

            'cargo4.string' => 'El campo cargo4 debe ser una cadena de texto.',
            'cargo4.max' => 'El campo cargo4 no debe exceder los 50 caracteres.',

            'nombre4.string' => 'El campo nombre4 debe ser una cadena de texto.',
            'nombre4.max' => 'El campo nombre4 no debe exceder los 60 caracteres.',

            'email4.string' => 'El campo email4 debe ser una cadena de texto.',
            'email4.email' => 'El campo email4 debe ser una dirección de correo electrónico válida.',
            'email4.max' => 'El campo email4 no debe exceder los 100 caracteres.',

            'foto4.file' => 'El campo foto4 debe ser un archivo.',
            'foto4.mimes' => 'El campo foto4 debe ser un archivo de tipo: jpeg, png, jpg, gif, svg.',
            'foto4.max' => 'El campo foto4 no debe exceder los 2048 kilobytes.',

            'logo.file' => 'El campo logo debe ser un archivo.',
            'logo.mimes' => 'El campo logo debe ser un archivo de tipo: jpeg, png, jpg, gif, svg.',
            'logo.max' => 'El campo logo no debe exceder los 2048 kilobytes.',

            'fax.string' => 'El campo fax debe ser una cadena de texto.',
            'fax.max' => 'El campo fax no debe exceder los 16 caracteres.',

            'logo_cabecera.file' => 'El campo_cabecera logo debe ser un archivo.',
            'logo_cabecera.mimes' => 'El campo_cabecera logo debe ser un archivo de tipo: jpeg, png, jpg, gif, svg.',
            'logo_cabecera.max' => 'El campo_cabecera logo no debe exceder los 2048 kilobytes.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['error' => $validator->errors()], 400));
    }
}