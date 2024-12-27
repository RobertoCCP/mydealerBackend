<?php

namespace App\Http\Requests\Reportes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ReporteCorreosRequest extends FormRequest
{
    public function rules()
    {
        return [
            'fecha_inicio' => ['sometimes', 'date'],
            'fecha_fin' => ['sometimes', 'date'],
            'estado' => ['sometimes', 'string', 'max:1'],
        ];
    }

    public function messages()
    {
        return [
            'fecha_inicio.date' => 'El campo Fecha Inicial debe ser una fecha válida.',
            'fecha_fin.date' => 'El campo Fecha Final debe ser una fecha válida.',
            'estado.string' => 'El campo Estado debe ser una cadena de texto.',
            'estado.max' => 'El campo Estado no puede exceder un carácter.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['error' => $validator->errors()], 400));
    }
}
