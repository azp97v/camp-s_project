<?php $__env->startSection('title',$goal->title); ?>
<?php $__env->startSection('page-title',$goal->title); ?>

<?php $__env->startSection('content'); ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2">
            <div class="glass p-8 rounded-2xl animate-on-load card-hover border border-white/20 shadow-lg">
                <div class="flex items-start justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-3xl font-bold text-slate-900 mb-2">🎯 <?php echo e($goal->title); ?></h2>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($goal->description): ?>
                            <p class="text-slate-600 text-lg"><?php echo e($goal->description); ?></p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <div class="space-y-6">
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

                     <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="font-semibold text-slate-900">تقدم الهدف</span>
                            <span class="text-2xl font-bold text-blue-600"><?php echo e($percent); ?>%</span>
                        </div>
                        <div class="progress-outer rounded-full h-4 bg-gradient-to-r from-slate-200 to-slate-100 overflow-hidden">
                            <div class="progress-inner h-full rounded-full" data-percent="<?php echo e($percent); ?>" style="width:0%;background:linear-gradient(90deg,#3b82f6,#8b5cf6,#ec4899)"></div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 mt-6">
                            <div class="text-center">
                                <div class="text-sm text-slate-600">المتبقي</div>
                                <div class="text-lg font-bold text-slate-900"><?php echo e($remainStr); ?></div>
                            </div>
                            <div class="text-center">
                                <div class="text-sm text-slate-600">الإجمالي</div>
                                <div class="text-lg font-bold text-slate-900"><?php echo e(gmdate('H:i:s', $goal->total_duration_seconds)); ?></div>
                            </div>
                            <div class="text-center">
                                <div class="text-sm text-slate-600">المنجز</div>
                                <div class="text-lg font-bold text-blue-600"><?php echo e(gmdate('H:i:s', $goal->total_duration_seconds - $goal->remaining_duration_seconds)); ?></div>
                            </div>
                        </div>
                    </div>


                    <div>
                            
                            <h3 class="text-2xl font-bold text-slate-900 mb-4">📋 المهام (<span id="tasks-count"><?php echo e($tasks->count()); ?></span>)</h3>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tasks->isEmpty()): ?>
                            <div class="p-6 text-center bg-slate-50 rounded-lg">
                                <p class="text-slate-600">لا توجد مهام حتى الآن</p>
                            </div>
                        <?php else: ?>
                            
                            <div id="tasks-list" class="space-y-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route('tasks.show', $task)); ?>" class="glass p-4 rounded-lg card-hover border border-white/20 block hover:shadow-md transition-all group">
                                        <div class="flex justify-between items-start gap-3">
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-semibold text-slate-900 group-hover:text-blue-600 transition-colors"><?php echo e($task->title); ?></h4>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($task->description): ?>
                                                    <p class="text-sm text-slate-600 line-clamp-1"><?php echo e($task->description); ?></p>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <div class="flex gap-3 mt-2 text-xs text-slate-500">
                                                    <span>🕐 <?php echo e($task->status === 'running' ? 'جاري' : ($task->status === 'stopped' ? 'موقوف' : 'معلق')); ?></span>
                                                    <span>⏱️ <?php echo e(gmdate('H:i:s', $task->total_tracked_seconds)); ?></span>
                                                </div>
                                            </div>
                                            <form method="POST" action="<?php echo e(route('tasks.destroy', $task)); ?>" class="flex-shrink-0" onclick="event.stopPropagation();">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg p-2 transition-all" onclick="return confirm('حذف هذه المهمة؟')">
                                                    🗑️
                                                </button>
                                            </form>
                                        </div>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-200/30">
                    <a href="<?php echo e(route('goals.index')); ?>" class="px-4 py-2 text-blue-600 hover:text-blue-700 font-medium inline-flex items-center gap-2">
                        ← رجوع للأهداف
                    </a>
                </div>
            </div>
        </div>


                <div>
            <div class="glass p-6 rounded-2xl sticky top-24 border border-white/20 shadow-lg">
                <h3 class="text-lg font-bold text-slate-900 mb-4">➕ مهمة جديدة</h3>
                
                <form method="POST" action="<?php echo e(route('tasks.store', $goal)); ?>" class="ajax-form space-y-4">
                    <?php echo csrf_field(); ?>
                    <div>
                        <input name="title" placeholder="عنوان المهمة" required class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all" />
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-xs text-red-600 mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div>
                        <textarea name="description" placeholder="الوصف (اختياري)" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all resize-none" rows="3"></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-xs text-red-600 mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div>
                        <input name="deadline" type="datetime-local" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <input name="estimated_duration_input" type="number" min="0" placeholder="مدة" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all" />
                        </div>
                        <div>
                            <select name="estimated_unit" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all">
                                <option value="minutes">دقائق</option>
                                <option value="hours">ساعات</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="w-full px-4 py-2 rounded-lg bg-gradient-to-r from-sky-500 to-indigo-600 text-white font-medium hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                        إضافة المهمة
                    </button>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\camp's_project\resources\views/goals/show.blade.php ENDPATH**/ ?>