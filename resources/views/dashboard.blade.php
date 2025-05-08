<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestor de Pagos') }}
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex justify-center"> {{-- Añadido flex justify-center para centrar la imagen --}}
                    {{-- Aquí insertamos la imagen usando asset() --}}
                    <img src="{{ asset('imgs/LOGOAYUNTAMIENTOHINOJOS.jpg') }}" alt="Logo del Ayuntamiento de Hinojos"
                        class="max-w-full h-auto"> {{-- Añadidas clases para que la imagen sea responsiva y grande --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
