<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

// Importar los nuevos Request
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::latest()->get();
        return view('users.index', compact('users'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request) // Inyectar StoreUserRequest
    {
        Log::info('Attempting to create a new user.', [
            'ip' => $request->ip(),
            'data' => $request->only('name', 'email', 'role')
        ]);

        try {
            // La validación ya se realizó automáticamente por StoreUserRequest
            $validatedData = $request->validated(); // Obtener los datos validados

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => $validatedData['role'],
            ]);

            Log::info('User created successfully.', ['user_id' => $user->id, 'email' => $user->email]);

            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]);
        } catch (ValidationException $e) {
            Log::warning('Validation failed during user creation.', [
                'ip' => $request->ip(),
                'errors' => $e->errors(),
                'data' => $request->only('name', 'email', 'role')
            ]);
            throw $e; // Laravel automáticamente convertirá esto en una respuesta JSON 422
        } catch (\Exception $e) {
            Log::error('Error creating user.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
                'data' => $request->only('name', 'email', 'role')
            ]);
            return response()->json(['message' => 'Ocurrió un error en el servidor al crear el usuario.'], 500);
        }
    }

    /**
     * Display the specified user (often not needed for modal-based CRUD on index).
     */
    public function show(User $user)
    {
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ]);
    }

    /**
     * Show the form for editing the specified user (or return data for modal).
     */
    public function edit(User $user)
    {
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, User $user) // Inyectar UpdateUserRequest
    {
        Log::info('Attempting to update user.', [
            'user_id' => $user->id,
            'ip' => $request->ip(),
            'data' => $request->only('name', 'email', 'role')
        ]);

        try {
            // La validación ya se realizó automáticamente por UpdateUserRequest
            $validatedData = $request->validated(); // Obtener los datos validados

            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->role = $validatedData['role'];

            // Si se proporciona una nueva contraseña, hashearla y guardarla
            if (isset($validatedData['password']) && !empty($validatedData['password'])) {
                $user->password = Hash::make($validatedData['password']);
            }

            $user->save();

            Log::info('User updated successfully.', ['user_id' => $user->id, 'email' => $user->email]);

            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]);
        } catch (ValidationException $e) {
            Log::warning('Validation failed during user update.', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
                'errors' => $e->errors(),
                'data' => $request->only('name', 'email', 'role')
            ]);
            throw $e; // Laravel automáticamente convertirá esto en una respuesta JSON 422
        } catch (\Exception $e) {
            Log::error('Error updating user.', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
                'data' => $request->only('name', 'email', 'role')
            ]);
            return response()->json(['message' => 'Ocurrió un error en el servidor al actualizar el usuario.'], 500);
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        Log::info('Attempting to delete user.', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => request()->ip()
        ]);

        try {
            /* Opcional: Añadir lógica de seguridad aquí, como no permitir eliminar al usuario autenticado
            if (auth()->check() && auth()->user()->id === $user->id) {
                Log::warning('Attempted to delete own user account.', ['user_id' => $user->id, 'ip' => request()->ip()]);
                return response()->json(['message' => 'No puedes eliminar tu propio usuario.'], 403);
            }*/

            $user->delete();

            Log::info('User deleted successfully.', ['user_id' => $user->id, 'email' => $user->email]);

            return response()->json(['message' => 'Usuario eliminado correctamente.']);
        } catch (\Exception $e) {
            Log::error('Error deleting user.', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => request()->ip()
            ]);

            return response()->json(['message' => 'Ocurrió un error en el servidor al eliminar el usuario.'], 500);
        }
    }
}
