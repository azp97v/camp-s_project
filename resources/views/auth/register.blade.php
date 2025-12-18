<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-slate-800 mb-2">إنشاء حساب جديد</h1>
        <p class="text-slate-600 text-sm">انضم إلينا وابدأ بإدارة أهدافك</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('الاسم')" />
            <x-text-input
                id="name"
                class="block mt-2 w-full px-4 py-2 rounded-lg border border-slate-200 bg-white/50 focus:outline-none focus:ring-2 focus:ring-sky-400"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
            />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600" />
        </div>

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
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('تأكيد كلمة المرور')" />
            <x-text-input
                id="password_confirmation"
                class="block mt-2 w-full px-4 py-2 rounded-lg border border-slate-200 bg-white/50 focus:outline-none focus:ring-2 focus:ring-sky-400"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col gap-3 pt-2">
            <button type="submit" class="w-full btn-primary rounded-lg py-2.5 font-semibold hover:shadow-lg transition-all">
                {{ __('إنشاء حساب') }}
            </button>

            <div class="text-center border-t border-slate-200/50 pt-4">
                <p class="text-sm text-slate-600">
                    هل لديك حساب بالفعل؟
                    <a class="text-sky-500 font-semibold hover:text-sky-700 transition-colors" href="{{ route('login') }}">
                        {{ __('تسجيل الدخول') }}
                    </a>
                </p>
            </div>
        </div>
    </form>
</x-guest-layout>
