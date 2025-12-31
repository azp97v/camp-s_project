<?php $__env->startSection('title','تعديل الهدف'); ?>
<?php $__env->startSection('page-title','✏️ تعديل الهدف'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/tasks-show.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('assets/css/goals-edit.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="goal-edit-container">
    <!-- Header Card -->
    <div class="form-header-card">
        <h3>تعديل معلومات الهدف</h3>
        <p>قم بتحديث تفاصيل الهدف والمدة الإجمالية</p>
    </div>

    <!-- Form Card -->
    <div class="form-main-card">
        <!-- Info Box -->
        <div class="info-box">
            <strong>ملاحظة:</strong> عند تعديل المدة الإجمالية للهدف، ستتم مراعاة الوقت المتبقي تلقائياً.
        </div>

        <form method="POST" action="<?php echo e(route('goals.update', $goal)); ?>" class="goal-edit-form">
            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>

            <!-- Title Field -->
            <div class="form-group-edit">
                <label for="title">
                    📝 عنوان الهدف
                    <span class="required">*</span>
                </label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="<?php echo e(old('title', $goal->title)); ?>"
                    placeholder="أدخل عنوان الهدف..."
                    class="form-input-edit"
                    required
                >
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Description Field -->
            <div class="form-group-edit">
                <label for="description">
                    📄 الوصف (اختياري)
                </label>
                <textarea
                    id="description"
                    name="description"
                    placeholder="أدخل وصفاً تفصيلياً للهدف..."
                    class="form-textarea-edit"
                ><?php echo e(old('description', $goal->description)); ?></textarea>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Duration Section -->
            <div class="form-group-edit">
                <label>
                    ⏱️ المدة الإجمالية
                    <span class="required">*</span>
                </label>
                <div class="form-group-inline">
                    <?php
                        $isDay = $goal->total_duration_seconds % 86400 === 0 && $goal->total_duration_seconds >= 86400;
                        $durationValue = $isDay ? intdiv($goal->total_duration_seconds, 86400) : intdiv($goal->total_duration_seconds, 3600);
                    ?>
                    <input
                        type="number"
                        name="total_duration_input"
                        min="0"
                        value="<?php echo e(old('total_duration_input', $durationValue)); ?>"
                        placeholder="أدخل الرقم..."
                        class="form-input-edit"
                        required
                    >
                    <select name="total_unit" class="form-select-edit" required>
                        <option value="hours" <?php echo e(!$isDay ? 'selected' : ''); ?>>ساعة</option>
                        <option value="days" <?php echo e($isDay ? 'selected' : ''); ?>>يوم</option>
                    </select>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['total_duration_input'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Current Stats -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin: 2rem 0; padding: 1.5rem; background: rgba(255,255,255,0.02); border-radius: 0.75rem; border: 1px solid var(--glass-border);">
                <div>
                    <p style="font-size: 0.85rem; opacity: 0.7; margin-bottom: 0.5rem;">الوقت المستغرق حالياً</p>
                    <p style="font-size: 1.25rem; font-weight: 700; color: var(--accent-color);"><?php echo e(gmdate('H:i:s', $goal->total_duration_seconds - $goal->remaining_duration_seconds)); ?></p>
                </div>
                <div>
                    <p style="font-size: 0.85rem; opacity: 0.7; margin-bottom: 0.5rem;">الوقت المتبقي</p>
                    <p style="font-size: 1.25rem; font-weight: 700; color: var(--highlight-color);"><?php echo e(gmdate('H:i:s', $goal->remaining_duration_seconds)); ?></p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-action btn-save">
                    💾 حفظ التغييرات
                </button>
                <a href="<?php echo e(route('goals.show', $goal)); ?>" class="btn-action btn-cancel">
                    ❌ إلغاء
                </a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\camp's_project\resources\views/goals/edit.blade.php ENDPATH**/ ?>