<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight py-2">
                {{ __('Detalles del Pago') }} #{{ $invoice->invoice_number ?? $invoice->id }}
            </h2>
            <div class="flex space-x-2">
                {{-- Botón para volver al listado --}}
                <a href="{{ route('invoices.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded text-sm">
                    {{ __('Volver al Listado') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">

                {{-- Contenedor específico para las secciones de pasos --}}
                {{-- Este contenedor tendrá una altura mínima para evitar saltos de diseño --}}
                <div id="steps-content-container">

                    {{-- Sección 1: Datos Generales de la Factura y Datos del Usuario Creador --}}
                    {{-- Esta sección siempre está visible inicialmente (controlado por JS) --}}
                    <div id="step-1" class="step-section mb-8 pb-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Datos Generales del Pago') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <p class="text-sm text-gray-500">{{ __('Número de Pago') }}:</p>
                                <p class="text-sm text-gray-900">{{ $invoice->invoice_number ?? '-' }}</p>
                            </div>
                            <div>
                                {{-- Fecha de Emisión usando created_at de la factura --}}
                                <p class="text-sm text-gray-500">{{ __('Fecha de Emisión') }}:</p>
                                <p class="text-sm text-gray-900">
                                    {{ $invoice->created_at ? $invoice->created_at->format('d/m/Y') : '-' }}
                                </p>
                            </div>
                            <div>
                                {{-- Total a Pagar sincronizado con Total a Pagar de Liquidación --}}
                                <p class="text-sm text-gray-500">{{ __('Total a Pagar') }}:</p>
                                <p class="text-sm text-gray-900">
                                    {{ number_format($invoice->liquidation->total_to_pay ?? 0, 2, ',', '.') }} €</p>
                            </div>
                        </div>

                        {{-- Datos del Usuario Creador --}}
                        {{-- ENVUELVE ESTA SECCIÓN CON LA CONDICIÓN DEL ROL --}}
                        @if (auth()->check() && auth()->user()->role === 'admin')
                            @if ($invoice->user)
                                <div>
                                    <h4 class="text-base font-semibold mb-2">{{ __('Datos del Usuario Creador') }}</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500">{{ __('Nombre') }}:</p>
                                            <p class="text-sm text-gray-900">{{ $invoice->user->name ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">{{ __('Correo Electrónico') }}:</p>
                                            <p class="text-sm text-gray-900">{{ $invoice->user->email ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                        {{-- FIN DE LA CONDICIÓN DEL ROL --}}
                    </div>

                    {{-- Sección 2: Datos del Interesado (Cliente) --}}
                    {{-- Esta sección solo se renderiza si $invoice->client existe --}}
                    @if ($invoice->client)
                        <div id="step-2" class="step-section mb-8 pb-4 border-b border-gray-200"
                            style="display: none;">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Datos del Interesado') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('CIF/NIF') }}:</p>
                                    <p class="text-sm text-gray-900">{{ $invoice->client->cif_nif ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('Nombre/Razón Social') }}:</p>
                                    <p class="text-sm text-gray-900">{{ $invoice->client->name ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('Teléfono') }}:</p>
                                    <p class="text-sm text-gray-900">{{ $invoice->client->phone ?? '-' }}</p>
                                </div>
                                <div class="md:col-span-2 lg:col-span-3">
                                    <p class="text-sm text-gray-500">{{ __('Dirección') }}:</p>
                                    <p class="text-sm text-gray-900">
                                        {{ $invoice->client->address ?? '' }}
                                        @if ($invoice->client->number)
                                            nº {{ $invoice->client->number }}
                                        @endif
                                        @if ($invoice->client->letter)
                                            {{ $invoice->client->letter }}
                                        @endif
                                        @if ($invoice->client->staircase)
                                            Esc. {{ $invoice->client->staircase }}
                                        @endif
                                        @if ($invoice->client->postal_code)
                                            - {{ $invoice->client->postal_code }}
                                        @endif
                                        @if ($invoice->client->town_municipality)
                                            {{ $invoice->client->town_municipality }}
                                        @endif
                                        @if ($invoice->client->province)
                                            ({{ $invoice->client->province }})
                                        @endif
                                        {{-- Añadir un guion si todos los campos de dirección están vacíos --}}
                                        @if (
                                            !($invoice->client->address ?? '') &&
                                                !($invoice->client->number ?? '') &&
                                                !($invoice->client->letter ?? '') &&
                                                !($invoice->client->staircase ?? '') &&
                                                !($invoice->client->postal_code ?? '') &&
                                                !($invoice->client->town_municipality ?? '') &&
                                                !($invoice->client->province ?? ''))
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div class="md:col-span-2 lg:col-span-3">
                                    <p class="text-sm text-gray-500">{{ __('Correo Electrónico') }}:</p>
                                    <p class="text-sm text-gray-900">{{ $invoice->client->email ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Sección 3: Datos del Representante (Opcional) --}}
                    {{-- Esta sección solo se renderiza si $invoice->representative existe --}}
                    @if ($invoice->representative)
                        <div id="step-3" class="step-section mb-8 pb-4 border-b border-gray-200"
                            style="display: none;">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Datos del Representante') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('CIF/NIF') }}:</p>
                                    <p class="text-sm text-gray-900">{{ $invoice->representative->cif_nif ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('Nombre/Razón Social') }}:</p>
                                    <p class="text-sm text-gray-900">{{ $invoice->representative->name ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-sm text-gray-500">{{ __('Teléfono') }}:</p>
                                    <p class="text-sm text-gray-900">{{ $invoice->representative->phone ?? '-' }}</p>
                                </div>
                                <div class="md:col-span-2 lg:col-span-3">
                                    <p class="text-sm text-gray-500">{{ __('Dirección') }}:</p>
                                    <p class="text-sm text-gray-900">
                                        {{ $invoice->representative->address ?? '' }}
                                        @if ($invoice->representative->number)
                                            nº {{ $invoice->representative->number }}
                                        @endif
                                        @if ($invoice->representative->letter)
                                            {{ $invoice->representative->letter }}
                                        @endif
                                        @if ($invoice->representative->staircase)
                                            Esc. {{ $invoice->representative->staircase }}
                                        @endif
                                        @if ($invoice->representative->postal_code)
                                            - {{ $invoice->representative->postal_code }}
                                        @endif
                                        @if ($invoice->representative->town_municipality)
                                            {{ $invoice->representative->town_municipality }}
                                        @endif
                                        @if ($invoice->representative->province)
                                            ({{ $invoice->representative->province }})
                                        @endif
                                        {{-- Añadir un guion si todos los campos de dirección están vacíos --}}
                                        @if (
                                            !($invoice->representative->address ?? '') &&
                                                !($invoice->representative->number ?? '') &&
                                                !($invoice->representative->letter ?? '') &&
                                                !($invoice->representative->staircase ?? '') &&
                                                !($invoice->representative->postal_code ?? '') &&
                                                !($invoice->representative->town_municipality ?? '') &&
                                                !($invoice->representative->province ?? ''))
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div class="md:col-span-2 lg:col-span-3">
                                    <p class="text-sm text-gray-500">{{ __('Correo Electrónico') }}:</p>
                                    <p class="text-sm text-gray-900">{{ $invoice->representative->email ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Sección 4: Datos de la Liquidación --}}
                    {{-- Esta sección solo se renderiza si $invoice->liquidation existe --}}
                    @if ($invoice->liquidation)
                        <div id="step-4" class="step-section mb-8" style="display: none;">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Datos de la Liquidación') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('Número de Expediente') }}:</p>
                                    <p class="text-sm text-gray-900">{{ $invoice->liquidation->file_number ?? '-' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('Concepto') }}:</p>
                                    <p class="text-sm text-gray-900">{{ $invoice->liquidation->concept ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('Base Imponible') }}:</p>
                                    <p class="text-sm text-gray-900">
                                        {{ number_format($invoice->liquidation->taxable_base ?? 0, 2, ',', '.') }} €
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('Tipo Impositivo') }}:</p>
                                    <p class="text-sm text-gray-900">
                                        {{ number_format($invoice->liquidation->tax_rate ?? 0, 2, ',', '.') }} %</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('Cuota Total') }}:</p>
                                    <p class="text-sm text-gray-900">
                                        {{ number_format($invoice->liquidation->total_fee ?? 0, 2, ',', '.') }} €</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('Fianza') }}:</p>
                                    <p class="text-sm text-gray-900">
                                        {{ number_format($invoice->liquidation->bond ?? 0, 2, ',', '.') }} €</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">{{ __('Total a Pagar (Liquidación)') }}:</p>
                                    <p class="text-sm text-gray-900">
                                        {{ number_format($invoice->liquidation->total_to_pay ?? 0, 2, ',', '.') }} €
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                </div> {{-- Fin #steps-content-container --}}


                {{-- Controles de Navegación --}}
                <div class="flex justify-between mt-6" id="navigation-buttons-container">
                    <button type="button" id="prev-step"
                        class="px-4 py-2 bg-gray-300 text-gray-700 font-semibold rounded-md hover:bg-gray-400 transition"
                        style="display: none;">
                        Atrás
                    </button>
                    <button type="button" id="next-step"
                        class="px-4 py-2 bg-gray-300 text-gray-700 font-semibold rounded-md hover:bg-gray-400 transition"
                        style="display: none;">
                        Siguiente
                    </button>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const stepSections = document.querySelectorAll('.step-section');
                const prevButton = document.getElementById('prev-step');
                const nextButton = document.getElementById('next-step');
                const navigationButtonsContainer = document.getElementById('navigation-buttons-container');
                const stepsContentContainer = document.getElementById(
                    'steps-content-container'); // Get the new container

                let currentStepIndex = 0;
                const visibleSteps = [];
                let maxSectionHeight = 0;

                // Identify the steps that are actually present in the DOM
                stepSections.forEach((step) => {
                    if (step) {
                        visibleSteps.push(step);
                    }
                });

                // If there are steps to navigate, calculate max height and setup navigation
                if (visibleSteps.length > 1) {
                    // Calculate the maximum height of the visible sections
                    // Temporarily show all sections to measure their height accurately
                    // Ensure temporary display doesn't cause flickering by doing it before initial hide
                    visibleSteps.forEach(step => {
                        // Temporarily show to measure, but keep their initial hidden state if applicable
                        const originalDisplay = step.style.display;
                        step.style.display = 'block';
                        if (step.offsetHeight > maxSectionHeight) {
                            maxSectionHeight = step.offsetHeight;
                        }
                        step.style.display = originalDisplay; // Restore original display (mostly 'none')
                    });

                    // Apply the max height as min-height to the dedicated steps content container
                    if (stepsContentContainer && maxSectionHeight > 0) {
                        // Add a small buffer for padding/margins consistency
                        stepsContentContainer.style.minHeight = `${maxSectionHeight + 20}px`; // Adjust buffer as needed
                    }

                    // Function to show a specific step
                    function showStep(index) {
                        visibleSteps.forEach((step) => {
                            step.style.display = 'none';
                        });

                        if (visibleSteps[index]) {
                            visibleSteps[index].style.display = 'block';
                        }

                        updateButtons();
                    }

                    // Function to update button visibility and alignment
                    function updateButtons() {
                        let showPrev = currentStepIndex > 0;
                        let showNext = currentStepIndex < visibleSteps.length - 1;

                        if (prevButton) prevButton.style.display = showPrev ? 'inline-flex' : 'none';
                        if (nextButton) nextButton.style.display = showNext ? 'inline-flex' : 'none';

                        if (navigationButtonsContainer) {
                            navigationButtonsContainer.classList.remove('justify-start', 'justify-end',
                                'justify-between');

                            if (showPrev && showNext) {
                                navigationButtonsContainer.classList.add('justify-between');
                            } else if (showNext) {
                                navigationButtonsContainer.classList.add('justify-end');
                            } else if (showPrev) {
                                navigationButtonsContainer.classList.add('justify-start');
                            } else {
                                // If no buttons visible (shouldn't happen if visibleSteps.length > 1, but for safety)
                                navigationButtonsContainer.style.display = 'none';
                            }
                        }
                    }

                    // Event listeners for buttons
                    if (nextButton) {
                        nextButton.addEventListener('click', function() {
                            if (currentStepIndex < visibleSteps.length - 1) {
                                currentStepIndex++;
                                showStep(currentStepIndex);
                            }
                        });
                    }

                    if (prevButton) {
                        prevButton.addEventListener('click', function() {
                            if (currentStepIndex > 0) {
                                currentStepIndex--;
                                showStep(currentStepIndex);
                            }
                        });
                    }

                    // Initial display: Show the first step and set up buttons
                    showStep(currentStepIndex);

                } else {
                    // Less than 2 visible steps, hide navigation buttons and remove min-height
                    console.warn("Less than 2 visible steps found. Hiding navigation.");
                    if (prevButton) prevButton.style.display = 'none';
                    if (nextButton) nextButton.style.display = 'none';
                    if (navigationButtonsContainer) {
                        navigationButtonsContainer.style.display = 'none'; // Hide the entire buttons container
                    }

                    // Ensure the single step is visible if it exists
                    if (visibleSteps[0]) {
                        visibleSteps[0].style.display = 'block';
                    }

                    // Remove the min-height if navigation is not needed
                    if (stepsContentContainer) {
                        stepsContentContainer.style.minHeight = '';
                    }
                }
            });
        </script>
    @endpush

</x-app-layout>
