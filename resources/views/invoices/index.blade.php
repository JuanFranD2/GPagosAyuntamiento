<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight py-2">
                {{ __('Gestión de Pagos') }}
            </h2>
            {{-- Contenedor para el botón de instrucciones, selector y botones de acción --}}
            <div class="flex items-center space-x-2">
                {{-- Botón para mostrar las instrucciones (AHORA UN POCO MÁS GRANDE con text-lg) --}}
                <button type="button" id="show-instructions-modal-btn"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-lg leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                    <i class="fas fa-question-circle"></i> {{-- Icono de interrogación --}}
                </button>

                {{-- Contenedor para el selector y el botón (existente) --}}
                <div class="flex items-center space-x-2">
                    {{-- Selector para filtrar (visible solo para admin) --}}
                    @if (auth()->user()?->isAdmin())
                        <label for="invoice-view-filter" class="block text-sm font-medium text-gray-700 sr-only">
                            {{ __('Ver') }} {{-- Texto para accesibilidad, visualmente oculto --}}
                        </label>
                        <select id="invoice-view-filter" name="view"
                            class="block rounded-md border-gray-300 shadow-sm text-sm py-2">
                            <option value="all">{{ __('Todos los Pagos') }}</option>
                            <option value="my">{{ __('Mis Pagos') }}</option>
                        </select>
                    @endif
                    {{-- Botón para crear nueva factura (comentado en el original) --}}
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Área de contenido principal --}}
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Mensaje que se muestra cuando no hay pagos - Reintroducido para consistencia con usuarios --}}
                    @if ($invoices->isEmpty())
                        <p id="no-invoices-message">{{ __('No hay pagos registrados.') }}</p>
                    @else
                        {{-- Este mensaje será ocultado por DataTables si hay datos - Reintroducido --}}
                        <p id="no-invoices-message" class="hidden">
                            {{ __('No hay pagos registrados.') }}</p>
                    @endif

                    {{-- Contenedor con scroll vertical y opacidad para transición --}}
                    {{-- Inicialmente oculta el contenedor con Blade si está vacío, como en usuarios --}}
                    <div id="invoices-datatable-wrapper"
                        class="opacity-0 transition-opacity duration-300 overflow-y-auto"
                        style="max-height: 500px; @if ($invoices->isEmpty()) display: none; @endif">
                        <table id="invoices-table" class="display w-full table-fixed">
                            <thead>
                                <tr>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Número de Carta') }}</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Número de Expediente') }}</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('CIF/NIF Cliente') }}</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Fecha de Creación') }}</th>
                                    {{-- Columna de Acciones --}}
                                    <th
                                        class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                {{-- Las facturas se mostrarán aquí inicialmente con Blade --}}
                                @foreach ($invoices as $invoice)
                                    <tr>
                                        <td class="border px-4 py-2 text-sm text-gray-900 text-left">
                                            {{ $invoice->invoice_number ?? '-' }}</td>
                                        <td class="border px-4 py-2 text-sm text-gray-900 text-left">
                                            {{ $invoice->liquidation->file_number ?? '-' }}</td>
                                        <td class="border px-4 py-2 text-sm text-gray-900 text-left">
                                            {{ $invoice->client->cif_nif ?? '-' }}</td>
                                        <td class="border px-4 py-2 text-sm text-gray-900 text-left">
                                            {{ $invoice->created_at ? $invoice->created_at->format('d/m/Y H:i:s') : '-' }}
                                        </td>
                                        {{-- Celda de Acciones --}}
                                        <td class="border px-4 py-2 text-center text-sm font-medium">
                                            <div class="flex space-x-2 justify-center">
                                                {{-- Botón Ver Detalles --}}
                                                <a href="{{ route('invoices.show', $invoice) }}"
                                                    class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                {{-- Botón Descargar PDF --}}
                                                <a href="{{ route('invoices.download', $invoice) }}"
                                                    class="inline-flex items-center px-4 py-2 bg-purple-500 hover:bg-purple-700 text-white rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                    target="_blank"> {{-- Open in new tab is good practice --}}
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>

                                                {{-- Formulario y Botón Eliminar (Ahora visible para 'oper' también) --}}
                                                <form action="{{ route('invoices.destroy', $invoice) }}" method="POST"
                                                    class="delete-invoice-form" data-id="{{ $invoice->id }}"
                                                    data-number="{{ $invoice->invoice_number ?? '-' }}"
                                                    data-client="{{ $invoice->client->cif_nif ?? '-' }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="delete-invoice-btn inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-700 text-white rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Estructura del Modal para Confirmar Eliminación (Adaptado para Facturas) --}}
    <div id="delete-confirmation-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Confirmar Eliminación') }}</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 mb-4" id="delete-confirmation-message">
                        {{ __('¿Estás seguro de eliminar este pago?') }}
                    </p>
                    {{-- Detalles de la factura a eliminar --}}
                    <p class="text-sm text-gray-700 font-semibold" id="item-to-delete-details"></p>
                    <div class="items-center px-4 py-3 mt-4 flex justify-end space-x-2">
                        <button type="button" id="cancel-delete-modal-btn"
                            class="px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2">
                            {{ __('Cancelar') }}
                        </button>
                        <button type="button" id="confirm-delete-btn"
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            {{ __('Eliminar') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Estructura del Modal de Mensaje Genérico (Sin botón de cerrar, se cierra solo) --}}
    <div id="message-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="message-modal-title">{{ __('Mensaje') }}
                </h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500" id="message-modal-body">
                        {{-- El mensaje se insertará aquí --}}
                    </p>
                </div>
                {{-- Botón de cerrar eliminado para cierre automático --}}
            </div>
        </div>
    </div>

    {{-- Estructura del Modal de Instrucciones --}}
    <div id="instructions-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        {{-- Usamos max-w-3xl como en la página de creación --}}
        <div class="relative top-20 mx-auto p-5 border max-w-3xl shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="instructions-modal-title">
                    {{ __('Instrucciones de Uso') }}</h3>
                <div class="mt-2 px-7 py-3 text-left modal-instructions-content"> {{-- Añadida clase para scroll --}}
                    <p class="text-sm text-gray-500" id="instructions-modal-body">
                        {{-- El texto de las instrucciones se insertará aquí --}}
                    </p>
                </div>
                <div class="items-center px-4 py-3 mt-4 flex justify-end">
                    <button type="button" id="close-instructions-modal-btn"
                        class="px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2">
                        {{ __('Cerrar') }}
                    </button>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        {{-- Incluir CSS de Font Awesome --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        {{-- Incluir CSS de DataTables --}}
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

        {{-- Incluir jQuery (si no está ya incluido en tu layout principal) --}}
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.0.js"></script>
        {{-- Incluir JS de DataTables --}}
        <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

        {{-- CSS para el espacio entre el filtro y la tabla, y ocultar la tabla inicialmente --}}
        <style>
            /* Añade un margen inferior al contenedor del filtro de búsqueda de DataTables */
            .dataTables_filter {
                margin-bottom: 10px;
            }

            /* Asegurarse de que las filas vacías tengan el mismo estilo de borde que las filas activas */
            .empty-row td {
                border: 1px solid #e5e7eb;
                /* Tailwind gray-200 border color */
            }

            /* Hacer que el contenido de las celdas vacías sea invisible pero ocupe espacio */
            .empty-row td span.invisible {
                visibility: hidden;
            }

            /* ESTILO PARA EL CONTENEDOR DEL TEXTO DE INSTRUCCIONES CON SCROLL */
            .modal-instructions-content {
                max-height: 400px;
                /* Altura máxima antes de que aparezca la barra de scroll */
                overflow-y: auto;
                /* Añade scroll vertical si el contenido excede la altura máxima */
                padding-right: 1rem;
                /* Añade un poco de padding a la derecha para que el scrollbar no pise el texto */
            }
        </style>

        <script>
            $(document).ready(function() {
                // Asegura que el token CSRF esté disponible para las peticiones AJAX
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                const table = $('#invoices-table').DataTable({
                    autoWidth: false, // Mantener autoWidth en false
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es_es.json',
                        search: "Buscar:",
                        info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                        infoEmpty: "Mostrando 0 a 0 de 0 registros",
                        paginate: {
                            first: "Primera",
                            previous: "Anterior",
                            next: "Siguiente",
                            last: "Última"
                        },
                        emptyTable: "{{ __('No existen pagos asignados a este usuario') }}",
                        zeroRecords: "{{ __('No se encuentra ningún pago con esa descripción') }}",
                    },
                    lengthChange: false, // Evita que el usuario cambie el número de filas por página
                    pageLength: 5, // Número de filas por página por defecto
                    scrollY: '300px', // Altura con scroll
                    scrollCollapse: true,
                    paging: true,
                    columnDefs: [
                        // Definición de columnas (ajustar según los datos mostrados)
                        {
                            targets: 0, // Número de Factura
                            className: 'border px-4 py-2 text-sm text-gray-900 text-left'
                        },
                        {
                            targets: 1, // Número de Expediente
                            className: 'border px-4 py-2 text-sm text-gray-900 text-left'
                        },
                        {
                            targets: 2, // CIF/NIF Cliente
                            className: 'border px-4 py-2 text-sm text-gray-900 text-left'
                        },
                        {
                            targets: 3, // Fecha de Creación
                            className: 'border px-4 py-2 text-sm text-gray-900 text-left'
                        },
                        // Columna 4: Acciones
                        {
                            targets: 4,
                            className: 'border px-4 py-2 text-center text-sm font-medium',
                            orderable: false, // Deshabilitar ordenación
                            searchable: false // Deshabilitar búsqueda
                        }
                    ],
                    // Ordenar por la columna Fecha de Creación (3) descendente por defecto
                    order: [
                        [0, 'desc']
                    ],
                    stateSave: false, // Mantener el estado de la tabla (paginación, filtro, orden)
                    initComplete: function() {
                        const api = this.api();

                        // *** Lógica similar a users/index.blade.php para forzar pageLength ***
                        if (api.page.len() !== 5) {
                            api.page.len(5).draw(false);
                        }

                        // *** MODIFICACIÓN para que coincida con users/index.blade.php ***
                        // Muestra/oculta el contenedor de la tabla y el mensaje basado en el conteo de datos
                        if (api.data().count() > 0) {
                            $('#invoices-datatable-wrapper').removeClass('opacity-0').show();
                            $('#no-invoices-message').hide();
                        } else {
                            $('#invoices-datatable-wrapper').hide();
                            $('#no-invoices-message').show();
                        }

                        // Ajustar columnas después de mostrar el contenedor (solo si hay datos)
                        if (api.data().count() > 0) {
                            setTimeout(function() {
                                table.columns.adjust().draw(false);
                            }, 10); // Pequeño retardo para asegurar el display: block
                        }
                        // *******************************************************************

                    },
                    drawCallback: function(settings) {
                        const api = this.api();

                        // *** MODIFICACIÓN para que coincida con users/index.blade.php ***
                        // Muestra/oculta el contenedor de la tabla y el mensaje basado en el conteo de datos filtrados
                        if (api.data().count() > 0) {
                            $('#no-invoices-message').hide();
                            // Asegura que esté visible si antes estaba oculto por filtro o paginación
                            $('#invoices-datatable-wrapper').show().removeClass('opacity-0');
                        } else {
                            $('#no-invoices-message').show();
                            $('#invoices-datatable-wrapper').hide();
                        }
                        // *******************************************************************


                        // Lógica para añadir filas vacías para rellenar la página
                        // *** NOTA: Esta lógica de filas vacías es para estética cuando HAY DATOS pero menos que pageLength.
                        //     Si la tabla está totalmente vacía, DataTables mostrará el mensaje emptyTable.
                        //     Esta parte NO debe ejecutarse si api.data().count() === 0.
                        //     La condición actual `rowsOnCurrentPage > 0` ya lo maneja. ***
                        const rowsOnCurrentPage = api.rows({
                            page: 'current'
                        }).nodes().length;
                        const pageLength = api.page.len();
                        const tableId = '#' + settings.sTableId;
                        const currentBody = $(tableId + ' tbody');
                        currentBody.find('.empty-row').remove(); // Elimina las filas vacías existentes

                        if (rowsOnCurrentPage > 0 && rowsOnCurrentPage < pageLength) {
                            let emptyRows = pageLength - rowsOnCurrentPage;
                            let emptyRowHtml = '';
                            const csrfToken = $('meta[name="csrf-token"]').attr(
                                'content'); // Obtiene el token CSRF

                            for (let i = 0; i < emptyRows; i++) {
                                emptyRowHtml += '<tr class="empty-row">';
                                // Celdas de datos (span invisible) - aseguran el tamaño de la fila
                                emptyRowHtml +=
                                    '<td class="border px-4 py-2 text-sm text-gray-900 text-left"><span class="invisible">-</span></td>'; // Col 0
                                emptyRowHtml +=
                                    '<td class="border px-4 py-2 text-sm text-gray-900 text-left"><span class="invisible">-</span></td>'; // Col 1
                                emptyRowHtml +=
                                    '<td class="border px-4 py-2 text-sm text-gray-900 text-left"><span class="invisible">-</span></td>'; // Col 2
                                emptyRowHtml +=
                                    '<td class="border px-4 py-2 text-sm text-gray-900 text-left"><span class="invisible">-</span></td>'; // Col 3
                                // Celda de acciones (botones deshabilitados)
                                emptyRowHtml += `<td class="border px-4 py-2 text-center text-sm font-medium">
                                            <div class="flex space-x-2 justify-center">
                                                {{-- Botones deshabilitados en filas vacías --}}
                                                <button type="button" disabled
                                                    class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none cursor-not-allowed"
                                                    style="pointer-events: none;">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" disabled
                                                    class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none cursor-not-allowed"
                                                    style="pointer-events: none;">
                                                    <i class="fas fa-file-pdf"></i>
                                                </button>
                                                <form action="#" method="POST" class="disabled-form">
                                                    <input type="hidden" name="_token" value="${csrfToken}">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="button" disabled
                                                        class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none cursor-not-allowed">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>`; // Col 4 (Índice de columna de acciones)

                                emptyRowHtml += '</tr>';
                            }
                            currentBody.append(emptyRowHtml);
                        }
                    }
                });

                // --- Invoice View Filter (Admin only) ---
                const invoiceViewFilter = $('#invoice-view-filter');
                const dataTableWrapper = $('#invoices-datatable-wrapper'); // Obtener el wrapper de la tabla


                if (invoiceViewFilter.length) { // Check if the select element exists (i.e., user is admin)
                    // Get current URL parameters to set initial select state
                    const urlParams = new URLSearchParams(window.location.search);
                    const currentView = urlParams.get('view');

                    if (currentView) {
                        invoiceViewFilter.val(currentView);
                    } else {
                        // Si no hay parámetro 'view' y el selector existe (es admin), el valor por defecto es 'all'
                        invoiceViewFilter.val('all');
                    }

                    // Add change listener
                    invoiceViewFilter.on('change', function() {
                        const selectedView = $(this).val();
                        const currentUrl = new URL(window.location.href);

                        if (selectedView === 'all') {
                            currentUrl.searchParams.delete('view'); // Remove parameter for 'all'
                        } else {
                            currentUrl.searchParams.set('view', selectedView); // Set parameter for 'my'
                        }

                        // --- Añadir una pequeña transición visual antes de recargar ---
                        // Atenuar la tabla
                        dataTableWrapper.css('opacity', 0.5);
                        // Opcional: podrías deshabilitar temporalmente los botones aquí también

                        // Esperar un momento antes de redirigir para que la transición sea visible
                        setTimeout(function() {
                            // Navigate to the new URL
                            window.location.href = currentUrl.toString();
                        }, 200); // 200ms de espera, ajusta si es necesario
                        // --- Fin de la transición visual ---
                    });
                }
                // --- End Invoice View Filter ---


                // --- Manejo del Modal de Confirmación de Eliminación ---
                const deleteConfirmationModal = $('#delete-confirmation-modal');
                const confirmDeleteBtn = $('#confirm-delete-btn');
                const cancelDeleteModalBtn = $('#cancel-delete-modal-btn');
                const itemToDeleteDetails = $('#item-to-delete-details');
                let currentDeletingRow = null; // Variable para almacenar la fila de DataTables que se está eliminando

                // Función para mostrar el modal de confirmación de eliminación
                function showDeleteConfirmationModal(invoiceNumber, clientCifNif, deleteUrl, dataTableRaw) {
                    itemToDeleteDetails.html(
                        `Pago: <strong>${invoiceNumber}</strong><br>Cliente (CIF/NIF): <strong>${clientCifNif}</strong>`
                    );
                    deleteConfirmationModal.data('delete-url', deleteUrl);
                    currentDeletingRow = dataTableRaw; // Almacena el objeto de la fila de DataTables
                    deleteConfirmationModal.removeClass('hidden');
                }

                // Función para ocultar el modal de confirmación de eliminación
                function hideDeleteConfirmationModal() {
                    deleteConfirmationModal.addClass('hidden');
                    deleteConfirmationModal.removeData('delete-url'); // Limpia los datos almacenados
                    currentDeletingRow = null; // Limpia la fila almacenada
                    itemToDeleteDetails.text(''); // Limpia el texto de detalles
                }

                // Manejar clic en el botón de eliminar de la tabla (muestra el modal de confirmación)
                // Usamos 'submit' en el formulario para capturarlo antes de que se envíe realmente
                $(document).on('submit', '.delete-invoice-form', function(e) {
                    e.preventDefault(); // Previene el envío predeterminado del formulario

                    const form = $(this);
                    const deleteUrl = form.attr('action');
                    const invoiceNumber = form.data('number'); // Obtiene el número de factura del atributo data
                    const clientCifNif = form.data(
                        'client'); // Obtiene el CIF/NIF del cliente del atributo data
                    const dataTableRaw = table.row($(this).closest(
                        'tr')); // Obtiene el objeto de la fila de DataTables

                    showDeleteConfirmationModal(invoiceNumber, clientCifNif, deleteUrl, dataTableRaw);
                });


                // Manejar clic en el botón "Eliminar" dentro del modal de confirmación
                confirmDeleteBtn.on('click', function() {
                    const deleteUrl = deleteConfirmationModal.data('delete-url');

                    if (!deleteUrl || !currentDeletingRow) {
                        showMessageModal('Error', 'No se pudo obtener la información para eliminar.');
                        hideDeleteConfirmationModal();
                        return;
                    }

                    // Realiza la eliminación mediante AJAX
                    $.ajax({
                        url: deleteUrl,
                        method: 'POST', // Usa POST para simular el método
                        data: {
                            _method: 'DELETE' // Simula el método DELETE
                            // El token CSRF se maneja por ajaxSetup
                        },
                        success: function(response) {
                            // Elimina la fila de la DataTable y redibuja
                            currentDeletingRow.remove().draw(false);

                            // *** MODIFICACIÓN para que coincida con users/index.blade.php ***
                            // Después de eliminar una fila, verifica si la tabla está ahora vacía
                            if (table.data().count() === 0) {
                                $('#invoices-datatable-wrapper')
                                    .hide(); // Oculta el contenedor de la tabla
                                $('#no-invoices-message')
                                    .show(); // Muestra el mensaje de tabla vacía
                            }
                            // *******************************************************************

                            hideDeleteConfirmationModal(); // Oculta el modal de confirmación

                            // Mostrar mensaje de éxito en el modal genérico
                            showMessageModal('Éxito', 'Pago eliminado correctamente.');
                        },
                        error: function(xhr) {
                            hideDeleteConfirmationModal(); // Oculta el modal de confirmación

                            // *** Lógica de errores similar a users/index.blade.php ***
                            let errorMessage = 'Ocurrió un error al eliminar el pago.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.status === 409 || xhr.status === 403) {
                                // Mensaje genérico si no hay mensaje específico del servidor para estos códigos
                                errorMessage =
                                    'No se puede eliminar el pago debido a restricciones.';
                            }
                            showMessageModal('Error', errorMessage);
                            console.error(xhr.responseText);
                            // *********************************************************
                        }
                    });
                });

                // Manejar clic en el botón "Cancelar" del modal de eliminación
                cancelDeleteModalBtn.on('click', hideDeleteConfirmationModal);

                // Manejar clics fuera del modal de eliminación
                deleteConfirmationModal.on('click', function(e) {
                    if ($(e.target).is(deleteConfirmationModal)) {
                        hideDeleteConfirmationModal();
                    }
                });

                // --- Manejo del Modal de Mensaje Genérico (con cierre automático) ---
                const messageModal = $('#message-modal');
                const messageModalTitle = $('#message-modal-title');
                const messageModalBody = $('#message-modal-body');

                // Función para mostrar el modal de mensaje
                function showMessageModal(title, message) {
                    messageModalTitle.text(title);
                    messageModalBody.html(message); // Usar html() permite etiquetas como <br>
                    messageModal.removeClass('hidden');

                    // Ocultar el modal automáticamente después de 2 segundos
                    setTimeout(function() {
                        hideMessageModal();
                    }, 2000); // 2000 milisegundos = 2 segundos
                }

                // Función para ocultar el modal de mensaje
                function hideMessageModal() {
                    messageModal.addClass('hidden');
                    messageModalTitle.text('');
                    messageModalBody.text('');
                }

                // Permitir cerrar el modal de mensaje haciendo clic fuera de él
                messageModal.on('click', function(e) {
                    if ($(e.target).is(messageModal)) {
                        hideMessageModal();
                    }
                });
                // --- FIN Manejo del Modal de Mensaje Genérico ---

                // --- Manejo del Modal de Instrucciones ---
                const instructionsModal = $('#instructions-modal');
                const showInstructionsModalBtn = $('#show-instructions-modal-btn');
                const closeInstructionsModalBtn = $('#close-instructions-modal-btn');
                const instructionsModalBody = $('#instructions-modal-body');

                // Texto de las instrucciones (versión reducida para trabajadores)
                const instructionsText = `
                    <p>Esta guía te ayudará a usar la sección de Gestión de Pagos.</p>
                    <h4 class="font-semibold mt-3 mb-1 text-gray-800">1. Ver tus Pagos</h4>
                    <p>Al entrar, verás una lista de pagos en una tabla.</p>
                    <p>Usa el campo "Buscar:" para encontrar pagos rápidamente escribiendo parte del número de factura, expediente o CIF/NIF del cliente.</p>
                    <p>Navega entre páginas con los botones de paginación debajo de la tabla.</p>
                    <h4 class="font-semibold mt-3 mb-1 text-gray-800">2. Acciones Disponibles</h4>
                    <p>En la columna "Acciones" de cada pago, encontrarás:</p>
                    <ul class="list-disc list-inside ml-4 text-gray-700">
                        <li><i class="fas fa-eye"></i> (Azul): **Ver Detalles.** Haz clic aquí para ver toda la información de ese pago.</li>
                        <li><i class="fas fa-file-pdf"></i> (Morado): **Descargar PDF.** Haz clic aquí para obtener el archivo PDF del pago (se abrirá en una nueva pestaña).</li>
                        <li><i class="fas fa-trash-alt"></i> (Rojo): **Eliminar.** Haz clic aquí para borrar el pago.</li>
                    </ul>
                    <h4 class="font-semibold mt-3 mb-1 text-gray-800">3. Eliminar un Pago</h4>
                    <p>Si haces clic en el botón <i class="fas fa-trash-alt"></i>, aparecerá una ventana para confirmar que quieres eliminarlo. Confirma o cancela según corresponda.</p>
                    <h4 class="font-semibold mt-3 mb-1 text-gray-800">4. Mensajes del Sistema</h4>
                    <p>Las ventanas pequeñas te informan (éxito o error) y se cierran solas.</p>
                    <p class="mt-3 text-gray-600"><strong>Consejo Rápido:</strong> Cierra cualquier ventana emergente presionando la tecla <code>Escape</code>.</p>
                `;


                // Función para mostrar el modal de instrucciones
                function showInstructionsModal() {
                    instructionsModalBody.html(instructionsText); // Inserta el texto HTML de las instrucciones
                    instructionsModal.removeClass('hidden');
                }

                // Función para ocultar el modal de instrucciones
                function hideInstructionsModal() {
                    instructionsModal.addClass('hidden');
                    instructionsModalBody.html(''); // Limpia el contenido del cuerpo al cerrar
                }

                // Manejar clic en el botón de instrucciones
                showInstructionsModalBtn.on('click', showInstructionsModal);

                // Manejar clic en el botón "Cerrar" dentro del modal de instrucciones
                closeInstructionsModalBtn.on('click', hideInstructionsModal);

                // Manejar clics fuera del modal de instrucciones
                instructionsModal.on('click', function(e) {
                    if ($(e.target).is(instructionsModal)) {
                        hideInstructionsModal();
                    }
                });
                // --- FIN Manejo del Modal de Instrucciones ---


                // Manejar la tecla Escape para cerrar modales
                // Ahora incluimos el modal de instrucciones
                $(document).on('keydown', function(e) {
                    if (e.key === 'Escape') {
                        if (!deleteConfirmationModal.hasClass('hidden')) {
                            hideDeleteConfirmationModal();
                        } else if (!messageModal.hasClass('hidden')) {
                            hideMessageModal();
                        } else if (!instructionsModal.hasClass('hidden')) { // Agregamos esta condición
                            hideInstructionsModal();
                        }
                    }
                });


                // --- PDF Download Trigger ---
                // Check if the 'download_invoice_id' flash session variable exists
                // Usamos Js::from para pasar el valor de sesión de forma segura a JS
                const invoiceIdToDownload = {{ Js::from(session('download_invoice_id', null)) }};

                if (invoiceIdToDownload) {
                    // Construct the URL for the download route
                    // Make sure your route name matches: Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'downloadPdf'])->name('invoices.download');
                    const downloadUrl = `{{ route('invoices.download', ':invoiceId') }}`.replace(':invoiceId',
                        invoiceIdToDownload);

                    // Trigger the download. Opening in a new tab is generally preferred
                    // so the user stays on the index page.
                    window.open(downloadUrl, '_blank');

                    // Note: Flash data is automatically cleared by Laravel after being accessed
                    // on the next request, so no need for manual clearing here unless you
                    // had a specific scenario where it might persist unexpectedly.
                }

                // --- End PDF Download Trigger ---


            });
        </script>
        {{-- Asegúrate de tener esta meta etiqueta en tu layout principal para el token CSRF --}}
        {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
    @endpush
</x-app-layout>
