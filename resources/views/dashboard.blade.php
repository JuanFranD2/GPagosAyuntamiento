<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestor de Pagos') }}
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex justify-center">
                    <img src="{{ asset('imgs/LOGOAYUNTAMIENTOHINOJOS.jpg') }}" alt="Logo del Ayuntamiento de Hinojos"
                        class="w-100% h-auto"> {{-- Ejemplo de ancho fijo: w-64 (256px) --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>