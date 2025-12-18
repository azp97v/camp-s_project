<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo e(config('app.name')); ?> - <?php echo $__env->yieldContent('title'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400;1,700&family=Quintessential&display=swap" rel="stylesheet">
    <style>

        .glass {
            background: rgba(255,255,255,0.55);
            backdrop-filter: blur(8px) saturate(120%);
            -webkit-backdrop-filter: blur(8px) saturate(120%);
            border: 1px solid rgba(255,255,255,0.3);
            box-shadow: 0 4px 30px rgba(0,0,0,0.05);
        }
        body { background: linear-gradient(180deg,#f3f7fb,#eef6f9); }
    </style>
</head>
<body class="font-sans text-gray-800">
<nav class="p-4 glass border-b border-white/20 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="<?php echo e(url('/')); ?>" class="text-2xl font-bold bg-gradient-to-r from-sky-600 to-indigo-600 bg-clip-text text-transparent"><?php echo e(config('app.name')); ?></a>
            <div class="flex items-center gap-6">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('goals.index')); ?>" class="text-slate-700 hover:text-blue-600 font-medium transition-colors">🎯 الأهداف</a>
                    <a href="<?php echo e(route('dashboard')); ?>" class="text-slate-700 hover:text-blue-600 font-medium transition-colors">📊 لوحة التحكم</a>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <button class="text-slate-700 hover:text-red-600 font-medium transition-colors">🚪 خروج</button>
                    </form>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="text-slate-700 hover:text-blue-600 font-medium transition-colors">🔐 دخول</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </nav>

    <header class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent"><?php echo $__env->yieldContent('page-title'); ?></h1>
    </header>

    <main class="max-w-7xl mx-auto px-4 pb-12 site-container">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
            <div id="__session_toast" data-message="<?php echo e(session('success')); ?>"></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <footer class="mt-16 py-8 text-center text-sm text-slate-500 border-t border-slate-200/30">
        © <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?> • MADE BY BIN SAEED
    </footer>

    <!-- Live Timer Script for Task Time Tracking -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const trackedTimeEl = document.getElementById('tracked-time');
            if (!trackedTimeEl) return;

            const taskStatus = trackedTimeEl.dataset.taskStatus;
            const timerStart = parseInt(trackedTimeEl.dataset.timerStart) || 0;
            const totalTracked = parseInt(trackedTimeEl.dataset.totalTracked) || 0;

            // تحديث العداد كل ثانية
            function updateTimer() {
                let elapsedSeconds = totalTracked;

                // إذا كان المؤقت جاري (running)، أضف الثواني المنقضية
                if (taskStatus === 'running' && timerStart > 0) {
                    const now = Math.floor(Date.now() / 1000);
                    elapsedSeconds = totalTracked + (now - timerStart);
                }

                // تحويل الثواني إلى صيغة HH:MM:SS
                const hours = Math.floor(elapsedSeconds / 3600);
                const minutes = Math.floor((elapsedSeconds % 3600) / 60);
                const seconds = elapsedSeconds % 60;

                const timeStr =
                    String(hours).padStart(2, '0') + ':' +
                    String(minutes).padStart(2, '0') + ':' +
                    String(seconds).padStart(2, '0');

                trackedTimeEl.textContent = timeStr;
            }

            // تحديث أول مرة فوراً
            updateTimer();

            // إذا كان المؤقت جاري، حدّث كل ثانية
            if (taskStatus === 'running') {
                setInterval(updateTimer, 1000);
            }
        });
    </script>
</body>
</html>
<?php /**PATH C:\xampp\camp's_project\resources\views/layouts/main.blade.php ENDPATH**/ ?>