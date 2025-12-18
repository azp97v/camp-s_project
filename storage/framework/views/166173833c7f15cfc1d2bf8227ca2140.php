<?php $__env->startSection('title','إنشاء هدف'); ?>
<?php $__env->startSection('page-title','إنشاء هدف جديد'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-2xl mx-auto">
        <div class="glass p-8 rounded-2xl border border-white/20 shadow-lg animate-on-load">
            <form method="POST" action="<?php echo e(route('goals.store')); ?>" class="ajax-form space-y-6">
                <?php echo csrf_field(); ?>


                <div>
                    <label class="block text-lg font-semibold text-slate-900 mb-3">🎯 عنوان الهدف</label>
                    <input name="title" required class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all" placeholder="مثال: إكمال مشروع البرمجة" value="<?php echo e(old('title')); ?>" />
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-sm text-red-600 mt-2 flex items-center gap-2"><span>⚠️</span> <?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>


                <div>
                    <label class="block text-lg font-semibold text-slate-900 mb-3">📝 الوصف</label>
                    <textarea name="description" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all resize-none" placeholder="أضف وصفاً تفصيلياً لهدفك..." rows="4"><?php echo e(old('description')); ?></textarea>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-sm text-red-600 mt-2 flex items-center gap-2"><span>⚠️</span> <?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>


                <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl p-6 border border-indigo-200/50">
                    <label class="block text-lg font-semibold text-slate-900 mb-4">⏱️ المدة الكلية</label>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-slate-700 mb-2">المدة</label>
                            <input name="total_duration_input" type="number" min="1" required class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all" placeholder="أدخل الرقم" value="<?php echo e(old('total_duration_input')); ?>" />
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['total_duration_input'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-xs text-red-600 mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-700 mb-2">الوحدة</label>
                            <select name="total_unit" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all font-medium">
                                <option value="hours" <?php echo e(old('total_unit') === 'hours' ? 'selected' : ''); ?>>⏰ ساعات</option>
                                <option value="days" <?php echo e(old('total_unit') === 'days' ? 'selected' : ''); ?>>📅 أيام</option>
                            </select>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['total_unit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-xs text-red-600 mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                    <div class="mt-3 text-xs text-slate-600 bg-white rounded p-3 border border-slate-200">
                        💡 <strong>نصيحة:</strong> اختر المدة التقديرية لإكمال هدفك. يمكنك إضافة مهام فرعية بعد ذلك.
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 pt-4">
                    <button type="submit" class="flex-1 px-6 py-3 rounded-lg bg-gradient-to-r from-sky-500 to-indigo-600 text-white font-semibold hover:shadow-lg transition-all transform hover:scale-105">
                        ✨ إنشاء الهدف
                    </button>
                    <a href="<?php echo e(route('goals.index')); ?>" class="flex-1 px-6 py-3 rounded-lg border-2 border-slate-300 text-slate-700 font-semibold hover:bg-slate-50 transition-all text-center">
                        ✕ إلغاء
                    </a>
                </div>
            </form>
        </div>


        <div class="mt-8 glass p-6 rounded-xl border border-white/20">
            <h3 class="text-lg font-semibold text-slate-900 mb-3">💡 نصائح مفيدة</h3>
            <ul class="space-y-2 text-slate-700">
                <li>✓ اجعل أهدافك واضحة وقابلة للقياس</li>
                <li>✓ قسّم الأهداف الكبيرة إلى مهام صغيرة</li>
                <li>✓ حدد مدة واقعية لإكمال الهدف</li>
                <li>✓ تابع تقدمك بشكل منتظم</li>
            </ul>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\camp's_project\resources\views/goals/create.blade.php ENDPATH**/ ?>