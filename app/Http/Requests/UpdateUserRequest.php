<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Similar al StoreUserRequest, aquí podrías tener lógica de autorización.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Accede al usuario de la ruta para ignorar su propio email en la validación unique
        // El parámetro 'user' se obtiene automáticamente del Route Model Binding
        $userId = $this->route('user')->id;

        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'role' => 'required|string|in:admin,oper',
            // La contraseña es opcional para la actualización
            'password' => 'nullable|string|min:8|confirmed',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El campo Nombre es obligatorio.',
            'name.string' => 'El campo Nombre debe ser texto.',
            'name.max' => 'El campo Nombre no debe exceder los 255 caracteres.',
            'email.required' => 'El campo Email es obligatorio.',
            'email.string' => 'El campo Email debe ser texto.',
            'email.email' => 'El campo Email debe ser una dirección de correo válida.',
            'email.max' => 'El campo Email no debe exceder los 255 caracteres.',
            'email.unique' => 'El Email ingresado ya está registrado.',
            'password.string' => 'El campo Nueva Contraseña debe contener mínimo una letra.',
            'password.min' => 'El campo Nueva Contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'role.required' => 'El campo Rol es obligatorio.',
            'role.string' => 'El campo Rol debe ser texto.',
            'role.in' => 'El Rol seleccionado no es válido.',
        ];
    }
}
