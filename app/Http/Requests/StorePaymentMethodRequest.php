<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentMethodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Si necesitas que solo usuarios autenticados o con ciertos permisos puedan crear métodos de pago,
        // puedes agregar aquí tu lógica de autorización. Por ahora, lo dejaremos como true para permitir la creación.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255|unique:payment_methods,account_number', // Agregado unique para el número de cuenta
            // Agrega aquí cualquier otra regla de validación para la creación
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'bank_name.required' => 'El nombre del banco es obligatorio.',
            'bank_name.string' => 'El nombre del banco debe ser una cadena de texto.',
            'bank_name.max' => 'El nombre del banco no puede exceder los 255 caracteres.',
            'account_number.required' => 'El número de cuenta es obligatorio.',
            'account_number.string' => 'El número de cuenta debe ser una cadena de texto.',
            'account_number.max' => 'El número de cuenta no puede exceder los 255 caracteres.',
            'account_number.unique' => 'Este número de cuenta ya está registrado.',
        ];
    }
}
