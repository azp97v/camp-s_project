<!-- Backup of resources/views/tasks.blade.php (legacy single-file HTML) -->

@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/tasks.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/theme.js') }}"></script>
@endpush

@section('content')
    <section class="tasks-section">
        <div class="container">
            <h1>المهام</h1>
            <!-- Goal Header -->
            <div class="goal-header glass">
                <div class="goal-info">
                    <h1 class="goal-title">🎯 مهام الهدف: تعلم البرمجة</h1>
                    <p class="goal-meta">
                        <span class="goal-date">📅 من 2024-01-01 إلى 2024-12-31</span>
                        <span class="goal-priority priority-high">🔴 أهمية عالية</span>
                    </p>
                </div>
            </div>
            <!-- Add Task Form -->
            <div class="add-task-card glass">
                <h3 class="section-title">➕ إضافة مهمة جديدة</h3>
                <form class="add-task-form" id="addTaskForm">
                    <div class="form-row">
                        <div class="form-group flex-grow">
                            <input
                                type="text"
                                id="taskName"
                                class="form-input"
                                placeholder="اسم المهمة..."
                                required
                            >
                        </div>
                        <div class="form-group">
                            <input
                                type="date"
                                id="taskDueDate"
                                class="form-input"
                                required
                            >
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <span>➕</span> إضافة
                        </button>
                    </div>
                </form>
            </div>
            <!-- Tasks List -->
            <div class="tasks-list">
                <h3 class="section-title">📋 قائمة المهام</h3>
                <div class="tasks-grid" id="tasksList">
                    <!-- Task 1 -->
                    <div class="task-item glass">
                        <div class="task-checkbox">
                            <input type="checkbox" id="task1" class="task-check">
                            <label for="task1" class="task-check-label"></label>
                        </div>
                        <div class="task-content">
                            <h4 class="task-name">تعلم أساسيات HTML و CSS</h4>
                            <p class="task-date">📅 2024-02-15</p>
                        </div>
                        <button class="task-delete" onclick="deleteTask(this)">
                            <span>🗑️</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<!-- legacy extra HTML below omitted in backup for brevity -->
