<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-slate-800 mb-2">نسيت كلمة المرور؟</h1>
        <p class="text-slate-600 text-sm">لا مشكلة، أخبرنا عن بريدك الإلكتروني وسنرسل لك رابط إعادة تعيين</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('البريد الإلكتروني')" />
            <x-text-input id="email" class="block mt-2 w-full px-4 py-2 rounded-lg border border-slate-200 bg-white/50 focus:outline-none focus:ring-2 focus:ring-sky-400" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="flex gap-3 pt-2">
            <a href="{{ route('login') }}" class="flex-1 text-center btn-ghost rounded-lg py-2.5 font-semibold">
                {{ __('العودة للدخول') }}
            </a>
            <button type="submit" class="flex-1 btn-primary rounded-lg py-2.5 font-semibold hover:shadow-lg transition-all">
                {{ __('إرسال رابط إعادة التعيين') }}
            </button>
        </div>
    </form>
</x-guest-layout>
