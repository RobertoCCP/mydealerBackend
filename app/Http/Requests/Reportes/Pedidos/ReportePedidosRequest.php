<?php

namespace App\Http\Requests\Reportes\Pedidos;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class ReportePedidosRequest extends BaseRequest {
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
            'cod_sucursal' => 'required|string',
            'cod_vendedor' => 'required|string',
            'cliente' => 'string|nullable',
            'estado_autorizacion' => 'required|string',
            'cod_motivo_rechazo' => 'required|string',
            'fecha_inicio' => 'required|date|date_format:Y-m-d',
            'fecha_fin' => 'required|date|date_format:Y-m-d',
            'tipo_pedido' => 'required|string',
            'origen' => 'required|string',
            'tipo_entrega' => 'required|string',
            'no_renegociado' => 'required|boolean'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array {
        return [
            'cod_sucursal.required' => 'El código de la sucursal es requerido',
            'cod_vendedor.required' => 'El código del vendedor es requerido',
            'cliente.string' => 'El cliente debe ser una cadena de texto',
            'estado_autorizacion.required' => 'El estado de autorización es requerido',
            'motivo_rechazo.required' => 'El motivo de rechazo es requerido',
            'fecha_inicio.required' => 'La fecha de inicio es requerida',
            'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha',
            'fecha_inicio.date_format' => 'La fecha de inicio debe tener el formato Y-m-d',
            'fecha_fin.required' => 'La fecha de fin es requerida',
            'fecha_fin.date' => 'La fecha de fin debe ser una fecha',
            'fecha_inicio.date_format' => 'La fecha de inicio debe tener el formato Y-m-d',
            'tipo_pedido.required' => 'El tipo de pedido es requerido',
            'origen.required' => 'El origen es requerido',
            'tipo_entrega.required' => 'El tipo de entrega es requerido',
            'no_renegociado.required' => 'El campo no_renegociado es requerido',
            'no_renegociado.boolean' => 'El campo no_renegociado debe ser un booleano'
        ];
    }
}
