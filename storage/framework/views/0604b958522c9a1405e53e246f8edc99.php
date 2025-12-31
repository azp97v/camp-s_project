<?php $__env->startSection('no_navbar'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/login.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    <section class="login-section">
        <div class="container">
            <div class="login-card glass">
                <div class="login-logo glass">
                    <img src="<?php echo e(asset('assets/img/Screenshot 2025-12-22 232017.png')); ?>" alt="Step by Step Logo" class="logo-image">
                </div>

                <h2 class="login-title">تهانينا — تم تفعيل حسابك!</h2>
                <p class="login-subtitle">أصبح الآن بإمكانك الوصول إلى جميع مميزات Step by Step.</p>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->check()): ?>
                    <div class="alert alert-error glass" style="background-color:rgba(16,185,129,0.08);border-color:rgba(16,185,129,0.25);margin:1rem 0;">
                        <div class="alert-icon">🎉</div>
                        <div class="alert-content">
                            <p class="alert-title" style="color:#059669;">مرحباً <?php echo e(auth()->user()->name); ?> — تم تفعيل حسابك بنجاح</p>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div style="margin-top:1.5rem;display:flex;gap:0.75rem;flex-direction:column;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->check()): ?>
                        <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-primary btn-full">اذهب إلى لوحة التحكم</a>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="btn btn-primary btn-full">تسجيل الدخول الآن</a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <a href="<?php echo e(route('home')); ?>" class="btn btn-full" style="background:transparent;color:var(--accent-color);border:2px solid var(--accent-color);">العودة إلى الصفحة الرئيسية</a>
                </div>

                <div class="login-footer" style="margin-top:1.5rem;">
                    <p>شكراً لانضمامك إلى <strong>Step by Step</strong></p>
                </div>
            </div>
        </div>
    </section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\camp's_project\resources\views/auth/otp-success.blade.php ENDPATH**/ ?>