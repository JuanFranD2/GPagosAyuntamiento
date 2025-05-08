<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Aquí puedes poner tu lógica de autorización.
        // Por ejemplo, verificar si el usuario está autenticado y tiene permiso para crear facturas.
        // Si tu autorización se maneja en middleware o policies, puedes simplemente retornar true.
        return true; // Asumiendo que la autorización se maneja externamente
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules()
    {
        return [
            // Validación para los datos del Interesado (Cliente)
            'client.cif_nif' => ['required', 'string', 'max:255'], // 'unique:clients,cif_nif' - Comentado porque updateOrCreate maneja la unicidad, pero valida formato
            'client.name' => ['required', 'string', 'max:255'],
            'client.phone' => ['nullable', 'string', 'max:50'],
            'client.address' => ['nullable', 'string', 'max:255'],
            'client.number' => ['nullable', 'string', 'max:50'],
            'client.letter' => ['nullable', 'string', 'max:10'],
            'client.staircase' => ['nullable', 'string', 'max:50'],
            'client.postal_code' => ['nullable', 'string', 'max:20'],
            'client.town_municipality' => ['nullable', 'string', 'max:255'],
            'client.province' => ['nullable', 'string', 'max:255'],
            'client.email' => ['nullable', 'email', 'max:255'],

            // Validación para los datos del Representante (Opcional)
            // Validar campos de representante SOLO si se proporciona el CIF/NIF
            'representative.cif_nif' => ['nullable', 'string', 'max:255'], // 'unique:clients,cif_nif' - Similar al cliente, comentado por updateOrCreate
            'representative.name' => ['required_with:representative.cif_nif', 'max:255'], // Requerido SOLO si cif_nif está presente
            'representative.phone' => ['nullable', 'string', 'max:50'],
            'representative.address' => ['nullable', 'string', 'max:255'],
            'representative.number' => ['nullable', 'string', 'max:50'],
            'representative.letter' => ['nullable', 'string', 'max:10'],
            'representative.staircase' => ['nullable', 'string', 'max:50'],
            'representative.postal_code' => ['nullable', 'string', 'max:20'],
            'representative.town_municipality' => ['nullable', 'string', 'max:255'],
            'representative.province' => ['nullable', 'string', 'max:255'],
            'representative.email' => ['nullable', 'email', 'max:255'],

            // Validación para los datos de Liquidación
            'liquidation.file_number' => ['required', 'string', 'max:255'], // Debe coincidir con la validación en el controller
            'liquidation.liquidation_date' => ['nullable', 'date'],
            'liquidation.concept' => ['required', 'string', 'max:255'], // Concepto parece ser requerido por el formulario

            // NOTA: Sin prepareForValidation, la regla 'numeric' esperará un punto como separador decimal.
            // Si tu frontend envía comas, la validación fallará. Podrías necesitar validar como string
            // y usar regex para aceptar comas/puntos, o asegurar que el frontend envíe puntos,
            // o manejar la conversión de coma a punto en el controlador después de validar.
            'liquidation.taxable_base' => ['required', 'numeric', 'min:0'], // regex:/^\d+(\.\d{1,2})?$/ - Eliminado si solo validas como numeric
            'liquidation.tax_rate' => ['required', 'numeric', 'min:0'],   // regex:/^\d+(\.\d{1,2})?$/ - Eliminado si solo validas como numeric
            'liquidation.bond' => ['required', 'numeric', 'min:0'],       // regex:/^\d+(\.\d{1,2})?$/ - Eliminado si solo validas como numeric


            // Campos específicos de la factura si se enviaran desde el formulario (parece que no en este caso)
            // 'issue_date' => ['nullable', 'date'], // Si la fecha se selecciona en el form
            // 'total_amount' => ['nullable', 'numeric'], // Si el total se calcula en el form y se envía
            // 'status' => ['nullable', 'string', 'max:50'], // Si el estado se selecciona en el form
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'client.cif_nif.required' => 'El CIF/NIF del interesado es obligatorio.',
            'client.cif_nif.string' => 'El CIF/NIF del interesado debe ser texto.',
            'client.name.required' => 'El nombre del interesado es obligatorio.',
            'client.name.string' => 'El nombre del interesado debe ser texto.',
            // ... agregar mensajes para otros campos del cliente

            'representative.name.required_with' => 'El nombre del representante es obligatorio si se proporciona su CIF/NIF.',
            // ... agregar mensajes para otros campos del representante

            'liquidation.file_number.required' => 'El número de expediente es obligatorio.',
            'liquidation.concept.required' => 'El concepto es obligatorio.',

            'liquidation.taxable_base.required' => 'La base imponible es obligatoria.',
            'liquidation.taxable_base.numeric' => 'La base imponible debe ser un número.', // Este mensaje aparecerá si se usa coma en lugar de punto
            'liquidation.taxable_base.min' => 'La base imponible debe ser un número positivo.',

            'liquidation.tax_rate.required' => 'El tipo impositivo es obligatorio.',
            'liquidation.tax_rate.numeric' => 'El tipo impositivo debe ser un número.', // Este mensaje aparecerá si se usa coma en lugar de punto
            'liquidation.tax_rate.min' => 'El tipo impositivo debe ser un número positivo.',

            'liquidation.bond.required' => 'La fianza es obligatoria.',
            'liquidation.bond.numeric' => 'La fianza debe ser un número.', // Este mensaje aparecerá si se usa coma en lugar de punto
            'liquidation.bond.min' => 'La fianza debe ser un número positivo.',
            // ... agregar mensajes para otros campos de liquidación
        ];
    }
}
