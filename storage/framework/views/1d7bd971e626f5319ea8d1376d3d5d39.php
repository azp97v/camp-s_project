<?php $__env->startSection('title', 'الملف الشخصي - Step by Step'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/profile.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <section class="profile-section" dir="rtl" lang="ar">
        <div class="container">
            <!-- Header -->
            <div class="profile-header mb-8">
                <h1 class="profile-title">👤 الملف الشخصي</h1>
                <p class="profile-subtitle">إدارة معلومات حسابك وإعداداتك الشخصية</p>
            </div>

            <!-- Profile Information Card -->
            <div class="profile-card glass">
                <div class="profile-card-header">
                    <h2 class="profile-card-title">ℹ️ معلومات الحساب</h2>
                </div>
                <div class="profile-card-content">
                    <form method="post" action="<?php echo e(route('profile.update')); ?>" class="space-y-6">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('patch'); ?>

                        <!-- Name Field -->
                        <div class="form-group">
                            <label for="name" class="form-label">اسم المستخدم</label>
                            <input id="name" name="name" type="text"
                                class="form-input"
                                value="<?php echo e(old('name', $user->name)); ?>"
                                required autofocus autocomplete="name" />
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="form-error"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <!-- Email Field -->
                        <div class="form-group">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input id="email" name="email" type="email"
                                class="form-input"
                                value="<?php echo e(old('email', $user->email)); ?>"
                                required autocomplete="username" />
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="form-error"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail()): ?>
                                <div class="verification-notice mt-3">
                                    <p class="text-warning">⚠️ بريدك الإلكتروني لم يتم التحقق منه بعد.</p>
                                    <form id="send-verification" method="post" action="<?php echo e(route('verification.send')); ?>" class="inline mt-2">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="text-link">أعد إرسال بريد التحقق</button>
                                    </form>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status') === 'verification-link-sent'): ?>
                                    <p class="text-success mt-2">✓ تم إرسال بريد التحقق إلى بريدك الإلكتروني.</p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">💾 حفظ التغييرات</button>
                            <?php if(session('status') === 'profile-updated'): ?>
                                <p class="text-success">✓ تم تحديث الملف الشخصي بنجاح!</p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Password Update Card -->
            <div class="profile-card glass">
                <div class="profile-card-header">
                    <h2 class="profile-card-title">🔒 تحديث كلمة المرور</h2>
                </div>
                <div class="profile-card-content">
                    <form id="password-form" method="post" action="<?php echo e(route('user.password.update')); ?>" class="space-y-6">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('put'); ?>

                        <!-- Current Password -->
                        <div class="form-group">
                            <label for="update_password_current_password" class="form-label">كلمة المرور الحالية</label>
                            <input id="update_password_current_password" name="current_password" type="password"
                                class="form-input"
                                autocomplete="current-password" required />
                            <span class="form-error" id="error-current_password" style="display: none;"></span>
                        </div>

                        <!-- New Password -->
                        <div class="form-group">
                            <label for="update_password_password" class="form-label">كلمة المرور الجديدة</label>
                            <input id="update_password_password" name="password" type="password"
                                class="form-input"
                                autocomplete="new-password" required />
                            <span class="form-error" id="error-password" style="display: none;"></span>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <label for="update_password_password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                                class="form-input"
                                autocomplete="new-password" required />
                            <span class="form-error" id="error-password_confirmation" style="display: none;"></span>
                        </div>

                        <!-- Submit Button and Messages -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" id="submit-btn">🔑 تحديث كلمة المرور</button>
                            <div id="success-message" class="text-success" style="display: none; margin-top: 1rem;">
                                ✓ تم تحديث كلمة المرور بنجاح!
                            </div>
                            <div id="general-error" class="form-error" style="display: none; margin-top: 1rem;"></div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danger Zone: Delete Account -->
            <div class="profile-card glass danger-zone">
                <div class="profile-card-header">
                    <h2 class="profile-card-title">⚠️ منطقة الخطر</h2>
                </div>
                <div class="profile-card-content">
                    <div class="danger-action">
                        <div>
                            <p class="danger-title">حذف الحساب</p>
                            <p class="danger-description">حذف حسابك وجميع بياناتك بشكل دائم. لا يمكن التراجع عن هذا الإجراء.</p>
                        </div>
                        <button type="button" class="btn btn-delete" onclick="confirmDeleteAccount()">🗑️ حذف الحساب</button>
                    </div>

                    <!-- Delete Account Form (Hidden) -->
                    <form id="delete-account-form" method="post" action="<?php echo e(route('profile.destroy')); ?>" style="display:none;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <input type="password" name="password" id="delete-password" style="display:none;">
                    </form>
                </div>
            </div>

            <!-- Back Link -->
            <div class="text-center mt-8">
                <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-ghost">← العودة إلى اللوحة</a>
            </div>
        </div>
    </section>

    <script>
        async function confirmDeleteAccount() {
            const password = prompt('أدخل كلمة المرور لتأكيد حذف الحساب:');
            if (password === null || password.trim() === '') {
                return;
            }

            try {
                const response = await fetch('<?php echo e(route("profile.destroy")); ?>', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        password: password
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    // Show success message
                    if (typeof showToast === 'function') {
                        showToast('تم حذف حسابك بنجاح. سيتم تحويلك قريباً...', 'success');
                    } else {
                        alert('تم حذف حسابك بنجاح!');
                    }

                    // Redirect after 2 seconds
                    setTimeout(() => {
                        window.location.href = '/';
                    }, 2000);
                } else {
                    // Show error message
                    const errorMessage = data.message || 'كلمة المرور غير صحيحة';
                    if (typeof showToast === 'function') {
                        showToast(errorMessage, 'error');
                    } else {
                        alert(errorMessage);
                    }
                }
            } catch (error) {
                const errorMessage = 'خطأ في الاتصال: ' + error.message;
                if (typeof showToast === 'function') {
                    showToast(errorMessage, 'error');
                } else {
                    alert(errorMessage);
                }
                console.error('Error:', error);
            }
        }

        // AJAX form submission for password update
        document.getElementById('password-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            // Clear previous errors and messages
            document.querySelectorAll('.form-error').forEach(el => el.style.display = 'none');
            document.getElementById('success-message').style.display = 'none';
            document.getElementById('general-error').style.display = 'none';
            document.getElementById('submit-btn').disabled = true;

            const formData = new FormData(this);
            const url = this.getAttribute('action');

            // Get CSRF token from meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // Add CSRF token to form data
            if (csrfToken) {
                formData.append('_token', csrfToken);
            }

            try {
                const response = await fetch(url, {
                    method: 'POST',  // Use POST instead of PUT
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok) {
                    // Success: show success message and clear form
                    document.getElementById('success-message').style.display = 'block';
                    document.getElementById('password-form').reset();

                    // Hide success message after 3 seconds
                    setTimeout(() => {
                        document.getElementById('success-message').style.display = 'none';
                    }, 3000);
                } else {
                    // Handle validation errors (422) or other errors
                    if (data.errors && typeof data.errors === 'object') {
                        for (const [field, messages] of Object.entries(data.errors)) {
                            const errorEl = document.getElementById(`error-${field}`);
                            if (errorEl) {
                                // messages can be array or string
                                const message = Array.isArray(messages) ? messages[0] : messages;
                                errorEl.textContent = message;
                                errorEl.style.display = 'block';
                            }
                        }
                    } else if (data.message) {
                        document.getElementById('general-error').textContent = data.message;
                        document.getElementById('general-error').style.display = 'block';
                    } else {
                        document.getElementById('general-error').textContent = 'حدث خطأ أثناء التحديث';
                        document.getElementById('general-error').style.display = 'block';
                    }
                }
            } catch (error) {
                document.getElementById('general-error').textContent = 'خطأ في الاتصال: ' + error.message;
                document.getElementById('general-error').style.display = 'block';
                console.error('Error:', error);
            } finally {
                document.getElementById('submit-btn').disabled = false;
            }
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\camp's_project\resources\views/profile/edit.blade.php ENDPATH**/ ?>