@extends('layouts.main')
@section('title',$goal->title)
@section('page-title',$goal->title)

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2">
            <div class="glass p-8 rounded-2xl animate-on-load card-hover border border-white/20 shadow-lg">
                <div class="flex items-start justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-3xl font-bold text-slate-900 mb-2">🎯 {{ $goal->title }}</h2>
                        @if($goal->description)
                            <p class="text-slate-600 text-lg">{{ $goal->description }}</p>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
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

                     <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="font-semibold text-slate-900">تقدم الهدف</span>
                            <span class="text-2xl font-bold text-blue-600">{{ $percent }}%</span>
                        </div>
                        <div class="progress-outer rounded-full h-4 bg-gradient-to-r from-slate-200 to-slate-100 overflow-hidden">
                            <div class="progress-inner h-full rounded-full" data-percent="{{ $percent }}" style="width:0%;background:linear-gradient(90deg,#3b82f6,#8b5cf6,#ec4899)"></div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 mt-6">
                            <div class="text-center">
                                <div class="text-sm text-slate-600">المتبقي</div>
                                <div class="text-lg font-bold text-slate-900">{{ $remainStr }}</div>
                            </div>
                            <div class="text-center">
                                <div class="text-sm text-slate-600">الإجمالي</div>
                                <div class="text-lg font-bold text-slate-900">{{ gmdate('H:i:s', $goal->total_duration_seconds) }}</div>
                            </div>
                            <div class="text-center">
                                <div class="text-sm text-slate-600">المنجز</div>
                                <div class="text-lg font-bold text-blue-600">{{ gmdate('H:i:s', $goal->total_duration_seconds - $goal->remaining_duration_seconds) }}</div>
                            </div>
                        </div>
                    </div>


                    <div>
                            {{-- The tasks list area is updated by `resources/js/ui.js` when a task is created via AJAX.
                                - #tasks-count: numeric badge for quick updates
                                - #tasks-list: container where new task items are injected
                            --}}
                            <h3 class="text-2xl font-bold text-slate-900 mb-4">📋 المهام (<span id="tasks-count">{{ $tasks->count() }}</span>)</h3>

                        @if($tasks->isEmpty())
                            <div class="p-6 text-center bg-slate-50 rounded-lg">
                                <p class="text-slate-600">لا توجد مهام حتى الآن</p>
                            </div>
                        @else
                            {{-- #tasks-list: existing tasks are rendered here; new tasks are inserted at the top by JS. --}}
                            <div id="tasks-list" class="space-y-3">
                                @foreach($tasks as $task)
                                    <a href="{{ route('tasks.show', $task) }}" class="glass p-4 rounded-lg card-hover border border-white/20 block hover:shadow-md transition-all group">
                                        <div class="flex justify-between items-start gap-3">
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-semibold text-slate-900 group-hover:text-blue-600 transition-colors">{{ $task->title }}</h4>
                                                @if($task->description)
                                                    <p class="text-sm text-slate-600 line-clamp-1">{{ $task->description }}</p>
                                                @endif
                                                <div class="flex gap-3 mt-2 text-xs text-slate-500">
                                                    <span>🕐 {{ $task->status === 'running' ? 'جاري' : ($task->status === 'stopped' ? 'موقوف' : 'معلق') }}</span>
                                                    <span>⏱️ {{ gmdate('H:i:s', $task->total_tracked_seconds) }}</span>
                                                </div>
                                            </div>
                                            <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="flex-shrink-0" onclick="event.stopPropagation();">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg p-2 transition-all" onclick="return confirm('حذف هذه المهمة؟')">
                                                    🗑️
                                                </button>
                                            </form>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-200/30">
                    <a href="{{ route('goals.index') }}" class="px-4 py-2 text-blue-600 hover:text-blue-700 font-medium inline-flex items-center gap-2">
                        ← رجوع للأهداف
                    </a>
                </div>
            </div>
        </div>


                <div>
            <div class="glass p-6 rounded-2xl sticky top-24 border border-white/20 shadow-lg">
                <h3 class="text-lg font-bold text-slate-900 mb-4">➕ مهمة جديدة</h3>
                {{-- The creation form uses `ajax-form` and will POST via fetch; `resources/js/ui.js`
                     will inject the returned `task` JSON into `#tasks-list` to avoid a full reload. --}}
                <form method="POST" action="{{ route('tasks.store', $goal) }}" class="ajax-form space-y-4">
                    @csrf
                    <div>
                        <input name="title" placeholder="عنوان المهمة" required class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all" />
                        @error('title') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <textarea name="description" placeholder="الوصف (اختياري)" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all resize-none" rows="3"></textarea>
                        @error('description') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <input name="deadline" type="datetime-local" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <input name="estimated_duration_input" type="number" min="0" placeholder="مدة" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all" />
                        </div>
                        <div>
                            <select name="estimated_unit" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all">
                                <option value="minutes">دقائق</option>
                                <option value="hours">ساعات</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="w-full px-4 py-2 rounded-lg bg-gradient-to-r from-sky-500 to-indigo-600 text-white font-medium hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                        إضافة المهمة
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
