<section>
    <header>
        <h2 class="text-xl font-bold text-slate-800 mb-1">
            {{ __('معلومات الملف الشخصي') }}
        </h2>

        <p class="text-slate-600 text-sm">
            {{ __('قم بتحديث معلومات حسابك والبريد الإلكتروني الخاص بك.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('الاسم')" />
            <x-text-input id="name" name="name" type="text" class="mt-2 block w-full px-4 py-2 rounded-lg border border-slate-200 bg-white/50 focus:outline-none focus:ring-2 focus:ring-sky-400" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('البريد الإلكتروني')" />
            <x-text-input id="email" name="email" type="email" class="mt-2 block w-full px-4 py-2 rounded-lg border border-slate-200 bg-white/50 focus:outline-none focus:ring-2 focus:ring-sky-400" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-amber-700 bg-amber-50 p-3 rounded-lg border border-amber-200">
                        ⚠️ {{ __('بريدك الإلكتروني لم يتم التحقق منه بعد.') }}

                        <button form="send-verification" class="text-sky-600 font-semibold hover:text-sky-700 underline ms-1">
                            {{ __('اضغط هنا لإعادة إرسال بريد التحقق.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-lg">
                            ✓ {{ __('تم إرسال بريد التحقق إلى بريدك الإلكتروني.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-4">
            <x-primary-button>{{ __('💾 حفظ') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-semibold"
                >✓ {{ __('تم الحفظ بنجاح.') }}</p>
            @endif
        </div>
    </form>
</section>
