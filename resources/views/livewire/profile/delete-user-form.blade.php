<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Eliminar cuenta') }} {{-- Traducido --}}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente. Antes de eliminar tu cuenta, por favor descarga cualquier dato o información que desees conservar.') }}
            {{-- Traducido --}}
        </p>
    </header>

    <x-danger-button x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">{{ __('Eliminar cuenta') }}</x-danger-button>
    {{-- Traducido --}}

    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-6">

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('¿Estás seguro de que quieres eliminar tu cuenta?') }} {{-- Traducido --}}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente. Por favor, introduce tu contraseña para confirmar que deseas eliminar tu cuenta de forma permanente.') }}
                {{-- Traducido --}}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Contraseña') }}" class="sr-only" /> {{-- Traducido --}}

                <x-text-input wire:model="password" id="password" name="password" type="password"
                    class="mt-1 block w-3/4" placeholder="{{ __('Contraseña') }}" {{-- Traducido --}} />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancelar') }} {{-- Traducido --}}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Eliminar cuenta') }} {{-- Traducido --}}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
