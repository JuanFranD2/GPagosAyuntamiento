<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Importar la fachada Hash
use Illuminate\Validation\Rule; // Importar Rule para validación unique

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::latest()->get(); // Obtener todos los usuarios
        // NOTA: Si tienes muchos usuarios, considera paginación:
        // $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', // Email debe ser único
            'password' => 'required|string|min:8|confirmed', // Contraseña requerida, mínimo 8, con confirmación
            'role' => 'required|string|in:admin,oper', // Validar el rol
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hashear la contraseña
            'role' => $request->role,
        ]);

        // Devolver el usuario creado (o los datos relevantes) para actualizar la tabla vía AJAX
        return response()->json($user);
    }

    /**
     * Display the specified user (often not needed for modal-based CRUD on index).
     */
    public function show(User $user)
    {
        // Podría usarse para devolver datos de un usuario específico via AJAX si se necesita
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified user (or return data for modal).
     */
    public function edit(User $user)
    {
        // Este método podría usarse para devolver los datos del usuario para el modal de edición via AJAX
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
        // Validar los datos, el email debe ser único excepto para el usuario actual
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|string|in:admin,oper', // Validar el rol
            // La contraseña es opcional en la actualización, pero si se proporciona, validarla
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        // Si se proporciona una nueva contraseña, hashearla y guardarla
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Devolver el usuario actualizado (o los datos relevantes) para actualizar la tabla vía AJAX
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
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        /* Opcional: Añadir lógica de seguridad aquí, como no permitir eliminar al usuario autenticado
        if (auth()->check() && auth()->user()->id === $user->id) {
            return response()->json(['message' => 'No puedes eliminar tu propio usuario.'], 403);
        }*/

        $user->delete();

        return response()->json(['message' => 'Usuario eliminado correctamente.']);
    }
}
