{{--
    Component: primary-button
    --------------------------------------------------------
    Arabic: زر رئيسي موحد يستخدم عبر التطبيق.
    English: Primary call-to-action button component (uses `btn-primary` CSS class).
--}}
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-primary']) }}>
    {{ $slot }}
</button>
