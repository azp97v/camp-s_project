<?php $__env->startSection('title','الأهداف'); ?>
<?php $__env->startSection('page-title','قائمة الأهداف'); ?>

<?php $__env->startSection('content'); ?>
    <div class="flex items-center justify-between mb-8">
        <div class="flex-1"></div>
        <a href="<?php echo e(route('goals.create')); ?>" class="px-6 py-3 glass rounded-xl text-white font-medium bg-gradient-to-r from-sky-500 via-blue-500 to-indigo-600 hover:shadow-lg transition-all duration-300 transform hover:scale-105">✨ إنشاء هدف جديد</a>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($goals->isEmpty()): ?>
        <div class="p-12 glass rounded-2xl text-center">
            <div class="text-6xl mb-4">🏃</div>
            <p class="text-xl text-slate-600 mb-4">لا توجد أهداف حتى الآن</p>
            <p class="text-slate-500 mb-6">ابدأ رحلتك بإنشاء هدفك الأول الآن</p>
            <a href="<?php echo e(route('goals.create')); ?>" class="inline-block px-6 py-2 rounded-lg bg-gradient-to-r from-sky-500 to-indigo-500 text-white font-medium hover:shadow-lg transition-all">إنشاء الهدف الأول</a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
                ?>
                <div class="p-6 rounded-2xl glass animate-on-load card-hover border border-white/20 shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="flex justify-between items-start gap-4 mb-4">
                        <a href="<?php echo e(route('goals.show', $goal)); ?>" class="text-lg font-bold text-slate-900 line-clamp-2 hover:text-blue-600 transition-colors">
                            🏃 <?php echo e($goal->title); ?>

                        </a>
                        <form method="POST" action="<?php echo e(route('goals.destroy', $goal)); ?>" class="flex-shrink-0">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg p-2 transition-all duration-200" onclick="return confirm('هل تريد حقاً حذف هذا الهدف؟')" title="حذف">
                                🗑️
                            </button>
                        </form>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($goal->description): ?>
                        <p class="text-slate-600 text-sm mb-4 line-clamp-2"><?php echo e($goal->description); ?></p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between text-xs mb-2">
                                <span class="font-semibold text-slate-700">التقدم: <span class="text-blue-600"><?php echo e($percent); ?>%</span></span>
                                <span class="text-slate-500"><?php echo e($goal->total_duration_seconds - $goal->remaining_duration_seconds); ?>/<?php echo e($goal->total_duration_seconds); ?> ثانية</span>
                            </div>
                            <div class="progress-outer rounded-full h-3 bg-gradient-to-r from-slate-200 to-slate-100 overflow-hidden">
                                <div class="progress-inner h-full rounded-full" data-percent="<?php echo e($percent); ?>" style="width:0%;background:linear-gradient(90deg,#3b82f6,#8b5cf6,#ec4899)"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-3">
                                <div class="text-xs text-slate-600">المتبقي</div>
                                <div class="text-sm font-bold text-blue-700"><?php echo e($remainStr); ?></div>
                            </div>
                            <a href="<?php echo e(route('goals.show', $goal)); ?>" class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg p-3 text-center hover:shadow-md transition-all">
                                <div class="text-xs text-slate-600">المهام</div>
                                <div class="text-sm font-bold text-indigo-700"><?php echo e($goal->tasks_count ?? $goal->tasks()->count()); ?> 📋</div>
                            </a>
                        </div>
                    </div>

                    <a href="<?php echo e(route('goals.show', $goal)); ?>" class="mt-4 w-full block text-center px-4 py-2 rounded-lg bg-gradient-to-r from-sky-500 to-indigo-600 text-white font-medium text-sm hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                        عرض الهدف →
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\camp's_project\resources\views/goals/index.blade.php ENDPATH**/ ?>