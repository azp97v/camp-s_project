{{--
    Task Edit Blade
    --------------------------------------------------------
    صفحة تعديل مهمة: تمكن المستخدم من تعديل العنوان والوصف والمدة والموعد النهائي.
    English: Task edit form; updates Task model via TaskController@update. No logic changes.
--}}
@extends('layouts.app')
@section('title','تعديل المهمة')
@section('page-title','✏️ تعديل المهمة')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/tasks-show.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/tasks-edit.css') }}">
@endpush

@section('content')
<div class="task-edit-container">
    <!-- Header Card -->
    <div class="form-header-card">
        <h3>تعديل معلومات المهمة</h3>
        <p>قم بتحديث تفاصيل المهمة وجدول الأوقات الخاص بها</p>
    </div>

    <!-- Form Card -->
    <div class="form-main-card">
        <form method="POST" action="{{ route('tasks.update', $task) }}" class="task-edit-form">
            @csrf @method('PATCH')

            <!-- Title Field -->
            <div class="form-group-edit">
                <label for="title">
                    📝 عنوان المهمة
                    <span class="required">*</span>
                </label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title', $task->title) }}"
                    placeholder="أدخل عنوان المهمة..."
                    class="form-input-edit"
                    required
                >
                @error('title')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Description Field -->
            <div class="form-group-edit">
                <label for="description">
                    📄 الوصف (اختياري)
                </label>
                <textarea
                    id="description"
                    name="description"
                    placeholder="أدخل وصفاً تفصيلياً للمهمة..."
                    class="form-textarea-edit"
                >{{ old('description', $task->description) }}</textarea>
                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Deadline and Duration Row -->
            <div class="form-group-row">
                <div class="form-group-edit">
                    <label for="deadline">
                        📅 الموعد النهائي (اختياري)
                    </label>
                    <input
                        type="datetime-local"
                        id="deadline"
                        name="deadline"
                        value="{{ old('deadline', optional($task->deadline)->format('Y-m-d\TH:i')) }}"
                        class="form-datetime-edit"
                    >
                    @error('deadline')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-edit">
                    <label>⏱️ المدة المقدرة <span style="color: #ef4444;">*</span></label>
                    <div class="form-group-inline">
                        <input
                            type="number"
                            name="estimated_duration_input"
                            min="1"
                            value="{{ old('estimated_duration_input', intdiv($task->estimated_duration_seconds ?? 0, 60)) }}"
                            placeholder="أدخل الرقم..."
                            class="form-input-edit"
                            required
                        >
                        <select name="estimated_unit" class="form-select-edit" required>
                            <option value="minutes" {{ (old('estimated_unit') == 'hours') ? '' : 'selected' }}>دقيقة</option>
                            <option value="hours" {{ (old('estimated_unit') == 'hours') ? 'selected' : '' }}>ساعة</option>
                        </select>
                    </div>
                    @error('estimated_duration_input')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    @error('estimated_unit')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group-edit">
                <label for="priority">🔖 الأولوية</label>
                <select id="priority" name="priority" class="form-select-edit">
                    <option value="low" {{ (old('priority', $task->priority) == 'low') ? 'selected' : '' }}>منخفضة</option>
                    <option value="medium" {{ (old('priority', $task->priority) == 'medium') ? 'selected' : '' }}>متوسطة</option>
                    <option value="high" {{ (old('priority', $task->priority) == 'high') ? 'selected' : '' }}>عالية</option>
                </select>
                @error('priority')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-action btn-save">
                    💾 حفظ التغييرات
                </button>
                <a href="{{ route('tasks.show', $task) }}" class="btn-action btn-cancel">
                    ❌ إلغاء
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
