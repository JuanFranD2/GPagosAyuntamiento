<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight py-2">
            {{ __('Crear Pago') }}
        </h2>
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

                    {{-- Sección: Representante (Paso 2) - Aplica la misma lógica aquí --}}
                    <div id="step-2" style="display: none;">
                        <h3 class="text-lg font-semibold mb-4">Representante</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-x-4 gap-y-2 mb-6">
                            {{-- Ejemplo para el CIF/NIF del representante --}}
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
                            {{-- Repite para rep_name, rep_phone, etc. --}}
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
                                        placeholder="0.00" value="{{ old('liquidation.tax_rate') }}" />
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
                                <label for="total_fee" class="block text-sm font-medium text-gray-700">Cuota
                                    Total</label>
                                <div class="relative">
                                    <input type="text" name="liquidation[total_fee]" id="total_fee"
                                        class="mt-1 block w-full pr-10 rounded-md border-gray-300 shadow-sm text-sm @error('liquidation.total_fee') border-red-500 @enderror"
                                        value="{{ old('liquidation.total_fee', '0,00') }}" readonly />
                                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm">€</span>
                                    </div>
                                </div>
                                {{-- Añadir CSS para asegurar espacio fijo --}}
                                @error('liquidation.total_fee')
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

    {{-- Incluir tus scripts JS aquí --}}
    @push('scripts')
        {{-- Asegúrate de incluir jQuery si aún no está en tu layout principal --}}
        <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

        <script>
            // Incluye aquí la función initializeInvoiceFormJs que unimos antes
            // Asegúrate de que los scripts de manejo de pasos (step-1, step-2, step-3)
            // también manejen la visibilidad de los pasos correctamente al cargar la página
            // con errores de validación (Laravel redirige al URL anterior, que es /invoices/create,
            // pero el JS podría reiniciar al step-1 si no se le indica lo contrario).
            // Una forma de manejar esto es comprobar si hay errores en el DOM al cargar
            // y navegar al paso donde está el primer error.

            function initializeInvoiceFormJs() {
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

                // NOTA: Sin prepareForValidation, esta función convierte a mayúsculas
                // SOLO si el usuario escribe en el campo. Los valores antiguos (old)
                // NO serán convertidos a mayúsculas automáticamente al recargar la página
                // a menos que apliques la conversión en el bucle inicial de abajo.
                function convertirAMayusculas() {
                    const camposTexto = document.querySelectorAll('input[type="text"]');
                    camposTexto.forEach(campo => {
                        const oldHandler = campo._toUpperCaseHandler;
                        if (oldHandler) {
                            campo.removeEventListener('input', oldHandler);
                        }

                        const newHandler = function() {
                            this.value = this.value.toUpperCase();
                        };
                        campo.addEventListener('input', newHandler);
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

                    inputElement.off('input.autocomplete');
                    inputElement.off('change.autocomplete');

                    let debounceTimer;
                    const minLength = 8;

                    inputElement.on('input.autocomplete', function() {
                        clearTimeout(debounceTimer);
                        const cifNif = $(this).val().trim();

                        if (cifNif === '') {
                            clearFormFields(prefix);
                            return;
                        }

                        debounceTimer = setTimeout(() => {
                            if (cifNif.length >= minLength) {
                                $.ajax({
                                    url: `/clients/find-by-cif-nif/${cifNif}`,
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
                                        clearFormFields(prefix);
                                    }
                                });
                            } else {
                                clearFormFields(prefix);
                            }
                        }, 500);
                    });

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

                    fileNumberInput.off('input.concept_autocomplete');
                    fileNumberInput.off('change.concept_autocomplete');

                    let debounceTimer;

                    conceptInput.prop('readonly', false);
                    conceptInput.removeClass('bg-gray-100 cursor-not-allowed');

                    // NOTA: Sin prepareForValidation, la conversión a mayúsculas del número de expediente
                    // debe ocurrir aquí antes de usarlo en la URL de la API si es necesario.
                    fileNumberInput.on('input.concept_autocomplete', function() {
                        clearTimeout(debounceTimer);

                        const fileNumber = $(this).val().trim().toUpperCase(); // Convertir a mayúsculas aquí

                        if (fileNumber === '') {
                            conceptInput.val('');
                            conceptInput.prop('readonly', false);
                            conceptInput.removeClass('bg-gray-100 cursor-not-allowed');
                            return;
                        }

                        conceptInput.val(`EXPEDIENTE ${fileNumber} - `);
                        conceptInput.prop('readonly', false);
                        conceptInput.removeClass('bg-gray-100 cursor-not-allowed');


                        debounceTimer = setTimeout(() => {
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
                                            `Concept for file number ${fileNumber} not found. Keeping default and editable.`
                                        );
                                        conceptInput.prop('readonly', false);
                                        conceptInput.removeClass('bg-gray-100 cursor-not-allowed');
                                    }
                                },
                                error: function(xhr) {
                                    if (xhr.status === 404) {
                                        console.log(
                                            `Proceeding with file number ${fileNumber} not found. Keeping default concept and editable.`
                                        );
                                    } else {
                                        console.error('Error searching proceeding concept:', xhr);
                                    }
                                    conceptInput.prop('readonly', false);
                                    conceptInput.removeClass('bg-gray-100 cursor-not-allowed');
                                }
                            });
                        }, 500);
                    });

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
                    const totalFeeInput = document.getElementById('total_fee');
                    const bondInput = document.getElementById('bond');
                    const totalToPayInput = document.getElementById('total_to_pay');

                    if (!baseInput || !tipoInput || !totalFeeInput || !bondInput || !totalToPayInput) {
                        console.error("Missing calculation inputs");
                        return;
                    }

                    // Limpiar listeners previos
                    if (baseInput._calculationHandler) baseInput.removeEventListener('input', baseInput._calculationHandler);
                    if (tipoInput._calculationHandler) tipoInput.removeEventListener('input', tipoInput._calculationHandler);
                    if (bondInput._calculationHandler) bondInput.removeEventListener('input', bondInput._calculationHandler);


                    function actualizarCuotaYTotal() {
                        // NOTA: En el frontend, parseFloat() puede manejar comas o puntos.
                        // Sin embargo, la validación en el backend espera puntos si no usas prepareForValidation.
                        // Podrías añadir un listener 'change' aquí para convertir la coma a punto antes de que se envíe el formulario,
                        // o confiar en la conversión que añadimos en el controlador.
                        const base = parseFloat(baseInput.value.replace(',', '.')) || 0;
                        const tipo = parseFloat(tipoInput.value.replace(',', '.')) || 0;
                        const bond = parseFloat(bondInput.value.replace(',', '.')) || 0;

                        const totalFee = base + (base * tipo / 100);
                        totalFeeInput.value = totalFee.toFixed(2).replace('.', ','); // Formatear para visualización

                        const totalToPay = totalFee + bond;
                        totalToPayInput.value = totalToPay.toFixed(2).replace('.', ','); // Formatear para visualización
                    }

                    // Escuchar cambios en base, tipo y fianza
                    baseInput.addEventListener('input', actualizarCuotaYTotal);
                    tipoInput.addEventListener('input', actualizarCuotaYTotal);
                    bondInput.addEventListener('input', actualizarCuotaYTotal);

                    // Almacenar los handlers
                    baseInput._calculationHandler = actualizarCuotaYTotal;
                    tipoInput._calculationHandler = actualizarCuotaYTotal;
                    bondInput._calculationHandler = actualizarCuotaYTotal;

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

                // Limpiar listeners previos para los botones de navegación
                if (next1) next1.onclick = null;
                if (prev2) prev2.onclick = null;
                if (next2) next2.onclick = null;
                if (prev3) prev3.onclick = null;


                if (next1) next1.onclick = () => {
                    step1.style.display = 'none';
                    step2.style.display = 'block';
                    // Opcional: enfocar el primer campo del siguiente paso
                    step2.querySelector('input, select, textarea')?.focus();
                };
                if (prev2) prev2.onclick = () => {
                    step2.style.display = 'none';
                    step1.style.display = 'block';
                    step1.querySelector('input, select, textarea')?.focus();
                };
                if (next2) next2.onclick = () => {
                    step2.style.display = 'none';
                    step3.style.display = 'block';
                    step3.querySelector('input, select, textarea')?.focus();
                };
                if (prev3) prev3.onclick = () => {
                    step3.style.display = 'none';
                    step2.style.display = 'block';
                    step2.querySelector('input, select, textarea')?.focus();
                };

                // Función para determinar qué paso mostrar al cargar
                function showCorrectStep() {
                    // Comprobar si hay errores de validación. La variable $errors existe en la vista Blade
                    // y contendrá los errores si los hay. En JS, podemos buscar elementos con la clase 'text-red-500'
                    // o que tengan el párrafo de error asociado.
                    const hasErrors = document.querySelectorAll('.text-red-500.text-xs.mt-1').length > 0;

                    if (hasErrors) {
                        // Encontrar el primer campo con error y mostrar su paso
                        const firstErrorField = document.querySelector('.text-red-500.text-xs.mt-1');
                        if (firstErrorField) {
                            const errorStep = firstErrorField.closest('[id^="step-"]');
                            if (errorStep) {
                                // Ocultar todos los pasos primero
                                if (step1) step1.style.display = 'none';
                                if (step2) step2.style.display = 'none';
                                if (step3) step3.style.display = 'none';
                                // Mostrar el paso que contiene el primer error
                                errorStep.style.display = 'block';
                                console.log("Showing step with first validation error:", errorStep.id);
                                return; // Salir después de mostrar el paso con errores
                            }
                        }
                    }

                    // Si no hay errores, mostrar el primer paso por defecto
                    if (step1) step1.style.display = 'block';
                    if (step2) step2.style.display = 'none';
                    if (step3) step3.style.display = 'none';
                    console.log("No validation errors, showing step-1");
                }


                // --- Inicialización ---

                // Asegurarse de que los campos existentes con old() se conviertan a mayúsculas al cargar
                // Esto es necesario si eliminaste la conversión de mayúsculas en prepareForValidation
                document.querySelectorAll('input[type="text"]').forEach(campo => {
                    if (campo.value) { // Solo si el campo tiene valor (si hay old() data)
                        campo.value = campo.value.toUpperCase();
                    }
                });


                // 1. Para la carga inicial de la página (sin wire:navigate)
                $(document).ready(function() {
                    showCorrectStep(); // Decidir qué paso mostrar basado en errores
                    initializeInvoiceFormJsLogic(); // Llamar a una función separada para la lógica de setup
                    console.log("Invoice form JS initialized via document.ready");
                });

                // Extraer la lógica principal de JS a una función aparte para evitar duplicación con Livewire hook
                function initializeInvoiceFormJsLogic() {
                    aplicarCalculoDniAutomatico('client_cif_nif');
                    aplicarCalculoDniAutomatico('rep_cif_nif');
                    convertirAMayusculas();
                    setupCifNifAutocomplete('client_cif_nif', 'client');
                    setupCifNifAutocomplete('rep_cif_nif', 'rep');
                    setupConceptAutocompleteByFileNumber('file_number', 'concept');
                    calcularCuotaTotalYTotalAPagar();
                }


                // 2. Para las navegaciones de Livewire (con wire:navigate)
                Livewire.hook('morph.finished', ({
                    component,
                    finish,
                    children
                }) => {
                    // No necesitamos showCorrectStep() aquí si Livewire mantiene el estado de los pasos.
                    // Si Livewire NO mantiene el estado visual de los pasos, tendrías que re-implementar
                    // la lógica de mostrar el paso correcto aquí también.
                    // Asumiendo que Livewire podría necesitar re-aplicar listeners a los elementos actualizados:
                    initializeInvoiceFormJsLogic();
                    console.log("Invoice form JS initialized via Livewire morph.finished");

                });

            } // Fin de initializeInvoiceFormJs

            // Llamar a la función principal de inicialización
            initializeInvoiceFormJs();
        </script>
    @endpush

</x-app-layout>
