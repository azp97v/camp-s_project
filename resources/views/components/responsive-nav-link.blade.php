{{--
    Component: responsive-nav-link
    --------------------------------------------------------
    Arabic: رابط متجاوب لشريط التنقل يظهر في القوائم الصغيرة (mobile).
    English: Mobile-friendly navigation link with active state styling.
--}}
@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-4 pe-4 py-3 border-s-4 border-sky-400 text-start text-base font-semibold text-sky-700 bg-sky-50/50 transition-all duration-200'
            : 'block w-full ps-4 pe-4 py-3 border-s-4 border-transparent text-start text-base font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50/50 hover:border-sky-300 transition-all duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} dir="auto">
    {{ $slot }}
</a>
