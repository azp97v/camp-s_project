<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['active']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['active']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
$classes = ($active ?? false)
            ? 'block w-full ps-4 pe-4 py-3 border-s-4 border-sky-400 text-start text-base font-semibold text-sky-700 bg-sky-50/50 transition-all duration-200'
            : 'block w-full ps-4 pe-4 py-3 border-s-4 border-transparent text-start text-base font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50/50 hover:border-sky-300 transition-all duration-200';
?>

<a <?php echo e($attributes->merge(['class' => $classes])); ?> dir="auto">
    <?php echo e($slot); ?>

</a>
<?php /**PATH C:\xampp\camp's_project\resources\views/components/responsive-nav-link.blade.php ENDPATH**/ ?>