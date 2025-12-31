{{--
    Component: danger-button
    --------------------------------------------------------
    Arabic: زر أحمر للعمليات الحاسمة (حذف/إلغاء) مع ستايل جاهز.
    English: Danger-style submit button used for destructive actions.
--}}
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'px-4 py-2 rounded-lg font-semibold text-sm text-white bg-gradient-to-r from-red-500 to-rose-600 border border-transparent shadow-md hover:shadow-lg hover:from-red-600 hover:to-rose-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
