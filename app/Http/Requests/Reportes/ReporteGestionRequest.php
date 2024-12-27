<?php

namespace App\Http\Requests\Reportes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ReporteGestionRequest extends FormRequest
{
    public function rules()
    {
        return [
            'supervisor' => ['sometimes', 'string', 'max:50'],
            'vendedor' => ['sometimes', 'string', 'max:10'],
            'cliente' => ['sometimes', 'string', 'max:20'],
            'fecha_inicio' => ['sometimes', 'date'],
            'fecha_fin' => ['sometimes', 'date'],
            'tipo_novedad' => ['sometimes', 'string', 'max:1'],
        ];
    }

    public function messages()
    {
        return [
            'supervisor.string' => 'El campo Supervisor debe ser una cadena de texto.',
            'supervisor.max' => 'El campo Supervisor no puede exceder los 50 caracteres.',

            'vendedor.string' => 'El campo Vendedor debe ser una cadena de texto.',
            'vendedor.max' => 'El campo Vendedor no puede exceder los 10 caracteres.',

            'cliente.string' => 'El campo Cliente debe ser una cadena de texto.',
            'cliente.max' => 'El campo Cliente no puede exceder los 20 caracteres.',

            'fecha_inicio.date' => 'El campo Fecha Inicio debe ser una fecha válida.',
            'fecha_fin.date' => 'El campo Fecha Fin debe ser una fecha válida.',

            'tipo_novedad.string' => 'El campo Tipo Novedad debe ser una cadena de texto.',
            'tipo_novedad.max' => 'El campo Tipo Novedad no puede exceder los 1 caracteres.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['error' => $validator->errors()], 400));
    }
}


