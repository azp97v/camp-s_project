<x-guest-layout>
    <section class="login-section">
        <div class="login-card">
            <div class="login-logo">
                <img src="/assets/images/logo.png" alt="logo" class="logo-image" onerror="this.style.display='none'">
            </div>

            <h2 class="login-title">🔑 إعادة تعيين كلمة المرور</h2>
            <p class="login-subtitle">أدخل كلمة مرور جديدة قوية</p>

            <form method="POST" action="{{ route('password.store') }}" class="w-full" style="text-align:right;">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div style="text-align:right;">
                    <x-input-label for="email" :value="__('البريد الإلكتروني')" />
                    <x-text-input id="email" class="form-input mt-2 w-full px-4 py-2 rounded-lg" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm error-text" />
                </div>

                <!-- Password -->
                <div style="text-align:right;">
                    <x-input-label for="password" :value="__('كلمة المرور الجديدة')" />
                    <x-text-input id="password" class="form-input mt-2 w-full px-4 py-2 rounded-lg" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm error-text" />
                </div>

                <!-- Confirm Password -->
                <div style="text-align:right;">
                    <x-input-label for="password_confirmation" :value="__('تأكيد كلمة المرور')" />
                    <x-text-input id="password_confirmation" class="form-input mt-2 w-full px-4 py-2 rounded-lg" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm error-text" />
                </div>

                <div class="pt-4">
                    <button type="submit" class="btn-full" style="width:100%">{{ __('إعادة تعيين كلمة المرور') }}</button>
                </div>
            </form>
        </div>
    </section>
</x-guest-layout>
