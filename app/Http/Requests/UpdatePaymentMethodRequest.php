<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePaymentMethodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Al igual que con el Store Request, puedes agregar lógica de autorización aquí.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Accede al modelo de método de pago que se está actualizando
        $paymentMethodId = $this->route('payment_method')->id;

        return [
            'bank_name' => 'required|string|max:255',
            // Rule::unique ignorará el ID del método de pago actual al verificar la unicidad
            'account_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('payment_methods', 'account_number')->ignore($paymentMethodId),
            ],
            // Agrega aquí cualquier otra regla de validación para la actualización
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
            'account_number.unique' => 'Este número de cuenta ya está registrado por otro método de pago.',
        ];
    }
}
