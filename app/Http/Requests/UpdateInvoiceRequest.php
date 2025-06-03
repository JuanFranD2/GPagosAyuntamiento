<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Asegúrate de que el usuario autenticado pueda actualizar esta factura.
        // Aquí podrías usar una política de autorización: $this->user()->can('update', $this->invoice);
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Accede a la factura que se está actualizando a través de la inyección de ruta
        $invoiceId = $this->route('invoice')->id;

        return [
            // Validar que el invoice_number sea único, ignorando el de la factura actual
            'invoice_number' => ['nullable', 'string', 'max:255', Rule::unique('invoices')->ignore($invoiceId)],
            'liquidation_id' => ['required', 'exists:liquidations,id'],
            'client_id' => ['required', 'exists:clients,id'],
            'issue_date' => ['nullable', 'date'],
            'total_amount' => ['nullable', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
            'status' => ['nullable', 'string', 'max:50'],
            // Añade aquí cualquier otro campo que se pueda actualizar desde el modal
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'invoice_number.unique' => 'El número de factura ya existe.',
            'liquidation_id.required' => 'La liquidación es obligatoria.',
            'liquidation_id.exists' => 'La liquidación seleccionada no es válida.',
            'client_id.required' => 'El cliente es obligatorio.',
            'client_id.exists' => 'El cliente seleccionado no es válido.',
            'total_amount.numeric' => 'El monto total debe ser un número.',
            'total_amount.regex' => 'El monto total debe tener un formato numérico válido (ej. 1000.50).',
        ];
    }
}