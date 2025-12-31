@extends('layouts.app')

@section('title', 'لوحة التحكم - Step by Step')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
@endpush

@section('content')
    <section class="dashboard-section" dir="rtl" lang="ar">
        <div class="container" role="main">
            <!-- Welcome -->
            <div class="glass dashboard-welcome animate-on-load">
                <div class="welcome-content">
                    <div>
                        <h2 class="welcome-title">مرحباً، {{ auth()->user()->name ?? 'المستخدم' }}! 👋</h2>
                        <p class="welcome-subtitle">أهلاً وسهلاً في لوحة التحكم — إدارة أهدافك ومهامك بسهولة.</p>
                    </div>
                    <div class="welcome-emoji">📊</div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="dashboard-stats">
                <a href="{{ route('goals.index') }}" class="stat-card glass stat-link" aria-label="عرض جميع الأهداف">
                    <div class="stat-content">
                        <div>
                            <p class="stat-label">🎯 إجمالي الأهداف</p>
                            <p class="stat-value">{{ $goalsCount ?? 0 }}</p>
                        </div>
                        <div class="stat-meta text-xs text-slate-500">↑ {{ $newGoalsThisMonth ?? 0 }} هذا الشهر</div>
                    </div>
                </a>

                <a href="{{ route('tasks.index') }}" class="stat-card glass stat-link" aria-label="عرض جميع المهام">
                    <div class="stat-content">
                        <div>
                            <p class="stat-label">📋 إجمالي المهام</p>
                            <p class="stat-value">{{ $totalTasks ?? 0 }}</p>
                        </div>
                        <div class="stat-meta text-xs text-slate-500">مكتملة: {{ $completedTasks ?? 0 }}</div>
                    </div>
                </a>

                <a href="{{ route('goals.index') }}" class="stat-card glass stat-link" aria-label="عرض معدل الإنجاز">
                    <div class="stat-content">
                        <div>
                            <p class="stat-label">📈 معدل الإنجاز</p>
                            <p class="stat-value">{{ $completionRate ?? 0 }}%</p>
                        </div>
                        <div class="stat-meta text-xs text-slate-500">متوسط الأهداف: {{ $avgGoalProgress ?? 0 }}%</div>
                    </div>
                </a>

                <a href="{{ route('goals.index', ['filter' => 'overdue']) }}" class="stat-card glass stat-link" aria-label="عرض الأهداف المتأخرة">
                    <div class="stat-content">
                        <div>
                            <p class="stat-label">⏰ الأهداف المتأخرة</p>
                            <p class="stat-value text-yellow-600">{{ $overdueTasks ?? 0 }}</p>
                        </div>
                        <div class="stat-meta text-xs text-slate-500">تحتاج اهتمام</div>
                    </div>
                </a>
            </div>

            <!-- Progress Overview (goals) -->
            <div class="glass p-8 mb-8">
                <h3 class="section-title">نظرة عامة على التقدم</h3>
                <div class="progress-grid">
                    @forelse($goalsList ?? [] as $goal)
                        <a href="{{ route('goals.show', $goal['id']) }}" class="progress-item progress-link" aria-label="عرض الهدف {{ $goal['name'] }}">
                            <div class="progress-header">
                                <div class="progress-name">{{ $goal['name'] }}</div>
                                <div class="progress-percent">{{ $goal['progress'] }}%</div>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $goal['progress'] }}%"></div>
                            </div>
                            <div class="progress-info">
                                <span>{{ $goal['tasks'] }} من {{ $goal['total_tasks'] }} مهمة</span>
                                <span>{{ $goal['deadline'] }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="progress-item">
                            لا توجد أهداف بعد.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Upcoming Deadlines Section (Enhanced) -->
            <div class="glass mb-8 deadlines-section">
                <div class="deadlines-header">
                    <div class="deadlines-title-group">
                        <h3 class="section-title">المواعيد القادمة ⏰</h3>
                        <p class="deadlines-subtitle">المهام المهمة التي تحتاج اهتمامك</p>
                    </div>
                    <div class="deadlines-count-badge">
                        <span class="count-number">{{ count($upcomingDeadlines ?? []) }}</span>
                        <span class="count-label">مهمة</span>
                    </div>
                </div>

                @if(!empty($upcomingDeadlines) && count($upcomingDeadlines) > 0)
                    <div class="deadlines-list">
                        @foreach($upcomingDeadlines as $index => $deadline)
                            @php
                                $isUrg = !empty($deadline['is_urgent']);
                                $hours = isset($deadline['hours_until']) ? (int) $deadline['hours_until'] : null;
                                $days = isset($deadline['days_until']) ? (int) $deadline['days_until'] : null;
                                $remainingText = $hours !== null && $hours < 24 ? ('تبقى ' . $hours . ' ساعة') : ('تبقى ' . ($days ?? 0) . ' يوم');
                                $titleAttr = $remainingText . ' — ' . ($deadline['deadline'] ?? '');
                            @endphp
                            <a href="{{ route('goals.show', $deadline['goal_id']) ?? '#' }}" class="deadline-card deadline-card-{{ $deadline['is_urgent'] ? 'urgent' : ($deadline['is_soon'] ? 'soon' : 'normal') }} animated-deadline {{ $isUrg ? 'urgent' : '' }}" data-urgent="{{ $isUrg ? '1' : '0' }}" title="{{ $titleAttr }}" style="animation-delay: {{ $index * 0.08 }}s">
                                <div class="deadline-priority-bar {{ strtolower($deadline['priority'] ?? 'medium') }}"></div>

                                <div class="deadline-content">
                                    <div class="deadline-left">
                                        <div class="deadline-icon-wrapper">
                                            @if($deadline['is_urgent'])
                                                🔴
                                            @elseif($deadline['is_soon'])
                                                🟠
                                            @else
                                                🟡
                                            @endif
                                        </div>
                                        <div class="deadline-info">
                                            <p class="deadline-title">{{ $deadline['title'] }}</p>
                                            <p class="deadline-goal">📁 {{ $deadline['goal'] ?? 'بدون هدف' }}</p>
                                        </div>
                                    </div>

                                    <div class="deadline-right">
                                        <div class="deadline-days">
                                            @php
                                                $hours = isset($deadline['hours_until']) ? (int) $deadline['hours_until'] : null;
                                                $days = isset($deadline['days_until']) ? (int) $deadline['days_until'] : null;
                                            @endphp

                                            @if($hours !== null && $hours < 24)
                                                <span class="days-number">{{ $hours }}</span>
                                                <span class="days-label">ساعات</span>
                                            @else
                                                <span class="days-number">{{ $days ?? 0 }}</span>
                                                <span class="days-label">أيام</span>
                                            @endif
                                        </div>
                                        <div class="deadline-date">{{ $deadline['deadline'] }}</div>
                                    </div>
                                </div>

                                <div class="deadline-hover-glow"></div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="deadlines-empty-state">
                        <div class="empty-icon-large">✨</div>
                        <p class="empty-title">لا توجد مواعيد قريبة</p>
                        <p class="empty-description">رائع! أنت في المسار الصحيح</p>
                    </div>
                @endif
            </div>

            <!-- Action Boxes (Unchanged) -->
            <div class="action-boxes-section">
                <div class="action-boxes">
                    <a href="{{ route('goals.create') }}" class="action-box" aria-label="إنشاء هدف جديد">
                        <div class="action-box-icon">➕</div>
                        <div class="action-box-label">إنشاء هدف جديد</div>
                    </a>
                    <a href="{{ route('tasks.index') }}" class="action-box" aria-label="عرض المهام">
                        <div class="action-box-icon">📋</div>
                        <div class="action-box-label">عرض المهام</div>
                    </a>
                    <a href="{{ route('settings.index') }}" class="action-box" aria-label="الإعدادات">
                        <div class="action-box-icon">⚙️</div>
                        <div class="action-box-label">الإعدادات</div>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="action-box" aria-label="الملف الشخصي">
                        <div class="action-box-icon">👤</div>
                        <div class="action-box-label">الملف الشخصي</div>
                    </a>
                </div>
            </div>

            <!-- Recent Activity (Enhanced) -->
            <div class="glass mb-8 activity-section">
                <div class="activity-section-header">
                    <div>
                        <h3 class="section-title">النشاط الأخير</h3>
                        <p class="activity-subtitle">تحديثات نشاطك الأخيرة وتطورك</p>
                    </div>
                    <div class="activity-header-icon">📊</div>
                </div>
                <div class="activity-container">
                    @forelse($recentActivities ?? [] as $index => $act)
                        <a href="{{ $act['url'] ?? '#' }}" class="activity-card animated-activity activity-link" style="animation-delay: {{ $index * 0.1 }}s" title="انقر لعرض المهمة">
                            <div class="activity-timeline-dot"></div>
                            <div class="activity-card-inner">
                                <div class="activity-card-header">
                                    <div class="activity-icon-wrapper">{!! $act['icon'] !!}</div>
                                    <div class="activity-meta">
                                        <p class="activity-title">{{ $act['title'] }}</p>
                                        <p class="activity-time">{{ $act['time'] }}</p>
                                    </div>
                                </div>
                                <div class="activity-card-accent"></div>
                            </div>
                        </a>
                    @empty
                        <div class="activity-empty-state">
                            <div class="empty-icon">🕐</div>
                            <p class="empty-text">لا توجد أنشطة حالياً</p>
                            <p class="empty-subtext">ابدأ بإنشاء هدف جديد أو إضافة مهمة</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

@endsection
