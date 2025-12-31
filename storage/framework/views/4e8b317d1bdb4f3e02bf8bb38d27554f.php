<?php $__env->startSection('title', 'لوحة التحكم - Step by Step'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/dashboard.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <section class="dashboard-section" dir="rtl" lang="ar">
        <div class="container" role="main">
            <!-- Welcome -->
            <div class="glass dashboard-welcome animate-on-load">
                <div class="welcome-content">
                    <div>
                        <h2 class="welcome-title">مرحباً، <?php echo e(auth()->user()->name ?? 'المستخدم'); ?>! 👋</h2>
                        <p class="welcome-subtitle">أهلاً وسهلاً في لوحة التحكم — إدارة أهدافك ومهامك بسهولة.</p>
                    </div>
                    <div class="welcome-emoji">📊</div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="dashboard-stats">
                <a href="<?php echo e(route('goals.index')); ?>" class="stat-card glass stat-link" aria-label="عرض جميع الأهداف">
                    <div class="stat-content">
                        <div>
                            <p class="stat-label">🎯 إجمالي الأهداف</p>
                            <p class="stat-value"><?php echo e($goalsCount ?? 0); ?></p>
                        </div>
                        <div class="stat-meta text-xs text-slate-500">↑ <?php echo e($newGoalsThisMonth ?? 0); ?> هذا الشهر</div>
                    </div>
                </a>

                <a href="<?php echo e(route('tasks.index')); ?>" class="stat-card glass stat-link" aria-label="عرض جميع المهام">
                    <div class="stat-content">
                        <div>
                            <p class="stat-label">📋 إجمالي المهام</p>
                            <p class="stat-value"><?php echo e($totalTasks ?? 0); ?></p>
                        </div>
                        <div class="stat-meta text-xs text-slate-500">مكتملة: <?php echo e($completedTasks ?? 0); ?></div>
                    </div>
                </a>

                <a href="<?php echo e(route('goals.index')); ?>" class="stat-card glass stat-link" aria-label="عرض معدل الإنجاز">
                    <div class="stat-content">
                        <div>
                            <p class="stat-label">📈 معدل الإنجاز</p>
                            <p class="stat-value"><?php echo e($completionRate ?? 0); ?>%</p>
                        </div>
                        <div class="stat-meta text-xs text-slate-500">متوسط الأهداف: <?php echo e($avgGoalProgress ?? 0); ?>%</div>
                    </div>
                </a>

                <a href="<?php echo e(route('goals.index', ['filter' => 'overdue'])); ?>" class="stat-card glass stat-link" aria-label="عرض الأهداف المتأخرة">
                    <div class="stat-content">
                        <div>
                            <p class="stat-label">⏰ الأهداف المتأخرة</p>
                            <p class="stat-value text-yellow-600"><?php echo e($overdueTasks ?? 0); ?></p>
                        </div>
                        <div class="stat-meta text-xs text-slate-500">تحتاج اهتمام</div>
                    </div>
                </a>
            </div>

            <!-- Progress Overview (goals) -->
            <div class="glass p-8 mb-8">
                <h3 class="section-title">نظرة عامة على التقدم</h3>
                <div class="progress-grid">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $goalsList ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $goal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <a href="<?php echo e(route('goals.show', $goal['id'])); ?>" class="progress-item progress-link" aria-label="عرض الهدف <?php echo e($goal['name']); ?>">
                            <div class="progress-header">
                                <div class="progress-name"><?php echo e($goal['name']); ?></div>
                                <div class="progress-percent"><?php echo e($goal['progress']); ?>%</div>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo e($goal['progress']); ?>%"></div>
                            </div>
                            <div class="progress-info">
                                <span><?php echo e($goal['tasks']); ?> من <?php echo e($goal['total_tasks']); ?> مهمة</span>
                                <span><?php echo e($goal['deadline']); ?></span>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="progress-item">
                            لا توجد أهداف بعد.
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <!-- Upcoming Deadlines Section (Enhanced) -->
            <div class="glass mb-8 deadlines-section">
                <div class="deadlines-header">
                    <div class="deadlines-title-group">
                        <h3 class="section-title">المواعيد القادمة ⏰</h3>
                        <p class="deadlines-subtitle">المهام المهمة التي تحتاج اهتمامك</p>
                    </div>
                    <div class="deadlines-count-badge">
                        <span class="count-number"><?php echo e(count($upcomingDeadlines ?? [])); ?></span>
                        <span class="count-label">مهمة</span>
                    </div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($upcomingDeadlines) && count($upcomingDeadlines) > 0): ?>
                    <div class="deadlines-list">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $upcomingDeadlines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $deadline): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $isUrg = !empty($deadline['is_urgent']);
                                $hours = isset($deadline['hours_until']) ? (int) $deadline['hours_until'] : null;
                                $days = isset($deadline['days_until']) ? (int) $deadline['days_until'] : null;
                                $remainingText = $hours !== null && $hours < 24 ? ('تبقى ' . $hours . ' ساعة') : ('تبقى ' . ($days ?? 0) . ' يوم');
                                $titleAttr = $remainingText . ' — ' . ($deadline['deadline'] ?? '');
                            ?>
                            <a href="<?php echo e(route('goals.show', $deadline['goal_id']) ?? '#'); ?>" class="deadline-card deadline-card-<?php echo e($deadline['is_urgent'] ? 'urgent' : ($deadline['is_soon'] ? 'soon' : 'normal')); ?> animated-deadline <?php echo e($isUrg ? 'urgent' : ''); ?>" data-urgent="<?php echo e($isUrg ? '1' : '0'); ?>" title="<?php echo e($titleAttr); ?>" style="animation-delay: <?php echo e($index * 0.08); ?>s">
                                <div class="deadline-priority-bar <?php echo e(strtolower($deadline['priority'] ?? 'medium')); ?>"></div>

                                <div class="deadline-content">
                                    <div class="deadline-left">
                                        <div class="deadline-icon-wrapper">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($deadline['is_urgent']): ?>
                                                🔴
                                            <?php elseif($deadline['is_soon']): ?>
                                                🟠
                                            <?php else: ?>
                                                🟡
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <div class="deadline-info">
                                            <p class="deadline-title"><?php echo e($deadline['title']); ?></p>
                                            <p class="deadline-goal">📁 <?php echo e($deadline['goal'] ?? 'بدون هدف'); ?></p>
                                        </div>
                                    </div>

                                    <div class="deadline-right">
                                        <div class="deadline-days">
                                            <?php
                                                $hours = isset($deadline['hours_until']) ? (int) $deadline['hours_until'] : null;
                                                $days = isset($deadline['days_until']) ? (int) $deadline['days_until'] : null;
                                            ?>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hours !== null && $hours < 24): ?>
                                                <span class="days-number"><?php echo e($hours); ?></span>
                                                <span class="days-label">ساعات</span>
                                            <?php else: ?>
                                                <span class="days-number"><?php echo e($days ?? 0); ?></span>
                                                <span class="days-label">أيام</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <div class="deadline-date"><?php echo e($deadline['deadline']); ?></div>
                                    </div>
                                </div>

                                <div class="deadline-hover-glow"></div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="deadlines-empty-state">
                        <div class="empty-icon-large">✨</div>
                        <p class="empty-title">لا توجد مواعيد قريبة</p>
                        <p class="empty-description">رائع! أنت في المسار الصحيح</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Action Boxes (Unchanged) -->
            <div class="action-boxes-section">
                <div class="action-boxes">
                    <a href="<?php echo e(route('goals.create')); ?>" class="action-box" aria-label="إنشاء هدف جديد">
                        <div class="action-box-icon">➕</div>
                        <div class="action-box-label">إنشاء هدف جديد</div>
                    </a>
                    <a href="<?php echo e(route('tasks.index')); ?>" class="action-box" aria-label="عرض المهام">
                        <div class="action-box-icon">📋</div>
                        <div class="action-box-label">عرض المهام</div>
                    </a>
                    <a href="<?php echo e(route('settings.index')); ?>" class="action-box" aria-label="الإعدادات">
                        <div class="action-box-icon">⚙️</div>
                        <div class="action-box-label">الإعدادات</div>
                    </a>
                    <a href="<?php echo e(route('profile.edit')); ?>" class="action-box" aria-label="الملف الشخصي">
                        <div class="action-box-icon">👤</div>
                        <div class="action-box-label">الملف الشخصي</div>
                    </a>
                </div>
            </div>

            <!-- Recent Activity (Enhanced) -->
            <div class="glass mb-8 activity-section">
                <div class="activity-section-header">
                    <div>
                        <h3 class="section-title">النشاط الأخير</h3>
                        <p class="activity-subtitle">تحديثات نشاطك الأخيرة وتطورك</p>
                    </div>
                    <div class="activity-header-icon">📊</div>
                </div>
                <div class="activity-container">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recentActivities ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $act): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <a href="<?php echo e($act['url'] ?? '#'); ?>" class="activity-card animated-activity activity-link" style="animation-delay: <?php echo e($index * 0.1); ?>s" title="انقر لعرض المهمة">
                            <div class="activity-timeline-dot"></div>
                            <div class="activity-card-inner">
                                <div class="activity-card-header">
                                    <div class="activity-icon-wrapper"><?php echo $act['icon']; ?></div>
                                    <div class="activity-meta">
                                        <p class="activity-title"><?php echo e($act['title']); ?></p>
                                        <p class="activity-time"><?php echo e($act['time']); ?></p>
                                    </div>
                                </div>
                                <div class="activity-card-accent"></div>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="activity-empty-state">
                            <div class="empty-icon">🕐</div>
                            <p class="empty-text">لا توجد أنشطة حالياً</p>
                            <p class="empty-subtext">ابدأ بإنشاء هدف جديد أو إضافة مهمة</p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\camp's_project\resources\views/dashboard.blade.php ENDPATH**/ ?>