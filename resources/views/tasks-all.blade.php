@extends('layouts.app')

@section('title', 'جميع المهام')
@section('page-title', '📋 جميع المهام')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/tasks.css') }}">
@endpush

@section('content')
    <section class="tasks-section" dir="rtl" lang="ar">
        <div class="container">
            <!-- Header -->
            <div class="goal-header glass">
                <div class="goal-info">
                    <h1 class="goal-title">📋 جميع المهام</h1>
                    <p class="goal-meta">
                        <span class="goal-date">إجمالي المهام: {{ $allTasks->count() }}</span>
                        <span class="goal-date">المكتملة: {{ $allTasks->where('status', 'completed')->count() }}</span>
                    </p>
                </div>
            </div>

            <!-- Tasks List Grouped by Goal -->
            <div class="tasks-list">
                @if($allTasks && $allTasks->count() > 0)
                    @php
                        // Group tasks by goal
                        $tasksByGoal = $allTasks->groupBy('goal.id');
                    @endphp

                    @foreach($tasksByGoal as $goalId => $tasksGroup)
                        @php
                            $goal = $tasksGroup->first()->goal;
                            $completedCount = $tasksGroup->where('status', 'completed')->count();
                            $totalCount = $tasksGroup->count();
                            $progressPercent = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
                        @endphp

                        <div style="margin-bottom: 2rem;">
                            <div class="goal-header glass" style="margin-bottom: 1rem;">
                                <div class="goal-info">
                                    <h3 class="goal-title" style="font-size: 1.3rem; margin-bottom: 0;">🎯 {{ $goal->title }}</h3>
                                    <p class="goal-meta" style="margin-top: 0.5rem;">
                                        <span>{{ $completedCount }} / {{ $totalCount }} مهمة</span>
                                    </p>
                                </div>
                                <div>
                                    <a href="{{ route('goals.tasks.index', $goal) }}" class="btn btn-primary" style="font-size: 0.9rem;">عرض التفاصيل</a>
                                </div>
                            </div>

                            <div class="tasks-grid">
                                @foreach($tasksGroup as $task)
                                    <div class="task-item glass {{ $task->status === 'completed' ? 'completed' : '' }}">
                                        @php
                                            $canComplete = $task->estimated_duration_seconds && $task->total_tracked_seconds >= $task->estimated_duration_seconds;
                                        @endphp
                                        <form action="{{ route('tasks.toggle', $task) }}" method="POST" class="task-checkbox">
                                            @csrf
                                            @method('POST')
                                            <input
                                                type="checkbox"
                                                id="task-{{ $task->id }}"
                                                class="task-check"
                                                {{ $task->status === 'completed' ? 'checked' : '' }}
                                                {{ ($task->status === 'completed' || ! $canComplete) ? 'disabled' : '' }}
                                                title="{{ $task->status === 'completed' ? 'المهمة مكتملة - لا يمكن التراجع' : ($canComplete ? 'وضع كمكتملة' : 'لا يمكن وضع علامة مكتملة إلا بعد إتمام مدة المهمة') }}"
                                            >
                                            <label for="task-{{ $task->id }}" class="task-check-label"></label>
                                        </form>

                                        @if($task->status === 'completed')
                                            <div class="task-content task-link disabled-link" title="المهمة مكتملة - لا يمكن فتحها">
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
                                            </div>
                                        @else
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
                                        @endif

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
                        </div>
                    @endforeach
                @else
                    <div class="empty-state glass">
                        <div class="empty-icon">📭</div>
                        <p class="empty-text">لا توجد مهام حتى الآن</p>
                        <p class="empty-subtitle">
                            <a href="{{ route('goals.index') }}" class="btn btn-ghost">← العودة إلى الأهداف</a>
                        </p>
                    </div>
                @endif
            </div>

            <!-- Back Link -->
            <div class="text-center mt-8">
                <a href="{{ route('dashboard') }}" class="btn btn-ghost">← العودة إلى لوحة التحكم</a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle task toggle via AJAX
        document.querySelectorAll('.task-checkbox .task-check').forEach(checkbox => {
            checkbox.addEventListener('change', function(e) {
                e.preventDefault();

                const form = this.closest('.task-checkbox');
                const taskItem = this.closest('.task-item');
                const taskId = this.id.replace('task-', '');
                const formAction = form.getAttribute('action');

                // Disable checkbox during request
                this.disabled = true;

                // Send AJAX request
                fetch(formAction, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({})
                })
                .then(async response => {
                    const data = await response.json().catch(() => ({}));
                    if (!response.ok || data.success === false) {
                        const msg = data.message || 'حدث خطأ أثناء تحديث الحالة.';
                        throw new Error(msg);
                    }
                    return data;
                })
                .then(data => {
                    // Update checkbox state
                    const isCompleted = data.task.status === 'completed';
                    checkbox.checked = isCompleted;

                    // Update task item class
                    if (isCompleted) {
                        taskItem.classList.add('completed');
                    } else {
                        taskItem.classList.remove('completed');
                    }

                    // Update completion counts
                    updateAllCounts();

                    // Notify user
                    showToast(data.message || 'تم تحديث حالة المهمة.', 'success');
                })
                .catch(error => {
                    // Show message to the user and revert checkbox
                    showToast(error.message || 'حدث خطأ', 'error');
                    checkbox.checked = !checkbox.checked;
                })
                .finally(() => {
                    // Re-enable checkbox
                    checkbox.disabled = false;
                });
            });
        });

        // If user clicks the label of a disabled checkbox, show informative message
        document.querySelectorAll('.task-checkbox .task-check-label').forEach(label => {
            label.addEventListener('click', function(e) {
                const inputId = this.getAttribute('for');
                const input = document.getElementById(inputId);
                if (input && input.disabled) {
                    e.preventDefault();
                    showToast('لا يمكن وضع علامة مكتملة إلا بعد إتمام مدة المهمة.', 'error');
                }
            });
        });

        // Update all completion counts on the page
        function updateAllCounts() {
            // Get all tasks (completed + uncompleted)
            const allTasks = document.querySelectorAll('.task-item');
            const completedTasks = document.querySelectorAll('.task-item.completed');

            // Update header counts
            const headerMeta = document.querySelector('.goal-header .goal-meta');
            if (headerMeta) {
                const spans = headerMeta.querySelectorAll('.goal-date');
                if (spans.length >= 2) {
                    spans[0].textContent = `إجمالي المهام: ${allTasks.length}`;
                    spans[1].textContent = `المكتملة: ${completedTasks.length}`;
                }
            }

            // Update per-goal counts
            updatePerGoalCounts();
        }

        // Update completion counts for each goal
        function updatePerGoalCounts() {
            const goalSections = document.querySelectorAll('.tasks-grid');

            goalSections.forEach(section => {
                // Find the header for this section
                const header = section.previousElementSibling;
                if (!header || !header.classList.contains('goal-header')) return;

                // Count completed tasks in this section
                const tasksInGroup = section.querySelectorAll('.task-item');
                const completedInGroup = section.querySelectorAll('.task-item.completed');

                // Update the goal meta
                const meta = header.querySelector('.goal-meta');
                if (meta) {
                    const span = meta.querySelector('span');
                    if (span) {
                        const totalCount = tasksInGroup.length;
                        const completedCount = completedInGroup.length;
                        const progressPercent = totalCount > 0 ? Math.round((completedCount / totalCount) * 100) : 0;
                        span.textContent = `${completedCount} / ${totalCount} مهمة`;
                    }
                }
            });
        }
    });
</script>
@endpush
