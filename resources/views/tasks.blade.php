@extends('layouts.app')

@section('title', $goal->title . ' - المهام')
@section('page-title', '🎯 ' . $goal->title)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/tasks.css') }}">
@endpush

@section('content')
    <section class="goal-show-section tasks-section" dir="rtl" lang="ar">
        <div class="container">
            <!-- Goal Header with Progress Circle -->
            @php
                $completedCount = $tasks->where('status', 'completed')->count();
                $totalCount = $tasks->count();
                $progressPercent = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
                $strokeDashOffset = 220 - ($progressPercent / 100) * 220;
            @endphp
            <div class="goal-header glass">
                <div class="goal-info">
                    <h1 class="goal-title">🎯 مهام الهدف: {{ $goal->title }}</h1>
                    <p class="goal-meta">
                        <span class="goal-date">📅 {{ $goal->created_at ? $goal->created_at->format('d M Y') : 'تاريخ غير محدد' }}</span>
                        @if($goal->priority)
                            <span class="goal-priority priority-{{ strtolower($goal->priority) }}">
                                @if(strtolower($goal->priority) === 'high') 🔴 أهمية عالية
                                @elseif(strtolower($goal->priority) === 'medium') 🟡 أهمية متوسطة
                                @else 🟢 أهمية منخفضة
                                @endif
                            </span>
                        @endif
                    </p>
                </div>
                <div class="goal-progress">
                    <div class="progress-circle">
                        <svg width="80" height="80" viewBox="0 0 80 80" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="40" cy="40" r="35" fill="none" stroke="var(--glass-border)" stroke-width="6"/>
                            <circle cx="40" cy="40" r="35" fill="none" stroke="var(--accent-color)" stroke-width="6"
                                    stroke-dasharray="220" stroke-dashoffset="{{ $strokeDashOffset }}" stroke-linecap="round"
                                    style="transform: rotate(-90deg); transform-origin: center;"/>
                        </svg>
                        <span class="progress-text">{{ $progressPercent }}%</span>
                    </div>
                </div>
            </div>

            <!-- Add Task Form -->
            <div class="add-task-card glass">
                <h3 class="section-title">➕ إضافة مهمة جديدة</h3>
                <form action="{{ route('tasks.store', $goal) }}" method="POST" class="add-task-form">
                    @csrf

                    <div class="form-row">
                        <div class="form-group flex-grow">
                            <input
                                type="text"
                                name="title"
                                class="form-input"
                                placeholder="اسم المهمة..."
                                required
                            >
                            @error('title')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input
                                type="date"
                                name="deadline"
                                class="form-input"
                            >
                            @error('deadline')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <select name="priority" class="form-input">
                                <option value="low">منخفضة</option>
                                <option value="medium" selected>متوسطة</option>
                                <option value="high">عالية</option>
                            </select>
                            @error('priority')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <span>➕</span> إضافة
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tasks List -->
            <div class="tasks-list">
                <h3 class="section-title">📋 قائمة المهام ({{ $tasks->count() ?? 0 }} مهمة)</h3>

                @if($tasks && $tasks->count() > 0)
                    <div class="tasks-grid">
                        @foreach($tasks as $task)
                            <div class="task-item glass {{ $task->status === 'completed' ? 'completed' : '' }}">
                                <form action="{{ route('tasks.toggle', $task) }}" method="POST" class="task-checkbox">
                                    @csrf
                                    @method('POST')
                                    <input
                                        type="checkbox"
                                        id="task-{{ $task->id }}"
                                        class="task-check"
                                        {{ $task->status === 'completed' ? 'checked' : '' }}
                                        onchange="this.form.submit()"
                                    >
                                    <label for="task-{{ $task->id }}" class="task-check-label"></label>
                                </form>

                                <a href="{{ route('tasks.show', $task) }}" class="task-content task-link" title="عرض المهمة">
                                    <h4 class="task-name">
                                        @if($task->priority === 'high') 🔴
                                        @elseif($task->priority === 'medium') 🟠
                                        @else 🟢
                                        @endif
                                        {{ $task->title }}
                                    </h4>
                                    <p class="task-date">
                                        📅 {{ $task->deadline ? $task->deadline->format('d M Y') : 'بدون موعد' }}
                                    </p>
                                </a>

                                <div class="task-actions">
                                    <a href="{{ route('tasks.edit', $task) }}" class="task-edit" title="تعديل">
                                        <span>✏️</span>
                                    </a>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline-form" onsubmit="return confirm('هل تريد حقاً حذف هذه المهمة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="task-delete" title="حذف">
                                            <span>🗑️</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state glass">
                        <div class="empty-icon">📭</div>
                        <p class="empty-text">لا توجد مهام حتى الآن</p>
                        <p class="empty-subtitle">ابدأ بإضافة مهمة جديدة من الأعلى</p>
                    </div>
                @endif
            </div>

            <!-- Back Link -->
            <div class="text-center mt-8">
                <a href="{{ route('goals.index') }}" class="btn btn-ghost">← العودة إلى الأهداف</a>
            </div>
        </div>
    </section>

    <script>
        // Set default due date to today
        const today = new Date().toISOString().split('T')[0];
        const dateInputs = document.querySelectorAll('input[type="date"]:not([value])');
        dateInputs.forEach(input => {
            input.value = today;
        });
    </script>
@endsection


