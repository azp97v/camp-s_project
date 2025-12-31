{{--
    Goals Index Blade
    --------------------------------------------------------
    هذا الملف يعرض قائمة الأهداف للمستخدم الحالي، ويحتوي على ملخصات
    وأزرار الانتقال لإنشاء/عرض/تعديل الأهداف.
    English: Lists user goals with stats and goal cards. No logic changes.
--}}
@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/goals-display.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/goals.css') }}">
@endpush
@section('title','أهدافي')
@section('page-title','🎯 أهدافي')

@section('content')
    <section class="goals-section">
        <div class="container">
            <!-- Header -->
            <div class="goals-header">
                <div class="header-content">
                    <h1 class="page-title">🎯 أهدافي</h1>
                    <p class="page-subtitle">إدارة وتتبع جميع أهدافك في مكان واحد</p>
                </div>
                <a href="{{ route('goals.create') }}" class="btn btn-primary">+ إنشاء هدف جديد</a>
            </div>

            @if($goals->isEmpty())
                <!-- Empty State -->
                <div class="glass p-12 rounded-2xl text-center">
                    <div class="text-6xl mb-4">🏃</div>
                    <p class="text-xl text-slate-600 mb-4">لا توجد أهداف حتى الآن</p>
                    <p class="text-slate-500 mb-6">ابدأ رحلتك بإنشاء هدفك الأول الآن</p>
                    <a href="{{ route('goals.create') }}" class="btn btn-primary">✨ إنشاء الهدف الأول</a>
                </div>
            @else
                <!-- Stats Overview -->
                <div class="stats-row glass">
                    @php
                        $totalGoals = $goals->count();
                        $completedGoals = $goals->filter(fn($g) => ($g->total_duration_seconds - $g->remaining_duration_seconds) >= $g->total_duration_seconds)->count();
                        $activeGoals = $totalGoals - $completedGoals;
                        $totalSeconds = $goals->sum('total_duration_seconds');
                        $completedSeconds = $goals->sum(fn($g) => $g->total_duration_seconds - $g->remaining_duration_seconds);
                        $completionRate = $totalSeconds > 0 ? round(($completedSeconds / $totalSeconds) * 100) : 0;
                    @endphp
                    <div class="stat-box" style="--index: 0">
                        <div class="stat-icon">📊</div>
                        <div class="stat-content">
                            <p class="stat-label">إجمالي الأهداف</p>
                            <p class="stat-value">{{ $totalGoals }}</p>
                        </div>
                    </div>
                    <div class="stat-box" style="--index: 1">
                        <div class="stat-icon">✅</div>
                        <div class="stat-content">
                            <p class="stat-label">الأهداف المكتملة</p>
                            <p class="stat-value">{{ $completedGoals }}</p>
                        </div>
                    </div>
                    <div class="stat-box" style="--index: 2">
                        <div class="stat-icon">⏳</div>
                        <div class="stat-content">
                            <p class="stat-label">قيد التنفيذ</p>
                            <p class="stat-value">{{ $activeGoals }}</p>
                        </div>
                    </div>
                    <div class="stat-box" style="--index: 3">
                        <div class="stat-icon">📈</div>
                        <div class="stat-content">
                            <p class="stat-label">معدل الإنجاز</p>
                            <p class="stat-value">{{ $completionRate }}%</p>
                        </div>
                    </div>
                </div>

                <!-- Goals Grid -->
                <div class="goals-grid">
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
                            $isCompleted = ($goal->total_duration_seconds - $goal->remaining_duration_seconds) >= $goal->total_duration_seconds;
                        @endphp
                        <!-- Goal Card -->
                        <div class="goal-card glass" style="--index: {{ $loop->index }}">
                            <div class="goal-header-card">
                                <div class="goal-icon">{{ ['📚', '💪', '📖', '🌍', '💼', '🎨', '🏆', '🚀'][$loop->index % 8] ?? '🎯' }}</div>
                                <span class="goal-status {{ $isCompleted ? 'completed' : 'active' }}">
                                    {{ $isCompleted ? '✅ مكتملة' : '⏳ قيد التنفيذ' }}
                                </span>
                            </div>

                            <h3 class="goal-card-title">{{ $goal->title }}</h3>
                            @if($goal->description)
                                <p class="goal-card-description">{{ Str::limit($goal->description, 100) }}</p>
                            @endif

                            <div class="goal-meta">
                                <div class="meta-item">
                                    <span class="meta-icon">📅</span>
                                    <span class="meta-text">آخر تحديث: {{ $goal->updated_at->diffForHumans() }}</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-icon">⚡</span>
                                    <span class="meta-text">{{ $goal->tasks()->count() ?? 0 }} مهام</span>
                                </div>
                            </div>

                            <div class="progress-section">
                                <div class="progress-header">
                                    <span class="progress-label">التقدم</span>
                                    <span class="progress-percent">{{ $percent }}%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $percent }}%; --percent: {{ $percent }}%"></div>
                                </div>
                                <p class="progress-detail">{{ gmdate('H:i:s', $goal->total_duration_seconds - $goal->remaining_duration_seconds) }} / {{ gmdate('H:i:s', $goal->total_duration_seconds) }}</p>
                            </div>

                            <div class="goal-actions">
                                <a href="{{ route('goals.show', $goal) }}" class="btn btn-sm btn-primary">عرض التفاصيل</a>
                                <a href="{{ route('goals.tasks.index', $goal) }}" class="btn btn-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; text-decoration: none;">📋 المهام</a>
                                <a href="{{ route('goals.edit', $goal) }}" class="btn btn-sm" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; text-decoration: none;">✏️ تعديل</a>
                                <form method="POST" action="{{ route('goals.destroy', $goal) }}" style="flex: 1;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-delete" onclick="return confirm('هل تريد حقاً حذف هذا الهدف؟')" style="width: 100%;">🗑️</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
