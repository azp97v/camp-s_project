{{--
    Component: secondary-button
    --------------------------------------------------------
    Arabic: زر ثانوي (ghost) يستخدم للعمليات غير الأساسية.
    English: Secondary button component using `btn-ghost` styling.
--}}
<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn-ghost']) }}>
    {{ $slot }}
</button>
