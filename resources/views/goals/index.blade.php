@extends('layouts.main')
@section('title','الأهداف')
@section('page-title','قائمة الأهداف')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div class="flex-1"></div>
        <a href="{{ route('goals.create') }}" class="px-6 py-3 glass rounded-xl text-white font-medium bg-gradient-to-r from-sky-500 via-blue-500 to-indigo-600 hover:shadow-lg transition-all duration-300 transform hover:scale-105">✨ إنشاء هدف جديد</a>
    </div>

    @if($goals->isEmpty())
        <div class="p-12 glass rounded-2xl text-center">
            <div class="text-6xl mb-4">🏃</div>
            <p class="text-xl text-slate-600 mb-4">لا توجد أهداف حتى الآن</p>
            <p class="text-slate-500 mb-6">ابدأ رحلتك بإنشاء هدفك الأول الآن</p>
            <a href="{{ route('goals.create') }}" class="inline-block px-6 py-2 rounded-lg bg-gradient-to-r from-sky-500 to-indigo-500 text-white font-medium hover:shadow-lg transition-all">إنشاء الهدف الأول</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($goals as $goal)
                @php
                    $percent = $goal->total_duration_seconds > 0 ? round((($goal->total_duration_seconds - $goal->remaining_duration_seconds) / $goal->total_duration_seconds) * 100) : 0;
                    $rem = $goal->remaining_duration_seconds;
                    if ($rem >= 86400) {
                        $d = floor($rem/86400);
                        $h = floor(($rem%86400)/3600);
                        $remainStr = $d . ' يوم' . ($h? ' و '.$h.' ساعة':'');
                    } else {
                        $h = floor($rem/3600);
                        $m = floor(($rem%3600)/60);
                        $remainStr = $h . ' ساعة' . ($m? ' و '.$m.' دقيقة':'');
                    }
                @endphp
                <div class="p-6 rounded-2xl glass animate-on-load card-hover border border-white/20 shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="flex justify-between items-start gap-4 mb-4">
                        <a href="{{ route('goals.show', $goal) }}" class="text-lg font-bold text-slate-900 line-clamp-2 hover:text-blue-600 transition-colors">
                            🏃 {{ $goal->title }}
                        </a>
                        <form method="POST" action="{{ route('goals.destroy', $goal) }}" class="flex-shrink-0">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg p-2 transition-all duration-200" onclick="return confirm('هل تريد حقاً حذف هذا الهدف؟')" title="حذف">
                                🗑️
                            </button>
                        </form>
                    </div>

                    @if($goal->description)
                        <p class="text-slate-600 text-sm mb-4 line-clamp-2">{{ $goal->description }}</p>
                    @endif

                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between text-xs mb-2">
                                <span class="font-semibold text-slate-700">التقدم: <span class="text-blue-600">{{ $percent }}%</span></span>
                                <span class="text-slate-500">{{ $goal->total_duration_seconds - $goal->remaining_duration_seconds }}/{{ $goal->total_duration_seconds }} ثانية</span>
                            </div>
                            <div class="progress-outer rounded-full h-3 bg-gradient-to-r from-slate-200 to-slate-100 overflow-hidden">
                                <div class="progress-inner h-full rounded-full" data-percent="{{ $percent }}" style="width:0%;background:linear-gradient(90deg,#3b82f6,#8b5cf6,#ec4899)"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-3">
                                <div class="text-xs text-slate-600">المتبقي</div>
                                <div class="text-sm font-bold text-blue-700">{{ $remainStr }}</div>
                            </div>
                            <a href="{{ route('goals.show', $goal) }}" class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg p-3 text-center hover:shadow-md transition-all">
                                <div class="text-xs text-slate-600">المهام</div>
                                <div class="text-sm font-bold text-indigo-700">{{ $goal->tasks_count ?? $goal->tasks()->count() }} 📋</div>
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('goals.show', $goal) }}" class="mt-4 w-full block text-center px-4 py-2 rounded-lg bg-gradient-to-r from-sky-500 to-indigo-600 text-white font-medium text-sm hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                        عرض الهدف →
                    </a>
                </div>
            @endforeach
        </div>
    @endif
@endsection
