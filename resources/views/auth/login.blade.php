<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-slate-800 mb-2">مرحباً بك</h1>
        <p class="text-slate-600 text-sm">قم بتسجيل الدخول للمتابعة</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('البريد الإلكتروني')" />
            <x-text-input
                id="email"
                class="block mt-2 w-full px-4 py-2 rounded-lg border border-slate-200 bg-white/50 focus:outline-none focus:ring-2 focus:ring-sky-400"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('كلمة المرور')" />
            <x-text-input
                id="password"
                class="block mt-2 w-full px-4 py-2 rounded-lg border border-slate-200 bg-white/50 focus:outline-none focus:ring-2 focus:ring-sky-400"
                type="password"
                name="password"
                required
                autocomplete="current-password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input
                id="remember_me"
                type="checkbox"
                class="rounded border-slate-300 text-sky-400 shadow-sm focus:ring-sky-400"
                name="remember"
            >
            <label for="remember_me" class="ms-2 text-sm text-slate-700">
                {{ __('تذكرني') }}
            </label>
        </div>

        <!-- Action Links -->
        <div class="flex flex-col gap-3">
            <button type="submit" class="w-full btn-primary rounded-lg py-2.5 font-semibold hover:shadow-lg transition-all">
                {{ __('تسجيل الدخول') }}
            </button>

            @if (Route::has('password.request'))
                <a class="text-center text-sm text-sky-500 hover:text-sky-700 transition-colors" href="{{ route('password.request') }}">
                    {{ __('نسيت كلمة المرور؟') }}
                </a>
            @endif

            @if (Route::has('register'))
                <div class="text-center pt-2 border-t border-slate-200/50">
                    <p class="text-sm text-slate-600">
                        ليس لديك حساب؟
                        <a class="text-sky-500 font-semibold hover:text-sky-700 transition-colors" href="{{ route('register') }}">
                            {{ __('إنشاء حساب جديد') }}
                        </a>
                    </p>
                </div>
            @endif
        </div>
    </form>
</x-guest-layout>
