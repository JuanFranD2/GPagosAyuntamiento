<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight py-2">
                {{ __('Gestión de Métodos de Pago') }}
            </h2>
            {{-- Botón para abrir el modal de crear nuevo método de pago --}}
            <button id="create-payment-method-btn"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                {{ __('Nuevo Método de Pago') }}
            </button>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- El mensaje "No hay métodos de pago registrados." y su lógica Blade han sido eliminados.
                         DataTables mostrará su propio mensaje emptyTable si no hay datos. --}}

                    {{-- Contenedor con scroll vertical cuando hay muchas filas --}}
                    {{-- Inicialmente oculta la opacidad, DataTables la controlará en initComplete --}}
                    {{-- NOTA: El display: none condicional de Blade ha sido eliminado.
                             La tabla siempre estará en el DOM, DataTables gestiona el mensaje de vacío. --}}
                    <div id="payment-methods-datatable-wrapper"
                        class="opacity-0 transition-opacity duration-300 overflow-y-auto" style="max-height: 500px;">
                        <table id="payment-methods-table" class="display w-full table-fixed">
                            <thead>
                                <tr>
                                    {{-- Solo incluimos los headers deseados --}}
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Nombre del Banco') }}</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Número de Cuenta') }}</th>
                                    {{-- Columna de Acciones --}}
                                    <th
                                        class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                {{-- Los métodos de pago se mostrarán aquí inicialmente con Blade --}}
                                {{-- DataTables tomará estos datos al inicializarse --}}
                                @foreach ($paymentMethods as $method)
                                    <tr>
                                        {{-- Solo mostramos los campos deseados y las acciones --}}
                                        <td class="border px-4 py-2 text-sm text-gray-900 text-left">
                                            {{ $method->bank_name ?? '-' }}</td>
                                        <td class="border px-4 py-2 text-sm text-gray-900 text-left">
                                            {{ $method->account_number ?? '-' }}</td>
                                        {{-- Celda de Acciones --}}
                                        <td class="border px-4 py-2 text-center text-sm font-medium">
                                            <div class="flex space-x-2 justify-center">
                                                {{-- Botón Modificar (Edit) --}}
                                                {{-- Added data attributes for bank_name and account_number --}}
                                                <button type="button"
                                                    class="edit-payment-method-btn inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-700 text-white rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                    data-id="{{ $method->id }}"
                                                    data-bank-name="{{ $method->bank_name ?? '' }}"
                                                    data-account-number="{{ $method->account_number ?? '' }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                {{-- Formulario y Botón Eliminar --}}
                                                <form action="{{ route('payment-methods.destroy', $method->id) }}"
                                                    method="POST" class="delete-payment-method-form"
                                                    data-id="{{ $method->id }}"
                                                    data-bank-name="{{ $method->bank_name ?? '-' }}"
                                                    data-account-number="{{ $method->account_number ?? '-' }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="delete-payment-method-btn inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-700 text-white rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
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

    {{-- Modal Structure for Creating Payment Method --}}
    <div id="create-payment-method-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Crear Nuevo Método de Pago') }}</h3>
                <div class="mt-2 px-7 py-3">
                    <form id="create-payment-method-form" action="{{ route('payment-methods.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="bank_name"
                                class="block text-sm font-medium text-gray-700 text-left">{{ __('Nombre del Banco') }}</label>
                            <input type="text" name="bank_name" id="bank_name" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="text-red-500 text-xs italic mt-1" id="bank_name_error"></p>
                        </div>
                        <div class="mb-4">
                            <label for="account_number"
                                class="block text-sm font-medium text-gray-700 text-left">{{ __('Número de Cuenta') }}</label>
                            <input type="text" name="account_number" id="account_number" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="text-red-500 text-xs italic mt-1" id="account_number_error"></p>
                        </div>
                        <div class="items-center px-4 py-3">
                            <button type="submit" id="submit-create-payment-method"
                                class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                {{ __('Guardar') }}
                            </button>
                            <button type="button" id="close-create-modal-btn"
                                class="mt-3 px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2">
                                {{ __('Cancelar') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Structure for Editing Payment Method --}}
    <div id="edit-payment-method-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Editar Método de Pago') }}</h3>
                <div class="mt-2 px-7 py-3">
                    {{-- Note: Action and Method will be set dynamically by JavaScript --}}
                    <form id="edit-payment-method-form" action="" method="POST">
                        @csrf
                        @method('PUT') {{-- Use PUT method for updates --}}
                        <input type="hidden" name="id" id="edit_method_id"> {{-- Hidden field for ID --}}

                        <div class="mb-4">
                            <label for="edit_bank_name"
                                class="block text-sm font-medium text-gray-700 text-left">{{ __('Nombre del Banco') }}</label>
                            <input type="text" name="bank_name" id="edit_bank_name" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="text-red-500 text-xs italic mt-1" id="edit_bank_name_error"></p>
                        </div>
                        <div class="mb-4">
                            <label for="edit_account_number"
                                class="block text-sm font-medium text-gray-700 text-left">{{ __('Número de Cuenta') }}</label>
                            <input type="text" name="account_number" id="edit_account_number" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="text-red-500 text-xs italic mt-1" id="edit_account_number_error"></p>
                        </div>
                        <div class="items-center px-4 py-3">
                            <button type="submit" id="submit-edit-payment-method"
                                class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                {{ __('Actualizar') }}
                            </button>
                            <button type="button" id="close-edit-modal-btn"
                                class="mt-3 px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2">
                                {{ __('Cancelar') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- Modal Structure for Delete Confirmation --}}
    <div id="delete-confirmation-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Confirmar Eliminación') }}</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 mb-4" id="delete-confirmation-message">
                        {{ __('¿Estás seguro de eliminar este método de pago?') }}
                    </p>
                    {{-- Optional: Add details about the item being deleted --}}
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


    @push('scripts')
        {{-- Incluir CSS de Font Awesome si se usan iconos --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        {{-- Incluir CSS de DataTables --}}
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

        {{-- Incluir jQuery (si no está ya incluido en tu layout principal) --}}
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.0.js"></script>
        {{-- Incluir JS de DataTables --}}
        <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

        {{-- CSS para el espacio entre el filtro y la tabla, y ocultar la opacidad inicial --}}
        <style>
            /* Añade un margen inferior al contenedor del filtro de búsqueda de DataTables */
            .dataTables_filter {
                margin-bottom: 10px;
                /* Ajusta este valor según necesites un espacio mayor o menor */
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

            /* Eliminada la regla display: none; inicial para payment-methods-datatable-wrapper */
        </style>

        <script>
            $(document).ready(function() {
                // Ensure CSRF token is available
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                const table = $('#payment-methods-table').DataTable({
                    autoWidth: false,
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
                        emptyTable: "{{ __('No existe ningún método de pago') }}", // DataTables handles the empty message
                        zeroRecords: "{{ __('No se encuentra ningún método de pago con esa descripción') }}",
                    },
                    lengthChange: false,
                    pageLength: 5,
                    scrollY: '300px',
                    scrollCollapse: true,
                    paging: true,
                    columnDefs: [
                        // Columna 0: Nombre del Banco
                        {
                            targets: 0,
                            className: 'border px-4 py-2 text-sm text-gray-900 text-left'
                        },
                        // Columna 1: Número de Cuenta
                        {
                            targets: 1,
                            className: 'border px-4 py-2 text-sm text-gray-900 text-left'
                        },
                        // Columna 2: Acciones
                        {
                            targets: 2,
                            className: 'border px-4 py-2 text-center text-sm font-medium'
                        },
                        // Deshabilitar ordenación y búsqueda para la columna de acciones
                        {
                            targets: 2,
                            orderable: false,
                            searchable: false
                        }
                    ],
                    stateSave: true,
                    initComplete: function() {
                        const api = this.api();

                        // Force pageLength to 5 on initialization if not already set
                        if (api.page.len() !== 5) {
                            api.page.len(5).draw(false);
                        }

                        // Simply make the table wrapper visible once DataTables is ready
                        // DataTables will handle showing the emptyTable message if there's no data
                        $('#payment-methods-datatable-wrapper').removeClass('opacity-0').show();

                        // Adjust columns after ensuring the wrapper is displayed
                        setTimeout(function() {
                            table.columns.adjust().draw(false);
                        }, 10); // Small delay to ensure display: block has rendered

                        // Removed the logic that hides/shows the message element
                    },
                    drawCallback: function(settings) {
                        const api = this.api();

                        // Removed the logic that hides/shows the message element

                        // Logic to add empty rows to fill the page
                        const rowsOnCurrentPage = api.rows({
                            page: 'current'
                        }).nodes().length;
                        const pageLength = api.page.len();
                        const tableId = '#' + settings.sTableId;
                        const currentBody = $(tableId + ' tbody');

                        currentBody.find('.empty-row').remove(); // Remove existing empty rows

                        if (rowsOnCurrentPage > 0 && rowsOnCurrentPage < pageLength) {
                            let emptyRows = pageLength - rowsOnCurrentPage;
                            let emptyRowHtml = '';
                            const csrfToken = $('meta[name="csrf-token"]').attr(
                                'content'); // Get CSRF token for dynamic forms

                            for (let i = 0; i < emptyRows; i++) {
                                emptyRowHtml += '<tr class="empty-row">';
                                // Data cells (invisible span)
                                emptyRowHtml +=
                                    '<td class="border px-4 py-2 text-sm text-gray-900 text-left"><span class="invisible">-</span></td>'; // Col 0
                                emptyRowHtml +=
                                    '<td class="border px-4 py-2 text-sm text-gray-900 text-left"><span class="invisible">-</span></td>'; // Col 1

                                // Actions cell (disabled buttons)
                                emptyRowHtml += `<td class="border px-4 py-2 text-center text-sm font-medium">
                                            <div class="flex space-x-2 justify-center">
                                                {{-- Botón Modificar (deshabilitado) --}}
                                                <button type="button" disabled
                                                    class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none cursor-not-allowed"
                                                    style="pointer-events: none;">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                {{-- Formulario y Botón Eliminar (deshabilitado) --}}
                                                <form action="#" method="POST" class="disabled-form">
                                                    <input type="hidden" name="_token" value="${csrfToken}">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="button" disabled
                                                        class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none cursor-not-allowed">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>`; // Col 2

                                emptyRowHtml += '</tr>';
                            }
                            currentBody.append(emptyRowHtml);
                        }
                    }
                });

                // --- Create Modal Handling ---
                const createModal = $('#create-payment-method-modal');
                const openCreateModalBtn = $('#create-payment-method-btn');
                const closeCreateModalBtn = $('#close-create-modal-btn');
                const createPaymentMethodForm = $('#create-payment-method-form');
                const createFormErrors = createPaymentMethodForm.find('.text-red-500');

                function showCreateModal() {
                    createModal.removeClass('hidden');
                }

                function hideCreateModal() {
                    createModal.addClass('hidden');
                    createPaymentMethodForm[0].reset();
                    createFormErrors.text('');
                }

                openCreateModalBtn.on('click', showCreateModal);
                closeCreateModalBtn.on('click', hideCreateModal);

                createModal.on('click', function(e) {
                    if ($(e.target).is(createModal)) {
                        hideCreateModal();
                    }
                });

                // --- Edit Modal Handling ---
                const editModal = $('#edit-payment-method-modal');
                const closeEditModalBtn = $('#close-edit-modal-btn');
                const editPaymentMethodForm = $('#edit-payment-method-form');
                const editFormErrors = editPaymentMethodForm.find('.text-red-500');
                const editMethodIdInput = $('#edit_method_id');
                const editBankNameInput = $('#edit_bank_name');
                const editAccountNumberInput = $('#edit_account_number');

                let currentEditingRow = null; // Variable to store the DataTables row being edited

                function showEditModal(methodData, row) {
                    editMethodIdInput.val(methodData.id);
                    editBankNameInput.val(methodData.bank_name); // Corrected: used bank_name
                    editAccountNumberInput.val(methodData.account_number);
                    editPaymentMethodForm.attr('action',
                        `/payment-methods/${methodData.id}`); // Set form action dynamically
                    currentEditingRow = row; // Store the DataTables row object
                    editModal.removeClass('hidden');
                }

                function hideEditModal() {
                    editModal.addClass('hidden');
                    editPaymentMethodForm[0].reset();
                    editFormErrors.text('');
                    currentEditingRow = null; // Clear the stored row
                }

                closeEditModalBtn.on('click', hideEditModal);

                editModal.on('click', function(e) {
                    if ($(e.target).is(editModal)) {
                        hideEditModal();
                    }
                });

                // Handle click on Edit button in the table
                $(document).on('click', '.edit-payment-method-btn', function() {
                    const methodId = $(this).data('id');
                    const bankName = $(this).data('bank-name');
                    const accountNumber = $(this).data('account-number');
                    const row = table.row($(this).closest('tr')); // Get the DataTables row object

                    // Pass the data to the showEditModal function
                    showEditModal({
                        id: methodId,
                        bank_name: bankName,
                        account_number: accountNumber
                    }, row);
                });

                // Handle Edit form submission via AJAX
                editPaymentMethodForm.on('submit', function(e) {
                    e.preventDefault();

                    const form = $(this);
                    const url = form.attr('action');
                    const formData = form.serialize();
                    const submitButton = form.find('#submit-edit-payment-method');

                    editFormErrors.text('');
                    submitButton.prop('disabled', true).text('Actualizando...');

                    $.ajax({
                        url: url,
                        method: 'POST', // Use POST for method spoofing
                        data: formData + '&_method=PUT', // Append _method=PUT to spoof the PUT request
                        success: function(response) {
                            const updatedMethod = response;

                            // Build the updated actions HTML (including Edit and Delete buttons)
                            const actionsHtml = `
                                <div class="flex space-x-2 justify-center">
                                    <button type="button"
                                        class="edit-payment-method-btn inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-700 text-white rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                        data-id="${updatedMethod.id}"
                                        data-bank-name="${updatedMethod.bank_name ?? ''}"
                                        data-account-number="${updatedMethod.account_number ?? ''}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="/payment-methods/${updatedMethod.id}" method="POST" class="delete-payment-method-form" data-id="${updatedMethod.id}" data-bank-name="${updatedMethod.bank_name ?? '-'}" data-account-number="${updatedMethod.account_number ?? '-'}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="delete-payment-method-btn inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-700 text-white rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            `;

                            // Update the row data in DataTables and redraw
                            if (currentEditingRow) {
                                currentEditingRow.data([
                                    updatedMethod.bank_name ?? '-', // Col 0
                                    updatedMethod.account_number ?? '-', // Col 1
                                    actionsHtml // Col 2 (HTML content)
                                ]).draw(false); // Use draw(false) to not re-sort/filter
                            }

                            hideEditModal(); // Close the modal on success
                            submitButton.prop('disabled', false).text('Actualizar');

                            // Optional: Show a success message using the message-modal
                            showMessageModal('Éxito', 'Método de pago actualizado correctamente.');
                        },
                        error: function(xhr) {
                            submitButton.prop('disabled', false).text('Actualizar');
                            if (xhr.status === 422) { // Validation errors
                                const errors = xhr.responseJSON.errors;
                                for (const field in errors) {
                                    $(`#edit_${field}_error`).text(errors[field][
                                        0
                                    ]); // Use correct IDs
                                }
                            } else {
                                // *** Puedes adaptar esto para usar el message-modal si lo necesitas ***
                                showMessageModal('Error',
                                    'Ocurrió un error al actualizar el método de pago.');
                                console.error(xhr.responseText);
                            }
                        }
                    });
                });


                // --- Delete Confirmation Modal Handling ---
                const deleteConfirmationModal = $('#delete-confirmation-modal');
                const confirmDeleteBtn = $('#confirm-delete-btn');
                const cancelDeleteModalBtn = $('#cancel-delete-modal-btn');
                const itemToDeleteDetails = $('#item-to-delete-details');

                let currentDeletingRow = null; // Variable to store the DataTables row being deleted

                // Function to show the delete confirmation modal
                function showDeleteConfirmationModal(bankName, accountNumber, deleteUrl, dataTableRaw) {
                    // Use .html() to render <br>
                    itemToDeleteDetails.html(`Banco: ${bankName}<br>Cuenta: ${accountNumber}`);
                    deleteConfirmationModal.data('delete-url', deleteUrl);
                    currentDeletingRow = dataTableRaw; // Store the DataTables row object
                    deleteConfirmationModal.removeClass('hidden');
                }

                // Function to hide the delete confirmation modal
                function hideDeleteConfirmationModal() {
                    deleteConfirmationModal.addClass('hidden');
                    deleteConfirmationModal.removeData('delete-url'); // Clear stored data on close
                    currentDeletingRow = null; // Clear the stored row
                    itemToDeleteDetails.text(''); // Clear details text
                }

                // Handle click on delete button in the table (shows the confirmation modal)
                $(document).on('submit', '.delete-payment-method-form', function(e) {
                    e.preventDefault(); // Prevent the default form submission

                    const form = $(this);
                    const deleteUrl = form.attr('action');
                    const bankName = form.data('bank-name'); // Get data attributes
                    const accountNumber = form.data('account-number');
                    const dataTableRaw = table.row($(this).closest('tr')); // Get the DataTables row object

                    showDeleteConfirmationModal(bankName, accountNumber, deleteUrl, dataTableRaw);
                });

                // Handle click on the "Eliminar" button inside the confirmation modal
                confirmDeleteBtn.on('click', function() {
                    const deleteUrl = deleteConfirmationModal.data('delete-url');

                    if (!deleteUrl || !currentDeletingRow) {
                        showMessageModal('Error', 'No se pudo obtener la información para eliminar.');
                        hideDeleteConfirmationModal();
                        return;
                    }

                    // Perform the AJAX deletion
                    $.ajax({
                        url: deleteUrl,
                        method: 'POST', // Use POST for method spoofing
                        data: {
                            _method: 'DELETE' // Spoof DELETE method
                            // CSRF token is handled by ajaxSetup
                        },
                        success: function(response) {
                            // Remove the row from the DataTable and redraw
                            currentDeletingRow.remove().draw(false);

                            // Check if the table is now empty after removing a row
                            // The emptyTable message from DataTables will handle this visually
                            // We don't need to hide/show the entire wrapper or a custom message


                            hideDeleteConfirmationModal(); // Hide the confirmation modal

                            showMessageModal('Éxito', 'Método de pago eliminado correctamente.');
                        },
                        error: function(xhr) {
                            hideDeleteConfirmationModal(); // Hide the modal even on error

                            let errorMessage = 'Ocurrió un error al eliminar el método de pago.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.status === 409 || xhr.status === 403) {
                                // Generic message if no specific server message for these codes
                                errorMessage =
                                    'No se puede eliminar el método de pago debido a restricciones.';
                            }
                            showMessageModal('Error', errorMessage);
                            console.error(xhr.responseText);
                        }
                    });
                });

                // Handle click on the "Cancelar" button in the delete modal
                cancelDeleteModalBtn.on('click', hideDeleteConfirmationModal);

                // Handle clicks outside the delete modal
                deleteConfirmationModal.on('click', function(e) {
                    if ($(e.target).is(deleteConfirmationModal)) {
                        hideDeleteConfirmationModal();
                    }
                });


                // --- AJAX Create Form Submission ---
                createPaymentMethodForm.on('submit', function(e) {
                    e.preventDefault();

                    const form = $(this);
                    const url = form.attr('action');
                    const formData = form.serialize();
                    const submitButton = form.find('#submit-create-payment-method');

                    createFormErrors.text('');
                    submitButton.prop('disabled', true).text('Guardando...');

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: formData,
                        // CSRF token is handled by ajaxSetup
                        success: function(response) {
                            const newMethod = response;
                            // We need to rebuild the actions HTML similar to the edit success callback
                            const actionsHtml = `
                                <div class="flex space-x-2 justify-center">
                                    <button type="button"
                                        class="edit-payment-method-btn inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-700 text-white rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                        data-id="${newMethod.id}"
                                        data-bank-name="${newMethod.bank_name ?? ''}"
                                        data-account-number="${newMethod.account_number ?? ''}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="/payment-methods/${newMethod.id}" method="POST" class="delete-payment-method-form" data-id="${newMethod.id}" data-bank-name="${newMethod.bank_name ?? '-'}" data-account-number="${newMethod.account_number ?? '-'}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="delete-payment-method-btn inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-700 text-white rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            `;

                            // Add the new row to the DataTable and redraw
                            table.row.add([
                                newMethod.bank_name ?? '-', // Col 0
                                newMethod.account_number ?? '-', // Col 1
                                actionsHtml // Col 2 (HTML content)
                            ]).draw(false); // Use draw(false) to not re-sort/filter

                            // After adding a row, ensure the table wrapper is visible
                            // This is already handled by initComplete, but doesn't hurt to be explicit
                            $('#payment-methods-datatable-wrapper').show().removeClass('opacity-0');
                            // We removed the custom message, so no need to hide it here


                            hideCreateModal(); // Close the modal on success
                            submitButton.prop('disabled', false).text('Guardar');

                            // Optional: Show a success message using the message-modal
                            showMessageModal('Éxito', 'Método de pago creado con éxito.');
                        },
                        error: function(xhr) {
                            submitButton.prop('disabled', false).text('Guardar');
                            if (xhr.status === 422) { // Validation errors
                                const errors = xhr.responseJSON.errors;
                                for (const field in errors) {
                                    $('#' + field + '_error').text(errors[field][0]);
                                }
                            } else {
                                showMessageModal('Error',
                                    'Ocurrió un error al crear el método de pago.');
                                console.error(xhr.responseText);
                            }
                        }
                    });
                });


                // --- Añadido Manejo del Modal de Mensaje Genérico ---
                const messageModal = $('#message-modal');
                const messageModalTitle = $('#message-modal-title');
                const messageModalBody = $('#message-modal-body');

                // Function to show the message modal
                function showMessageModal(title, message) {
                    messageModalTitle.text(title);
                    messageModalBody.html(message); // Use html() allows tags like <br>
                    messageModal.removeClass('hidden');

                    // Hide the modal automatically after 2 seconds
                    setTimeout(function() {
                        hideMessageModal();
                    }, 2000); // 2000 milliseconds = 2 seconds
                }

                // Function to hide the message modal
                function hideMessageModal() {
                    messageModal.addClass('hidden');
                    messageModalTitle.text('');
                    messageModalBody.text('');
                }

                // Allow closing the message modal by clicking outside of it
                messageModal.on('click', function(e) {
                    if ($(e.target).is(messageModal)) {
                        hideMessageModal();
                    }
                });
                // --- FIN Añadido Manejo del Modal de Mensaje Genérico ---


                // Keep global escape handler, but check which modal is open
                $(document).on('keydown', function(e) {
                    if (e.key === 'Escape') {
                        if (!createModal.hasClass('hidden')) {
                            hideCreateModal();
                        } else if (!editModal.hasClass('hidden')) { // Check edit modal
                            hideEditModal();
                        } else if (!deleteConfirmationModal.hasClass('hidden')) {
                            hideDeleteConfirmationModal();
                        } else if (!messageModal.hasClass('hidden')) { // Check message modal
                            hideMessageModal();
                        }
                    }
                });


                // --- Convert modal inputs to uppercase ---
                $('#bank_name, #account_number, #edit_bank_name, #edit_account_number').on('input', function() {
                    $(this).val($(this).val().toUpperCase());
                });
            });
        </script>
        {{-- Asegúrate de tener esta meta tag en tu layout principal para el token CSRF --}}
        {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
    @endpush
</x-app-layout>
