<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/index.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">حقق أهدافك بذكاء وسهولة</h1>
                <p class="hero-description">
                    Step by Step هو نظام متقدم لإدارة الأهداف والمهام يساعدك على تحويل أحلامك إلى واقع ملموس.
                    خطط، نفذ، وتابع تقدمك بطريقة احترافية وممتعة.
                </p>
                <div class="hero-buttons">
                   <a href="<?php echo e(route('register')); ?>" class="btn btn-primary">ابدأ الآن مجاناً</a>
<a href="<?php echo e(route('login')); ?>" class="btn btn-glass">تسجيل الدخول</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <h2 class="section-title text-center">لماذا Step by Step؟</h2>
            <div class="features-grid">
                <!-- Feature 1 -->
                <div class="feature-card glass">
                    <div class="feature-icon">🎯</div>
                    <h3 class="feature-title">إدارة الأهداف</h3>
                    <p class="feature-description">
                        حدد أهدافك بوضوح، قسمها إلى مهام صغيرة، وتابع تقدمك خطوة بخطوة نحو النجاح.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card glass">
                    <div class="feature-icon">📊</div>
                    <h3 class="feature-title">تتبع التقدم</h3>
                    <p class="feature-description">
                        احصل على رؤية واضحة لإنجازاتك من خلال إحصائيات مفصلة ورسوم بيانية تفاعلية.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card glass">
                    <div class="feature-icon">⏰</div>
                    <h3 class="feature-title">تذكير ذكي</h3>
                    <p class="feature-description">
                        لن تفوتك أي مهمة مع نظام التذكير الذكي الذي يساعدك على البقاء على المسار الصحيح.
                    </p>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\camp's_project\resources\views/index.blade.php ENDPATH**/ ?>