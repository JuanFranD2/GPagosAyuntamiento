<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight py-2">
                {{ __('Gestión de Usuarios') }} {{-- Título adaptado --}}
            </h2>
            {{-- Contenedor para el botón de instrucciones y el botón de crear --}}
            <div class="flex items-center space-x-2">
                {{-- Botón para mostrar las instrucciones (adaptado para usuarios) --}}
                <button type="button" id="show-user-instructions-modal-btn" {{-- ID adaptado --}}
                    class="inline-flex items-center px-4 py-2 border border-transparent text-lg leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                    <i class="fas fa-question-circle"></i> {{-- Icono de interrogación --}}
                </button>

                {{-- Botón para abrir el modal de crear nuevo usuario --}}
                <button id="create-user-btn" {{-- ID adaptado --}}
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                    {{ __('Nuevo Usuario') }} {{-- Texto adaptado --}}
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- DataTables mostrará su propio mensaje emptyTable si no hay datos. --}}

                    {{-- Contenedor con scroll vertical cuando hay muchas filas
                    Inicialmente oculto con CSS opacity: 0 para la transición suave --}}
                    <div id="users-datatable-wrapper" {{-- ID adaptado --}}
                        class="overflow-y-auto opacity-0 transition ease-in-out duration-500"
                        style="max-height: 500px;">
                        <table id="users-table" class="display w-full table-fixed"> {{-- ID adaptado --}}
                            <thead>
                                <tr>
                                    {{-- Headers adaptados --}}
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Nombre') }}</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Email') }}</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Rol') }}</th>
                                    {{-- Columna de Acciones --}}
                                    <th
                                        class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                {{-- Los usuarios se mostrarán aquí inicialmente con Blade --}}
                                @foreach ($users as $user)
                                    <tr>
                                        {{-- Datos de usuario --}}
                                        <td class="border px-4 py-2 text-sm text-gray-900 text-left">
                                            {{ $user->name ?? '-' }}</td>
                                        <td class="border px-4 py-2 text-sm text-gray-900 text-left">
                                            {{ $user->email ?? '-' }}</td>
                                        <td class="border px-4 py-2 text-sm text-gray-900 text-left">
                                            {{ $user->role ?? '-' }}</td>
                                        {{-- Celda de Acciones --}}
                                        <td class="border px-4 py-2 text-center text-sm font-medium">
                                            <div class="flex space-x-2 justify-center">
                                                {{-- Botón Modificar (Edit) - Data attributes adaptados --}}
                                                <button type="button"
                                                    class="edit-user-btn inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-700 text-white rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                    data-id="{{ $user->id }}" data-name="{{ $user->name ?? '' }}"
                                                    data-email="{{ $user->email ?? '' }}"
                                                    data-role="{{ $user->role ?? '' }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                {{-- Formulario y Botón Eliminar - Adaptado --}}
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                    class="delete-user-form" {{-- Clase adaptada --}}
                                                    data-id="{{ $user->id }}" data-name="{{ $user->name ?? '-' }}"
                                                    data-email="{{ $user->email ?? '-' }}"
                                                    data-role="{{ $user->role ?? '-' }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="delete-user-btn inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-700 text-white rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
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

    {{-- Modal Structure for Creating User --}}
    <div id="create-user-modal" {{-- ID adaptado --}}
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Crear Nuevo Usuario') }}</h3>
                <div class="mt-2 px-7 py-3">
                    <form id="create-user-form" action="{{ route('users.store') }}" method="POST">
                        {{-- ID y acción adaptados --}}
                        @csrf
                        <div class="mb-4">
                            <label for="name"
                                class="block text-sm font-medium text-gray-700 text-left">{{ __('Nombre') }}</label>
                            <input type="text" name="name" id="name" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="text-red-500 text-xs italic mt-1" id="name_error"></p> {{-- ID de error --}}
                        </div>
                        <div class="mb-4">
                            <label for="email"
                                class="block text-sm font-medium text-gray-700 text-left">{{ __('Email') }}</label>
                            <input type="email" name="email" id="email" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="text-red-500 text-xs italic mt-1" id="email_error"></p> {{-- ID de error --}}
                        </div>
                        {{-- Campos de Contraseña y Confirmación --}}
                        <div class="mb-4">
                            <label for="password"
                                class="block text-sm font-medium text-gray-700 text-left">{{ __('Contraseña') }}</label>
                            <input type="password" name="password" id="password" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="text-red-500 text-xs italic mt-1" id="password_error"></p> {{-- ID de error --}}
                        </div>
                        <div class="mb-4">
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-gray-700 text-left">{{ __('Confirmar Contraseña') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="text-red-500 text-xs italic mt-1" id="password_confirmation_error"></p>
                            {{-- ID de error --}}
                        </div>
                        <div class="mb-4">
                            <label for="role"
                                class="block text-sm font-medium text-gray-700 text-left">{{ __('Rol') }}</label>
                            {{-- *** MODIFICADO: Usamos SELECT para el rol *** --}}
                            <select name="role" id="role" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">{{ __('Seleccione un Rol') }}</option>
                                <option value="admin">Admin</option>
                                <option value="oper">Operador</option>
                            </select>
                            <p class="text-red-500 text-xs italic mt-1" id="role_error"></p> {{-- ID de error --}}
                        </div>

                        <div class="items-center px-4 py-3">
                            <button type="submit" id="submit-create-user" {{-- ID adaptado --}}
                                class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                {{ __('Guardar') }}
                            </button>
                            <button type="button" id="close-create-modal-btn" {{-- ID adaptado --}}
                                class="mt-3 px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2">
                                {{ __('Cancelar') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Structure for Editing User --}}
    <div id="edit-user-modal" {{-- ID adaptado --}}
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Editar Usuario') }}</h3>
                <div class="mt-2 px-7 py-3">
                    {{-- Note: Action and Method will be set dynamically by JavaScript --}}
                    <form id="edit-user-form" action="" method="POST"> {{-- ID adaptado --}}
                        @csrf
                        @method('PUT') {{-- Use PUT method for updates --}}
                        <input type="hidden" name="id" id="edit_user_id"> {{-- ID adaptado --}}

                        <div class="mb-4">
                            <label for="edit_name"
                                class="block text-sm font-medium text-gray-700 text-left">{{ __('Nombre') }}</label>
                            <input type="text" name="name" id="edit_name" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="text-red-500 text-xs italic mt-1" id="edit_name_error"></p>
                            {{-- ID de error --}}
                        </div>
                        <div class="mb-4">
                            <label for="edit_email"
                                class="block text-sm font-medium text-gray-700 text-left">{{ __('Email') }}</label>
                            <input type="email" name="email" id="edit_email" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="text-red-500 text-xs italic mt-1" id="edit_email_error"></p>
                            {{-- ID de error --}}
                        </div>
                        <div class="mb-4">
                            <label for="edit_role"
                                class="block text-sm font-medium text-gray-700 text-left">{{ __('Rol') }}</label>
                            {{-- *** MODIFICADO: Usamos SELECT para el rol *** --}}
                            <select name="role" id="edit_role" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">{{ __('Seleccione un Rol') }}</option>
                                <option value="admin">Admin</option>
                                <option value="oper">Operador</option>
                            </select>
                            <p class="text-red-500 text-xs italic mt-1" id="edit_role_error"></p>
                            {{-- ID de error --}}
                        </div>
                        {{-- Campos de Contraseña y Confirmación (Opcionales en edición) --}}
                        <div class="mb-4">
                            <label for="edit_password"
                                class="block text-sm font-medium text-gray-700 text-left">{{ __('Nueva Contraseña (Opcional)') }}</label>
                            <input type="password" name="password" id="edit_password"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="text-red-500 text-xs italic mt-1" id="edit_password_error"></p>
                            {{-- ID de error --}}
                        </div>
                        <div class="mb-4">
                            <label for="edit_password_confirmation"
                                class="block text-sm font-medium text-gray-700 text-left">{{ __('Confirmar Nueva Contraseña') }}</label>
                            <input type="password" name="password_confirmation" id="edit_password_confirmation"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="text-red-500 text-xs italic mt-1" id="edit_password_confirmation_error"></p>
                            {{-- ID de error --}}
                        </div>

                        <div class="items-center px-4 py-3">
                            <button type="submit" id="submit-edit-user" {{-- ID adaptado --}}
                                class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                {{ __('Actualizar') }}
                            </button>
                            <button type="button" id="close-edit-modal-btn" {{-- ID adaptado --}}
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
                        {{ __('¿Estás seguro de eliminar este usuario?') }} {{-- Texto adaptado --}}
                    </p>
                    {{-- Detalles del usuario a eliminar --}}
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

    {{-- Estructura del Modal de Instrucciones (Adaptado para Usuarios) --}}
    <div id="user-instructions-modal" {{-- ID adaptado --}}
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border max-w-3xl shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="user-instructions-modal-title">
                    {{ __('Instrucciones de Uso - Gestión de Usuarios') }}</h3> {{-- Título adaptado --}}
                {{-- CONTENEDOR DEL TEXTO DE INSTRUCCIONES CON SCROLL --}}
                <div class="mt-2 px-7 py-3 text-left modal-instructions-content">
                    <p class="text-sm text-gray-500" id="user-instructions-modal-body">
                        {{-- El texto de las instrucciones se insertará aquí via JS --}}
                    </p>
                </div>
                <div class="items-center px-4 py-3 mt-4 flex justify-end">
                    <button type="button" id="close-user-instructions-modal-btn" {{-- ID adaptado --}}
                        class="px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2">
                        {{ __('Cerrar') }}
                    </button>
                </div>
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

        {{-- CSS para el espacio entre el filtro y la tabla, ocultar la tabla inicialmente y estilos de inputs --}}
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
                overflow-y: auto;
                padding-right: 1rem;
            }

            /* Asegúrate de que el texto en los inputs de Nombre en mayúsculas se muestre correctamente */
            /* Excluir email y password, y ahora el select de rol */
            input[type="text"]#name,
            input[type="text"]#edit_name {
                text-transform: uppercase;
            }

            /* No aplicar text-transform a selects */
        </style>

        <script>
            $(document).ready(function() {
                // Ensure CSRF token is available
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                const table = $('#users-table').DataTable({ // ID adaptado
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
                        emptyTable: "{{ __('No existe ningún usuario') }}", // Mensaje adaptado
                        zeroRecords: "{{ __('No se encuentra ningún usuario con esa descripción') }}", // Mensaje adaptado
                    },
                    lengthChange: false,
                    pageLength: 5,
                    scrollY: '300px',
                    scrollCollapse: true,
                    paging: true,
                    columnDefs: [
                        // Columnas de datos (0: Nombre, 1: Email, 2: Rol)
                        {
                            targets: [0, 1, 2],
                            className: 'border px-4 py-2 text-sm text-gray-900 text-left'
                        },
                        // Columna 3: Acciones
                        {
                            targets: 3,
                            className: 'border px-4 py-2 text-center text-sm font-medium',
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

                        // Make the table wrapper visible once DataTables is ready
                        // Remove opacity-0 class for transition
                        $('#users-datatable-wrapper').removeClass('opacity-0'); // ID adaptado

                        // Adjust columns after ensuring the wrapper is displayed
                        setTimeout(function() {
                            table.columns.adjust().draw(false);
                        }, 10);
                    },
                    drawCallback: function(settings) {
                        const api = this.api();
                        const rowsOnCurrentPage = api.rows({
                            page: 'current'
                        }).nodes().length;
                        const pageLength = api.page.len();
                        const tableId = '#' + settings.sTableId;
                        const currentBody = $(tableId + ' tbody');

                        currentBody.find('.empty-row').remove(); // Remove existing empty rows

                        // Only add empty rows if there are SOME filtered results and not a full page
                        if (rowsOnCurrentPage > 0 && rowsOnCurrentPage < pageLength) {
                            let emptyRows = pageLength - rowsOnCurrentPage;
                            let emptyRowHtml = '';
                            const csrfToken = $('meta[name="csrf-token"]').attr('content');

                            for (let i = 0; i < emptyRows; i++) {
                                emptyRowHtml += '<tr class="empty-row">';
                                // Data cells (invisible span) - 3 columns for data
                                emptyRowHtml +=
                                    '<td class="border px-4 py-2 text-sm text-gray-900 text-left"><span class="invisible">-</span></td>'; // Col 0
                                emptyRowHtml +=
                                    '<td class="border px-4 py-2 text-sm text-gray-900 text-left"><span class="invisible">-</span></td>'; // Col 1
                                emptyRowHtml +=
                                    '<td class="border px-4 py-2 text-sm text-gray-900 text-left"><span class="invisible">-</span></td>'; // Col 2


                                // Actions cell (disabled buttons) - 1 column for actions
                                emptyRowHtml += `<td class="border px-4 py-2 text-center text-sm font-medium">
                                                <div class="flex space-x-2 justify-center">
                                                    <button type="button" disabled
                                                        class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none cursor-not-allowed"
                                                        style="pointer-events: none;">
                                                        <i class="fas fa-edit"></i>
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
                                            </td>`; // Col 3

                                emptyRowHtml += '</tr>';
                            }
                            currentBody.append(emptyRowHtml);
                        }
                    }
                });

                // --- Create Modal Handling ---
                const createModal = $('#create-user-modal'); // ID adaptado
                const openCreateModalBtn = $('#create-user-btn'); // ID adaptado
                const closeCreateModalBtn = $('#close-create-modal-btn');
                const createUserForm = $('#create-user-form'); // ID adaptado
                // Seleccionar todos los elementos con clases de error dentro del formulario de creación
                const createFormErrors = createUserForm.find('.text-red-500');


                function showCreateModal() {
                    createModal.removeClass('hidden');
                }

                function hideCreateModal() {
                    createModal.addClass('hidden');
                    createUserForm[0].reset();
                    // Limpiar mensajes de error al cerrar el modal
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
                const editModal = $('#edit-user-modal'); // ID adaptado
                const closeEditModalBtn = $('#close-edit-modal-btn');
                const editUserForm = $('#edit-user-form'); // ID adaptado
                // Seleccionar todos los elementos con clases de error dentro del formulario de edición
                const editFormErrors = editUserForm.find('.text-red-500');

                const editUserIdInput = $('#edit_user_id'); // ID adaptado
                const editNameInput = $('#edit_name'); // ID adaptado
                const editEmailInput = $('#edit_email'); // ID adaptado
                const editRoleSelect = $('#edit_role'); // *** MODIFICADO: Referencia al SELECT ***
                const editPasswordInput = $('#edit_password'); // ID adaptado
                const editPasswordConfirmationInput = $('#edit_password_confirmation'); // ID adaptado


                let currentEditingRow = null; // Variable to store the DataTables row being edited

                function showEditModal(userData, row) {
                    editUserIdInput.val(userData.id);
                    editNameInput.val(userData.name);
                    editEmailInput.val(userData.email);
                    editRoleSelect.val(userData.role); // *** MODIFICADO: Establecer valor en SELECT ***
                    // Limpiar campos de contraseña al abrir el modal de edición
                    editPasswordInput.val('');
                    editPasswordConfirmationInput.val('');
                    // Limpiar errores previos
                    editFormErrors.text('');

                    editUserForm.attr('action',
                        `/users/${userData.id}`); // Set form action dynamically (ruta adaptada)
                    currentEditingRow = row; // Store the DataTables row object
                    editModal.removeClass('hidden');
                }

                function hideEditModal() {
                    editModal.addClass('hidden');
                    editUserForm[0].reset();
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
                $(document).on('click', '.edit-user-btn', function() { // Clase adaptada
                    const userId = $(this).data('id');
                    // Obtener otros datos del usuario de los data attributes
                    const userName = $(this).data('name');
                    const userEmail = $(this).data('email');
                    const userRole = $(this).data('role');

                    const row = table.row($(this).closest('tr')); // Get the DataTables row object

                    // Pass the data to the showEditModal function
                    showEditModal({
                        id: userId,
                        name: userName,
                        email: userEmail,
                        role: userRole
                    }, row);
                });

                // Handle Edit form submission via AJAX
                editUserForm.on('submit', function(e) { // ID adaptado
                    e.preventDefault();

                    const form = $(this);
                    const url = form.attr('action');
                    const formData = form.serialize();
                    const submitButton = form.find('#submit-edit-user'); // ID adaptado

                    // Limpiar errores previos antes de la nueva solicitud
                    editFormErrors.text('');
                    submitButton.prop('disabled', true).text('Actualizando...');

                    $.ajax({
                        url: url,
                        method: 'POST', // Use POST for method spoofing
                        data: formData + '&_method=PUT', // Append _method=PUT to spoof the PUT request
                        success: function(response) {
                            const updatedUser = response;

                            // Build the updated actions HTML (including Edit and Delete buttons) - Rutas adaptadas
                            const actionsHtml = `
                                <div class="flex space-x-2 justify-center">
                                    <button type="button"
                                        class="edit-user-btn inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-700 text-white rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                        data-id="${updatedUser.id}"
                                        data-name="${updatedUser.name ?? ''}"
                                        data-email="${updatedUser.email ?? ''}"
                                        data-role="${updatedUser.role ?? ''}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="/users/${updatedUser.id}" method="POST" class="delete-user-form" data-id="${updatedUser.id}" data-name="${updatedUser.name ?? '-'}" data-email="${updatedUser.email ?? '-'}" data-role="${updatedUser.role ?? '-'}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="delete-user-btn inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-700 text-white rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            `;

                            // Update the row data in DataTables and redraw
                            if (currentEditingRow) {
                                currentEditingRow.data([
                                    updatedUser.name ?? '-', // Col 0
                                    updatedUser.email ?? '-', // Col 1
                                    updatedUser.role ?? '-', // Col 2
                                    actionsHtml // Col 3 (HTML content)
                                ]).draw(false); // Use draw(false) to not re-sort/filter
                            }

                            hideEditModal(); // Close the modal on success
                            submitButton.prop('disabled', false).text('Actualizar');

                            // Show a success message using the message-modal
                            showMessageModal('Éxito', 'Usuario actualizado correctamente.');
                        },
                        // *** Lógica para mostrar errores de validación en el modal de edición ***
                        error: function(xhr) {
                            submitButton.prop('disabled', false).text('Actualizar');
                            if (xhr.status === 422) { // Validation errors from Laravel
                                const errors = xhr.responseJSON.errors;
                                // Iterar sobre los errores y mostrarlos
                                for (const field in errors) {
                                    // Usar los IDs de los elementos de error del modal de edición (ej: #edit_name_error)
                                    $(`#edit_${field}_error`).text(errors[field][0]);
                                }
                            } else {
                                // Mostrar un mensaje genérico para otros tipos de error
                                showMessageModal('Error',
                                    'Ocurrió un error al actualizar el usuario.');
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
                function showDeleteConfirmationModal(userName, userEmail, deleteUrl, dataTableRaw) {
                    // Usar .html() para renderizar <br> y mostrar detalles del usuario
                    itemToDeleteDetails.html(`Nombre: ${userName}<br>Email: ${userEmail}`); // Detalles adaptados
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
                $(document).on('submit', '.delete-user-form', function(e) { // Clase adaptada
                    e.preventDefault(); // Prevent the default form submission

                    const form = $(this);
                    const deleteUrl = form.attr('action');
                    // Obtener datos del usuario de los data attributes
                    const userName = form.data('name');
                    const userEmail = form.data('email');
                    const dataTableRaw = table.row($(this).closest('tr')); // Get the DataTables row object

                    showDeleteConfirmationModal(userName, userEmail, deleteUrl,
                    dataTableRaw); // Datos adaptados
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

                            hideDeleteConfirmationModal(); // Hide the confirmation modal

                            showMessageModal('Éxito',
                            'Usuario eliminado correctamente.'); // Mensaje adaptado
                        },
                        error: function(xhr) {
                            hideDeleteConfirmationModal(); // Hide the modal even on error

                            let errorMessage =
                            'Ocurrió un error al eliminar el usuario.'; // Mensaje adaptado
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.status === 403) { // Check for 403 Forbidden
                                errorMessage =
                                    'No tienes permiso para realizar esta acción (posiblemente intentas borrar tu propio usuario).'; // Mensaje específico si es relevante
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
                createUserForm.on('submit', function(e) { // ID adaptado
                    e.preventDefault();

                    const form = $(this);
                    const url = form.attr('action');
                    const formData = form.serialize();
                    const submitButton = form.find('#submit-create-user'); // ID adaptado

                    // Limpiar errores previos antes de la nueva solicitud
                    createFormErrors.text('');
                    submitButton.prop('disabled', true).text('Guardando...');

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: formData,
                        // CSRF token is handled by ajaxSetup
                        success: function(response) {
                            const newUser = response;
                            // Rebuild the actions HTML for the new row - Rutas adaptadas
                            const actionsHtml = `
                                <div class="flex space-x-2 justify-center">
                                    <button type="button"
                                        class="edit-user-btn inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-700 text-white rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                        data-id="${newUser.id}"
                                        data-name="${newUser.name ?? ''}"
                                        data-email="${newUser.email ?? ''}"
                                        data-role="${newUser.role ?? ''}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="/users/${newUser.id}" method="POST" class="delete-user-form" data-id="${newUser.id}" data-name="${newUser.name ?? '-'}" data-email="${newUser.email ?? '-'}" data-role="${newUser.role ?? '-'}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="delete-user-btn inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-700 text-white rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            `;

                            // Add the new row to the DataTable and redraw
                            table.row.add([
                                newUser.name ?? '-', // Col 0
                                newUser.email ?? '-', // Col 1
                                newUser.role ?? '-', // Col 2
                                actionsHtml // Col 3 (HTML content)
                            ]).draw(false); // Use draw(false) to not re-sort/filter

                            // Ensure the table wrapper is visible after adding the first row
                            $('#users-datatable-wrapper').removeClass('opacity-0'); // ID adaptado

                            hideCreateModal(); // Close the modal on success
                            submitButton.prop('disabled', false).text('Guardar');

                            // Show a success message using the message-modal
                            showMessageModal('Éxito',
                            'Usuario creado con éxito.'); // Mensaje adaptado
                        },
                        // *** Lógica para mostrar errores de validación en el modal de creación ***
                        error: function(xhr) {
                            submitButton.prop('disabled', false).text('Guardar');
                            if (xhr.status === 422) { // Validation errors from Laravel
                                const errors = xhr.responseJSON.errors;
                                // Iterar sobre los errores y mostrarlos
                                for (const field in errors) {
                                    // Usar los IDs de los elementos de error del modal de creación (ej: #name_error)
                                    $(`#${field}_error`).text(errors[field][0]);
                                }
                            } else {
                                // Mostrar un mensaje genérico para otros tipos de error
                                showMessageModal('Error',
                                    'Ocurrió un error al crear el usuario.');
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


                // --- Manejo del Modal de Instrucciones de Usuario ---
                const userInstructionsModal = $('#user-instructions-modal'); // ID adaptado
                const showUserInstructionsModalBtn = $('#show-user-instructions-modal-btn'); // ID adaptado
                const closeUserInstructionsModalBtn = $('#close-user-instructions-modal-btn'); // ID adaptado
                const userInstructionsModalBody = $('#user-instructions-modal-body'); // ID adaptado

                // Texto de las instrucciones específico para Usuarios
                const userInstructionsText = ` {{-- Texto adaptado --}}
                    <p>Esta guía te ayudará a usar la sección de Gestión de Usuarios.</p>
                    <h4 class="font-semibold mt-3 mb-1 text-gray-800">1. Ver tus Usuarios</h4>
                    <p>Al entrar, verás una lista de los usuarios registrados en una tabla con su nombre, email y rol.</p>
                    <p>Usa el campo "Buscar:" para encontrar usuarios rápidamente escribiendo parte de su nombre, email o rol.</p>
                    <p>Navega entre páginas con los botones de paginación debajo de la tabla.</p>
                    <h4 class="font-semibold mt-3 mb-1 text-gray-800">2. Crear un Nuevo Usuario</h4>
                    <p>Haz clic en el botón "<strong class="text-green-600">Nuevo Usuario</strong>" para abrir un formulario. Rellena los datos requeridos (nombre, email, contraseña, confirmación de contraseña y rol) y guarda los cambios.</p>
                     <p class="mt-3 text-gray-600">**Nota:** Asegúrate de asignar un rol válido (admin u oper) para que el usuario tenga los permisos correctos.</p> {{-- Instrucción añadida si es relevante --}}
                    <h4 class="font-semibold mt-3 mb-1 text-gray-800">3. Acciones Disponibles</h4>
                    <p>En la columna de acciones de cada usuario, encontrarás:</p>
                    <ul class="list-disc list-inside ml-4 text-gray-700">
                        <li><i class="fas fa-edit"></i> (Amarillo): **Modificar.** Haz clic aquí para editar los datos del usuario. Puedes cambiar la contraseña si lo necesitas.</li>
                        <li><i class="fas fa-trash-alt"></i> (Rojo): **Eliminar.** Haz clic aquí para borrar el usuario.</li>
                    </ul>
                    <h4 class="font-semibold mt-3 mb-1 text-gray-800">4. Eliminar un Usuario</h4>
                    <p>Si haces clic en el botón <i class="fas fa-trash-alt"></i>, aparecerá una ventana para confirmar que quieres eliminarlo. Confirma o cancela según corresponda.</p>
                     <p class="mt-3 text-red-600">**Advertencia:** No podrás eliminar tu propio usuario si has implementado esa restricción.</p> {{-- Advertencia añadida si es relevante --}}
                    <h4 class="font-semibold mt-3 mb-1 text-gray-800">5. Mensajes del Sistema</h4>
                    <p>Las ventanas pequeñas te informan (éxito o error) y se cierran solas.</p>
                    <p class="mt-3 text-gray-600"><strong>Consejo Rápido:</strong> Cierra cualquier ventana emergente presionando la tecla <code>Escape</code>.</p>
                `;


                // Función para mostrar el modal de instrucciones
                function showUserInstructionsModal() { // Función adaptada
                    userInstructionsModalBody.html(
                        userInstructionsText); // Inserta el texto HTML de las instrucciones
                    userInstructionsModal.removeClass('hidden');
                }

                // Función para ocultar el modal de instrucciones
                function hideUserInstructionsModal() { // Función adaptada
                    userInstructionsModal.addClass('hidden');
                    userInstructionsModalBody.html(''); // Limpia el contenido del cuerpo al cerrar
                }

                // Manejar clic en el botón de instrucciones
                showUserInstructionsModalBtn.on('click', showUserInstructionsModal);

                // Manejar clic en el botón "Cerrar" dentro del modal de instrucciones
                closeUserInstructionsModalBtn.on('click', hideUserInstructionsModal);

                // Manejar clics fuera del modal de instrucciones
                userInstructionsModal.on('click', function(e) { // ID adaptado
                    if ($(e.target).is(userInstructionsModal)) {
                        hideUserInstructionsModal();
                    }
                });
                // --- FIN Manejo del Modal de Instrucciones de Usuario ---


                // Keep global escape handler, but check which modal is open
                $(document).on('keydown', function(e) {
                    if (e.key === 'Escape') {
                        if (!createModal.hasClass('hidden')) {
                            hideCreateModal();
                        } else if (!editModal.hasClass('hidden')) {
                            hideEditModal();
                        } else if (!deleteConfirmationModal.hasClass('hidden')) {
                            hideDeleteConfirmationModal();
                        } else if (!messageModal.hasClass('hidden')) {
                            hideMessageModal();
                        } else if (!userInstructionsModal.hasClass( // ID adaptado
                                'hidden')) {
                            hideUserInstructionsModal();
                        }
                    }
                });

                // --- Convert specific modal inputs to uppercase ---
                // Aplicar solo a los campos 'name', no a 'email', 'password', o el select 'role'
                $('#name, #edit_name').on('input', function() { // Selectores actualizados
                    $(this).val($(this).val().toUpperCase());
                });

            });
        </script>
        {{-- Asegúrate de tener esta meta tag en tu layout principal para el token CSRF --}}
        {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
    @endpush
</x-app-layout>
