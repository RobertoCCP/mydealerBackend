<?php

namespace App\Http\Requests\Configuracion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class TipoNovedadRequest extends FormRequest
{
    public function rules()
    {
        return [
            'Codigo' => ['required', 'string', 'max:5'],
            'Tipo_novedad' => ['required', 'string', 'max:120'],
            'Categoria' => ['required', 'string', 'max:120'],
            'Orden' => ['required', 'numeric'],
            'Control' => ['required', 'regex:/^[RC]$/'],
            'Estado' => ['nullable', 'regex:/^[IA]$/'],
        ];
    }

    public function messages()
{
    return [
        'Codigo.required' => 'El campo Codigo es obligatorio.',
        'Codigo.string' => 'El campo Codigo debe ser una cadena de texto.',
        'Codigo.max' => 'El campo Codigo debe tener entre 1-5 letras.',
        
        'Tipo_novedad.required' => 'El campo Tipo_novedad es obligatorio.',
        'Tipo_novedad.string' => 'El campo Tipo_novedad debe ser una cadena de texto.',
        'Tipo_novedad.max' => 'El campo Tipo_novedad debe tener un máximo de 120 caracteres.',
        
        'Categoria.required' => 'El campo Categoria es obligatorio.',
        'Categoria.string' => 'El campo Categoria debe ser una cadena de texto.',
        'Categoria.max' => 'El campo Categoria debe tener un máximo de 120 caracteres.',
        
        'Orden.required' => 'El campo Orden es obligatorio.',
        'Orden.numeric' => 'El campo Orden debe ser un número entero.',
        
        'Control.required' => 'El campo Control es obligatorio.',
        'Control.regex' => 'El campo Control debe ser "R" o "C".',
        
        'Estado.regex' => 'El campo Estado debe ser "I" o "A".',
    ];
}

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['error' => $validator->errors()], 400));
    }
}