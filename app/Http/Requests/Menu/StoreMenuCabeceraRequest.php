<?php

namespace App\Http\Requests\Menu;

use App\Http\Requests\BaseRequest;

class StoreMenuCabeceraRequest extends BaseRequest {

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'nombre' => 'required|string|max:20',
            'orden' => 'required|numeric',
            'sitio' => 'required|string|max:10'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */

    public function messages() {
        return [
            'nombre.required' => 'El campo Nombre es obligatorio.',
            'nombre.string' => 'El campo Nombre debe ser una cadena de texto.',
            'nombre.max' => 'El campo Nombre no puede exceder los 20 caracteres.',

            'orden.required' => 'El campo Orden es obligatorio.',
            'orden.numeric' => 'El campo Orden debe ser un número.',

            'sitio.required' => 'El campo Sitio es obligatorio.',
            'sitio.string' => 'El campo Sitio debe ser una cadena de texto.',
            'sitio.max' => 'El campo Sitio no puede exceder los 10 caracteres.',
        ];
    }
}
