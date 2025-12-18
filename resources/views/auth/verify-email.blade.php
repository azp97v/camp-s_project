<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-slate-800 mb-2">✉️ تحقق من بريدك</h1>
        <p class="text-slate-600 text-sm">شكراً للتسجيل! يرجى التحقق من بريدك الإلكتروني بالنقر على الرابط المرسل إليك</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-sm font-medium text-green-700">
            ✓ تم إرسال رابط التحقق إلى بريدك الإلكتروني
        </div>
    @endif

    <div class="space-y-3 pt-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full btn-primary rounded-lg py-2.5 font-semibold hover:shadow-lg transition-all">
                {{ __('إعادة إرسال رابط التحقق') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full btn-ghost rounded-lg py-2.5 font-semibold hover:glass transition-all">
                {{ __('تسجيل الخروج') }}
            </button>
        </form>
    </div>
</x-guest-layout>
