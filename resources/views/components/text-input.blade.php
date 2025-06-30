@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border border-gray-300 bg-gray-200 text-gray-900 rounded-2xl shadow-md focus:ring-blue-600 focus:border-blue-600 block w-full mt-1']) }}>
