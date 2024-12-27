<?php

namespace App\Http\Requests\Productos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class TipoProductoRequest extends FormRequest
{
    public function rules()
    {
        return [
            'CodigoTipoProducto' => ['required', 'string', 'max:10'],
            'Descripcion' => ['required', 'string', 'max:60'],
            'CodigoGrupoMaterial' => ['required', 'string', 'max:10'],
        ];
    }

    public function messages()
    {
        return [
            'CodigoTipoProducto.required' => 'El campo Código Tipo Producto es obligatorio.',
            'CodigoTipoProducto.string' => 'El campo Código Tipo Producto debe ser una cadena de texto.',
            'CodigoTipoProducto.max' => 'El campo Código Tipo Producto no puede exceder los 10 caracteres.',

            'Descripcion.required' => 'El campo Descripción es obligatorio.',
            'Descripcion.string' => 'El campo Descripción debe ser una cadena de texto.',
            'Descripcion.max' => 'El campo Descripción no puede exceder los 60 caracteres.',

            'CodigoGrupoMaterial.required' => 'El campo Código Grupo Material es obligatorio.',
            'CodigoGrupoMaterial.string' => 'El campo Código Grupo Material debe ser una cadena de texto.',
            'CodigoGrupoMaterial.max' => 'El campo Código Grupo Material no puede exceder los 10 caracteres.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['error' => $validator->errors()], 400));
    }
}
