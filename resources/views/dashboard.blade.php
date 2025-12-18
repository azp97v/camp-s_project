<x-app-layout>
    <x-slot name="header">
        {{ __('الداشبورد') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            <div class="glass rounded-2xl overflow-hidden shadow-xl p-8 mb-8 animate-on-load">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800 mb-2">مرحباً، {{ auth()->user()->name }}! 👋</h2>
                        <p class="text-slate-600">أهلاً وسهلاً في لوحة التحكم. إدارة أهدافك ومهامك بكل سهولة.</p>
                    </div>
                    <div class="text-5xl">📊</div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Goals Stat -->
                <div class="glass rounded-xl p-6 shadow-md hover:shadow-lg transition-all animate-on-load" style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-600 text-sm font-medium">الأهداف المتابعة</p>
                            <p class="text-3xl font-bold text-slate-800 mt-2">{{ \App\Models\Goal::where('user_id', auth()->id())->count() }}</p>
                        </div>
                        <div class="text-4xl">🎯</div>
                    </div>
                </div>

                <!-- Tasks Stat -->
                <div class="glass rounded-xl p-6 shadow-md hover:shadow-lg transition-all animate-on-load" style="animation-delay: 0.15s">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-600 text-sm font-medium">المهام النشطة</p>
                            <p class="text-3xl font-bold text-slate-800 mt-2">{{ \App\Models\Task::whereHas('goal', function($q) { $q->where('user_id', auth()->id()); })->where('status', '!=', 'finished')->count() }}</p>
                        </div>
                        <div class="text-4xl">✓</div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="glass rounded-2xl p-8 shadow-md animate-on-load" style="animation-delay: 0.2s">
                <h3 class="text-xl font-bold text-slate-800 mb-6">ابدأ الآن</h3>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('goals.index') }}" class="btn-primary rounded-lg py-3 px-6 text-center font-semibold hover:shadow-lg transition-all">
                        📋 عرض أهدافي
                    </a>
                    <a href="{{ route('goals.create') }}" class="btn-ghost rounded-lg py-3 px-6 text-center font-semibold hover:glass transition-all">
                        ➕ إنشاء هدف جديد
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
