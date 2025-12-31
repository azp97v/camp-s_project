<?php $__env->startSection('title', 'الإعدادات - Step by Step'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/settings.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <section class="settings-section" dir="rtl" lang="ar">
        <div class="container">
            <!-- Header -->
            <div class="settings-header mb-8">
                <h1 class="settings-title">⚙️ الإعدادات</h1>
                <p class="settings-subtitle">إدارة حسابك وتفضيلاتك</p>
            </div>

            <!-- Profile Section -->
            <div class="settings-card glass">
                <div class="settings-card-header">
                    <h2 class="settings-card-title">👤 معلومات الحساب</h2>
                </div>
                <div class="settings-card-content">
                    <div class="profile-info">
                        <div class="info-item">
                            <label class="info-label">اسم المستخدم</label>
                            <p class="info-value"><?php echo e(auth()->user()->name ?? '-'); ?></p>
                        </div>
                        <div class="info-item">
                            <label class="info-label">البريد الإلكتروني</label>
                            <p class="info-value"><?php echo e(auth()->user()->email ?? '-'); ?></p>
                        </div>
                    </div>
                    <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-primary">✏️ تعديل البيانات</a>
                </div>
            </div>

            <!-- Password & Security -->
            <div class="settings-card glass">
                <div class="settings-card-header">
                    <h2 class="settings-card-title">🔒 الأمان</h2>
                </div>
                <div class="settings-card-content">
                    <p class="text-slate-600 mb-4">تحديث كلمة المرور والإعدادات الأمنية</p>
                    <a href="<?php echo e(route('profile.update')); ?>" class="btn btn-primary">🔑 تغيير كلمة المرور</a>
                </div>
            </div>

            <!-- Preferences -->
            <div class="settings-card glass">
                <div class="settings-card-header">
                    <h2 class="settings-card-title">🎨 التفضيلات</h2>
                </div>
                <div class="settings-card-content">
                    <div class="preference-item">
                        <label class="preference-label">
                            <input type="checkbox" class="preference-checkbox" checked>
                            <span>تفعيل إشعارات البريد الإلكتروني</span>
                        </label>
                    </div>
                    <div class="preference-item">
                        <label class="preference-label">
                            <input type="checkbox" class="preference-checkbox" checked>
                            <span>تذكيرات المهام القادمة</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="settings-card glass danger-zone">
                <div class="settings-card-header">
                    <h2 class="settings-card-title">⚠️ منطقة الخطر</h2>
                </div>
                <div class="settings-card-content">
                    <div class="danger-action">
                        <div>
                            <p class="danger-title">تسجيل الخروج</p>
                            <p class="danger-description">سيتم تسجيل خروجك من حسابك على جميع الأجهزة</p>
                        </div>
                        <form action="<?php echo e(route('logout')); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-logout">🚪 تسجيل الخروج</button>
                        </form>
                    </div>

                    <div class="danger-action">
                        <div>
                            <p class="danger-title">حذف الحساب</p>
                            <p class="danger-description">حذف حسابك وجميع بياناتك بشكل دائم</p>
                        </div>
                        <form action="<?php echo e(route('profile.destroy')); ?>" method="POST" class="inline" onsubmit="return confirm('هل تريد فعلاً حذف حسابك؟ لا يمكن التراجع عن هذا الإجراء.');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-delete">🗑️ حذف الحساب</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Back Link -->
            <div class="text-center mt-8">
                <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-ghost">← العودة إلى اللوحة</a>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\camp's_project\resources\views/settings/index.blade.php ENDPATH**/ ?>