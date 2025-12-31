{{--
    Unified Goal Show (merged design)
    --------------------------------------------------------
    This view merges the visual design of `resources/views/tasks.blade.php`
    into the goal show page so `/goals/{id}` contains the tasks list and
    add-task form in the tasks-style layout.
--}}
@extends('layouts.app')

@section('title', $goal->title . ' - المهام')
@section('page-title', '🎯 ' . $goal->title)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/tasks.css') }}">
@endpush

@section('content')
    <section class="goal-show-section tasks-section" dir="rtl" lang="ar">
        <div class="container">
            <!-- Goal Header with Progress Circle (tasks design) -->
            @php
                $completedCount = $tasks->where('status', 'completed')->count();
                $totalCount = $tasks->count();
                $progressPercent = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
                $strokeDashOffset = 220 - ($progressPercent / 100) * 220;

                // Keep goal duration stats from previous design
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

            <div class="goal-header glass">
                <div class="goal-info" style="flex:1;">
                    <h1 class="goal-title">🎯 {{ $goal->title }}</h1>
                    @if($goal->description)
                        <p class="goal-meta">{{ $goal->description }}</p>
                    @endif
                    <div style="margin-top:1rem;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
                            <span class="completed-count" style="font-size:0.95rem;">✅ {{ $completedCount }} من {{ $totalCount }} مهمة مكتملة</span>
                        </div>
                        <div class="progress-bar" style="height:8px; border-radius:4px;">
                            <div class="progress-fill" style="width: {{ $progressPercent }}%;"></div>
                        </div>
                    </div>
                </div>
                <div class="goal-progress">
                    <div class="progress-circle">
                        <svg width="100" height="100" viewBox="0 0 80 80" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="40" cy="40" r="35" fill="none" stroke="var(--glass-border)" stroke-width="6"/>
                            <circle cx="40" cy="40" r="35" fill="none" stroke="var(--accent-color)" stroke-width="6"
                                    stroke-dasharray="220" stroke-dashoffset="{{ $strokeDashOffset }}" stroke-linecap="round"
                                    style="transform: rotate(-90deg); transform-origin: center;"/>
                        </svg>
                        <span class="progress-text" style="font-size:1.5rem;">{{ $progressPercent }}%</span>
                    </div>
                </div>
            </div>

            <!-- Tasks List (tasks design) -->
            <div class="tasks-list" style="margin-top:2rem;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
                    <h3 class="section-title">📋 قائمة المهام ({{ $tasks->count() ?? 0 }} مهمة)</h3>
                    <div style="display:flex; gap:0.75rem;">
                        <select id="filterTasks" class="form-input" style="padding:0.5rem; border-radius:6px; font-size:0.9rem;">
                            <option value="all">الكل</option>
                            <option value="pending">المتبقية</option>
                            <option value="completed">المكتملة</option>
                            <option value="overdue">المتأخرة</option>
                        </select>
                        <select id="sortTasks" class="form-input" style="padding:0.5rem; border-radius:6px; font-size:0.9rem;">
                            <option value="newest">الأحدث</option>
                            <option value="oldest">الأقدم</option>
                            <option value="priority">الأولوية</option>
                            <option value="deadline">الموعد</option>
                        </select>
                    </div>
                </div>

                @if($tasks && $tasks->count() > 0)
                    <div class="tasks-grid" id="tasksContainer">
                        @php
                            $overdueTasks = [];
                            $now = \Carbon\Carbon::now();
                        @endphp
                        @foreach($tasks as $task)
                            @php
                                $isOverdue = $task->deadline && $task->deadline < $now && $task->status !== 'completed';
                                if ($isOverdue) $overdueTasks[] = $task->id;
                            @endphp
                            <div class="task-item glass {{ $task->status === 'completed' ? 'completed' : '' }} {{ $isOverdue ? 'overdue' : '' }}"
                                 data-task-id="{{ $task->id }}"
                                 data-status="{{ $task->status }}"
                                 data-priority="{{ $task->priority ?? 'medium' }}"
                                 data-deadline="{{ $task->deadline?->timestamp ?? 0 }}"
                                 data-created="{{ $task->created_at?->timestamp ?? 0 }}">

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

                                <div style="flex:1;">
                                    @if($task->status === 'completed')
                                        <div class="task-content" title="المهمة مكتملة">
                                            <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.25rem;">
                                                <h4 class="task-name">{{ $task->title }}</h4>
                                                <span class="priority-badge priority-{{ $task->priority ?? 'medium' }}">{{ $task->priority === 'high' ? '🔴 عالية' : ($task->priority === 'low' ? '🟢 منخفضة' : '🟡 متوسطة') }}</span>
                                            </div>
                                            <p class="task-date">📅 {{ $task->deadline ? $task->deadline->format('d M Y') : 'بدون موعد' }}</p>
                                        </div>
                                    @else
                                        <a href="{{ route('tasks.show', $task) }}" class="task-content task-link" title="عرض المهمة">
                                            <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.25rem;">
                                                <h4 class="task-name">{{ $task->title }}</h4>
                                                <span class="priority-badge priority-{{ $task->priority ?? 'medium' }}">{{ $task->priority === 'high' ? '🔴 عالية' : ($task->priority === 'low' ? '🟢 منخفضة' : '🟡 متوسطة') }}</span>
                                                @if($isOverdue)
                                                    <span class="overdue-badge">⚠️ متأخرة</span>
                                                @endif
                                            </div>
                                            <p class="task-date">📅 {{ $task->deadline ? $task->deadline->format('d M Y') : 'بدون موعد' }}</p>
                                        </a>
                                    @endif
                                </div>

                                <div class="task-actions">
                                    <a href="{{ route('tasks.edit', $task) }}" class="task-edit" title="تعديل">✏️</a>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline-form" onsubmit="return confirm('هل تريد حقاً حذف هذه المهمة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="task-delete">🗑️</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state glass">
                        <div class="empty-icon">✨</div>
                        <p class="empty-text">قائمة المهام فارغة</p>
                        <p class="empty-subtitle">أضف مهمة جديدة من أسفل الصفحة للبدء</p>
                    </div>
                @endif
            </div>

            <!-- Add Task Form (use the more detailed form fields) -->
            <div class="add-task-card glass" style="margin-top:1rem;">
                <h3 class="section-title">➕ إضافة مهمة جديدة</h3>
                <form method="POST" action="{{ route('tasks.store', $goal) }}" id="add-task-form">
                    @csrf

                    <div class="add-task-grid" style="display:grid; grid-template-columns: 1fr 360px; gap:1rem; align-items:start;">
                        <div class="left-col">
                            <label for="title">عنوان المهمة <span style="color:#ef4444">*</span></label>
                            <input type="text" id="title" name="title" placeholder="اسم المهمة..." required class="form-input" value="{{ old('title') }}">
                            @error('title')<div class="form-error">{{ $message }}</div>@enderror

                            <label for="description" style="margin-top:0.75rem;">الوصف (اختياري)</label>
                            <textarea id="description" name="description" rows="3" class="form-input">{{ old('description') }}</textarea>
                            @error('description')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="right-col" style="display:flex; flex-direction:column; gap:0.75rem;">
                            <div>
                                <label for="deadline">الموعد النهائي <span style="color:#ef4444">*</span></label>
                                <input type="datetime-local" id="deadline" name="deadline" class="form-input" value="{{ old('deadline') }}" required>
                                @error('deadline')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <div style="display:flex; gap:0.5rem;">
                                <div style="flex:1;">
                                    <label for="estimated_duration">المدة المتوقعة <span style="color:#ef4444">*</span></label>
                                    <input type="number" id="estimated_duration" name="estimated_duration_input" min="1" class="form-input" value="{{ old('estimated_duration_input') }}" required>
                                    @error('estimated_duration_input')<div class="form-error">{{ $message }}</div>@enderror
                                </div>
                                <div style="width:110px;">
                                    <label for="estimated_unit">الوحدة</label>
                                    <select id="estimated_unit" name="estimated_unit" class="form-input">
                                        <option value="minutes" @selected(old('estimated_unit') === 'minutes' || !old('estimated_unit'))>دقائق</option>
                                        <option value="hours" @selected(old('estimated_unit') === 'hours')>ساعات</option>
                                    </select>
                                    @error('estimated_unit')<div class="form-error">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div>
                                <label for="priority">الأولوية</label>
                                <select id="priority" name="priority" class="form-input">
                                    <option value="low" @selected(old('priority') === 'low')>منخفضة</option>
                                    <option value="medium" @selected(old('priority') === 'medium' || !old('priority'))>متوسطة</option>
                                    <option value="high" @selected(old('priority') === 'high')>عالية</option>
                                </select>
                                @error('priority')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <div style="margin-top:0.5rem; display:flex; gap:0.5rem;">
                                <button type="submit" class="btn btn-primary" style="flex:1;">➕ إضافة المهمة</button>
                                <a href="{{ route('goals.index') }}" class="btn btn-secondary" style="flex:1; text-decoration:none; display:inline-flex; align-items:center; justify-content:center;">← رجوع</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Small helper: keep behavior of tasks page (set default dates)
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        const dateInputs = document.querySelectorAll('input[type="date"], input[type="datetime-local"]');
        dateInputs.forEach(input => {
            if (!input.value) input.value = today;
        });

        // Task filtering and sorting functionality
        const filterSelect = document.getElementById('filterTasks');
        const sortSelect = document.getElementById('sortTasks');
        const tasksContainer = document.getElementById('tasksContainer');

        if (filterSelect && sortSelect && tasksContainer) {
            // Store original tasks - keep them as reference
            const allTasks = Array.from(tasksContainer.querySelectorAll('.task-item'));

            function updateTasks() {
                const filter = filterSelect.value;
                const sort = sortSelect.value;

                // Filter tasks
                let visibleTasks = allTasks.filter(task => {
                    const status = task.getAttribute('data-status');
                    const isOverdue = task.classList.contains('overdue');

                    if (filter === 'all') return true;
                    if (filter === 'pending') return status !== 'completed' && !isOverdue;
                    if (filter === 'completed') return status === 'completed';
                    if (filter === 'overdue') return isOverdue;
                    return true;
                });

                // Sort visible tasks
                visibleTasks.sort((a, b) => {
                    const priorityOrder = { 'high': 3, 'medium': 2, 'low': 1 };
                    const aPriority = priorityOrder[a.getAttribute('data-priority')] || 2;
                    const bPriority = priorityOrder[b.getAttribute('data-priority')] || 2;
                    const aDeadline = parseInt(a.getAttribute('data-deadline')) || Infinity;
                    const bDeadline = parseInt(b.getAttribute('data-deadline')) || Infinity;
                    const aCreated = parseInt(a.getAttribute('data-created')) || 0;
                    const bCreated = parseInt(b.getAttribute('data-created')) || 0;

                    switch(sort) {
                        case 'newest':
                            return bCreated - aCreated;
                        case 'oldest':
                            return aCreated - bCreated;
                        case 'priority':
                            return bPriority - aPriority;
                        case 'deadline':
                            return aDeadline - bDeadline;
                        default:
                            return 0;
                    }
                });

                // Hide all tasks
                allTasks.forEach(task => {
                    task.style.display = 'none';
                    task.style.animation = 'none';
                });

                // Show and reorder visible tasks with staggered animation
                visibleTasks.forEach((task, index) => {
                    task.style.display = '';
                    // Reset animation
                    task.style.animation = 'none';
                    // Trigger reflow to restart animation
                    void task.offsetWidth;
                    // Apply animation with stagger
                    task.style.animation = `slideInTaskSmooth 0.5s ease-out ${index * 0.1}s forwards`;
                });
            }

            filterSelect.addEventListener('change', updateTasks);
            sortSelect.addEventListener('change', updateTasks);
        }
    });
</script>
@endpush
