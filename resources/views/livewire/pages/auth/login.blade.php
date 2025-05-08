<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    {{-- El color del texto del estado de sesión no se cambia a negro (mantener el estilo por defecto suele ser mejor para mensajes) --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <div>
            {{-- Etiqueta de Email - Texto en negro --}}
            <x-input-label for="email" :value="__('Correo Electrónico')" class="text-black" />
            {{-- El borde de enfoque (ring) ya se maneja en text-input.blade.php --}}
            <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full" type="email" name="email"
                required autofocus autocomplete="username" />
            {{-- El color del texto de error no se cambia a negro (mantener el estilo por defecto suele ser mejor para errores) --}}
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <div class="mt-4">
            {{-- Etiqueta de Contraseña - Texto en negro --}}
            <x-input-label for="password" :value="__('Contraseña')" class="text-black" />

            {{-- El borde de enfoque (ring) ya se maneja en text-input.blade.php --}}
            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full" type="password"
                name="password" required autocomplete="current-password" />

            {{-- El color del texto de error no se cambia a negro --}}
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                {{-- Checkbox - Color del "check" en verde, y borde de enfoque en verde --}}
                <input wire:model="form.remember" id="remember" type="checkbox"
                    class="rounded border-gray-300 shadow-sm text-[#419639] focus:ring-[#419639]" name="remember">
                {{-- Texto "Recuérdame" - Texto en negro --}}
                <span class="ms-2 text-sm text-black">{{ __('Recuérdame') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            {{-- Enlace "Olvidaste tu contraseña" (si está activo) - Texto en negro --}}
            {{-- @if (Route::has('password.request'))
                <a class="underline text-sm text-black hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Forgot your password?') }}
                </a>
            @endif
            --}}
            {{-- Botón "Iniciar Sesión" - Ya tiene el color de fondo verde --}}
            <x-primary-button class="ms-3 bg-[#419639] hover:bg-[#347e2d]">
                {{-- El texto del botón suele ser blanco o muy oscuro por defecto --}}
                {{ __('Iniciar Sesión') }}
            </x-primary-button>
        </div>
    </form>
</div>
