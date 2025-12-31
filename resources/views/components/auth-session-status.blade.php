{{--
    Component: auth-session-status
    --------------------------------------------------------
    Arabic: يعرض رسالة حالة الجلسة (نجاح العمليات) عند وجودها.
    English: Shows a green success status message when present (used after actions).
--}}
@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'p-4 mb-6 rounded-lg bg-green-50 border-l-4 border-green-600 text-sm font-medium text-green-700 shadow-sm']) }}>
        ✓ {{ $status }}
    </div>
@endif
