{{--
    Goal Edit Blade
    --------------------------------------------------------
    صفحة تعديل الهدف — نماذج الحقول لتحديث العنوان والوصف والمدة.
    English: Edit form for a Goal (title, description, duration). No behavior changes.
--}}
@extends('layouts.app')
@section('title','تعديل الهدف')
@section('page-title','✏️ تعديل الهدف')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/tasks-show.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/goals-edit.css') }}">
@endpush

@section('content')
<div class="goal-edit-container">
    <!-- Header Card -->
    <div class="form-header-card">
        <h3>تعديل معلومات الهدف</h3>
        <p>قم بتحديث تفاصيل الهدف والمدة الإجمالية</p>
    </div>

    <!-- Form Card -->
    <div class="form-main-card">
        <!-- Info Box -->
        <div class="info-box">
            <strong>ملاحظة:</strong> عند تعديل المدة الإجمالية للهدف، ستتم مراعاة الوقت المتبقي تلقائياً.
        </div>

        <form method="POST" action="{{ route('goals.update', $goal) }}" class="goal-edit-form">
            @csrf @method('PATCH')

            <!-- Title Field -->
            <div class="form-group-edit">
                <label for="title">
                    📝 عنوان الهدف
                    <span class="required">*</span>
                </label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title', $goal->title) }}"
                    placeholder="أدخل عنوان الهدف..."
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
                    placeholder="أدخل وصفاً تفصيلياً للهدف..."
                    class="form-textarea-edit"
                >{{ old('description', $goal->description) }}</textarea>
                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Duration Section -->
            <div class="form-group-edit">
                <label>
                    ⏱️ المدة الإجمالية
                    <span class="required">*</span>
                </label>
                <div class="form-group-inline">
                    @php
                        $isDay = $goal->total_duration_seconds % 86400 === 0 && $goal->total_duration_seconds >= 86400;
                        $durationValue = $isDay ? intdiv($goal->total_duration_seconds, 86400) : intdiv($goal->total_duration_seconds, 3600);
                    @endphp
                    <input
                        type="number"
                        name="total_duration_input"
                        min="0"
                        value="{{ old('total_duration_input', $durationValue) }}"
                        placeholder="أدخل الرقم..."
                        class="form-input-edit"
                        required
                    >
                    <select name="total_unit" class="form-select-edit" required>
                        <option value="hours" {{ !$isDay ? 'selected' : '' }}>ساعة</option>
                        <option value="days" {{ $isDay ? 'selected' : '' }}>يوم</option>
                    </select>
                </div>
                @error('total_duration_input')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Current Stats -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin: 2rem 0; padding: 1.5rem; background: rgba(255,255,255,0.02); border-radius: 0.75rem; border: 1px solid var(--glass-border);">
                <div>
                    <p style="font-size: 0.85rem; opacity: 0.7; margin-bottom: 0.5rem;">الوقت المستغرق حالياً</p>
                    <p style="font-size: 1.25rem; font-weight: 700; color: var(--accent-color);">{{ gmdate('H:i:s', $goal->total_duration_seconds - $goal->remaining_duration_seconds) }}</p>
                </div>
                <div>
                    <p style="font-size: 0.85rem; opacity: 0.7; margin-bottom: 0.5rem;">الوقت المتبقي</p>
                    <p style="font-size: 1.25rem; font-weight: 700; color: var(--highlight-color);">{{ gmdate('H:i:s', $goal->remaining_duration_seconds) }}</p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn-action btn-save">
                    💾 حفظ التغييرات
                </button>
                <a href="{{ route('goals.show', $goal) }}" class="btn-action btn-cancel">
                    ❌ إلغاء
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
