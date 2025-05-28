<x-app-layout>
    <x-slot name="header">
        {{-- Añadimos 'relative' al contenedor flex para posicionamiento --}}
        <div class="flex justify-between items-center **relative**">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight py-2">
                {{ __('Crear Pago') }}
            </h2>
            {{-- Botón para mostrar las instrucciones de este formulario --}}
            {{-- Lo posicionamos 'absolute' y lo movemos a la derecha, centrado verticalmente --}}
            <button type="button" id="show-create-invoice-instructions-btn"
                class="inline-flex items-center px-4 py-2 border border-transparent text-lg leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150
                       **absolute top-1/2 right-4 -translate-y-1/2**">
                {{-- Clases de posicionamiento absoluto --}}
                <i class="fas fa-question-circle"></i> {{-- Icono de interrogación --}}
            </button>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-900">

                <form method="POST" action="{{ route('invoices.store') }}">
                    @csrf

                    {{-- Sección: Interesado (Paso 1) --}}
                    <div id="step-1" style="display: block;">
                        <h3 class="text-lg font-semibold mb-4">Interesado</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-x-4 gap-y-2 mb-6">
                            <div>
                                <label for="client_cif_nif"
                                    class="block text-sm font-medium text-gray-700">CIF/NIF</label>
                                <input type="text" name="client[cif_nif]" id="client_cif_nif"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm @error('client.cif_nif') border-red-500 @enderror"
                                    value="{{ old('client.cif_nif') }}" />
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('client.cif_nif')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label for="client_name" class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" name="client[name]" id="client_name"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm @error('client.name') border-red-500 @enderror"
                                    value="{{ old('client.name') }}" />
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('client.name')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                            <div>
                                <label for="client_phone"
                                    class="block text-sm font-medium text-gray-700">Teléfono</label>
                                <input type="text" name="client[phone]" id="client_phone"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm @error('client.phone') border-red-500 @enderror"
                                    value="{{ old('client.phone') }}" />
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('client.phone')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                            <div>
                                <label for="client_address"
                                    class="block text-sm font-medium text-gray-700">Dirección</label>
                                <input type="text" name="client[address]" id="client_address"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm @error('client.address') border-red-500 @enderror"
                                    value="{{ old('client.address') }}" />
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('client.address')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                            <div>
                                <label for="client_number"
                                    class="block text-sm font-medium text-gray-700">Número</label>
                                <input type="text" name="client[number]" id="client_number"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm @error('client.number') border-red-500 @enderror"
                                    value="{{ old('client.number') }}" />
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('client.number')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                            <div>
                                <label for="client_letter" class="block text-sm font-medium text-gray-700">Letra</label>
                                <input type="text" name="client[letter]" id="client_letter"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm @error('client.letter') border-red-500 @enderror"
                                    value="{{ old('client.letter') }}" />
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('client.letter')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                            <div>
                                <label for="client_staircase"
                                    class="block text-sm font-medium text-gray-700">Escalera</label>
                                <input type="text" name="client[staircase]" id="client_staircase"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm @error('client.staircase') border-red-500 @enderror"
                                    value="{{ old('client.staircase') }}" />
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('client.staircase')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                            <div>
                                <label for="client_postal_code" class="block text-sm font-medium text-gray-700">C.
                                    Postal</label>
                                <input type="text" name="client[postal_code]" id="client_postal_code"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm @error('client.postal_code') border-red-500 @enderror"
                                    value="{{ old('client.postal_code') }}" />
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('client.postal_code')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                            <div>
                                <label for="client_town_municipality"
                                    class="block text-sm font-medium text-gray-700">Municipio</label>
                                <input type="text" name="client[town_municipality]" id="client_town_municipality"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm @error('client.town_municipality') border-red-500 @enderror"
                                    value="{{ old('client.town_municipality') }}" />
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('client.town_municipality')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label for="client_province"
                                    class="block text-sm font-medium text-gray-700">Provincia</label>
                                <input type="text" name="client[province]" id="client_province"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm @error('client.province') border-red-500 @enderror"
                                    value="{{ old('client.province') }}" />
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('client.province')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                            <div class="md:col-span-4">
                                <label for="client_email" class="block text-sm font-medium text-gray-700">Correo
                                    electrónico</label>
                                <input type="text" name="client[email]" id="client_email"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm @error('client.email') border-red-500 @enderror"
                                    value="{{ old('client.email') }}" />
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('client.email')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                            <button type="button" id="next-step-1"
                                class="px-4 py-2 bg-gray-300 text-gray-700 font-semibold rounded-md hover:bg-gray-400 transition">
                                Siguiente
                            </button>
                        </div>
                    </div>

                    {{-- Sección: Representante (Paso 2) --}}
                    <div id="step-2" style="display: none;">
                        <h3 class="text-lg font-semibold mb-4">Representante</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-x-4 gap-y-2 mb-6">
                            <div>
                                <label for="rep_cif_nif"
                                    class="block text-sm font-medium text-gray-700">CIF/NIF</label>
                                <input type="text" name="representative[cif_nif]" id="rep_cif_nif"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm @error('representative.cif_nif') border-red-500 @enderror"
                                    value="{{ old('representative.cif_nif') }}" />
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('representative.cif_nif')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label for="rep_name" class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" name="representative[name]" id="rep_name"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm @error('representative.name') border-red-500 @enderror"
                                    value="{{ old('representative.name') }}" />
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('representative.name')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                            <div>
                                <label for="rep_phone"
                                    class="block text-sm font-medium text-gray-700">Teléfono</label>
                                <input type="text" name="representative[phone]" id="rep_phone"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm @error('representative.phone') border-red-500 @enderror"
                                    value="{{ old('representative.phone') }}" />
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('representative.phone')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                            <div>
                                <label for="rep_address"
                                    class="block text-sm font-medium text-gray-700">Dirección</label>
                                <input type="text" name="representative[address]" id="rep_address"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm @error('representative.address') border-red-500 @enderror"
                                    value="{{ old('representative.address') }}" />
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('representative.address')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                            <div>
                                <label for="rep_number" class="block text-sm font-medium text-gray-700">Número</label>
                                <input type="text" name="representative[number]" id="rep_number"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm @error('representative.number') border-red-500 @enderror"
                                    value="{{ old('representative.number') }}" />
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('representative.number')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                            <div>
                                <label for="rep_letter" class="block text-sm font-medium text-gray-700">Letra</label>
                                <input type="text" name="representative[letter]" id="rep_letter"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm @error('representative.letter') border-red-500 @enderror"
                                    value="{{ old('representative.letter') }}" />
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('representative.letter')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                            <div>
                                <label for="rep_staircase"
                                    class="block text-sm font-medium text-gray-700">Escalera</label>
                                <input type="text" name="representative[staircase]" id="rep_staircase"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm @error('representative.staircase') border-red-500 @enderror"
                                    value="{{ old('representative.staircase') }}" />
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('representative.staircase')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                            <div>
                                <label for="rep_postal_code" class="block text-sm font-medium text-gray-700">C.
                                    Postal</label>
                                <input type="text" name="representative[postal_code]" id="rep_postal_code"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm @error('representative.postal_code') border-red-500 @enderror"
                                    value="{{ old('representative.postal_code') }}" />
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('representative.postal_code')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                            <div>
                                <label for="rep_town_municipality"
                                    class="block text-sm font-medium text-gray-700">Municipio</label>
                                <input type="text" name="representative[town_municipality]"
                                    id="rep_town_municipality"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm @error('representative.town_municipality') border-red-500 @enderror"
                                    value="{{ old('representative.town_municipality') }}" />
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('representative.town_municipality')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label for="rep_province"
                                    class="block text-sm font-medium text-gray-700">Provincia</label>
                                <input type="text" name="representative[province]" id="rep_province"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm @error('representative.province') border-red-500 @enderror"
                                    value="{{ old('representative.province') }}" />
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('representative.province')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                            <div class="md:col-span-4">
                                <label for="rep_email" class="block text-sm font-medium text-gray-700">Correo
                                    electrónico</label>
                                <input type="text" name="representative[email]" id="rep_email"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm @error('representative.email') border-red-500 @enderror"
                                    value="{{ old('representative.email') }}" />
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('representative.email')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                        </div>
                        <div class="flex justify-between mt-4">
                            <button type="button" id="prev-step-2"
                                class="px-4 py-2 bg-gray-300 text-gray-700 font-semibold rounded-md hover:bg-gray-400 transition">
                                Atrás
                            </button>
                            <button type="button" id="next-step-2"
                                class="px-4 py-2 bg-gray-300 text-gray-700 font-semibold rounded-md hover:bg-gray-400 transition">
                                Siguiente
                            </button>
                        </div>
                    </div>

                    {{-- Sección: Liquidación y Métodos de Pago (Paso 3) --}}
                    <div id="step-3" style="display: none;">
                        <h3 class="text-lg font-semibold mb-4">Liquidación</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2 mb-6">
                            <div>
                                <label for="file_number" class="block text-sm font-medium text-gray-700">Nº
                                    Expediente</label>
                                <input type="text" name="liquidation[file_number]" id="file_number"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm @error('liquidation.file_number') border-red-500 @enderror"
                                    value="{{ old('liquidation.file_number') }}" />
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('liquidation.file_number')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                            <div>
                                <label for="liquidation_date" class="block text-sm font-medium text-gray-700">Fecha
                                    Liquidación</label>
                                <input type="date" name="liquidation[liquidation_date]" id="liquidation_date"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm @error('liquidation.liquidation_date') border-red-500 @enderror"
                                    value="{{ old('liquidation.liquidation_date', now()->format('Y-m-d')) }}"
                                    readonly />
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('liquidation.liquidation_date')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                            <div>
                                <label for="concept" class="block text-sm font-medium text-gray-700">Concepto</label>
                                <input type="text" name="liquidation[concept]" id="concept"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm @error('liquidation.concept') border-red-500 @enderror"
                                    value="{{ old('liquidation.concept') }}" />
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('liquidation.concept')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                            <div>
                                <label for="taxable_base" class="block text-sm font-medium text-gray-700">Base
                                    Imponible</label>
                                <div class="relative">
                                    <input type="text" name="liquidation[taxable_base]" id="taxable_base"
                                        class="mt-1 block w-full pr-10 rounded-md border-gray-300 shadow-sm text-sm @error('liquidation.taxable_base') border-red-500 @enderror"
                                        placeholder="0.00" value="{{ old('liquidation.taxable_base') }}" />
                                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm">€</span>
                                    </div>
                                </div>
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('liquidation.taxable_base')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                            <div>
                                <label for="tax_rate" class="block text-sm font-medium text-gray-700">Tipo
                                    Impositivo</label>
                                <div class="relative">
                                    <input type="text" name="liquidation[tax_rate]" id="tax_rate"
                                        class="mt-1 block w-full pr-10 rounded-md border-gray-300 shadow-sm text-sm @error('liquidation.tax_rate') border-red-500 @enderror"
                                        placeholder="0.00" value="{{ old('liquidation.tax_rate', '0') }}" />
                                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm">%</span>
                                    </div>
                                </div>
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('liquidation.tax_rate')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                            <div>
                                <label for="bond" class="block text-sm font-medium text-gray-700">Fianza</label>
                                <div class="relative">
                                    <input type="text" name="liquidation[bond]" id="bond"
                                        class="mt-1 block w-full pr-10 rounded-md border-gray-300 shadow-sm text-sm @error('liquidation.bond') border-red-500 @enderror"
                                        placeholder="0.00" value="{{ old('liquidation.bond') }}" />
                                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm">€</span>
                                    </div>
                                </div>
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('liquidation.bond')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                            <div>
                                <label for="total_to_pay" class="block text-sm font-medium text-gray-700">Total a
                                    Pagar</label>
                                <div class="relative">
                                    <input type="text" name="liquidation[total_to_pay]" id="total_to_pay"
                                        class="mt-1 block w-full pr-10 rounded-md border-gray-300 shadow-sm text-sm @error('liquidation.total_to_pay') border-red-500 @enderror"
                                        value="{{ old('liquidation.total_to_pay', '0,00') }}" readonly />
                                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm">€</span>
                                    </div>
                                </div>
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('liquidation.total_to_pay')
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;">
                                        {{ $message }}</p>
                                @else
                                    <p class="text-red-500 text-xs mt-1" style="height: 15px; overflow: hidden;"></p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-between mt-6">
                            <button type="button" id="prev-step-3"
                                class="px-4 py-2 bg-gray-300 text-gray-700 font-semibold rounded-md hover:bg-gray-400 transition">
                                Atrás
                            </button>
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition">
                                Crear Pago
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Estilos CSS para asegurar el espacio fijo de los mensajes de error --}}
    <style>
        .text-red-500.text-xs.mt-1 {
            height: 15px;
            /* Fija la altura exacta */
            overflow: hidden;
            /* Oculta el contenido que exceda la altura */
            display: block;
            /* Asegura que height y overflow se apliquen */
        }
    </style>

    {{-- Estructura del Modal de Instrucciones de Creación de Pago --}}
    <div id="create-invoice-instructions-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border max-w-3xl shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="create-invoice-instructions-modal-title">
                    {{ __('Instrucciones: Crear Pago') }}</h3>
                <div class="mt-2 px-7 py-3 text-left">
                    <p class="text-sm text-gray-500" id="create-invoice-instructions-modal-body">
                        {{-- El texto de las instrucciones se insertará aquí --}}
                    </p>
                </div>
                <div class="items-center px-4 py-3 mt-4 flex justify-end">
                    <button type="button" id="close-create-invoice-modal-btn"
                        class="px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2">
                        {{ __('Cerrar') }}
                    </button>
                </div>
            </div>
        </div>
    </div>


    {{-- Incluir tus scripts JS aquí --}}
    @push('scripts')
        {{-- Asegúrate de incluir jQuery si aún no está en tu layout principal --}}
        <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
        {{-- Asegúrate de incluir Font Awesome si aún no está en tu layout principal --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


        <script>
            // Envuelve toda la lógica JS en una función para poder reinicializarla con Livewire
            function initializeCreateInvoiceForm() {

                // --- Funciones de utilidad ---
                function calcularLetraDni(dni) {
                    const letras = "TRWAGMYFPDXBNJZSQVHLCKET";
                    const numero = parseInt(dni, 10);

                    if (isNaN(numero) || dni.length !== 8) {
                        return null;
                    }

                    const posicion = numero % 23;
                    return letras[posicion];
                }

                function aplicarCalculoDniAutomatico(inputId) {
                    const input = document.getElementById(inputId);
                    if (!input) return;

                    // Clonar y reemplazar para limpiar listeners
                    const newInputElement = input.cloneNode(true);
                    input.parentNode.replaceChild(newInputElement, input);
                    const updatedInput = document.getElementById(inputId); // Get the new element instance

                    updatedInput.addEventListener('input', function() {
                        let valor = this.value.trim().toUpperCase();
                        if (/^\d{8}[A-Z]$/.test(valor)) {
                            valor = valor.slice(0, 8);
                        }
                        if (/^\d{8}$/.test(valor)) {
                            const letra = calcularLetraDni(valor);
                            if (letra !== null) {
                                this.value = valor + letra;
                            }
                        }
                    });
                }

                // Convierte a mayúsculas al escribir (para todos los campos de texto)
                // Esto es una alternativa si no se usa prepareForValidation en el Request/FormRequest
                function convertirAMayusculas() {
                    const camposTexto = document.querySelectorAll('input[type="text"]');
                    camposTexto.forEach(campo => {
                        // Limpiar listeners previos si existen
                        const oldHandler = campo._toUpperCaseHandler;
                        if (oldHandler) {
                            campo.removeEventListener('input', oldHandler);
                        }

                        const newHandler = function() {
                            this.value = this.value.toUpperCase();
                        };
                        campo.addEventListener('input', newHandler);
                        // Almacenar el nuevo handler para poder limpiarlo después
                        campo._toUpperCaseHandler = newHandler;
                    });
                }


                function populateFormFields(prefix, data) {
                    $(`#${prefix}_name`).val(data.name || '');
                    $(`#${prefix}_address`).val(data.address || '');
                    $(`#${prefix}_number`).val(data.number || '');
                    $(`#${prefix}_letter`).val(data.letter || '');
                    $(`#${prefix}_staircase`).val(data.staircase || '');
                    $(`#${prefix}_phone`).val(data.phone || '');
                    $(`#${prefix}_town_municipality`).val(data.town_municipality || '');
                    $(`#${prefix}_province`).val(data.province || '');
                    $(`#${prefix}_postal_code`).val(data.postal_code || '');
                    $(`#${prefix}_email`).val(data.email || '');

                    // Convertir a mayúsculas los campos rellenados por populateFormFields
                    // Esto es importante si no usas prepareForValidation en el backend
                    document.querySelectorAll(`#step-1 input[type="text"], #step-2 input[type="text"]`).forEach(campo => {
                        campo.value = campo.value.toUpperCase();
                    });
                }

                function clearFormFields(prefix) {
                    $(`#${prefix}_name`).val('');
                    $(`#${prefix}_address`).val('');
                    $(`#${prefix}_number`).val('');
                    $(`#${prefix}_letter`).val('');
                    $(`#${prefix}_staircase`).val('');
                    $(`#${prefix}_phone`).val('');
                    $(`#${prefix}_town_municipality`).val('');
                    $(`#${prefix}_province`).val('');
                    $(`#${prefix}_postal_code`).val('');
                    $(`#${prefix}_email`).val('');
                }

                function setupCifNifAutocomplete(inputId, prefix) {
                    const inputElement = $(`#${inputId}`);
                    if (!inputElement.length) return;

                    // Limpiar listeners previos
                    inputElement.off('input.autocomplete');
                    inputElement.off('change.autocomplete');

                    let debounceTimer;
                    const minLength = 8; // Mínimo 8 caracteres para CIF/NIF

                    inputElement.on('input.autocomplete', function() {
                        clearTimeout(debounceTimer);
                        const cifNif = $(this).val().trim();

                        if (cifNif === '') {
                            clearFormFields(prefix);
                            return;
                        }

                        // Opcional: Si quieres limpiar los campos de autocompletado
                        // inmediatamente al escribir antes de alcanzar minLength
                        if (cifNif.length < minLength) {
                            clearFormFields(prefix);
                        }


                        debounceTimer = setTimeout(() => {
                            // Convertir a mayúsculas antes de la llamada AJAX
                            if (cifNif.length >= minLength) {
                                $.ajax({
                                    url: `/clients/find-by-cif-nif/${cifNif.toUpperCase()}`, // Usar toUpperCase() aquí
                                    method: 'GET',
                                    success: function(response) {
                                        populateFormFields(prefix, response);
                                    },
                                    error: function(xhr) {
                                        if (xhr.status === 404) {
                                            console.log(
                                                `Client with CIF/NIF ${cifNif} not found. Cleaning fields.`
                                            );
                                        } else {
                                            console.error('Error searching client:', xhr);
                                        }
                                        // Solo limpiar campos si no se encontró el cliente o hubo otro error
                                        if (xhr.status === 404 || xhr.status >= 400) {
                                            clearFormFields(prefix);
                                        }
                                    }
                                });
                            }
                        }, 500); // Retardo de 500ms después de la última pulsación
                    });

                    // Asegurarse de limpiar campos si el campo queda vacío (e.g. cortar/pegar o borrar todo)
                    inputElement.on('change.autocomplete', function() {
                        if ($(this).val().trim() === '') {
                            clearFormFields(prefix);
                        }
                    });
                }

                function setupConceptAutocompleteByFileNumber(fileNumberInputId, conceptInputId) {
                    const fileNumberInput = $(`#${fileNumberInputId}`);
                    const conceptInput = $(`#${conceptInputId}`);

                    if (!fileNumberInput.length || !conceptInput.length) {
                        return;
                    }

                    // Limpiar listeners previos
                    fileNumberInput.off('input.concept_autocomplete');
                    fileNumberInput.off('change.concept_autocomplete');

                    let debounceTimer;
                    const minLength = 1; // Mínimo 1 caracter para buscar expediente? (Ajustar si es necesario)


                    conceptInput.prop('readonly', false); // Asegurarse de que sea editable inicialmente
                    conceptInput.removeClass(
                        'bg-gray-100 cursor-not-allowed'); // Asegurarse de quitar clases visuales de readonly


                    fileNumberInput.on('input.concept_autocomplete', function() {
                        clearTimeout(debounceTimer);

                        const fileNumber = $(this).val().trim().toUpperCase(); // Convertir a mayúsculas aquí

                        // Establecer el valor por defecto del concepto al empezar a escribir el expediente
                        if (fileNumber === '') {
                            conceptInput.val('');
                            conceptInput.prop('readonly', false);
                            conceptInput.removeClass('bg-gray-100 cursor-not-allowed');
                            return;
                        } else {
                            // Solo establecer el valor por defecto si el campo de concepto está vacío
                            // o si ya tenía un valor por defecto anterior de expediente
                            if (!conceptInput.val() || conceptInput.val().startsWith('EXPEDIENTE ')) {
                                conceptInput.val(`EXPEDIENTE ${fileNumber} - `);
                                conceptInput.prop('readonly', false);
                                conceptInput.removeClass('bg-gray-100 cursor-not-allowed');
                            }
                        }


                        debounceTimer = setTimeout(() => {
                            // Convertir a mayúsculas antes de la llamada AJAX (ya se hace en el input handler)
                            if (fileNumber.length >= minLength) {
                                $.ajax({
                                    url: `/proceedings/find-concept-by-file-number/${fileNumber}`, // Usar fileNumber en mayúsculas
                                    method: 'GET',
                                    success: function(response) {
                                        if (response && response.concept) {
                                            conceptInput.val(response.concept);
                                            conceptInput.prop('readonly', true);
                                            conceptInput.addClass('bg-gray-100 cursor-not-allowed');
                                        } else {
                                            console.log(
                                                `Concept for file number ${fileNumber} not found. Keeping current value and editable.`
                                            );
                                            // Si no se encuentra, mantener el valor actual del campo (que puede ser el por defecto "EXPEDIENTE X - ")
                                            conceptInput.prop('readonly', false);
                                            conceptInput.removeClass(
                                                'bg-gray-100 cursor-not-allowed');
                                        }
                                    },
                                    error: function(xhr) {
                                        if (xhr.status === 404) {
                                            console.log(
                                                `Proceeding with file number ${fileNumber} not found. Keeping current value and editable.`
                                            );
                                        } else {
                                            console.error('Error searching proceeding concept:',
                                                xhr);
                                        }
                                        // En caso de error, asegurarse de que el campo sea editable y no tenga estilo de readonly
                                        conceptInput.prop('readonly', false);
                                        conceptInput.removeClass('bg-gray-100 cursor-not-allowed');
                                    }
                                });
                            }
                        }, 500); // Retardo de 500ms
                    });

                    // Asegurarse de limpiar el concepto si el campo del expediente queda vacío
                    fileNumberInput.on('change.concept_autocomplete', function() {
                        if ($(this).val().trim() === '') {
                            conceptInput.val('');
                            conceptInput.prop('readonly', false);
                            conceptInput.removeClass('bg-gray-100 cursor-not-allowed');
                        }
                    });
                }


                function calcularCuotaTotalYTotalAPagar() {
                    const baseInput = document.getElementById('taxable_base');
                    const tipoInput = document.getElementById('tax_rate');
                    // Eliminar total_feeInput ya que total_fee no está en el HTML
                    // const totalFeeInput = document.getElementById('total_fee');
                    const bondInput = document.getElementById('bond');
                    const totalToPayInput = document.getElementById('total_to_pay');

                    if (!baseInput || !tipoInput || /*!totalFeeInput ||*/ !bondInput || !totalToPayInput) {
                        console.error("Missing calculation inputs");
                        return;
                    }

                    // Limpiar listeners previos (usando jQuery para off)
                    $(baseInput).off('input.calculation');
                    $(tipoInput).off('input.calculation');
                    $(bondInput).off('input.calculation');


                    function actualizarCuotaYTotal() {
                        // NOTA: En el frontend, parseFloat() puede manejar comas o puntos.
                        // Sin embargo, la validación en el backend espera puntos si no usas prepareForValidation.
                        // La conversión de coma a punto al parsear aquí es correcta para el cálculo JS.
                        const base = parseFloat(baseInput.value.replace(',', '.')) || 0;
                        const tipo = parseFloat(tipoInput.value.replace(',', '.')) || 0;
                        const bond = parseFloat(bondInput.value.replace(',', '.')) || 0;

                        // Calcula la cuota total (Base + IVA) - parece que el HTML no tiene un campo para mostrar solo la cuota total, solo el total a pagar
                        // const totalFee = base + (base * tipo / 100);
                        // if (totalFeeInput) totalFeeInput.value = totalFee.toFixed(2).replace('.', ','); // Formatear para visualización

                        // Calcula el total a pagar (Base + IVA + Fianza)
                        const totalToPay = base + (base * tipo / 100) + bond;
                        totalToPayInput.value = totalToPay.toFixed(2).replace('.',
                            ','); // Formatear para visualización con coma
                    }

                    // Escuchar cambios en base, tipo y fianza
                    // Usar namespace .calculation para evitar conflictos
                    $(baseInput).on('input.calculation', actualizarCuotaYTotal);
                    $(tipoInput).on('input.calculation', actualizarCuotaYTotal);
                    $(bondInput).on('input.calculation', actualizarCuotaYTotal);

                    // Ejecutar el cálculo inicial si hay valores antiguos
                    actualizarCuotaYTotal();
                }


                // --- Lógica Multi-step ---
                const step1 = document.getElementById('step-1');
                const step2 = document.getElementById('step-2');
                const step3 = document.getElementById('step-3');

                const next1 = document.getElementById('next-step-1');
                const prev2 = document.getElementById('prev-step-2');
                const next2 = document.getElementById('next-step-2');
                const prev3 = document.getElementById('prev-step-3');

                // Limpiar listeners previos para los botones de navegación (usando jQuery para off)
                $(next1).off('click');
                $(prev2).off('click');
                $(next2).off('click');
                $(prev3).off('click');


                if (next1) $(next1).on('click', () => {
                    step1.style.display = 'none';
                    step2.style.display = 'block';
                    // Opcional: enfocar el primer campo del siguiente paso
                    step2.querySelector('input, select, textarea')?.focus();
                });
                if (prev2) $(prev2).on('click', () => {
                    step2.style.display = 'none';
                    step1.style.display = 'block';
                    step1.querySelector('input, select, textarea')?.focus();
                });
                if (next2) $(next2).on('click', () => {
                    step2.style.display = 'none';
                    step3.style.display = 'block';
                    step3.querySelector('input, select, textarea')?.focus();
                    // Ejecutar cálculo inicial al mostrar el paso 3 por si los valores old() ya estaban presentes
                    calcularCuotaTotalYTotalAPagar();
                });
                if (prev3) $(prev3).on('click', () => {
                    step3.style.display = 'none';
                    step2.style.display = 'block';
                    step2.querySelector('input, select, textarea')?.focus();
                });

                // Función para determinar qué paso mostrar al cargar
                function showCorrectStep() {
                    // Comprobar si hay errores de validación. La variable $errors existe en la vista Blade
                    // y contendrá los errores si los hay. En JS, podemos buscar elementos con la clase 'text-red-500'
                    // que Laravel añade a los mensajes de error.
                    const firstErrorField = document.querySelector('.text-red-500.text-xs.mt-1');

                    if (firstErrorField) {
                        // Encontrar el ancestro más cercano que sea un paso (con id que empieza por 'step-')
                        const errorStep = firstErrorField.closest('[id^="step-"]');
                        if (errorStep) {
                            // Ocultar todos los pasos primero
                            if (step1) step1.style.display = 'none';
                            if (step2) step2.style.display = 'none';
                            if (step3) step3.style.display = 'none';
                            // Mostrar el paso que contiene el primer error
                            errorStep.style.display = 'block';
                            console.log("Showing step with first validation error:", errorStep.id);

                            // Si el paso 3 es el que tiene errores, ejecutar el cálculo inicial
                            if (errorStep.id === 'step-3') {
                                calcularCuotaTotalYTotalAPagar();
                            }

                            return; // Salir después de mostrar el paso con errores
                        }
                    }

                    // Si no hay errores, mostrar el primer paso por defecto
                    if (step1) step1.style.display = 'block';
                    if (step2) step2.style.display = 'none';
                    if (step3) step3.style.display = 'none';
                    console.log("No validation errors, showing step-1");
                }


                // --- Manejo del Modal de Instrucciones de Creación ---
                const createInvoiceInstructionsModal = $('#create-invoice-instructions-modal');
                const showCreateInvoiceInstructionsBtn = $('#show-create-invoice-instructions-btn');
                const closeCreateInvoiceModalBtn = $('#close-create-invoice-modal-btn');
                const createInvoiceInstructionsModalBody = $('#create-invoice-instructions-modal-body');

                // Texto de las instrucciones para este formulario
                const createInvoiceInstructionsText = `
                    <p>Este formulario te permite crear un nuevo pago siguiendo 3 pasos:</p>
                    <h4 class="font-semibold mt-3 mb-1 text-gray-800">Paso 1: Interesado</h4>
                    <p>Introduce los datos del interesado. Al escribir el CIF/NIF, el sistema intentará rellenar automáticamente otros campos si el cliente ya existe.</p>
                    <h4 class="font-semibold mt-3 mb-1 text-gray-800">Paso 2: Representante</h4>
                    <p>Introduce los datos del representante, si aplica. También hay autocompletado por CIF/NIF aquí.</p>
                    <h4 class="font-semibold mt-3 mb-1 text-gray-800">Paso 3: Liquidación</h4>
                    <p>Introduce los detalles de la liquidación.</p>
                    <ul class="list-disc list-inside ml-4 text-gray-700">
                        <li><i class="fas fa-file-alt fa-fw"></i> **Nº Expediente:** Al escribir el número de expediente, se autocompletará el campo 'Concepto' si el expediente existe.</li> {{-- Añadido fa-fw para iconos de ancho fijo --}}
                        <li><i class="fas fa-calendar-alt fa-fw"></i> **Fecha Liquidación:** Se rellena automáticamente con la fecha actual.</li> {{-- Añadido fa-fw --}}
                        <li><i class="fas fa-euro-sign fa-fw"></i> **Base Imponible / Tipo Impositivo / Fianza:** Introduce los valores. El campo 'Total a Pagar' (<i class="fas fa-calculator fa-fw"></i>) se calculará automáticamente a medida que escribes.</li> {{-- Añadido fa-fw --}}
                    </ul>
                    <h4 class="font-semibold mt-3 mb-1 text-gray-800">Navegación y Envío</h4>
                    <p>Usa los botones "Siguiente" y "Atrás" para moverte entre los pasos. Una vez completado el Paso 3, usa el botón "Crear Pago" para guardar la información.</p>
                    <p class="mt-3 text-gray-600">Si hay errores al enviar, el formulario te mostrará automáticamente el paso con el primer error.</p>
                    <p class="mt-3 text-gray-600"><strong>Consejo Rápido:</strong> Cierra esta ventana o cualquier otra emergente presionando la tecla <code>Escape</code>.</p>
                `;

                // Función para mostrar el modal de instrucciones
                function showCreateInvoiceInstructionsModal() {
                    createInvoiceInstructionsModalBody.html(
                        createInvoiceInstructionsText); // Inserta el texto HTML de las instrucciones
                    createInvoiceInstructionsModal.removeClass('hidden');
                }

                // Función para ocultar el modal de instrucciones
                function hideCreateInvoiceInstructionsModal() {
                    createInvoiceInstructionsModal.addClass('hidden');
                    createInvoiceInstructionsModalBody.html(''); // Limpia el contenido del cuerpo al cerrar
                }

                // Manejar clic en el botón de instrucciones
                showCreateInvoiceInstructionsBtn.on('click', showCreateInvoiceInstructionsModal);

                // Manejar clic en el botón "Cerrar" dentro del modal de instrucciones
                closeCreateInvoiceModalBtn.on('click', hideCreateInvoiceInstructionsModal);

                // Manejar clics fuera del modal de instrucciones
                createInvoiceInstructionsModal.on('click', function(e) {
                    if ($(e.target).is(createInvoiceInstructionsModal)) {
                        hideCreateInvoiceInstructionsModal();
                    }
                });
                // --- FIN Manejo del Modal de Instrucciones de Creación ---


                // --- Inicialización ---

                // Asegurarse de que los campos existentes con old() se conviertan a mayúsculas al cargar
                // Esto es necesario si no usas prepareForValidation en el Request/FormRequest
                document.querySelectorAll('input[type="text"]').forEach(campo => {
                    if (campo.value) { // Solo si el campo tiene valor (si hay old() data)
                        campo.value = campo.value.toUpperCase();
                    }
                });


                // 1. Para la carga inicial de la página (sin wire:navigate)
                $(document).ready(function() {
                    showCorrectStep(); // Decidir qué paso mostrar basado en errores
                    // Llamar a las funciones de setup de listeners después de determinar el paso
                    aplicarCalculoDniAutomatico('client_cif_nif');
                    aplicarCalculoDniAutomatico('rep_cif_nif');
                    convertirAMayusculas(); // Aplicar conversión a mayúsculas al escribir
                    setupCifNifAutocomplete('client_cif_nif', 'client');
                    setupCifNifAutocomplete('rep_cif_nif', 'rep');
                    setupConceptAutocompleteByFileNumber('file_number', 'concept');
                    // El cálculo del total se ejecutará automáticamente si step-3 se muestra inicialmente
                    // o cuando se navegue a step-3.

                    console.log("Create Invoice form JS initialized via document.ready");
                });


                // 2. Para las navegaciones de Livewire (si usas wire:navigate en tu app)
                // Este hook asegura que el JS se reinicialice después de las actualizaciones del DOM por Livewire.
                // Si no usas wire:navigate o no hay Livewire en esta página, este hook puede ser ignorado o eliminado.
                if (typeof Livewire !== 'undefined') {
                    Livewire.hook('morph.finished', ({
                        component,
                        finish,
                        children
                    }) => {
                        // Re-aplicar los listeners y configuraciones después de que Livewire actualice el DOM.
                        // No necesitamos showCorrectStep() aquí si Livewire maneja el estado visual de los pasos.
                        // Sin embargo, los listeners de input y autocomplete necesitan ser re-enganchados.
                        aplicarCalculoDniAutomatico('client_cif_nif');
                        aplicarCalculoDniAutomatico('rep_cif_nif');
                        convertirAMayusculas(); // Re-aplicar al escribir
                        setupCifNifAutocomplete('client_cif_nif', 'client');
                        setupCifNifAutocomplete('rep_cif_nif', 'rep');
                        setupConceptAutocompleteByFileNumber('file_number', 'concept');
                        calcularCuotaTotalYTotalAPagar(); // Re-aplicar cálculo

                        console.log("Create Invoice form JS initialized via Livewire morph.finished");
                    });
                    // Limpiar el manejador de tecla Escape cuando el componente es removido
                    Livewire.hook('beforeNavigateOut', ({
                        detail: {
                            visit
                        }
                    }) => {
                        $(document).off('keydown.createinvoice');
                    });
                }


                // Manejar la tecla Escape para cerrar modales en esta página
                // Usamos un namespace '.createinvoice' para que este manejador solo se aplique a esta página.
                $(document).on('keydown.createinvoice', function(e) {
                    if (e.key === 'Escape') {
                        // Solo comprobamos el modal de instrucciones de creación en esta página
                        if (!createInvoiceInstructionsModal.hasClass('hidden')) {
                            hideCreateInvoiceInstructionsModal();
                        }
                        // Si hubiera otros modales específicos de esta página, se añadirían aquí.
                    }
                });


            } // Fin de initializeCreateInvoiceForm

            // Llamar a la función principal de inicialización al final del script
            initializeCreateInvoiceForm();
        </script>
    @endpush

</x-app-layout>
