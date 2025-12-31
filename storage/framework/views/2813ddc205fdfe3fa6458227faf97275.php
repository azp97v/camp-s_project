<?php $__env->startSection('title','تعديل المهمة'); ?>
<?php $__env->startSection('page-title','✏️ تعديل المهمة'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/tasks-show.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('assets/css/tasks-edit.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="task-edit-container">
    <!-- Header Card -->
    <div class="form-header-card">
        <h3>تعديل معلومات المهمة</h3>
        <p>قم بتحديث تفاصيل المهمة وجدول الأوقات الخاص بها</p>
    </div>

    <!-- Form Card -->
    <div class="form-main-card">
        <form method="POST" action="<?php echo e(route('tasks.update', $task)); ?>" class="task-edit-form">
            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>

            <!-- Title Field -->
            <div class="form-group-edit">
                <label for="title">
                    📝 عنوان المهمة
                    <span class="required">*</span>
                </label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="<?php echo e(old('title', $task->title)); ?>"
                    placeholder="أدخل عنوان المهمة..."
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
                    placeholder="أدخل وصفاً تفصيلياً للمهمة..."
                    class="form-textarea-edit"
                ><?php echo e(old('description', $task->description)); ?></textarea>
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

            <!-- Deadline and Duration Row -->
            <div class="form-group-row">
                <div class="form-group-edit">
                    <label for="deadline">
                        📅 الموعد النهائي (اختياري)
                    </label>
                    <input
                        type="datetime-local"
                        id="deadline"
                        name="deadline"
                        value="<?php echo e(old('deadline', optional($task->deadline)->format('Y-m-d\TH:i'))); ?>"
                        class="form-datetime-edit"
                    >
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['deadline'];
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

                <div class="form-group-edit">
                    <label>⏱️ المدة المقدرة <span style="color: #ef4444;">*</span></label>
                    <div class="form-group-inline">
                        <input
                            type="number"
                            name="estimated_duration_input"
                            min="1"
                            value="<?php echo e(old('estimated_duration_input', intdiv($task->estimated_duration_seconds ?? 0, 60))); ?>"
                            placeholder="أدخل الرقم..."
                            class="form-input-edit"
                            required
                        >
                        <select name="estimated_unit" class="form-select-edit" required>
                            <option value="minutes" <?php echo e((old('estimated_unit') == 'hours') ? '' : 'selected'); ?>>دقيقة</option>
                            <option value="hours" <?php echo e((old('estimated_unit') == 'hours') ? 'selected' : ''); ?>>ساعة</option>
                        </select>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['estimated_duration_input'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['estimated_unit'];
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
            </div>

            <div class="form-group-edit">
                <label for="priority">🔖 الأولوية</label>
                <select id="priority" name="priority" class="form-select-edit">
                    <option value="low" <?php echo e((old('priority', $task->priority) == 'low') ? 'selected' : ''); ?>>منخفضة</option>
                    <option value="medium" <?php echo e((old('priority', $task->priority) == 'medium') ? 'selected' : ''); ?>>متوسطة</option>
                    <option value="high" <?php echo e((old('priority', $task->priority) == 'high') ? 'selected' : ''); ?>>عالية</option>
                </select>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['priority'];
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

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-action btn-save">
                    💾 حفظ التغييرات
                </button>
                <a href="<?php echo e(route('tasks.show', $task)); ?>" class="btn-action btn-cancel">
                    ❌ إلغاء
                </a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\camp's_project\resources\views/tasks/edit.blade.php ENDPATH**/ ?>