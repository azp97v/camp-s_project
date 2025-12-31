<?php $__env->startSection('title', 'جميع المهام'); ?>
<?php $__env->startSection('page-title', '📋 جميع المهام'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/tasks.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <section class="tasks-section" dir="rtl" lang="ar">
        <div class="container">
            <!-- Header -->
            <div class="goal-header glass">
                <div class="goal-info">
                    <h1 class="goal-title">📋 جميع المهام</h1>
                    <p class="goal-meta">
                        <span class="goal-date">إجمالي المهام: <?php echo e($allTasks->count()); ?></span>
                        <span class="goal-date">المكتملة: <?php echo e($allTasks->where('status', 'completed')->count()); ?></span>
                    </p>
                </div>
            </div>

            <!-- Tasks List Grouped by Goal -->
            <div class="tasks-list">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($allTasks && $allTasks->count() > 0): ?>
                    <?php
                        // Group tasks by goal
                        $tasksByGoal = $allTasks->groupBy('goal.id');
                    ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $tasksByGoal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $goalId => $tasksGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $goal = $tasksGroup->first()->goal;
                            $completedCount = $tasksGroup->where('status', 'completed')->count();
                            $totalCount = $tasksGroup->count();
                            $progressPercent = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
                        ?>

                        <div style="margin-bottom: 2rem;">
                            <div class="goal-header glass" style="margin-bottom: 1rem;">
                                <div class="goal-info">
                                    <h3 class="goal-title" style="font-size: 1.3rem; margin-bottom: 0;">🎯 <?php echo e($goal->title); ?></h3>
                                    <p class="goal-meta" style="margin-top: 0.5rem;">
                                        <span><?php echo e($completedCount); ?> / <?php echo e($totalCount); ?> مهمة</span>
                                    </p>
                                </div>
                                <div>
                                    <a href="<?php echo e(route('goals.tasks.index', $goal)); ?>" class="btn btn-primary" style="font-size: 0.9rem;">عرض التفاصيل</a>
                                </div>
                            </div>

                            <div class="tasks-grid">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $tasksGroup; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="task-item glass <?php echo e($task->status === 'completed' ? 'completed' : ''); ?>">
                                        <?php
                                            $canComplete = $task->estimated_duration_seconds && $task->total_tracked_seconds >= $task->estimated_duration_seconds;
                                        ?>
                                        <form action="<?php echo e(route('tasks.toggle', $task)); ?>" method="POST" class="task-checkbox">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('POST'); ?>
                                            <input
                                                type="checkbox"
                                                id="task-<?php echo e($task->id); ?>"
                                                class="task-check"
                                                <?php echo e($task->status === 'completed' ? 'checked' : ''); ?>

                                                <?php echo e(($task->status === 'completed' || ! $canComplete) ? 'disabled' : ''); ?>

                                                title="<?php echo e($task->status === 'completed' ? 'المهمة مكتملة - لا يمكن التراجع' : ($canComplete ? 'وضع كمكتملة' : 'لا يمكن وضع علامة مكتملة إلا بعد إتمام مدة المهمة')); ?>"
                                            >
                                            <label for="task-<?php echo e($task->id); ?>" class="task-check-label"></label>
                                        </form>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($task->status === 'completed'): ?>
                                            <div class="task-content task-link disabled-link" title="المهمة مكتملة - لا يمكن فتحها">
                                                <h4 class="task-name">
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($task->priority === 'high'): ?> 🔴
                                                    <?php elseif($task->priority === 'medium'): ?> 🟠
                                                    <?php else: ?> 🟢
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    <?php echo e($task->title); ?>

                                                </h4>
                                                <p class="task-date">
                                                    📅 <?php echo e($task->deadline ? $task->deadline->format('d M Y') : 'بدون موعد'); ?>

                                                </p>
                                            </div>
                                        <?php else: ?>
                                            <a href="<?php echo e(route('tasks.show', $task)); ?>" class="task-content task-link" title="عرض المهمة">
                                                <h4 class="task-name">
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($task->priority === 'high'): ?> 🔴
                                                    <?php elseif($task->priority === 'medium'): ?> 🟠
                                                    <?php else: ?> 🟢
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    <?php echo e($task->title); ?>

                                                </h4>
                                                <p class="task-date">
                                                    📅 <?php echo e($task->deadline ? $task->deadline->format('d M Y') : 'بدون موعد'); ?>

                                                </p>
                                            </a>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                        <div class="task-actions">
                                            <a href="<?php echo e(route('tasks.edit', $task)); ?>" class="task-edit" title="تعديل">
                                                <span>✏️</span>
                                            </a>
                                            <form action="<?php echo e(route('tasks.destroy', $task)); ?>" method="POST" class="inline-form" onsubmit="return confirm('هل تريد حقاً حذف هذه المهمة؟')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="task-delete" title="حذف">
                                                    <span>🗑️</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php else: ?>
                    <div class="empty-state glass">
                        <div class="empty-icon">📭</div>
                        <p class="empty-text">لا توجد مهام حتى الآن</p>
                        <p class="empty-subtitle">
                            <a href="<?php echo e(route('goals.index')); ?>" class="btn btn-ghost">← العودة إلى الأهداف</a>
                        </p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Back Link -->
            <div class="text-center mt-8">
                <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-ghost">← العودة إلى لوحة التحكم</a>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle task toggle via AJAX
        document.querySelectorAll('.task-checkbox .task-check').forEach(checkbox => {
            checkbox.addEventListener('change', function(e) {
                e.preventDefault();

                const form = this.closest('.task-checkbox');
                const taskItem = this.closest('.task-item');
                const taskId = this.id.replace('task-', '');
                const formAction = form.getAttribute('action');

                // Disable checkbox during request
                this.disabled = true;

                // Send AJAX request
                fetch(formAction, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({})
                })
                .then(async response => {
                    const data = await response.json().catch(() => ({}));
                    if (!response.ok || data.success === false) {
                        const msg = data.message || 'حدث خطأ أثناء تحديث الحالة.';
                        throw new Error(msg);
                    }
                    return data;
                })
                .then(data => {
                    // Update checkbox state
                    const isCompleted = data.task.status === 'completed';
                    checkbox.checked = isCompleted;

                    // Update task item class
                    if (isCompleted) {
                        taskItem.classList.add('completed');
                    } else {
                        taskItem.classList.remove('completed');
                    }

                    // Update completion counts
                    updateAllCounts();

                    // Notify user
                    showToast(data.message || 'تم تحديث حالة المهمة.', 'success');
                })
                .catch(error => {
                    // Show message to the user and revert checkbox
                    showToast(error.message || 'حدث خطأ', 'error');
                    checkbox.checked = !checkbox.checked;
                })
                .finally(() => {
                    // Re-enable checkbox
                    checkbox.disabled = false;
                });
            });
        });

        // If user clicks the label of a disabled checkbox, show informative message
        document.querySelectorAll('.task-checkbox .task-check-label').forEach(label => {
            label.addEventListener('click', function(e) {
                const inputId = this.getAttribute('for');
                const input = document.getElementById(inputId);
                if (input && input.disabled) {
                    e.preventDefault();
                    showToast('لا يمكن وضع علامة مكتملة إلا بعد إتمام مدة المهمة.', 'error');
                }
            });
        });

        // Update all completion counts on the page
        function updateAllCounts() {
            // Get all tasks (completed + uncompleted)
            const allTasks = document.querySelectorAll('.task-item');
            const completedTasks = document.querySelectorAll('.task-item.completed');

            // Update header counts
            const headerMeta = document.querySelector('.goal-header .goal-meta');
            if (headerMeta) {
                const spans = headerMeta.querySelectorAll('.goal-date');
                if (spans.length >= 2) {
                    spans[0].textContent = `إجمالي المهام: ${allTasks.length}`;
                    spans[1].textContent = `المكتملة: ${completedTasks.length}`;
                }
            }

            // Update per-goal counts
            updatePerGoalCounts();
        }

        // Update completion counts for each goal
        function updatePerGoalCounts() {
            const goalSections = document.querySelectorAll('.tasks-grid');

            goalSections.forEach(section => {
                // Find the header for this section
                const header = section.previousElementSibling;
                if (!header || !header.classList.contains('goal-header')) return;

                // Count completed tasks in this section
                const tasksInGroup = section.querySelectorAll('.task-item');
                const completedInGroup = section.querySelectorAll('.task-item.completed');

                // Update the goal meta
                const meta = header.querySelector('.goal-meta');
                if (meta) {
                    const span = meta.querySelector('span');
                    if (span) {
                        const totalCount = tasksInGroup.length;
                        const completedCount = completedInGroup.length;
                        const progressPercent = totalCount > 0 ? Math.round((completedCount / totalCount) * 100) : 0;
                        span.textContent = `${completedCount} / ${totalCount} مهمة`;
                    }
                }
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\camp's_project\resources\views/tasks-all.blade.php ENDPATH**/ ?>