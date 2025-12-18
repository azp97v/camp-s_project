<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-slate-800 mb-2">🔒 تأكيد الهوية</h1>
        <p class="text-slate-600 text-sm">هذه منطقة آمنة. يرجى تأكيد كلمة المرور قبل المتابعة</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('كلمة المرور')" />

            <x-text-input id="password" class="block mt-2 w-full px-4 py-2 rounded-lg border border-slate-200 bg-white/50 focus:outline-none focus:ring-2 focus:ring-sky-400"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full btn-primary rounded-lg py-2.5 font-semibold hover:shadow-lg transition-all">
                {{ __('تأكيد') }}
            </button>
        </div>
    </form>
</x-guest-layout>
