{{--
    Goal Create Page
    --------------------------------------------------------
    صفحة إنشاء هدف جديد حيث يقدم المستخدم اسم وصف والمدة الإجمالية.
    English: Create goal form view — renders input fields and submission handler. No behavior changes.
--}}
@extends('layouts.app')

@section('title', 'إنشاء هدف جديد - Step by Step')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/goals.css') }}">
@endpush


@section('content')
    <section class="create-goal-section">
        <div class="container">
            <div class="create-goal-card glass">
                <div class="card-header">
                    <h1 class="card-title">🎯 إنشاء هدف جديد</h1>
                    <p class="card-subtitle">حدد أهدافك وابدأ رحلتك نحو النجاح</p>
                </div>

                <!-- Create Goal Form -->
                <form class="create-goal-form" id="createGoalForm" method="POST" action="{{ route('goals.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="title" class="form-label">📝 اسم الهدف *</label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            class="form-input"
                            placeholder="مثال: تعلم البرمجة بلغة PHP"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">📋 وصف الهدف</label>
                        <textarea
                            id="description"
                            name="description"
                            class="form-textarea"
                            placeholder="اكتب وصفاً تفصيلياً لهدفك والخطوات المطلوبة..."
                            rows="4"
                        ></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="total_duration_input" class="form-label">⏱️ المدة (عدد)</label>
                            <input
                                type="number"
                                id="total_duration_input"
                                name="total_duration_input"
                                class="form-input"
                                min="1"
                                value="1"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="total_unit" class="form-label">📅 وحدة المدة</label>
                            <select id="total_unit" name="total_unit" class="form-select" required>
                                <option value="hours">ساعات</option>
                                <option value="days">أيام</option>
                                <option value="weeks">أسابيع</option>
                                <option value="months">أشهر</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-large">
                            <span>💾</span> حفظ الهدف
                        </button>
                        <a href="{{ route('goals.index') }}" class="btn btn-glass">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Set sensible default for duration
        document.addEventListener('DOMContentLoaded', function(){
            const durationInput = document.getElementById('total_duration_input');
            if (durationInput && Number(durationInput.value) === 0) {
                durationInput.value = 1;
            }
        });
    </script>
@endpush
