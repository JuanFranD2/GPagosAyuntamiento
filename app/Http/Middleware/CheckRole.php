<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // Importar la fachada Auth

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  // El rol requerido como parámetro
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            // Si no está autenticado, redirigir a la página de login
            return redirect()->route('login');
        }

        // Obtener el usuario autenticado
        $user = Auth::user();

        // Verificar si el rol del usuario NO coincide con el rol requerido
        // Asumimos que tu modelo User tiene un atributo 'role'
        if ($user->role !== $role) {
            // Si el rol no coincide, abortar con un error 403 Forbidden
            // o redirigir a una página de error o al dashboard con un mensaje.
            // Abortar 403 es lo estándar para "prohibido".
            abort(403, 'No tienes los permisos necesarios para acceder a esta sección.');
        }

        // Si el usuario tiene el rol correcto, permitir que la solicitud continúe
        return $next($request);
    }
}
