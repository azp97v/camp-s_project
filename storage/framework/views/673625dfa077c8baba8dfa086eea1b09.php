<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/goals-display.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/goals.css')); ?>">
<?php $__env->stopPush(); ?>
<?php $__env->startSection('title','أهدافي'); ?>
<?php $__env->startSection('page-title','🎯 أهدافي'); ?>

<?php $__env->startSection('content'); ?>
    <section class="goals-section">
        <div class="container">
            <!-- Header -->
            <div class="goals-header">
                <div class="header-content">
                    <h1 class="page-title">🎯 أهدافي</h1>
                    <p class="page-subtitle">إدارة وتتبع جميع أهدافك في مكان واحد</p>
                </div>
                <a href="<?php echo e(route('goals.create')); ?>" class="btn btn-primary">+ إنشاء هدف جديد</a>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($goals->isEmpty()): ?>
                <!-- Empty State -->
                <div class="glass p-12 rounded-2xl text-center">
                    <div class="text-6xl mb-4">🏃</div>
                    <p class="text-xl text-slate-600 mb-4">لا توجد أهداف حتى الآن</p>
                    <p class="text-slate-500 mb-6">ابدأ رحلتك بإنشاء هدفك الأول الآن</p>
                    <a href="<?php echo e(route('goals.create')); ?>" class="btn btn-primary">✨ إنشاء الهدف الأول</a>
                </div>
            <?php else: ?>
                <!-- Stats Overview -->
                <div class="stats-row glass">
                    <?php
                        $totalGoals = $goals->count();
                        $completedGoals = $goals->filter(fn($g) => ($g->total_duration_seconds - $g->remaining_duration_seconds) >= $g->total_duration_seconds)->count();
                        $activeGoals = $totalGoals - $completedGoals;
                        $totalSeconds = $goals->sum('total_duration_seconds');
                        $completedSeconds = $goals->sum(fn($g) => $g->total_duration_seconds - $g->remaining_duration_seconds);
                        $completionRate = $totalSeconds > 0 ? round(($completedSeconds / $totalSeconds) * 100) : 0;
                    ?>
                    <div class="stat-box" style="--index: 0">
                        <div class="stat-icon">📊</div>
                        <div class="stat-content">
                            <p class="stat-label">إجمالي الأهداف</p>
                            <p class="stat-value"><?php echo e($totalGoals); ?></p>
                        </div>
                    </div>
                    <div class="stat-box" style="--index: 1">
                        <div class="stat-icon">✅</div>
                        <div class="stat-content">
                            <p class="stat-label">الأهداف المكتملة</p>
                            <p class="stat-value"><?php echo e($completedGoals); ?></p>
                        </div>
                    </div>
                    <div class="stat-box" style="--index: 2">
                        <div class="stat-icon">⏳</div>
                        <div class="stat-content">
                            <p class="stat-label">قيد التنفيذ</p>
                            <p class="stat-value"><?php echo e($activeGoals); ?></p>
                        </div>
                    </div>
                    <div class="stat-box" style="--index: 3">
                        <div class="stat-icon">📈</div>
                        <div class="stat-content">
                            <p class="stat-label">معدل الإنجاز</p>
                            <p class="stat-value"><?php echo e($completionRate); ?>%</p>
                        </div>
                    </div>
                </div>

                <!-- Goals Grid -->
                <div class="goals-grid">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $goals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $goal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
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
                            $isCompleted = ($goal->total_duration_seconds - $goal->remaining_duration_seconds) >= $goal->total_duration_seconds;
                        ?>
                        <!-- Goal Card -->
                        <div class="goal-card glass" style="--index: <?php echo e($loop->index); ?>">
                            <div class="goal-header-card">
                                <div class="goal-icon"><?php echo e(['📚', '💪', '📖', '🌍', '💼', '🎨', '🏆', '🚀'][$loop->index % 8] ?? '🎯'); ?></div>
                                <span class="goal-status <?php echo e($isCompleted ? 'completed' : 'active'); ?>">
                                    <?php echo e($isCompleted ? '✅ مكتملة' : '⏳ قيد التنفيذ'); ?>

                                </span>
                            </div>

                            <h3 class="goal-card-title"><?php echo e($goal->title); ?></h3>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($goal->description): ?>
                                <p class="goal-card-description"><?php echo e(Str::limit($goal->description, 100)); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <div class="goal-meta">
                                <div class="meta-item">
                                    <span class="meta-icon">📅</span>
                                    <span class="meta-text">آخر تحديث: <?php echo e($goal->updated_at->diffForHumans()); ?></span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-icon">⚡</span>
                                    <span class="meta-text"><?php echo e($goal->tasks()->count() ?? 0); ?> مهام</span>
                                </div>
                            </div>

                            <div class="progress-section">
                                <div class="progress-header">
                                    <span class="progress-label">التقدم</span>
                                    <span class="progress-percent"><?php echo e($percent); ?>%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?php echo e($percent); ?>%; --percent: <?php echo e($percent); ?>%"></div>
                                </div>
                                <p class="progress-detail"><?php echo e(gmdate('H:i:s', $goal->total_duration_seconds - $goal->remaining_duration_seconds)); ?> / <?php echo e(gmdate('H:i:s', $goal->total_duration_seconds)); ?></p>
                            </div>

                            <div class="goal-actions">
                                <a href="<?php echo e(route('goals.show', $goal)); ?>" class="btn btn-sm btn-primary">عرض التفاصيل</a>
                                <a href="<?php echo e(route('goals.tasks.index', $goal)); ?>" class="btn btn-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; text-decoration: none;">📋 المهام</a>
                                <a href="<?php echo e(route('goals.edit', $goal)); ?>" class="btn btn-sm" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; text-decoration: none;">✏️ تعديل</a>
                                <form method="POST" action="<?php echo e(route('goals.destroy', $goal)); ?>" style="flex: 1;">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-delete" onclick="return confirm('هل تريد حقاً حذف هذا الهدف؟')" style="width: 100%;">🗑️</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\camp's_project\resources\views/goals/index.blade.php ENDPATH**/ ?>