<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight py-2">
                {{ __('Gestión de Usuarios') }}
            </h2>
            {{-- Botón para abrir el modal de crear nuevo usuario --}}
            <button id="create-user-btn"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                {{ __('Nuevo Usuario') }}
            </button>
        </div>
    </x-slot>

    {{-- Área de contenido principal --}}
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Mensaje que se muestra cuando no hay usuarios --}}
                    @if ($users->isEmpty())
                        <p id="no-users-message">{{ __('No hay usuarios registrados.') }}</p>
                    @else
                        {{-- Este mensaje será ocultado por DataTables si hay datos --}}
                        <p id="no-users-message" class="hidden">
                            {{ __('No hay usuarios registrados.') }}</p>
                    @endif

                    {{-- Contenedor con scroll vertical cuando hay muchas filas --}}
                    {{-- Inicialmente oculta el contenedor, DataTables lo mostrará en initComplete si hay datos --}}
                    <div id="users-datatable-wrapper" class="opacity-0 transition-opacity duration-300 overflow-y-auto"
                        style="max-height: 500px; @if ($users->isEmpty()) display: none; @endif">
                        <table id="users-table" class="display w-full table-fixed">
                            <thead>
                                <tr>
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
                                {{-- Los usuarios se cargarán aquí por DataTables o se mostrarán inicialmente con Blade --}}
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="border px-4 py-2 text-sm text-gray-900 text-left">
                                            {{ $user->name ?? '-' }}</td>
                                        <td class="border px-4 py-2 text-sm text-gray-900 text-left">
                                            {{ $user->email ?? '-' }}</td>
                                        <td class="border px-4 py-2 text-sm text-gray-900 text-left">
                                            {{ ucfirst($user->role) ?? '-' }}</td> {{-- Capitalizar el rol --}}
                                        {{-- Celda de Acciones --}}
                                        <td class="border px-4 py-2 text-center text-sm font-medium">
                                            <div class="flex space-x-2 justify-center">
                                                {{-- Botón Modificar --}}
                                                <button type="button"
                                                    class="edit-user-btn inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-700 text-white rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                    data-id="{{ $user->id }}" data-name="{{ $user->name ?? '' }}"
                                                    data-email="{{ $user->email ?? '' }}"
                                                    data-role="{{ $user->role ?? '' }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                {{-- Formulario y Botón Eliminar --}}
                                                {{-- Verifica si el usuario no es el usuario autenticado actualmente antes de permitir la eliminación --}}
                                                @if (auth()->check() && auth()->user()->id !== $user->id)
                                                    <form action="{{ route('users.destroy', $user->id) }}"
                                                        method="POST" class="delete-user-form"
                                                        data-id="{{ $user->id }}"
                                                        data-name="{{ $user->name ?? '-' }}"
                                                        data-email="{{ $user->email ?? '-' }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="delete-user-btn inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-700 text-white rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    {{-- Deshabilita el botón de eliminar para el usuario autenticado --}}
                                                    <button type="button" disabled
                                                        class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none cursor-not-allowed"
                                                        style="pointer-events: none;">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                @endif
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

    {{-- Estructura del Modal para Crear Usuario --}}
    <div id="create-user-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Crear Nuevo Usuario') }}</h3>
                <div class="mt-2 px-7 py-3">
                    <form id="create-user-form" action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name"
                                class="block text-sm font-medium text-gray-700 text-left">{{ __('Nombre') }}</label>
                            <input type="text" name="name" id="name" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="text-red-500 text-xs italic mt-1" id="name_error"></p>
                        </div>
                        <div class="mb-4">
                            <label for="email"
                                class="block text-sm font-medium text-gray-700 text-left">{{ __('Email') }}</label>
                            <input type="email" name="email" id="email" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="text-red-500 text-xs italic mt-1" id="email_error"></p>
                        </div>
                        <div class="mb-4">
                            <label for="role"
                                class="block text-sm font-medium text-gray-700 text-left">{{ __('Rol') }}</label>
                            <select name="role" id="role" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="admin">{{ __('Admin') }}</option>
                                <option value="oper">{{ __('Operador') }}</option>
                            </select>
                            <p class="text-red-500 text-xs italic mt-1" id="role_error"></p>
                        </div>
                        <div class="mb-4">
                            <label for="password"
                                class="block text-sm font-medium text-gray-700 text-left">{{ __('Contraseña') }}</label>
                            <input type="password" name="password" id="password" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="text-red-500 text-xs italic mt-1" id="password_error"></p>
                        </div>
                        <div class="mb-4">
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-gray-700 text-left">{{ __('Confirmar Contraseña') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="text-red-500 text-xs italic mt-1" id="password_confirmation_error"></p>
                        </div>
                        <div class="items-center px-4 py-3">
                            <button type="submit" id="submit-create-user"
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

    {{-- Estructura del Modal para Editar Usuario --}}
    <div id="edit-user-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Editar Usuario') }}</h3>
                <div class="mt-2 px-7 py-3">
                    {{-- Nota: Action y Method se establecerán dinámicamente por JavaScript --}}
                    <form id="edit-user-form" action="" method="POST">
                        @csrf
                        @method('PUT') {{-- Usa el método PUT para actualizaciones --}}
                        <input type="hidden" name="id" id="edit_user_id"> {{-- Campo oculto para el ID --}}

                        <div class="mb-4">
                            <label for="edit_name"
                                class="block text-sm font-medium text-gray-700 text-left">{{ __('Nombre') }}</label>
                            <input type="text" name="name" id="edit_name" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="text-red-500 text-xs italic mt-1" id="edit_name_error"></p>
                        </div>
                        <div class="mb-4">
                            <label for="edit_email"
                                class="block text-sm font-medium text-gray-700 text-left">{{ __('Email') }}</label>
                            <input type="email" name="email" id="edit_email" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="text-red-500 text-xs italic mt-1" id="edit_email_error"></p>
                        </div>
                        <div class="mb-4">
                            <label for="edit_role"
                                class="block text-sm font-medium text-gray-700 text-left">{{ __('Rol') }}</label>
                            <select name="role" id="edit_role" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="admin">{{ __('Admin') }}</option>
                                <option value="oper">{{ __('Operador') }}</option>
                            </select>
                            <p class="text-red-500 text-xs italic mt-1" id="edit_role_error"></p>
                        </div>
                        <div class="mb-4">
                            <label for="edit_password"
                                class="block text-sm font-medium text-gray-700 text-left">{{ __('Nueva Contraseña (Opcional)') }}</label>
                            <input type="password" name="password" id="edit_password"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="text-red-500 text-xs italic mt-1" id="edit_password_error"></p>
                        </div>
                        <div class="mb-4">
                            <label for="edit_password_confirmation"
                                class="block text-sm font-medium text-gray-700 text-left">{{ __('Confirmar Nueva Contraseña') }}</label>
                            <input type="password" name="password_confirmation" id="edit_password_confirmation"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="text-red-500 text-xs italic mt-1" id="edit_password_confirmation_error"></p>
                        </div>
                        <div class="items-center px-4 py-3">
                            <button type="submit" id="submit-edit-user"
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


    {{-- Estructura del Modal para Confirmar Eliminación --}}
    <div id="delete-confirmation-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Confirmar Eliminación') }}</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 mb-4" id="delete-confirmation-message">
                        {{ __('¿Estás seguro de eliminar este usuario?') }}
                    </p>
                    {{-- Opcional: Añadir detalles sobre el elemento que se va a eliminar --}}
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

    {{-- *** ESTRUCTURA MODIFICADA DEL MODAL DE MENSAJE GENÉRICO (SIN BOTÓN DE CERRAR) *** --}}
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
                {{-- EL BOTÓN DE CERRAR HA SIDO ELIMINADO --}}
                {{-- <div class="items-center px-4 py-3 mt-4">
                    <button type="button" id="close-message-modal-btn"
                        class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        {{ __('Cerrar') }}
                    </button>
                </div> --}}
            </div>
        </div>
    </div>
    {{-- *** FIN ESTRUCTURA MODIFICADA DEL MODAL DE MENSAJE GENÉRICO *** --}}


    @push('scripts')
        {{-- Incluir CSS de Font Awesome --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        {{-- Incluir CSS de DataTables --}}
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

        {{-- Incluir jQuery (si no está ya incluido en tu layout principal) --}}
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.0.js"></script>
        {{-- Incluir JS de DataTables --}}
        <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

        {{-- CSS para el espacio entre el filtro y la tabla --}}
        <style>
            /* Añade un margen inferior al contenedor del filtro de búsqueda de DataTables */
            .dataTables_filter {
                margin-bottom: 10px;
                /* Ajusta este valor según necesites un espacio mayor o menor */
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

                const table = $('#users-table').DataTable({
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
                        emptyTable: "{{ __('No existen usuarios') }}",
                        zeroRecords: "{{ __('No se encuentra ningún usuario con esa descripción') }}",
                    },
                    lengthChange: false, // Esta opción evita que el usuario cambie el número de filas por página
                    pageLength: 5, // Aquí se establece el número de filas por página por defecto
                    scrollY: '300px',
                    scrollCollapse: true,
                    paging: true,
                    columnDefs: [
                        // Columna 0: Nombre
                        {
                            targets: 0,
                            className: 'border px-4 py-2 text-sm text-gray-900 text-left'
                        },
                        // Columna 1: Email
                        {
                            targets: 1,
                            className: 'border px-4 py-2 text-sm text-gray-900 text-left'
                        },
                        // Columna 2: Rol
                        {
                            targets: 2,
                            className: 'border px-4 py-2 text-sm text-gray-900 text-left'
                        },
                        // Columna 3: Acciones
                        {
                            targets: 3,
                            className: 'border px-4 py-2 text-center text-sm font-medium'
                        },
                        // Deshabilitar ordenación y búsqueda para la columna de acciones
                        {
                            targets: 3,
                            orderable: false,
                            searchable: false
                        }
                    ],
                    // Ordenar por la columna Nombre (0) ascendente por defecto
                    order: [
                        [0, 'asc']
                    ],
                    stateSave: true, // Mantener stateSave: true si necesitas guardar el estado (pero gestionaremos la paginación)
                    initComplete: function() {
                        const api = this.api();

                        // *** MODIFICACIÓN PARA FORZAR LA LONGITUD DE PÁGINA A 5 ***
                        // Esto se ejecuta después de que DataTables se inicializa y carga el estado guardado
                        if (api.page.len() !== 5) {
                            api.page.len(5).draw(
                                false
                            ); // Establece la longitud de página a 5 y redibuja sin cambiar orden/filtrado
                        }
                        // *******************************************************


                        if (api.data().count() > 0) {
                            $('#users-datatable-wrapper').removeClass('opacity-0').show();
                            $('#no-users-message').hide();
                        } else {
                            $('#users-datatable-wrapper').hide();
                            $('#no-users-message').show();
                        }
                    },
                    drawCallback: function(settings) {
                        const api = this.api();
                        if (api.data().count() > 0) {
                            $('#no-users-message').hide();
                            $('#users-datatable-wrapper').show().removeClass('opacity-0');
                        } else {
                            $('#no-users-message').show();
                            $('#users-datatable-wrapper').hide();
                        }

                        // Lógica para añadir filas vacías para rellenar la página
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
                                'content'); // Obtiene el token CSRF para formularios dinámicos

                            for (let i = 0; i < emptyRows; i++) {
                                emptyRowHtml += '<tr class="empty-row">';
                                // Celdas de datos (span invisible) - asegura suficientes celdas para todas las columnas
                                emptyRowHtml +=
                                    '<td class="border px-4 py-2 text-sm text-gray-900 text-left"><span class="invisible">-</span></td>'; // Col 0
                                emptyRowHtml +=
                                    '<td class="border px-4 py-2 text-sm text-gray-900 text-left"><span class="invisible">-</span></td>'; // Col 1
                                emptyRowHtml +=
                                    '<td class="border px-4 py-2 text-sm text-gray-900 text-left"><span class="invisible">-</span></td>'; // Col 2


                                // Celda de acciones (botones deshabilitados)
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
                                        </td>`; // Col 3

                                emptyRowHtml += '</tr>';
                            }
                            currentBody.append(emptyRowHtml);
                        }
                    }
                });

                // --- Manejo del Modal de Creación ---
                const createModal = $('#create-user-modal');
                const openCreateModalBtn = $('#create-user-btn');
                const closeCreateModalBtn = $('#close-create-modal-btn');
                const createUserForm = $('#create-user-form');
                const createFormErrors = createUserForm.find(
                    '.text-red-500'); // Selecciona todos los párrafos de error dentro del formulario

                function showCreateModal() {
                    createModal.removeClass('hidden');
                }

                function hideCreateModal() {
                    createModal.addClass('hidden');
                    createUserForm[0].reset(); // Reinicia los campos del formulario
                    createFormErrors.text(''); // Limpia los errores anteriores
                }

                openCreateModalBtn.on('click', showCreateModal);
                closeCreateModalBtn.on('click', hideCreateModal);

                createModal.on('click', function(e) {
                    if ($(e.target).is(createModal)) {
                        hideCreateModal();
                    }
                });


                // --- Manejo del Modal de Edición ---
                const editModal = $('#edit-user-modal');
                const closeEditModalBtn = $('#close-edit-modal-btn');
                const editUserForm = $('#edit-user-form');
                const editFormErrors = editUserForm.find(
                    '.text-red-500'); // Selecciona todos los párrafos de error dentro del formulario
                const editUserIdInput = $('#edit_user_id');
                const editNameInput = $('#edit_name');
                const editEmailInput = $('#edit_email');
                const editRoleSelect = $('#edit_role');
                const editPasswordInput = $('#edit_password');
                const editPasswordConfirmationInput = $('#edit_password_confirmation');

                let currentEditingRow = null; // Variable para almacenar la fila de DataTables que se está editando

                function showEditModal(userData, row) {
                    editUserIdInput.val(userData.id);
                    editNameInput.val(userData.name);
                    editEmailInput.val(userData.email);
                    editRoleSelect.val(userData.role); // Establece la opción seleccionada en el desplegable
                    editPasswordInput.val(''); // Limpia los campos de contraseña
                    editPasswordConfirmationInput.val(''); // Limpia los campos de confirmación de contraseña

                    editUserForm.attr('action',
                        `/users/${userData.id}`); // Establece la acción del formulario dinámicamente

                    currentEditingRow = row; // Almacena el objeto de la fila de DataTables
                    editModal.removeClass('hidden');
                }

                function hideEditModal() {
                    editModal.addClass('hidden');
                    editUserForm[0].reset(); // Reinicia los campos del formulario
                    editFormErrors.text(''); // Limpia los errores anteriores
                    currentEditingRow = null; // Limpia la fila almacenada
                }

                closeEditModalBtn.on('click', hideEditModal);

                editModal.on('click', function(e) {
                    if ($(e.target).is(editModal)) {
                        hideEditModal();
                    }
                });

                // Manejar clic en el botón Editar de la tabla
                $(document).on('click', '.edit-user-btn', function() {
                    const userId = $(this).data('id');
                    const userName = $(this).data('name');
                    const userEmail = $(this).data('email');
                    const userRole = $(this).data('role');
                    const row = table.row($(this).closest('tr')); // Obtiene el objeto de la fila de DataTables

                    // Pasa los datos a la función showEditModal
                    showEditModal({
                        id: userId,
                        name: userName,
                        email: userEmail,
                        role: userRole
                    }, row);
                });

                // Manejar el envío del formulario de edición mediante AJAX
                editUserForm.on('submit', function(e) {
                    e.preventDefault();

                    const form = $(this);
                    const url = form.attr('action');
                    const formData = form.serialize();
                    const submitButton = form.find('#submit-edit-user');

                    editFormErrors.text(''); // Limpia los errores anteriores
                    submitButton.prop('disabled', true).text('Actualizando...');

                    $.ajax({
                        url: url,
                        method: 'POST', // Usa POST para simular el método
                        data: formData +
                            '&_method=PUT', // Añade _method=PUT para simular la petición PUT
                        success: function(response) {
                            const updatedUser = response;

                            // Construye el HTML de las acciones actualizadas (incluidos los botones de Editar y Eliminar)
                            // Necesita verificar si el usuario es el autenticado para deshabilitar la eliminación
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
                                    ${updatedUser.id !== {{ auth()->id() }} ? `
                                                    <form action="/users/${updatedUser.id}" method="POST" class="delete-user-form" data-id="${updatedUser.id}" data-name="${updatedUser.name ?? '-'}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="delete-user-btn inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-700 text-white rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                    ` : `
                                                    <button type="button" disabled
                                                        class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none cursor-not-allowed"
                                                        style="pointer-events: none;">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                    `}
                                </div>
                            `;


                            // Actualiza los datos de la fila en DataTables y redibuja
                            if (currentEditingRow) {
                                currentEditingRow.data([
                                    updatedUser.name ?? '-', // Col 0
                                    updatedUser.email ?? '-', // Col 1
                                    updatedUser.role ? updatedUser.role.charAt(0)
                                    .toUpperCase() + updatedUser.role.slice(1) :
                                    '-', // Col 2 (Capitaliza el rol)
                                    actionsHtml // Col 3 (Contenido HTML)
                                ]).draw(false); // Usa draw(false) para no reordenar/filtrar
                            }

                            hideEditModal(); // Cierra el modal al tener éxito
                            submitButton.prop('disabled', false).text('Actualizar');

                            // Opcional: Mostrar un mensaje de éxito en el modal genérico
                            showMessageModal('Éxito', 'Usuario actualizado correctamente.');
                        },
                        error: function(xhr) {
                            submitButton.prop('disabled', false).text('Actualizar');
                            if (xhr.status === 422) { // Errores de validación
                                const errors = xhr.responseJSON.errors;
                                for (const field in errors) {
                                    // Maneja errores de password y password_confirmation específicamente si es necesario, o usa un enfoque genérico
                                    $('#edit_' + field + '_error').text(errors[field][0]);
                                }
                            } else {
                                // Para otros errores, muestra un mensaje genérico en el modal
                                showMessageModal('Error',
                                    'Ocurrió un error al actualizar el usuario.');
                                console.error(xhr.responseText);
                            }
                        }
                    });
                });


                // --- Manejo del Modal de Confirmación de Eliminación ---
                const deleteConfirmationModal = $('#delete-confirmation-modal');
                const confirmDeleteBtn = $('#confirm-delete-btn');
                const cancelDeleteModalBtn = $('#cancel-delete-modal-btn');
                const itemToDeleteDetails = $('#item-to-delete-details');

                let currentDeletingRow = null; // Variable para almacenar la fila de DataTables que se está eliminando

                function showDeleteConfirmationModal(userName, userEmail, deleteUrl, dataTableRaw) {
                    itemToDeleteDetails.html(`Nombre: ${userName}<br>Email: ${userEmail}`);
                    deleteConfirmationModal.data('delete-url', deleteUrl);
                    currentDeletingRow = dataTableRaw; // Almacena el objeto de la fila de DataTables
                    deleteConfirmationModal.removeClass('hidden');
                }

                function hideDeleteConfirmationModal() {
                    deleteConfirmationModal.addClass('hidden');
                    deleteConfirmationModal.removeData('delete-url'); // Limpia los datos almacenados al cerrar
                    currentDeletingRow = null; // Limpia la fila almacenada
                    itemToDeleteDetails.text(''); // Limpia el texto de detalles
                }

                // Manejar clic en el botón de eliminar de la tabla (muestra el modal de confirmación)
                $(document).on('submit', '.delete-user-form', function(e) {
                    e.preventDefault(); // Previene el envío predeterminado del formulario

                    const form = $(this);
                    const deleteUrl = form.attr('action');
                    const userName = form.data('name'); // Obtiene los atributos data
                    const userEmail = form.closest('tr').find('td:eq(1)').text()
                        .trim(); // Obtiene el email de la segunda columna
                    const dataTableRaw = table.row($(this).closest(
                        'tr')); // Obtiene el objeto de la fila de DataTables

                    showDeleteConfirmationModal(userName, userEmail, deleteUrl, dataTableRaw);
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

                            // Después de eliminar una fila, verifica si la tabla está ahora vacía
                            if (table.data().count() === 0) {
                                $('#users-datatable-wrapper')
                                    .hide(); // Oculta el contenedor de la tabla
                                $('#no-users-message').show(); // Muestra el mensaje de tabla vacía
                            }

                            hideDeleteConfirmationModal(); // Oculta el modal de confirmación

                            // Mostrar mensaje de éxito en el modal genérico
                            showMessageModal('Éxito', 'Usuario eliminado correctamente.');
                        },
                        error: function(xhr) {
                            hideDeleteConfirmationModal
                                (); // Oculta el modal de confirmación antes de mostrar el de mensaje

                            // *** MODIFICACIÓN DEL MANEJADOR DE ERRORES para usar el modal de mensaje ***
                            if (xhr.status === 409 || xhr.status === 403) {
                                // Muestra el mensaje de error personalizado del controlador en el modal
                                showMessageModal('Error al eliminar usuario', xhr.responseJSON
                                    .message);
                            } else {
                                // Mensaje de error genérico para otros tipos de errores
                                showMessageModal('Error',
                                    'No puedes eliminar un usuario con pagos asignados.');
                                console.error(xhr.responseText);
                            }
                            // ************************************************************************

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

                // --- Manejo del Modal de Mensaje Genérico ---
                const messageModal = $('#message-modal');
                const messageModalTitle = $('#message-modal-title');
                const messageModalBody = $('#message-modal-body');
                // ELIMINADO: const closeMessageModalBtn = $('#close-message-modal-btn');

                function showMessageModal(title, message) {
                    messageModalTitle.text(title);
                    messageModalBody.html(message); // Usar html() permite etiquetas como <br> si vienen del servidor
                    messageModal.removeClass('hidden');

                    // *** AÑADIDO: Ocultar el modal después de 2 segundos ***
                    setTimeout(function() {
                        hideMessageModal();
                    }, 2000); // 2000 milisegundos = 2 segundos
                    // *****************************************************
                }

                function hideMessageModal() {
                    messageModal.addClass('hidden');
                    messageModalTitle.text('');
                    messageModalBody.text('');
                }

                // ELIMINADO: closeMessageModalBtn.on('click', hideMessageModal);

                // Aún mantenemos la opción de cerrar haciendo clic fuera, si se desea
                messageModal.on('click', function(e) {
                    if ($(e.target).is(messageModal)) {
                        hideMessageModal();
                    }
                });
                // *** FIN Manejo del Modal de Mensaje Genérico (modificado) ***


                // --- Envío del formulario de Creación mediante AJAX ---
                createUserForm.on('submit', function(e) {
                    e.preventDefault();

                    const form = $(this);
                    const url = form.attr('action');
                    const formData = form.serialize();
                    const submitButton = form.find('#submit-create-user');

                    createFormErrors.text(''); // Limpia los errores anteriores
                    submitButton.prop('disabled', true).text('Guardando...');

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: formData,
                        // El token CSRF se maneja por ajaxSetup
                        success: function(response) {
                            const newUser = response;
                            // Construye el HTML de las acciones (incluidos los botones de Editar y Eliminar)
                            // Necesita verificar si el usuario es el autenticado para deshabilitar la eliminación
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
                                   ${newUser.id !== {{ auth()->id() }} ? `
                                                    <form action="/users/${newUser.id}" method="POST" class="delete-user-form" data-id="${newUser.id}" data-name="${newUser.name ?? '-'}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="delete-user-btn inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-700 text-white rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                    ` : `
                                                    <button type="button" disabled
                                                        class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none cursor-not-allowed"
                                                        style="pointer-events: none;">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                    `}
                                </div>
                            `;


                            // Añade la nueva fila a la DataTable y redibuja
                            table.row.add([
                                newUser.name ?? '-', // Col 0
                                newUser.email ?? '-', // Col 1
                                newUser.role ? newUser.role.charAt(0).toUpperCase() +
                                newUser.role.slice(1) : '-', // Col 2 (Capitaliza el rol)
                                actionsHtml // Col 3 (Contenido HTML)
                            ]).draw(false); // Usa draw(false) para no reordenar/filtrar

                            // Después de añadir una fila, asegura que la tabla sea visible y el mensaje de tabla vacía esté oculto
                            $('#users-datatable-wrapper').show().removeClass('opacity-0');
                            $('#no-users-message').hide();

                            hideCreateModal(); // Cierra el modal al tener éxito
                            submitButton.prop('disabled', false).text('Guardar');

                            // Mostrar mensaje de éxito en el modal genérico
                            showMessageModal('Éxito', 'Usuario creado correctamente.');
                        },
                        error: function(xhr) {
                            submitButton.prop('disabled', false).text('Guardar');
                            if (xhr.status === 422) { // Errores de validación
                                const errors = xhr.responseJSON.errors;
                                for (const field in errors) {
                                    // Maneja errores de password y password_confirmation específicamente si es necesario, o usa un enfoque genérico
                                    $('#' + field + '_error').text(errors[field][0]);
                                }
                            } else {
                                // Para otros errores, muestra un mensaje genérico en el modal
                                showMessageModal('Error', 'Ocurrió un error al crear el usuario.');
                                console.error(xhr.responseText);
                            }
                        }
                    });
                });

                // Manejar la tecla Escape para cerrar modales (excluyendo el modal de mensaje genérico si queremos que se cierre solo)
                // Si prefieres que ESC cierre también el modal de mensaje, descomenta la línea correspondiente y decide si el timer sigue activo.
                $(document).on('keydown', function(e) {
                    if (e.key === 'Escape') {
                        if (!createModal.hasClass('hidden')) {
                            hideCreateModal();
                        } else if (!editModal.hasClass('hidden')) {
                            hideEditModal();
                        } else if (!deleteConfirmationModal.hasClass('hidden')) {
                            hideDeleteConfirmationModal();
                        }
                        // Si quieres que ESC cierre también el modal de mensaje, descomenta la siguiente línea:
                        // else if (!messageModal.hasClass('hidden')) { hideMessageModal(); }
                    }
                });

                // No se necesita conversión a mayúsculas específica para campos de usuario típicamente

            });
        </script>
        {{-- Asegúrate de tener esta meta etiqueta en tu layout principal para el token CSRF --}}
        {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
    @endpush
</x-app-layout>
