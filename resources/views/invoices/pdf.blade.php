<!DOCTYPE html>
<html>

<head>
    <title>Carta de Pago #{{ $invoice->invoice_number ?? $invoice->id }} - Hinojos</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        /* Importa una fuente para soporte de caracteres especiales y tildes */
        /* Asegúrate de tener estos archivos .ttf en public/fonts y que Dompdf pueda acceder a ellos */
        @font-face {
            font-family: 'DejaVu Sans';
            src: url('{{ base_path('public/fonts/DejaVuSans.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'DejaVu Sans';
            src: url('{{ base_path('public/fonts/DejaVuSans-Bold.ttf') }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        /* Define una paleta de colores simple */
        :root {
            --primary-text: #333;
            --secondary-text: #555;
            --label-text: #777;
            --border-color: #419639;
            /* Mantener verde para las líneas generales */
            --accent-color: #4a90e2;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 8.5px;
            line-height: 1.2;
            margin: 10mm;
            color: var(--primary-text);
        }

        /* General Layout Helpers (using display: table for Dompdf reliability) */
        .data-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            /* Adjusted for consistent spacing */
        }

        .data-row {
            display: table-row;
        }

        .data-col {
            display: table-cell;
            padding: 2px 5px;
            /* Adjusted padding */
            vertical-align: top;
            width: 33.3%;
        }

        .data-col:last-child {
            padding-right: 0;
        }

        .data-col.w-20 {
            width: 20%;
        }

        .data-col.w-25 {
            width: 25%;
        }

        .data-col.w-30 {
            width: 30%;
        }

        .data-col.w-35 {
            width: 35%;
        }

        .data-col.w-40 {
            width: 40%;
        }

        .data-col.w-50 {
            width: 50%;
        }

        .data-col.w-60 {
            width: 60%;
        }

        .data-col.w-65 {
            width: 65%;
        }

        .data-col.w-70 {
            width: 70%;
        }

        .data-col.w-75 {
            width: 75%;
        }

        .data-col.w-80 {
            width: 80%;
        }

        .data-col.w-100 {
            width: 100%;
        }


        .label {
            color: var(--label-text);
            font-size: 8px;
            /* MODIFICADO: Tamaño de fuente un poco mayor */
            margin-bottom: 0px;
            display: block;
            font-weight: normal;
            text-transform: uppercase;
            /* AÑADIDO: Texto en mayúscula */
        }

        .value {
            font-size: 11.5px;
            /* Increased font size for general values */
            color: var(--secondary-text);
            margin-top: 0px;
            display: block;
        }


        /* Header Styles */
        header {
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid #000;
            /* CAMBIO: Línea negra para el encabezado */
            text-align: center;
        }

        /* Signature Area */
        /* Este estilo es ahora redundante, se usa el de abajo para el borde */
        /*
        .signature-area {
            margin-top: 8px;
            padding-top: 6px;
            border-top: 1px solid #000;
            width: 45%;
            float: right;
            text-align: center;
            margin-right: 0;
        }
        */


        .header-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .header-row {
            display: table-row;
        }

        .header-col {
            display: table-cell;
            vertical-align: middle;
            padding: 0 3px;
        }

        .header-col.left {
            width: 15%;
            text-align: left;
        }

        .header-col.center {
            width: 70%;
            text-align: center;
        }

        .header-col.right {
            width: 15%;
            text-align: right;
        }

        .header-col img {
            max-width: 100%;
            height: auto;
            width: 150px;
            height: 50px;
        }

        .header-col h1 {
            font-size: 14px;
            margin: 0 0 2px 0;
            color: var(--primary-text);
            font-weight: bold;
        }

        .header-col h2 {
            font-size: 11px;
            margin: 0;
            color: var(--secondary-text);
            font-weight: normal;
        }


        /* Invoice General Info Section (Integrated and compact) */
        .invoice-general-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            /* padding-bottom: 6px; REMOVED */
            padding-top: 5px;
            /* Added padding above content */
            padding-bottom: 5px;
            /* Added padding below content */
            border-bottom: 1px solid var(--border-color);
        }

        .invoice-general-grid .data-row {
            display: table-row;
        }

        .invoice-general-grid .data-col {
            display: table-cell;
            padding: 2px 8px;
            /* Adjusted padding */
            vertical-align: top;
        }

        .invoice-general-grid .data-col:last-child {
            padding-right: 0;
        }

        .invoice-general-grid .label {
            font-size: 7px;
            /* Keep smaller size for general grid labels if needed, or change here too */
            color: var(--label-text);
            margin-bottom: 0;
            display: inline;
            text-transform: uppercase;
            /* AÑADIDO: Texto en mayúscula para general grid labels */
        }

        .invoice-general-grid .value {
            font-size: 11px;
            /* Increased font size for general invoice values */
            color: var(--secondary-text);
            margin-top: 0;
            display: inline;
            margin-left: 2px;
        }

        .invoice-total-value {
            /* Moved class for specific styling */
            font-weight: bold;
            /* Negrita */
            color: #ff0000 !important;
            /* Rojo */
            font-size: 12px !important;
            /* Increased font size for total value */
            /* Un poco más grande */
        }


        /* Section Styles */
        .section {
            margin-bottom: 8px;
            padding-bottom: 5px;
            /* Keep this padding below content */
            border-bottom: 1px solid var(--border-color);
            overflow: hidden;
        }

        .section:last-of-type {
            /* Note: This will be overridden for legal-info-section below */
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .section h3 {
            color: var(--secondary-text);
            font-size: 13px;
            /* Increased font size for section titles */
            margin: 0 0 2px 0;
            /* Adjusted margin to create consistent space below h3 border */
            padding-bottom: 2px;
            border-bottom: 1px solid var(--border-color);
            /* CAMBIO: Usar variable */
            font-weight: bold;
        }

        .section .data-col .label {
            font-size: 8px;
            /* MODIFICADO: Tamaño de fuente un poco mayor para section labels */
            text-transform: uppercase;
            /* AÑADIDO: Texto en mayúscula para section labels */
        }

        .section .data-col .value {
            font-size: 11.5px;
            /* Increased font size for section values */
            color: var(--secondary-text);
        }

        /* Add padding above the data grid in sections */
        .section .data-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            /* Removed margin-bottom from grid */
            padding-top: 5px;
            /* Added space above the data grid */
        }


        /* Style for the legal text section */
        .legal-info-section {
            margin-bottom: 8px;
            padding-bottom: 5px;
            /* Keep this padding below content */
            /* MODIFICADO: Borde negro */
            border-bottom: 1px solid #000;
            overflow: hidden;
            font-size: 6.5px;
            /* Reduced font size general of the section */
            line-height: 1.2;
            color: var(--secondary-text);
        }

        .legal-info-section h3 {
            color: var(--secondary-text);
            font-size: 13px;
            /* Keep the increased size for this title */
            margin: 0 0 2px 0;
            /* Adjusted margin to create consistent space below h3 border */
            padding-bottom: 2px;
            border-bottom: 1px solid var(--border-color);
            /* CAMBIO: Usar variable */
            font-weight: bold;
        }

        .legal-info-section p {
            margin: 0 0 4px 0;
            /* Keep some margin between paragraphs */
            padding: 0;
            font-size: 6.5px;
            /* Reduced font size paragraph */
        }

        .legal-info-section p:first-of-type,
        .legal-info-section ul:first-of-type {
            margin-top: 0;
            /* Ensure no default margin */
            padding-top: 5px;
            /* Added space above the first content element */
        }

        .legal-info-section p:last-of-type,
        .legal-info-section ul:last-of-type {
            margin-bottom: 0;
            /* Removed bottom margin from the last content element */
        }


        .legal-info-section strong {
            font-weight: bold;
        }

        .legal-info-section ul {
            margin: 3px 0 4px 12px;
            /* Keep list margin */
            padding: 0;
            list-style: disc;
            font-size: 6.5px;
            /* Reduced font size ul */
        }

        .legal-info-section li {
            margin-bottom: 1px;
            /* Keep space between list items */
            padding: 0;
            font-size: 6.5px;
            /* Reduced font size li */
        }


        /* Specific Address Block Styling */
        .address-block .data-col {
            width: 100%;
            padding-right: 0;
        }


        /* Liquidation Total Highlight (within the liquidation section details) */
        .liquidation-details-total-value {
            /* This class might not be needed anymore if using invoice-total-value */
            font-weight: normal;
            color: var(--secondary-text);
            font-size: 11.5px;
            /* Increased font size for liquidation total value */
        }


        /* Place of Payment Section */
        .payment-methods .data-col .value {
            font-weight: normal;
            font-size: 11.5px;
            /* Increased font size for payment method values */
        }

        /* Note: The payment-info-text class exists in CSS but not used in HTML body */
        .payment-info-text {
            font-size: 7.5px;
            color: var(--label-text);
            margin-top: 6px;
            padding-top: 4px;
            border-top: 1px solid var(--border-color);
            /* CAMBIO: Usar variable */
        }

        /* Signature Area */
        .signature-area {
            margin-top: 8px;
            padding-top: 6px;
            /* MODIFICADO: Borde negro */
            border-top: 1px solid #000;
            /* Borde negro */
            width: 45%;
            float: right;
            text-align: center;
            margin-right: 0;
        }

        .signature-placeholder-space {
            height: 12px;
            display: block;
        }

        .signature-area p {
            font-size: 8px;
            margin-top: 2px;
            color: var(--secondary-text);
        }

        /* Clearfix for floats before footer */
        .clearfix {
            clear: both;
            height: 6px;
        }


        /* Footer Styles */
        footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            height: 16px;
            text-align: center;
            font-size: 7px;
            color: var(--label-text);
            border-top: 1px solid #000;
            /* CAMBIO: Línea negra en el pie de página */
            padding-top: 4px;
            background-color: #fff;
            /* Asegurar que el fondo no oscurezca la línea */
            z-index: 1000;
        }

        /* Dompdf Page Number Script Styling */
        <script type="text/php">
            if (isset($pdf)) {
                $font = $fontMetrics->getFont("DejaVu Sans");
                // Position the page number text - Adjusted Y coordinate for minimal bottom space
                // Aiming for footer text just above the bottom margin. Y coordinate around 820-830 points.
                $pdf->page_text(500, 828, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0.47, 0.47, 0.47)); // Font size 7, adjusted Y
            }
        </script>
    </style>
</head>

<body>

    <header>
        <div class="header-grid">
            <div class="header-row">
                <div class="header-col left">
                    {{-- Placeholder para la imagen izquierda --}}
                    {{-- Asegúrate de que la ruta de la imagen sea accesible por dompdf (ruta absoluta o base64) --}}
                    <img src="{{ public_path('imgs\LOGOAYUNTAMIENTOHINOJOS.jpg') }}" alt="Logo Izquierdo">
                </div>
                <div class="header-col center">
                    <h1>EXCMO. AYUNTAMIENTO DE HINOJOS</h1>
                    <h2>CARTA DE PAGO</h2>
                </div>
                <div class="header-col right">
                    {{-- Placeholder para la imagen derecha --}}
                    {{-- Example using public_path - you need to place your actual logo files here --}}
                </div>
            </div>
        </div>
    </header>

    {{-- Datos Generales de la Factura (Número, Fecha) --}}
    <div class="invoice-general-grid">
        <div class="data-row">
            <div class="data-col w-35"> {{-- Número de Pago --}}
                <span class="label">{{ __('Número de Pago') }}:</span>
                <span class="value">{{ $invoice->invoice_number ?? '-' }}</span>
            </div>
            <div class="data-col w-30">
                {{-- Este espacio queda vacío según el cambio, o podrías poner otra cosa aquí --}}
            </div>
            <div class="data-col w-35" style="text-align: right;"> {{-- Fecha de Emisión (movida aquí) --}}
                <span class="label">{{ __('Fecha de Emisión') }}:</span>
                <span class="value">{{ $invoice->created_at ? $invoice->created_at->format('d/m/Y') : '-' }}</span>
            </div>
        </div>
    </div>


    {{-- Eliminada la sección de Datos del Usuario Creador --}}

    {{-- Sección 2: Datos del Interesado (Cliente) --}}
    @if ($invoice->client)
        <div class="section">
            <h3>{{ __('DATOS DEL INTERESADO') }}</h3>

            {{-- Nuevo data-grid para las primeras dos filas (CIF/NIF, Nombre, Teléfono, Correo) --}}
            <div class="data-grid">
                <div class="data-row">
                    <div class="data-col w-30">
                        <span class="label">{{ __('CIF/NIF') }}:</span> <span
                            class="value">{{ $invoice->client->cif_nif ?? '-' }}</span>
                    </div>
                    <div class="data-col w-70"> {{-- Give more space to Name --}}
                        <span class="label">{{ __('Nombre/Razón Social') }}:</span> <span
                            class="value">{{ $invoice->client->name ?? '-' }}</span>
                    </div>
                </div>
                <div class="data-row">
                    <div class="data-col w-40">
                        <span class="label">{{ __('Teléfono') }}:</span> <span
                            class="value">{{ $invoice->client->phone ?? '-' }}</span>
                    </div>
                    <div class="data-col w-60">
                        <span class="label">{{ __('Correo Electrónico') }}:</span> <span
                            class="value">{{ $invoice->client->email ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- Nuevo data-grid para la tercera fila (Dirección) --}}
            {{-- Añadimos un pequeño margen superior si es necesario para separarlo del grid anterior --}}
            <div class="data-grid">
                <div class="data-row address-block">
                    <div class="data-col w-100">
                        <span class="label">{{ __('Dirección') }}:</span>
                        <span class="value">
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
                            {{-- Check if entire address is empty --}}
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
                        </span>
                    </div>
                </div>
            </div>

        </div>
    @endif

    {{-- Sección 3: Datos del Representante (Opcional) --}}
    @if ($invoice->representative)
        <div class="section">
            <h3>{{ __('Datos del Representante') }}</h3>

            {{-- Nuevo data-grid para las primeras dos filas (CIF/NIF, Nombre, Teléfono, Correo) --}}
            <div class="data-grid">
                <div class="data-row">
                    <div class="data-col w-30">
                        <span class="label">{{ __('CIF/NIF') }}:</span> <span
                            class="value">{{ $invoice->representative->cif_nif ?? '-' }}</span>
                    </div>
                    <div class="data-col w-70"> {{-- Give more space to Name --}}
                        <span class="label">{{ __('Nombre/Razón Social') }}:</span> <span
                            class="value">{{ $invoice->representative->name ?? '-' }}</span>
                    </div>
                </div>
                <div class="data-row">
                    <div class="data-col w-40">
                        <span class="label">{{ __('Teléfono') }}:</span> <span
                            class="value">{{ $invoice->representative->phone ?? '-' }}</span>
                    </div>
                    <div class="data-col w-60">
                        <span class="label">{{ __('Correo Electrónico') }}:</span> <span
                            class="value">{{ $invoice->representative->email ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- Nuevo data-grid para la tercera fila (Dirección) --}}
            {{-- Añadimos un pequeño margen superior si es necesario para separarlo del grid anterior --}}
            <div class="data-grid">
                <div class="data-row address-block">
                    <div class="data-col w-100">
                        <span class="label">{{ __('Dirección') }}:</span>
                        <span class="value">
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
                            {{-- Check if entire address is empty --}}
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
                        </span>
                    </div>
                </div>
            </div>

        </div>
    @endif

    {{-- Sección 4: Datos de la Liquidación --}}
    @if ($invoice->liquidation)
        <div class="section">
            <h3>{{ __('DATOS DE LA LIQUIDACIÓN') }}</h3>

            {{-- Nuevo data-grid para la primera fila (Expediente y Concepto) --}}
            <div class="data-grid">
                <div class="data-row">
                    <div class="data-col w-20"> {{-- Expediente & Concepto side-by-side --}}
                        <span class="label">{{ __('Número de Expediente') }}:</span>
                        <span class="value">{{ $invoice->liquidation->file_number ?? '-' }}</span>
                    </div>
                    <div class="data-col w-80">
                        <span class="label">{{ __('Concepto') }}:</span>
                        <span class="value">{{ $invoice->liquidation->concept ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- Nuevo data-grid para la segunda fila (detalles económicos) --}}
            {{-- Añadimos un pequeño margen superior si es necesario para separarlo del grid anterior --}}
            <div class="data-grid" style="margin-top: 5px;">
                <div class="data-row"> {{-- Economic details - 5 columns --}}
                    <div class="data-col w-20"> {{-- Base Imponible --}}
                        <span class="label">{{ __('Base Imponible') }}:</span>
                        <span class="value">{{ number_format($invoice->liquidation->taxable_base ?? 0, 2, ',', '.') }}
                            €</span>
                    </div>
                    <div class="data-col w-20"> {{-- Tipo Impositivo --}}
                        <span class="label">{{ __('Tipo Impositivo') }}:</span>
                        <span class="value">{{ number_format($invoice->liquidation->tax_rate ?? 0, 2, ',', '.') }}
                            %</span>
                    </div>
                    <div class="data-col w-20"> {{-- Total Tasa --}}
                        <span class="label">{{ __('Total Tasa') }}:</span>
                        <span class="value">{{ number_format($invoice->liquidation->total_fee ?? 0, 2, ',', '.') }}
                            €</span>
                    </div>
                    <div class="data-col w-20"> {{-- Fianza --}}
                        <span class="label">{{ __('Fianza') }}:</span>
                        <span class="value">{{ number_format($invoice->liquidation->bond ?? 0, 2, ',', '.') }}
                            €</span>
                    </div>
                    <div class="data-col w-20"> {{-- Total a Pagar (movido aquí) --}}
                        <span class="label">{{ __('Total a Pagar') }}:</span>
                        <span class="value invoice-total-value"> {{-- Usamos la clase original para mantener estilo --}}
                            {{ number_format($invoice->liquidation->total_to_pay ?? 0, 2, ',', '.') }} €
                        </span>
                    </div>
                </div>
            </div>

        </div>
    @endif
    {{-- Sección Lugar de Pago (Dinámico desde PaymentMethods) --}}
    <div class="section payment-methods">
        <h3>{{ __('LUGAR DE PAGO') }}</h3>
        <div class="data-grid">
            {{-- Iterar sobre los métodos de pago recibidos del controlador --}}
            @forelse ($paymentMethods as $paymentMethod)
                <div class="data-row">
                    <div class="data-col w-50"> {{-- Bank Name --}}
                        <span class="label">{{ __('Banco/Administración') }}:</span>
                        <span class="value">{{ $paymentMethod->bank_name ?? '-' }}</span>
                    </div>
                    <div class="data-col w-50"> {{-- Account Number --}}
                        <span class="label">{{ __('Número de Cuenta/Método de Pago') }}:</span>
                        <span class="value">{{ $paymentMethod->account_number ?? '-' }}</span>
                    </div>
                </div>
            @empty
                {{-- Mostrar un mensaje si no hay métodos de pago configurados --}}
                <div class="data-row">
                    <div class="data-col w-100">
                        <span class="value">{{ __('No hay métodos de pago configurados.') }}</span>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Nueva Sección: Información Legal --}}
    <div class="section legal-info-section">
        <h3>{{ __('INFORMACIÓN IMPORTANTE') }}</h3>
        <p>
            <strong>PARA EFECTUAR EL INGRESO (ART. 62 DE DE LA LEY 58/2003 GENERAL TRIBUTARIA).-</strong><br>
            a) Recibida la notificación entre los días 1 al 15 de cada mes, hasta el día 20 del mes siguiente o el
            inmediato hábil posterior.<br>
            b) Recibida la notificación entre los días 16 y último de cada mes, hasta el día 5 del segundo mes posterior
            o, si no fuera hábil, hasta el inmediato hábil siguiente.<br>
            Finalizado el plazo de pago en periodo voluntario sin que se haya hecho efectivo el mismo, el interesado
            incurrirá en los recargos ejecutivo (5%), de apremio reducido (10%) o recargo de apremio ordinario (20%)
            (Art. 161 L.G.T. y art. 28 del R.G.C.) más los intereses de demora que procedan (Art. 26 R.G.R.).
        </p>
        <p>
            <strong>RECURSO CONTRA LA LIQUIDACIÓN.-</strong>Contra la presente liquidación y según autorizan los arts.
            108 de la Ley 7/85, de 2 de Abril y 14 del Real Decreto Legislativo 2/2004 de 5 de Marzo por el que se
            aprueba el Texto Refundido de la Ley Reguladora de las Haciendas Locales, podrá interponer los siguientes
            recursos:
        </p>
        <ul>
            <li>
                Recurso de Reposición, previo al Contencioso-Administrativo, ante el Sr. Alcalde, en el plazo de un mes
                contado desde el día siguiente al de la recepción de la presente notificación.
                <br>No obstante se le advierte que la interposición del recurso de reposición no suspenderá la ejecución
                del acto impugnado, con las correspondientes consecuencias legales, incluso la recaudación de cuotas o
                derechos liquidados, intereses y recargos, salvo en los casos previstos en el artículo 101 del
                Reglamento General de Recaudación y Artículo 14.2 del Real Decreto Legislativo 2/2004 de 5 de Marzo por
                el que se aprueba el Texto Refundido de la Ley Reguladora de las Haciendas Locales.
            </li>
            <li>
                Contra el acto o acuerdo que resuelva el correspondiente recurso podrá interponer Recurso
                Contencioso-Administrativo ante el Juzgado de lo Contencioso Administrativo que corresponda de los de
                Huelva, en el plazo de dos meses contados desde el día siguiente a aquél en que se notifique la
                resolución expresa del recurso de reposición. Si no se hubiere resuelto expresamente dicho recurso, será
                de aplicación el régimen de los actos presuntos a tal efecto regulado en el art. 46 de la Ley de la
                Jurisdicción Contencioso-Administrativa.
            </li>
            <li>
                Todo ello sin perjuicio de que pueda ejercitar cualquier otro recurso que estime procedente.
            </li>
        </ul>
    </div>


    {{-- Área de Firma --}}
    <div class="signature-area" style="margin-top: 8px;">
        {{-- You could add a placeholder for the signature image here if needed --}}
        {{-- <img src="{{ public_path('ruta/a/la/firma.png') }}" alt="Signature" style="max-width: 150px; height: auto; margin-bottom: 5px;"> --}}
        <span class="signature-placeholder-space"></span> {{-- Space for signature --}}
        <p>{{ __('FIRMA DEL PRESENTADOR/A') }}</p>
    </div>

    {{-- Asegurar espacio al final antes del footer --}}
    <div class="clearfix" style="height: 6px;"></div>


    {{-- Pie de página --}}
    <footer>
        {{-- Text will be added by the script --}}
    </footer>

    {{-- Script for pagination count (works with dompdf if enabled) --}}
    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->getFont("DejaVu Sans");
            // Position the page number text - Adjusted Y coordinate for minimal bottom space
            // Aiming for footer text just above the bottom margin. Y coordinate around 820-830 points.
            $pdf->page_text(500, 828, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0.47, 0.47, 0.47)); // Font size 7, adjusted Y
        }
    </script>

</body>

</html>
