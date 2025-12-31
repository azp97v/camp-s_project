{{--
    Component: input-label
    --------------------------------------------------------
    Arabic: تسمية لحقل النموذج تستخدم خصائص CSS متناسقة.
    English: Reusable label component for form inputs.
--}}
@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-semibold text-slate-900 mb-2']) }}>
    {{ $value ?? $slot }}
</label>
