@props(['disabled' => false])

{{-- Aquí se modificaron las clases de enfoque --}}
<input @disabled($disabled)
    {{ $attributes->merge(['class' => 'border-gray-300 focus:border-[#419639] focus:ring-[#419639] rounded-md shadow-sm']) }}>
