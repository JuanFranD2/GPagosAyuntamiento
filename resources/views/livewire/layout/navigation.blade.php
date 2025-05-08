<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;
use App\Models\User; // Importa el modelo User para poder usar auth()->user()->isAdmin()

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }

    // No es necesario definir una propiedad para el usuario o el rol
    // si solo se va a usar en la vista para renderizado condicional simple.
}; ?>

<nav x-data="{ open: false }" class="bg-[#419639] border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    {{-- Eliminado el enlace al dashboard desde el logo --}}
                    {{-- <a href="{{ route('dashboard') }}" wire:navigate> --}}

                    {{-- REEMPLAZADO: Componente x-application-logo por una etiqueta img --}}
                    <img src="{{ asset('imgs/AYTO HINOJOS NUEVO - BLANCO.png') }}" alt="Logo del Ayuntamiento de Hinojos"
                        class="block h-12 w-auto"> {{-- Mantenemos las clases de tamaño --}}

                    {{-- </a> --}}
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    {{-- Eliminado el enlace al dashboard --}}
                    {{--
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    --}}
                    <x-nav-link :href="route('invoices.create')" :active="request()->routeIs('invoices.create')" wire:navigate>
                        {{ __('Nuevo Pago') }}
                    </x-nav-link>
                    {{-- NOTA: Los colores blanco/negro de estos enlaces NO se definen aquí, sino en los archivos de los componentes x-nav-link --}}
                    <x-nav-link :href="route('invoices.index')" :active="request()->routeIs('invoices.index')" wire:navigate>
                        {{ __('Pagos') }}
                    </x-nav-link>

                    {{-- Mostrar Métodos de Pago y Usuarios solo si el usuario es admin --}}
                    @if (auth()->user()?->isAdmin())
                        <x-nav-link :href="route('payment-methods.index')" :active="request()->routeIs('payment-methods.index')" wire:navigate>
                            {{ __('Métodos de Pago') }}
                        </x-nav-link>
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')" wire:navigate>
                            {{ __('Usuarios') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                                x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        {{-- Mostrar Perfil solo si el usuario es admin --}}
                        @if (auth()->user()?->isAdmin())
                            <x-dropdown-link :href="route('profile')" wire:navigate>
                                {{ __('Perfil') }}
                            </x-dropdown-link>
                        @endif
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Cerrar Sesión') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            {{-- Eliminado el enlace al dashboard responsive --}}
            {{--
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            --}}
            {{-- NOTA: Los colores blanco/negro de estos enlaces NO se definen aquí, sino en los archivos de los componentes x-responsive-nav-link --}}
            <x-responsive-nav-link :href="route('invoices.index')" :active="request()->routeIs('invoices.index')" wire:navigate>
                {{ __('Pagos') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('invoices.create')" :active="request()->routeIs('invoices.create')" wire:navigate>
                {{ __('Nuevo Pago') }}
            </x-responsive-nav-link>

            {{-- Mostrar Métodos de Pago y Usuarios solo si el usuario es admin --}}
            @if (auth()->user()?->isAdmin())
                <x-responsive-nav-link :href="route('payment-methods.index')" :active="request()->routeIs('payment-methods.index')" wire:navigate>
                    {{ __('Métodos de Pago') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')" wire:navigate>
                    {{ __('Usuarios') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                    x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                {{-- Mostrar Perfil solo si el usuario es admin --}}
                @if (auth()->user()?->isAdmin())
                    <x-responsive-nav-link :href="route('profile')" wire:navigate>
                        {{ __('Perfil') }}
                    </x-responsive-nav-link>
                @endif
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Cerrar Sesión') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
