<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/login.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    <!-- OTP Verification Section -->
    <section class="login-section">
        <div class="container">
            <div class="login-card glass">
                <!-- Logo -->
                <div class="login-logo glass">
                    <img src="<?php echo e(asset('assets/img/Screenshot 2025-12-22 232017.png')); ?>" alt="Step by Step Logo" class="logo-image">
                </div>

                <!-- Header -->
                <h2 class="login-title">تحقق من بريدك</h2>
                <p class="login-subtitle">أدخل رمز التحقق المرسل إليك</p>

                <!-- Success Message -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
                    <div class="alert alert-error glass" style="background-color:rgba(16,185,129,0.15);border-color:rgba(16,185,129,0.5);">
                        <div class="alert-icon">✅</div>
                        <div class="alert-content">
                            <p class="alert-title" style="color:#059669;"><?php echo e(session('status')); ?></p>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Countdown Timer for OTP Validity -->
                <div style="background:rgba(181,123,74,0.1);border:2px solid rgba(181,123,74,0.3);border-radius:10px;padding:1.5rem;margin-bottom:1.5rem;text-align:center;">
                    <p style="color:#666;font-size:0.9rem;margin-bottom:0.5rem;">صلاحية الرمز المتبقية:</p>
                    <div id="countdown-timer" style="font-size:2.5rem;font-weight:700;color:#b57b4a;font-family:'Courier New',monospace;letter-spacing:8px;">
                        10:00
                    </div>
                </div>

                <!-- OTP Form - Combined Form -->
                <form class="login-form" method="POST" action="<?php echo e(route('otp.verify.post')); ?>" id="otp-form">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="email" value="<?php echo e(session('otp_email') ?? $email ?? old('email')); ?>">

                    <!-- OTP Input -->
                    <div class="form-group">
                        <label for="otp" class="form-label">رمز التحقق (6 أحرف)</label>
                        <input
                            type="text"
                            id="otp"
                            name="otp"
                            class="form-input <?php $__errorArgs = ['otp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> input-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="أدخل الرمز هنا"
                            maxlength="6"
                            inputmode="numeric"
                            pattern="[A-Z0-9]{6}"
                            required
                            autofocus
                            style="text-align:center;font-size:1.3rem;letter-spacing:6px;font-weight:700;font-family:'Courier New',monospace;"
                        >
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['otp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="error-text"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary btn-full" style="margin-bottom:1rem;">تحقق من الرمز</button>

                    <!-- Resend Button -->
                    <button
                        type="button"
                        id="resend-btn"
                        class="btn btn-full"
                        style="background:transparent;color:var(--accent-color);border:2px solid var(--accent-color);font-weight:600;margin-bottom:0.5rem;"
                        disabled
                    >
                        🔄 إعادة إرسال الرمز
                    </button>
                    <p id="resend-info" data-seconds="<?php echo e($secondsUntilResend ?? 0); ?>" style="color:#666;font-size:0.85rem;margin-top:0.4rem;display:none;">
                        يمكنك إعادة الإرسال بعد <span id="resend-count">00:00</span>
                    </p>

                    <!-- Back to Login Link -->
                    <div class="login-footer">
                        <p><a href="<?php echo e(route('login')); ?>" class="register-link" style="margin-top:0.6rem;display:inline-block;">العودة لتسجيل الدخول</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        const countdownTimer = document.getElementById('countdown-timer');
        const resendBtn = document.getElementById('resend-btn');
        const resendInfo = document.getElementById('resend-info');
        const resendCount = document.getElementById('resend-count');
        const otpInput = document.getElementById('otp');
        const otpForm = document.getElementById('otp-form');

        // Server-provided timings
        let remainingSeconds = <?php echo e((int) ($remainingValidity ?? 0)); ?>; // seconds until OTP expires
        let secondsUntilResend = <?php echo e((int) ($secondsUntilResend ?? 0)); ?>; // seconds until resend allowed
        const resendCooldown = <?php echo e((int) ($resendCooldown ?? 90)); ?>; // default cooldown

        // Auto-format OTP input (uppercase only)
        otpInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        });

        function formatMMSS(sec) {
            const minutes = Math.floor(sec / 60);
            const seconds = sec % 60;
            return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }

        // Update countdown display
        function updateValidityDisplay() {
            countdownTimer.textContent = formatMMSS(Math.max(0, remainingSeconds));
            if (remainingSeconds <= 0) {
                // OTP expired: disable submit
                otpForm.querySelector('button[type="submit"]').disabled = true;
                otpForm.querySelector('button[type="submit"]').style.opacity = '0.6';
            }
        }

        // Update resend display
        function updateResendDisplay() {
            if (secondsUntilResend > 0) {
                resendInfo.style.display = 'block';
                resendCount.textContent = formatMMSS(secondsUntilResend);
                resendBtn.disabled = true;
                resendBtn.style.opacity = '0.5';
                resendBtn.style.cursor = 'not-allowed';
            } else {
                resendInfo.style.display = 'none';
                resendBtn.disabled = false;
                resendBtn.style.opacity = '1';
                resendBtn.style.cursor = 'pointer';
            }
        }

        // Run timers
        let validityInterval = null;
        let resendInterval = null;

        function startTimers() {
            // Clear any existing
            if (validityInterval) clearInterval(validityInterval);
            if (resendInterval) clearInterval(resendInterval);

            // validity
            updateValidityDisplay();
            validityInterval = setInterval(() => {
                remainingSeconds = Math.max(0, remainingSeconds - 1);
                updateValidityDisplay();
                if (remainingSeconds <= 0) {
                    clearInterval(validityInterval);
                }
            }, 1000);

            // resend
            updateResendDisplay();
            resendInterval = setInterval(() => {
                if (secondsUntilResend > 0) {
                    secondsUntilResend = Math.max(0, secondsUntilResend - 1);
                    updateResendDisplay();
                } else {
                    clearInterval(resendInterval);
                }
            }, 1000);
        }

        // Resend button click handler
        resendBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (resendBtn.disabled) return;

            const email = otpForm.querySelector('input[name="email"]').value;
            const formData = new FormData();
            formData.append('email', email);
            formData.append('_token', otpForm.querySelector('[name="_token"]').value);

            fetch('<?php echo e(route("otp.resend")); ?>', {
                method: 'POST',
                body: formData,
                headers: { 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (!data) {
                    showToast('❌ استجابة غير متوقعة من الخادم', 'error');
                    return;
                }

                if (data.status === 'sent') {
                    showAnchoredToast(resendBtn, '✅ تم إرسال رمز جديد إلى بريدك', 'success');
                    // apply server timings
                    secondsUntilResend = data.secondsUntilResend ?? resendCooldown;
                    remainingSeconds = data.remainingValidity ?? remainingSeconds;
                    startTimers();
                } else if (data.status === 'already_sent') {
                    // Server indicates a recent code was already sent
                    secondsUntilResend = data.secondsUntilResend ?? resendCooldown;
                    remainingSeconds = data.remainingValidity ?? remainingSeconds;
                    startTimers();
                    showAnchoredToast(resendBtn, '⚠️ تم إرسال رمز بالفعل. تحقق من بريدك أو انتظر قليلاً.', 'info');
                } else {
                    showAnchoredToast(resendBtn, '❌ حدث خطأ في إعادة الإرسال', 'error');
                }
            })
            .catch(err => showToast('❌ حدث خطأ: ' + err.message, 'error'));
        });

        // Show anchored toast near an element (resend button or card)
        function showAnchoredToast(anchorEl, message, type = 'info', duration = 3500) {
            if (!anchorEl) return showToast(message, type, duration);
            const rect = anchorEl.getBoundingClientRect();
            const container = document.createElement('div');
            let bgColor = '#2563eb'; // default blue for info
            if (type === 'success') bgColor = '#16a34a';
            if (type === 'error') bgColor = '#dc2626';
            if (type === 'warning' || type === 'info') bgColor = '#f59e0b';

            container.style.cssText = `
                position: fixed;
                left: ${rect.left + rect.width / 2}px;
                top: ${rect.top - 12}px;
                z-index: 1200;
                background: ${bgColor};
                color: #fff;
                padding: 0.7rem 1rem;
                border-radius: 8px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.22);
                font-weight: 600;
                transform: translate(-50%, -100%);
                max-width: 90vw;
                text-align: center;
            `;
            container.textContent = message;

            // If there's not enough space above, place below the anchor
            if (rect.top < 80) {
                container.style.top = (rect.bottom + 12) + 'px';
                container.style.transform = 'translate(-50%, 0)';
            }

            document.body.appendChild(container);
            setTimeout(() => container.remove(), duration);
        }

        // Fallback generic toast (top-right)
        function showToast(message, type = 'info', duration = 3500) {
            const container = document.createElement('div');
            let bgColor = '#2563eb';
            if (type === 'success') bgColor = '#16a34a';
            if (type === 'error') bgColor = '#dc2626';
            if (type === 'warning' || type === 'info') bgColor = '#f59e0b';
            container.style.cssText = `
                position: fixed;
                top: 1.5rem;
                right: 1.5rem;
                z-index: 999;
                background: ${bgColor};
                color: #fff;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                box-shadow: 0 6px 16px rgba(0,0,0,0.2);
                font-weight: 500;
                animation: slideIn 0.3s ease;
                max-width: 90vw;
            `;
            container.textContent = message;
            document.body.appendChild(container);
            setTimeout(() => container.remove(), duration);
        }

        // If server passed a flash status message, show it as a prominent toast
        const serverStatus = <?php echo json_encode(session('status'), 15, 512) ?>;
        if (serverStatus) {
            const loginCardEl = document.querySelector('.login-card');
            if (loginCardEl) {
                showAnchoredToast(loginCardEl, serverStatus, 'success', 3800);
            } else {
                showToast(serverStatus, 'success', 3800);
            }
        }

        // Initialize timers on page load using server-provided values
        startTimers();

        // If server returned a validation error for the OTP field, show inline feedback
        const otpFieldError = <?php echo json_encode($errors->first('otp') ?? null, 15, 512) ?>;
        if (otpFieldError) {
            // add error styling and focus the field so the user notices immediately
            if (otpInput) {
                otpInput.classList.add('input-error');
                // add a short shake animation
                otpInput.classList.add('shake');
                try { otpInput.focus(); } catch(e) {}
                try { otpInput.scrollIntoView({ behavior: 'smooth', block: 'center' }); } catch(e) {}
                // show persistent anchored error toast (duration 0 = persistent)
                otpErrorToastEl = showAnchoredToast(otpInput, otpFieldError, 'error', 0);
                // remove shake shortly after
                setTimeout(() => otpInput.classList.remove('shake'), 500);
            } else {
                showToast(otpFieldError, 'error', 5000);
            }
        }

        // Remove shake quickly and only clear the error when the input looks valid (6 alnum chars)
        let otpErrorToastEl = null;
        if (otpInput) {
            otpInput.addEventListener('input', function() {
                // remove shake if user starts typing
                if (this.classList.contains('shake')) {
                    this.classList.remove('shake');
                }

                const cleaned = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
                // if user typed a valid-looking OTP (6 alnum), clear visual error
                if (/^[A-Z0-9]{6}$/.test(cleaned)) {
                    this.classList.remove('input-error');
                    // remove persistent error toast if present
                    try { if (otpErrorToastEl && otpErrorToastEl.remove) otpErrorToastEl.remove(); } catch(e) {}
                }
            });
        }
    </script>

    <style>
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #otp {
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        #otp:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(181, 123, 74, 0.1);
            color: rgba(255, 255, 255, 0.6);

        }

        #otp.input-error {
            border-color: #dc2626 !important;
            background-color: rgba(220, 38, 38, 0.05);
        }

        /* Shake animation for small input feedback */
        @keyframes subtleShake {
            0% { transform: translateX(0); }
            20% { transform: translateX(-6px); }
            40% { transform: translateX(6px); }
            60% { transform: translateX(-4px); }
            80% { transform: translateX(4px); }
            100% { transform: translateX(0); }
        }
        .shake {
            animation: subtleShake 0.45s ease-in-out;
        }

        .input-error {
            border-color: #dc2626 !important;
            box-shadow: 0 6px 18px rgba(220,38,38,0.08);

        }
    </style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\camp's_project\resources\views/auth/otp-verify.blade.php ENDPATH**/ ?>