@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 py-2 border-b-2 border-sky-400 text-sm font-semibold text-slate-900 hover:text-sky-500 transition-all duration-200'
            : 'inline-flex items-center px-3 py-2 border-b-2 border-transparent text-sm font-medium text-slate-600 hover:text-slate-900 hover:border-sky-300 transition-all duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} dir="auto">
    {{ $slot }}
</a>
