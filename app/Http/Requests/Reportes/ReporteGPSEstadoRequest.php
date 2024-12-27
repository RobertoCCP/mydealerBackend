<?php

namespace App\Http\Requests\Reportes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ReporteGPSEstadoRequest extends FormRequest
{
    public function rules()
    {
        return [
            'codvendedor' => ['sometimes', 'string', 'max:50'],
            'fechamovil' => ['sometimes', 'date'],
            'observacion' => ['sometimes', 'string', 'max:100'],
            'version' => ['sometimes', 'string', 'max:50'],
            'gestion' => ['sometimes', 'string', 'max:1'],
            'mac' => ['sometimes', 'string', 'max:50'],
            'bateria' => ['sometimes', 'integer'],
        ];
    }

    public function messages()
    {
        return [
            'codvendedor.string' => 'El campo Código Vendedor debe ser una cadena de texto.',
            'codvendedor.max' => 'El campo Código Vendedor no puede exceder los 50 caracteres.',

            'fechamovil.date' => 'El campo Fecha Móvil debe ser una fecha válida.',

            'observacion.string' => 'El campo Observación debe ser una cadena de texto.',
            'observacion.max' => 'El campo Observación no puede exceder los 100 caracteres.',

            'version.string' => 'El campo Versión debe ser una cadena de texto.',
            'version.max' => 'El campo Versión no puede exceder los 50 caracteres.',

            'gestion.string' => 'El campo Gestión debe ser una cadena de texto.',
            'gestion.max' => 'El campo Gestión no puede exceder los 1 caracteres.',

            'mac.string' => 'El campo MAC debe ser una cadena de texto.',
            'mac.max' => 'El campo MAC no puede exceder los 50 caracteres.',

            'bateria.integer' => 'El campo Batería debe ser un número entero.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['error' => $validator->errors()], 400));
    }
}
