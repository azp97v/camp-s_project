<?php $__env->startSection('title', 'إنشاء هدف جديد - Step by Step'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/goals.css')); ?>">
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>
    <section class="create-goal-section">
        <div class="container">
            <div class="create-goal-card glass">
                <div class="card-header">
                    <h1 class="card-title">🎯 إنشاء هدف جديد</h1>
                    <p class="card-subtitle">حدد أهدافك وابدأ رحلتك نحو النجاح</p>
                </div>

                <!-- Create Goal Form -->
                <form class="create-goal-form" id="createGoalForm" method="POST" action="<?php echo e(route('goals.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label for="title" class="form-label">📝 اسم الهدف *</label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            class="form-input"
                            placeholder="مثال: تعلم البرمجة بلغة PHP"
                            value="<?php echo e(old('title')); ?>"
                            required
                        >
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">📋 وصف الهدف</label>
                        <textarea
                            id="description"
                            name="description"
                            class="form-textarea"
                            placeholder="اكتب وصفاً تفصيلياً لهدفك والخطوات المطلوبة..."
                            rows="4"
                        ><?php echo e(old('description')); ?></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="total_duration_input" class="form-label">⏱️ المدة (عدد)</label>
                            <input
                                type="number"
                                id="total_duration_input"
                                name="total_duration_input"
                                class="form-input"
                                min="1"
                                value="<?php echo e(old('total_duration_input', 1)); ?>"
                                required
                            >
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['total_duration_input'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="total_unit" class="form-label">📅 وحدة المدة</label>
                            <select id="total_unit" name="total_unit" class="form-select" required>
                                <option value="hours" <?php echo e(old('total_unit') === 'hours' ? 'selected' : ''); ?>>ساعات</option>
                                <option value="days" <?php echo e(old('total_unit') === 'days' ? 'selected' : ''); ?>>أيام</option>
                                <option value="weeks" <?php echo e(old('total_unit') === 'weeks' ? 'selected' : ''); ?>>أسابيع</option>
                                <option value="months" <?php echo e(old('total_unit') === 'months' ? 'selected' : ''); ?>>أشهر</option>
                            </select>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['total_unit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <div id="duration-preview" class="duration-preview" aria-live="polite"></div>
                                <div id="duration-error" class="field-error" aria-live="assertive"></div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-large">
                            <span>💾</span> حفظ الهدف
                        </button>
                        <a href="<?php echo e(route('goals.index')); ?>" class="btn btn-glass">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        // Set sensible default for duration
        document.addEventListener('DOMContentLoaded', function(){
            const durationInput = document.getElementById('total_duration_input');
            if (durationInput && Number(durationInput.value) === 0) {
                durationInput.value = 1;
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const form = document.getElementById('createGoalForm');
    const title = document.getElementById('title');
    const durationInput = document.getElementById('total_duration_input');
    const unitSelect = document.getElementById('total_unit');
    const preview = document.getElementById('duration-preview');
    const durationError = document.getElementById('duration-error');
    let timeout;

    function formatDateArabic(d){
        try{
            return d.toLocaleString('ar-EG', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
        }catch(e){
            return d.toString();
        }
    }

    function updatePreview(){
        clearTimeout(timeout);
        timeout = setTimeout(()=>{
            const val = parseFloat(durationInput.value);
            const unit = unitSelect.value;
            if(isNaN(val) || val <= 0){
                preview.textContent = '';
                durationError.textContent = '';
                return;
            }
            const now = new Date();
            const end = new Date(now.getTime());
            switch(unit){
                case 'hours': end.setHours(end.getHours() + val); break;
                case 'days': end.setDate(end.getDate() + val); break;
                case 'weeks': end.setDate(end.getDate() + (val * 7)); break;
                case 'months': end.setMonth(end.getMonth() + val); break;
                default: break;
            }
            preview.textContent = 'تاريخ الانتهاء المتوقع: ' + formatDateArabic(end);
            durationError.textContent = '';
        }, 150);
    }

    durationInput.addEventListener('input', updatePreview);
    unitSelect.addEventListener('change', updatePreview);
    // initial
    updatePreview();

    form.addEventListener('submit', function(e){
        // simple client-side validation
        let firstInvalid = null;
        // title validation
        if(!title.value || title.value.trim().length < 3){
            e.preventDefault();
            const el = document.getElementById('title');
            if(!firstInvalid) firstInvalid = el;
            // show inline error
            let span = document.getElementById('title-error');
            if(!span){ span = document.createElement('div'); span.id = 'title-error'; span.className = 'field-error'; el.parentNode.appendChild(span); }
            span.textContent = 'الرجاء إدخال عنوان مكوّن من 3 أحرف على الأقل.';
        } else {
            const existing = document.getElementById('title-error'); if(existing) existing.remove();
        }

        // duration validation
        const val = parseFloat(durationInput.value);
        if(isNaN(val) || val < 1){
            e.preventDefault();
            if(!firstInvalid) firstInvalid = durationInput;
            durationError.textContent = 'المدّة يجب أن تكون عددًا صحيحًا أكبر من أو يساوي 1.';
        }

        if(firstInvalid){
            firstInvalid.focus();
            firstInvalid.scrollIntoView({behavior:'smooth', block:'center'});
        }
    });
});
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\camp's_project\resources\views/goals/create.blade.php ENDPATH**/ ?>