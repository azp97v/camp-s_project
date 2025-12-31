<?php $__env->startSection('title', $goal->title . ' - المهام'); ?>
<?php $__env->startSection('page-title', '🎯 ' . $goal->title); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/tasks.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <section class="goal-show-section tasks-section" dir="rtl" lang="ar">
        <div class="container">
            <!-- Goal Header with Progress Circle (tasks design) -->
            <?php
                $completedCount = $tasks->where('status', 'completed')->count();
                $totalCount = $tasks->count();
                $progressPercent = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
                $strokeDashOffset = 220 - ($progressPercent / 100) * 220;

                // Keep goal duration stats from previous design
                $percent = $goal->total_duration_seconds > 0 ? round((($goal->total_duration_seconds - $goal->remaining_duration_seconds) / $goal->total_duration_seconds) * 100) : 0;
                $rem = $goal->remaining_duration_seconds;
                if ($rem >= 86400) {
                    $d = floor($rem/86400);
                    $h = floor(($rem%86400)/3600);
                    $remainStr = $d . ' يوم' . ($h? ' و '.$h.' ساعة':'');
                } else {
                    $h = floor($rem/3600);
                    $m = floor(($rem%3600)/60);
                    $remainStr = $h . ' ساعة' . ($m? ' و '.$m.' دقيقة':'');
                }
            ?>

            <div class="goal-header glass">
                <div class="goal-info" style="flex:1;">
                    <h1 class="goal-title">🎯 <?php echo e($goal->title); ?></h1>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($goal->description): ?>
                        <p class="goal-meta"><?php echo e($goal->description); ?></p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div style="margin-top:1rem;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
                            <span class="completed-count" style="font-size:0.95rem;">✅ <?php echo e($completedCount); ?> من <?php echo e($totalCount); ?> مهمة مكتملة</span>
                        </div>
                        <div class="progress-bar" style="height:8px; border-radius:4px;">
                            <div class="progress-fill" style="width: <?php echo e($progressPercent); ?>%;"></div>
                        </div>
                    </div>
                </div>
                <div class="goal-progress">
                    <div class="progress-circle">
                        <svg width="100" height="100" viewBox="0 0 80 80" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="40" cy="40" r="35" fill="none" stroke="var(--glass-border)" stroke-width="6"/>
                            <circle cx="40" cy="40" r="35" fill="none" stroke="var(--accent-color)" stroke-width="6"
                                    stroke-dasharray="220" stroke-dashoffset="<?php echo e($strokeDashOffset); ?>" stroke-linecap="round"
                                    style="transform: rotate(-90deg); transform-origin: center;"/>
                        </svg>
                        <span class="progress-text" style="font-size:1.5rem;"><?php echo e($progressPercent); ?>%</span>
                    </div>
                </div>
            </div>

            <!-- Tasks List (tasks design) -->
            <div class="tasks-list" style="margin-top:2rem;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
                    <h3 class="section-title">📋 قائمة المهام (<?php echo e($tasks->count() ?? 0); ?> مهمة)</h3>
                    <div style="display:flex; gap:0.75rem;">
                        <select id="filterTasks" class="form-input" style="padding:0.5rem; border-radius:6px; font-size:0.9rem;">
                            <option value="all">الكل</option>
                            <option value="pending">المتبقية</option>
                            <option value="completed">المكتملة</option>
                            <option value="overdue">المتأخرة</option>
                        </select>
                        <select id="sortTasks" class="form-input" style="padding:0.5rem; border-radius:6px; font-size:0.9rem;">
                            <option value="newest">الأحدث</option>
                            <option value="oldest">الأقدم</option>
                            <option value="priority">الأولوية</option>
                            <option value="deadline">الموعد</option>
                        </select>
                    </div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tasks && $tasks->count() > 0): ?>
                    <div class="tasks-grid" id="tasksContainer">
                        <?php
                            $overdueTasks = [];
                            $now = \Carbon\Carbon::now();
                        ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $isOverdue = $task->deadline && $task->deadline < $now && $task->status !== 'completed';
                                if ($isOverdue) $overdueTasks[] = $task->id;
                            ?>
                            <div class="task-item glass <?php echo e($task->status === 'completed' ? 'completed' : ''); ?> <?php echo e($isOverdue ? 'overdue' : ''); ?>"
                                 data-task-id="<?php echo e($task->id); ?>"
                                 data-status="<?php echo e($task->status); ?>"
                                 data-priority="<?php echo e($task->priority ?? 'medium'); ?>"
                                 data-deadline="<?php echo e($task->deadline?->timestamp ?? 0); ?>"
                                 data-created="<?php echo e($task->created_at?->timestamp ?? 0); ?>">

                                <form action="<?php echo e(route('tasks.toggle', $task)); ?>" method="POST" class="task-checkbox">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('POST'); ?>
                                    <input
                                        type="checkbox"
                                        id="task-<?php echo e($task->id); ?>"
                                        class="task-check"
                                        <?php echo e($task->status === 'completed' ? 'checked' : ''); ?>

                                        onchange="this.form.submit()"
                                    >
                                    <label for="task-<?php echo e($task->id); ?>" class="task-check-label"></label>
                                </form>

                                <div style="flex:1;">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($task->status === 'completed'): ?>
                                        <div class="task-content" title="المهمة مكتملة">
                                            <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.25rem;">
                                                <h4 class="task-name"><?php echo e($task->title); ?></h4>
                                                <span class="priority-badge priority-<?php echo e($task->priority ?? 'medium'); ?>"><?php echo e($task->priority === 'high' ? '🔴 عالية' : ($task->priority === 'low' ? '🟢 منخفضة' : '🟡 متوسطة')); ?></span>
                                            </div>
                                            <p class="task-date">📅 <?php echo e($task->deadline ? $task->deadline->format('d M Y') : 'بدون موعد'); ?></p>
                                        </div>
                                    <?php else: ?>
                                        <a href="<?php echo e(route('tasks.show', $task)); ?>" class="task-content task-link" title="عرض المهمة">
                                            <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.25rem;">
                                                <h4 class="task-name"><?php echo e($task->title); ?></h4>
                                                <span class="priority-badge priority-<?php echo e($task->priority ?? 'medium'); ?>"><?php echo e($task->priority === 'high' ? '🔴 عالية' : ($task->priority === 'low' ? '🟢 منخفضة' : '🟡 متوسطة')); ?></span>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isOverdue): ?>
                                                    <span class="overdue-badge">⚠️ متأخرة</span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                            <p class="task-date">📅 <?php echo e($task->deadline ? $task->deadline->format('d M Y') : 'بدون موعد'); ?></p>
                                        </a>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="task-actions">
                                    <a href="<?php echo e(route('tasks.edit', $task)); ?>" class="task-edit" title="تعديل">✏️</a>
                                    <form action="<?php echo e(route('tasks.destroy', $task)); ?>" method="POST" class="inline-form" onsubmit="return confirm('هل تريد حقاً حذف هذه المهمة؟')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="task-delete">🗑️</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state glass">
                        <div class="empty-icon">✨</div>
                        <p class="empty-text">قائمة المهام فارغة</p>
                        <p class="empty-subtitle">أضف مهمة جديدة من أسفل الصفحة للبدء</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Add Task Form (use the more detailed form fields) -->
            <div class="add-task-card glass" style="margin-top:1rem;">
                <h3 class="section-title">➕ إضافة مهمة جديدة</h3>
                <form method="POST" action="<?php echo e(route('tasks.store', $goal)); ?>" id="add-task-form">
                    <?php echo csrf_field(); ?>

                    <div class="add-task-grid" style="display:grid; grid-template-columns: 1fr 360px; gap:1rem; align-items:start;">
                        <div class="left-col">
                            <label for="title">عنوان المهمة <span style="color:#ef4444">*</span></label>
                            <input type="text" id="title" name="title" placeholder="اسم المهمة..." required class="form-input" value="<?php echo e(old('title')); ?>">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <label for="description" style="margin-top:0.75rem;">الوصف (اختياري)</label>
                            <textarea id="description" name="description" rows="3" class="form-input"><?php echo e(old('description')); ?></textarea>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="right-col" style="display:flex; flex-direction:column; gap:0.75rem;">
                            <div>
                                <label for="deadline">الموعد النهائي <span style="color:#ef4444">*</span></label>
                                <input type="datetime-local" id="deadline" name="deadline" class="form-input" value="<?php echo e(old('deadline')); ?>" required>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['deadline'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div style="display:flex; gap:0.5rem;">
                                <div style="flex:1;">
                                    <label for="estimated_duration">المدة المتوقعة <span style="color:#ef4444">*</span></label>
                                    <input type="number" id="estimated_duration" name="estimated_duration_input" min="1" class="form-input" value="<?php echo e(old('estimated_duration_input')); ?>" required>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['estimated_duration_input'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div style="width:110px;">
                                    <label for="estimated_unit">الوحدة</label>
                                    <select id="estimated_unit" name="estimated_unit" class="form-input">
                                        <option value="minutes" <?php if(old('estimated_unit') === 'minutes' || !old('estimated_unit')): echo 'selected'; endif; ?>>دقائق</option>
                                        <option value="hours" <?php if(old('estimated_unit') === 'hours'): echo 'selected'; endif; ?>>ساعات</option>
                                    </select>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['estimated_unit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>

                            <div>
                                <label for="priority">الأولوية</label>
                                <select id="priority" name="priority" class="form-input">
                                    <option value="low" <?php if(old('priority') === 'low'): echo 'selected'; endif; ?>>منخفضة</option>
                                    <option value="medium" <?php if(old('priority') === 'medium' || !old('priority')): echo 'selected'; endif; ?>>متوسطة</option>
                                    <option value="high" <?php if(old('priority') === 'high'): echo 'selected'; endif; ?>>عالية</option>
                                </select>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['priority'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div style="margin-top:0.5rem; display:flex; gap:0.5rem;">
                                <button type="submit" class="btn btn-primary" style="flex:1;">➕ إضافة المهمة</button>
                                <a href="<?php echo e(route('goals.index')); ?>" class="btn btn-secondary" style="flex:1; text-decoration:none; display:inline-flex; align-items:center; justify-content:center;">← رجوع</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Small helper: keep behavior of tasks page (set default dates)
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        const dateInputs = document.querySelectorAll('input[type="date"], input[type="datetime-local"]');
        dateInputs.forEach(input => {
            if (!input.value) input.value = today;
        });

        // Task filtering and sorting functionality
        const filterSelect = document.getElementById('filterTasks');
        const sortSelect = document.getElementById('sortTasks');
        const tasksContainer = document.getElementById('tasksContainer');

        if (filterSelect && sortSelect && tasksContainer) {
            // Store original tasks - keep them as reference
            const allTasks = Array.from(tasksContainer.querySelectorAll('.task-item'));

            function updateTasks() {
                const filter = filterSelect.value;
                const sort = sortSelect.value;

                // Filter tasks
                let visibleTasks = allTasks.filter(task => {
                    const status = task.getAttribute('data-status');
                    const isOverdue = task.classList.contains('overdue');

                    if (filter === 'all') return true;
                    if (filter === 'pending') return status !== 'completed' && !isOverdue;
                    if (filter === 'completed') return status === 'completed';
                    if (filter === 'overdue') return isOverdue;
                    return true;
                });

                // Sort visible tasks
                visibleTasks.sort((a, b) => {
                    const priorityOrder = { 'high': 3, 'medium': 2, 'low': 1 };
                    const aPriority = priorityOrder[a.getAttribute('data-priority')] || 2;
                    const bPriority = priorityOrder[b.getAttribute('data-priority')] || 2;
                    const aDeadline = parseInt(a.getAttribute('data-deadline')) || Infinity;
                    const bDeadline = parseInt(b.getAttribute('data-deadline')) || Infinity;
                    const aCreated = parseInt(a.getAttribute('data-created')) || 0;
                    const bCreated = parseInt(b.getAttribute('data-created')) || 0;

                    switch(sort) {
                        case 'newest':
                            return bCreated - aCreated;
                        case 'oldest':
                            return aCreated - bCreated;
                        case 'priority':
                            return bPriority - aPriority;
                        case 'deadline':
                            return aDeadline - bDeadline;
                        default:
                            return 0;
                    }
                });

                // Hide all tasks
                allTasks.forEach(task => {
                    task.style.display = 'none';
                    task.style.animation = 'none';
                });

                // Show and reorder visible tasks with staggered animation
                visibleTasks.forEach((task, index) => {
                    task.style.display = '';
                    // Reset animation
                    task.style.animation = 'none';
                    // Trigger reflow to restart animation
                    void task.offsetWidth;
                    // Apply animation with stagger
                    task.style.animation = `slideInTaskSmooth 0.5s ease-out ${index * 0.1}s forwards`;
                });
            }

            filterSelect.addEventListener('change', updateTasks);
            sortSelect.addEventListener('change', updateTasks);
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\camp's_project\resources\views/goals/show.blade.php ENDPATH**/ ?>