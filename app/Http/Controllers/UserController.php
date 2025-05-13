<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log; // Importar la fachada Log
use Illuminate\Validation\ValidationException; // Importar ValidationException

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        // No logging needed typically for index display
        $users = User::latest()->get();
        return view('users.index', compact('users'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        // Log de entrada al método store
        Log::info('Attempting to create a new user.', [
            'ip' => $request->ip(),
            'data' => $request->only('name', 'email', 'role') // Log solo campos relevantes
        ]);

        try {
            // Validación con mensajes personalizados en español directamente en el controlador
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users', // Email debe ser único
                'password' => 'required|string|min:8|confirmed', // Contraseña requerida, mínimo 8, con confirmación
                'role' => 'required|string|in:admin,oper', // Validar el rol
            ], [
                // *** Mensajes de validación personalizados en español ***
                'name.required' => 'El campo Nombre es obligatorio.',
                'name.string' => 'El campo Nombre debe ser texto.',
                'name.max' => 'El campo Nombre no debe exceder los 255 caracteres.',
                'email.required' => 'El campo Email es obligatorio.',
                'email.string' => 'El campo Email debe ser texto.',
                'email.email' => 'El campo Email debe ser una dirección de correo válida.',
                'email.max' => 'El campo Email no debe exceder los 255 caracteres.',
                'email.unique' => 'El Email ingresado ya está registrado.',
                'password.required' => 'El campo Contraseña es obligatorio.',
                'password.string' => 'El campo Contraseña debe contener mínimo una letra.',
                'password.min' => 'El campo Contraseña debe tener al menos 8 caracteres.',
                'password.confirmed' => 'La confirmación de la contraseña no coincide.',
                'role.required' => 'El campo Rol es obligatorio.',
                'role.string' => 'El campo Rol debe ser texto.',
                'role.in' => 'El Rol seleccionado no es válido.',
            ]);

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']), // Hashear la contraseña
                'role' => $validatedData['role'],
            ]);

            // Log de éxito al crear usuario
            Log::info('User created successfully.', ['user_id' => $user->id, 'email' => $user->email]);

            // Devolver el usuario creado (o los datos relevantes) para actualizar la tabla vía AJAX
            // Asegúrate de NO devolver la contraseña
            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]);
        } catch (ValidationException $e) {
            // Log específico para errores de validación (opcional)
            Log::warning('Validation failed during user creation.', [
                'ip' => $request->ip(),
                'errors' => $e->errors(), // $e->errors() ya contiene los mensajes personalizados si se definieron
                'data' => $request->only('name', 'email', 'role')
            ]);
            // Relanzar la excepción para que Laravel devuelva la respuesta 422 JSON esperada por AJAX
            throw $e;
        } catch (\Exception $e) {
            // Log de cualquier otro error (ej: error de DB)
            Log::error('Error creating user.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
                'data' => $request->only('name', 'email', 'role')
            ]);

            // Devolver una respuesta de error genérica para peticiones AJAX
            return response()->json(['message' => 'Ocurrió un error en el servidor al crear el usuario.'], 500);
        }
    }

    /**
     * Display the specified user (often not needed for modal-based CRUD on index).
     */
    public function show(User $user)
    {
        // Método show, típicamente usado para devolver datos específicos de un recurso.
        // No logging complejo necesario aquí a menos que haya lógica de acceso.
        // Asegúrate de NO devolver la contraseña hasheada.
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            // No devolver password
        ]);
    }

    /**
     * Show the form for editing the specified user (or return data for modal).
     */
    public function edit(User $user)
    {
        // Método edit, típicamente usado para devolver datos para un formulario de edición.
        // No logging complejo necesario aquí a menos que haya lógica de acceso.
        // Asegúrate de NO devolver la contraseña hasheada.
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            // No devolver password
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // Log de entrada al método update
        Log::info('Attempting to update user.', [
            'user_id' => $user->id,
            'ip' => $request->ip(),
            'data' => $request->only('name', 'email', 'role') // Log solo campos relevantes
        ]);

        try {
            // Validación con mensajes personalizados en español directamente en el controlador
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'role' => 'required|string|in:admin,oper', // Validar el rol
                // La contraseña es opcional en la actualización, pero si se proporciona, validarla
                'password' => 'nullable|string|min:8|confirmed',
            ], [
                // *** Mensajes de validación personalizados en español ***
                'name.required' => 'El campo Nombre es obligatorio.',
                'name.string' => 'El campo Nombre debe ser texto.',
                'name.max' => 'El campo Nombre no debe exceder los 255 caracteres.',
                'email.required' => 'El campo Email es obligatorio.',
                'email.string' => 'El campo Email debe ser texto.',
                'email.email' => 'El campo Email debe ser una dirección de correo válida.',
                'email.max' => 'El campo Email no debe exceder los 255 caracteres.',
                'email.unique' => 'El Email ingresado ya está registrado.',
                'password.string' => 'El campo Nueva Contraseña debe contener mínimo una letra.', // Mensaje adaptado para edición
                'password.min' => 'El campo Nueva Contraseña debe tener al menos 8 caracteres.', // Mensaje adaptado
                'password.confirmed' => 'La confirmación de la contraseña no coincide.',
                // 'confirmed' se aplica por defecto si el campo existe, no necesita clave específica aquí a menos que quieras cambiar el mensaje genérico para *todos* los confirmed. Laravel usa 'validation.confirmed'.
                'role.required' => 'El campo Rol es obligatorio.',
                'role.string' => 'El campo Rol debe ser texto.',
                'role.in' => 'El Rol seleccionado no es válido.',
            ]);

            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->role = $validatedData['role'];

            // Si se proporciona una nueva contraseña (y ha pasado la validación), hashearla y guardarla
            // La clave 'password' solo estará en $validatedData si el campo password no era nulo y pasó la validación
            if (array_key_exists('password', $validatedData) && !empty($validatedData['password'])) {
                $user->password = Hash::make($validatedData['password']);
            }

            $user->save();

            // Log de éxito al actualizar usuario
            Log::info('User updated successfully.', ['user_id' => $user->id, 'email' => $user->email]);


            // Devolver el usuario actualizado (o los datos relevantes) para actualizar la tabla vía AJAX
            // Asegúrate de NO devolver la contraseña hasheada.
            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                // No devolver password
            ]);
        } catch (ValidationException $e) {
            // Log específico para errores de validación (opcional)
            Log::warning('Validation failed during user update.', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
                'errors' => $e->errors(), // $e->errors() ya contiene los mensajes personalizados si se definieron
                'data' => $request->only('name', 'email', 'role')
            ]);
            // Relanzar la excepción
            throw $e;
        } catch (\Exception $e) {
            // Log de cualquier otro error
            Log::error('Error updating user.', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
                'data' => $request->only('name', 'email', 'role')
            ]);

            // Devolver una respuesta de error genérica para peticiones AJAX
            return response()->json(['message' => 'Ocurrió un error en el servidor al actualizar el usuario.'], 500);
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Log de entrada al método destroy
        Log::info('Attempting to delete user.', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => request()->ip() // Usar helper request() si no se inyecta
        ]);

        try {
            /* Opcional: Añadir lógica de seguridad aquí, como no permitir eliminar al usuario autenticado
            if (auth()->check() && auth()->user()->id === $user->id) {
                Log::warning('Attempted to delete own user account.', ['user_id' => $user->id, 'ip' => request()->ip()]);
                return response()->json(['message' => 'No puedes eliminar tu propio usuario.'], 403);
            }*/

            $user->delete();

            // Log de éxito al eliminar usuario
            Log::info('User deleted successfully.', ['user_id' => $user->id, 'email' => $user->email]);

            return response()->json(['message' => 'Usuario eliminado correctamente.']);
        } catch (\Exception $e) {
            // Log de cualquier otro error
            Log::error('Error deleting user.', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => request()->ip()
            ]);

            // Devolver una respuesta de error genérica para peticiones AJAX
            // Usar 409 Conflict si hay restricciones de integridad (ej: usuario con pagos)
            return response()->json(['message' => 'Ocurrió un error en el servidor al eliminar el usuario.'], 500);
        }
    }
}
