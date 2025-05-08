@props(['active'])

@php
    $classes =
        $active ?? false
            ? // Clases para el estado ACTIVO
            // Cambiado: border-indigo-400 -> border-black, text-gray-900 -> text-black, focus:border-indigo-700 -> focus:border-black, focus:text-gray-900 -> focus:text-black
            'inline-flex items-center px-1 pt-1 border-b-2 border-black text-sm font-medium leading-5 text-black focus:outline-none focus:border-black transition duration-150 ease-in-out'
            : // Clases para el estado INACTIVO (incluyendo hover y focus)
            // Cambiado: text-gray-500 -> text-white, hover:text-gray-700 -> hover:text-black, focus:text-gray-700 -> focus:text-black
            // Mantenido: border-transparent por defecto, hover:border-gray-300 y focus:border-gray-300 para un ligero indicador en hover/focus inactivo
            'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-white hover:text-black hover:border-gray-300 focus:outline-none focus:text-black focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
