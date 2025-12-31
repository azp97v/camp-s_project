{{--
    Tasks Index Blade
    --------------------------------------------------------
    يعرِض قائمة المهام المجمعة من جميع الأهداف للمستخدم.
    English: Shows all tasks (flattened from user goals). Read-only comments only.
--}}
@extends('layouts.app')
@section('title','المهام')
@section('page-title','قائمة المهام')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/tasks-show.css') }}">
@endpush

@section('content')
<div class="glass p-6 rounded-2xl">
    <h2 class="text-2xl font-bold" style="color:var(--accent-color)">جميع المهام</h2>
    <div class="mt-4">
        @if($tasks->count())
            <ul class="space-y-3">
                @foreach($tasks as $task)
                    <li class="glass p-3 flex items-center justify-between">
                        <div>
                            <a href="{{ route('tasks.show', $task) }}" class="font-semibold text-lg">{{ $task->title }}</a>
                            <div class="text-sm" style="color:var(--text-color); opacity:0.8">{{ $task->goal?->title }}</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('tasks.edit', $task) }}" class="px-3 py-2 bg-blue-500 text-white rounded">تعديل</a>
                            <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('حذف المهمة؟')">
                                @csrf @method('DELETE')
                                <button class="px-3 py-2 bg-red-500 text-white rounded">حذف</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-muted">لا توجد مهام بعد.</div>
        @endif
    </div>
</div>
@endsection
