{{--
    Task Show Blade
    --------------------------------------------------------
    يعرض صفحة مهمة واحدة مع أزرار التحكم بالمؤقّت (بدء/إيقاف/إنهاء/إلغاء).
    English: Displays a single Task and exposes timer actions (start/stop/finish/cancel).
--}}
@extends('layouts.app')
@section('title',$task->title)
@section('page-title',$task->title)

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/tasks-show.css') }}">
@endpush

@section('content')
    <div class="task-show-page">
        <div class="site-container">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Task Details -->
        <div class="lg:col-span-2">
            <div class="glass p-8 rounded-2xl animate-on-load card-hover border border-white/20 shadow-lg">
                <div class="mb-6">
                    <h2 class="text-3xl font-bold mb-2" style="color: var(--accent-color);">{{ $task->title }}</h2>
                    <div class="task-meta-row">
                        <div class="meta-left">
                            @if($task->description)
                                <p class="text-lg task-desc" style="color: var(--text-color); opacity: 0.9;">{{ \Illuminate\Support\Str::limit($task->description, 120) }}</p>
                            @endif
                            <div class="meta-tags">
                                <a href="{{ route('goals.show', $task->goal) }}" class="goal-link">الهدف: {{ $task->goal->title }}</a>
                                <span class="meta-sep">•</span>
                                <span class="created-at">{{ $task->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="meta-right">
                            <select id="priority-selector" class="priority-selector priority-{{ $task->priority ?? 'medium' }}" data-task-id="{{ $task->id }}" style="cursor: pointer;">
                                <option value="low" {{ ($task->priority ?? 'medium') === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ ($task->priority ?? 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ ($task->priority ?? 'medium') === 'high' ? 'selected' : '' }}>High</option>
                            </select>
                            <a href="{{ route('tasks.edit', $task) }}" class="edit-btn" style="margin-left:0.6rem;">✏️ تعديل</a>
                        </div>
                    </div>
                </div>

                <!-- Visual Timer + Quick Info -->
                @php
                    $displayed_total = $task->total_tracked_seconds + ($task->status === 'stopped' ? ($task->last_session_seconds ?? 0) : 0);
                @endphp
                <div class="timer-visual-grid mb-8">
                    <div class="timer-visual">
                        <svg class="timer-ring" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Timer ring">
                            <defs>
                                <filter id="glow" x="-50%" y="-50%" width="200%" height="200%">
                                    <feGaussianBlur stdDeviation="3.5" result="coloredBlur" />
                                    <feMerge>
                                        <feMergeNode in="coloredBlur" />
                                        <feMergeNode in="SourceGraphic" />
                                    </feMerge>
                                </filter>
                            </defs>
                            <circle class="ring-bg" cx="60" cy="60" r="52" stroke="rgba(255,255,255,0.06)" stroke-width="12" fill="none" />
                            <circle class="ring-progress" cx="60" cy="60" r="52" stroke="var(--accent-color)" stroke-width="12" stroke-linecap="round" fill="none" transform="rotate(-90 60 60)" style="filter: url(#glow)" />
                        </svg>
                        <div class="timer-center">
                            <div id="tracked-time-large" data-task-id="{{ $task->id }}" data-task-status="{{ $task->status }}" data-timer-start="{{ $task->timer_started_at?->timestamp ?? 0 }}" data-total-tracked="{{ $task->total_tracked_seconds }}" data-last-session="{{ $task->last_session_seconds ?? 0 }}" data-last-finished-session="{{ $task->last_finished_session_seconds ?? 0 }}" data-estimated="{{ $task->estimated_duration_seconds ?? 0 }}">{{ gmdate('H:i:s', $displayed_total) }}</div>
                            <div class="timer-epoch small muted" id="timer-epoch-text">{{ $task->status === 'running' ? 'جاري الآن' : ucfirst($task->status) }}</div>
                        </div>
                    </div>

                    <div class="timer-quick-info">
                        <div class="priority-line">
                            <span class="priority-dot priority-{{ $task->priority ?? 'medium' }}" aria-hidden="true"></span>
                            <span class="priority-label">أولوية: {{ ucfirst($task->priority ?? 'medium') }}</span>
                        </div>

                        <div class="quick-metrics">
                            <div class="qm-item">
                                <div class="qm-label">الإجمالي</div>
                                <div class="qm-value" id="tracked-baseline"
                                    data-timer-start="{{ $task->timer_started_at?->timestamp ?? 0 }}"
                                    data-total-tracked="{{ $task->total_tracked_seconds }}">{{ gmdate('H:i:s', $task->total_tracked_seconds ?? 0) }}</div>
                            </div>
                            @php
                                $est = $task->estimated_duration_seconds ?? 0;
                                $remaining_seconds = max(0, ($est - ($displayed_total ?? 0)));
                            @endphp
                            <div class="qm-item">
                                    <div class="qm-label">الباقي لإنجاز المهمة</div>
                                    <div id="remaining-to-complete" class="qm-value" data-initial-remaining="{{ $remaining_seconds }}">{{ $est ? gmdate('H:i:s', $remaining_seconds) : '-' }}</div>
                                </div>
                            <div class="qm-item">
                                <div class="qm-label">تم الإنشاء</div>
                                <div class="qm-value">{{ $task->created_at->format('d/m/Y') }}</div>
                            </div>
                        </div>

                        <!-- Action Buttons + Focus Toggle -->
                        <div class="flex gap-3 items-center flex-nowrap">
                            <!-- Timer Control Buttons -->
                            <div id="task-container" data-status="{{ $task->status }}" class="flex gap-3 action-buttons-group">
                                {{-- Buttons are dynamically generated by JavaScript based on task status --}}
                            </div>

                            <!-- Focus Toggle Button -->
                            <button id="focus-toggle" class="action-btn action-btn-start whitespace-nowrap" type="button">🔒 وضع التركيز</button>
                        </div>
                    </div>
                </div>

                <!-- Status and Time Tracking -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8 task-metrics">
                    <div class="metric-card" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                        <div class="metric-label">الحالة</div>
                        <div class="metric-value" id="task-status-text">
                            @if($task->status === 'running')
                                <span style="color: #22c55e;">🟢 جاري</span>
                            @elseif($task->status === 'stopped')
                                <span style="color: #eab308;">🟡 موقوف</span>
                            @elseif($task->status === 'completed')
                                <span style="color: #3b82f6;">✓ منتهي</span>
                            @else
                                <span style="color: var(--text-color); opacity: 0.7;">⚪ معلق</span>
                            @endif
                        </div>
                    </div>

                    <div class="metric-card" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                        <div class="metric-label">الإجمالي المسجل</div>
                        {{-- Baseline total from server; kept static while live timer shows dynamic elapsed time --}}
                        <div id="tracked-baseline" class="metric-value" style="color: var(--highlight-color);" data-total-tracked="{{ $task->total_tracked_seconds }}" data-timer-start="0">
                            {{ gmdate('H:i:s', $task->total_tracked_seconds ?? 0) }}
                        </div>
                    </div>

                    <div class="metric-card" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                        <div class="metric-label">المدة المقدرة</div>
                        @php
                            $est = $task->estimated_duration_seconds ?? 0;
                            if ($est >= 3600) {
                                $h = floor($est/3600);
                                $m = floor(($est % 3600)/60);
                                $estText = $h . ' ساعة' . ($m ? ' و ' . $m . ' دقيقة' : '');
                            } elseif ($est >= 60) {
                                $m = floor($est/60);
                                $estText = $m . ' دقيقة';
                            } else {
                                $estText = $est . ' ثانية';
                            }
                        @endphp
                        <div class="metric-value" style="color: var(--accent-color);">{{ $estText ?: '-' }}</div>
                    </div>
                </div>

                <!-- Last Session Info -->
                @if($task->last_session_seconds > 0)
                    <div class="last-session-card" style="background: var(--glass-bg); border: 1px solid var(--glass-border); border-radius: 1rem; padding: 1.5rem; margin-bottom: 2rem;">
                        <div class="metric-label">آخر جلسة</div>
                        <div class="metric-value" style="color: var(--accent-color); font-size: 1.25rem;">{{ gmdate('H:i:s', $task->last_session_seconds) }}</div>
                    </div>
                @endif

                <!-- Progress + Sections -->
                @php
                    $displayed_total = $task->total_tracked_seconds + ($task->status === 'stopped' ? ($task->last_session_seconds ?? 0) : 0);
                    $est = $task->estimated_duration_seconds ?? 0;
                    $progressPct = 0;
                    if ($est > 0) {
                        $progressPct = (int) min(100, floor(($displayed_total / $est) * 100));
                    }
                @endphp

                @if($est > 0)
                <div class="progress-card mb-6">
                    <div class="progress-row">
                        <div class="progress-label">التقدّم</div>
                        <div class="progress-value">{{ $progressPct }}%</div>
                    </div>
                    <div class="progress-bar-outer">
                        <div class="progress-bar-inner" style="width: {{ $progressPct }}%"></div>
                    </div>
                    <div class="progress-meta small muted">{{ gmdate('H:i:s', $displayed_total) }} من {{ gmdate('H:i:s', $est) }}</div>
                </div>
                @else
                <div class="progress-card mb-6" style="background: rgba(255,255,255,0.02); border: 1px dashed rgba(255,255,255,0.1); padding: 1rem; border-radius: 0.85rem; text-align: center; color: rgba(255,255,255,0.5);">
                    لم تحدد مدة مقدرة للمهمة - قم بتحديثها
                </div>
                @endif

                <div class="detail-sections grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="glass section-card p-4">
                        <h4 class="section-title">ملاحظات</h4>
                        <div class="section-body">
                            @if($task->description)
                                <p class="note-text" style="color: var(--text-color);">{{ $task->description }}</p>
                            @else
                                <p class="note-text muted">لا توجد ملاحظات حالياً.</p>
                            @endif
                        </div>
                    </div>

                    <div class="glass section-card p-4">
                        <h4 class="section-title">سجل النشاط</h4>
                        <div class="section-body activity-list">
                            <div class="activity-item">
                                <div class="act-time">آخر جلسة</div>
                                <div class="act-desc">{{ $task->last_session_seconds ? gmdate('H:i:s', $task->last_session_seconds) : '-' }}</div>
                            </div>
                            <div class="activity-item">
                                <div class="act-time">الإجمالي</div>
                                <div class="act-desc">{{ gmdate('H:i:s', $task->total_tracked_seconds ?? 0) }}</div>
                            </div>
                            <div class="activity-item">
                                <div class="act-time">الحالة</div>
                                <div class="act-desc">{{ ucfirst($task->status) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="additional-info" style="border-top: 1px solid var(--glass-border); padding-top: 1.5rem;">
                    <div class="grid grid-cols-2 gap-4 mb-6 info-grid">
                        @if($task->deadline)
                            <div class="info-item">
                                <div class="info-label">الموعد النهائي</div>
                                <div class="info-value">{{ \Carbon\Carbon::parse($task->deadline)->format('d/m/Y H:i') }}</div>
                            </div>
                        @endif
                        <div class="info-item">
                            <div class="info-label">التاريخ الإنشاء</div>
                            <div class="info-value">{{ $task->created_at->format('d/m/Y') }}</div>
                        </div>
                    </div>

                    <a href="{{ route('goals.show', $task->goal) }}" class="back-link">
                         العودة للهدف
                    </a>
                </div>
            </div>
        </div>

        </div>
    </div>
    </div>
@endsection

@push('scripts')
<script>
// Toast Notification System
function showToast(message, type = 'success', duration = 3000) {
    const existingToasts = document.querySelectorAll('.toast-notification');
    existingToasts.forEach(t => t.remove());

    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    toast.textContent = message;

    const styles = {
        position: 'fixed',
        bottom: '2rem',
        right: '2rem',
        padding: '1rem 1.5rem',
        borderRadius: '0.75rem',
        color: 'white',
        fontWeight: '600',
        zIndex: '9999',
        animation: 'slideInToast 0.3s ease-out',
        fontFamily: "'Cairo', 'Josefin Slab', serif",
        boxShadow: 'var(--shadow-lg)',
        maxWidth: '350px',
        wordWrap: 'break-word'
    };

    const typeStyles = {
        success: { background: 'linear-gradient(135deg, #22c55e 0%, #16a34a 100%)' },
        error: { background: 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)' },
        warning: { background: 'linear-gradient(135deg, #eab308 0%, #ca8a04 100%)' },
        info: { background: 'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)' }
    };

    Object.assign(toast.style, styles);
    Object.assign(toast.style, typeStyles[type] || typeStyles.success);

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.animation = 'slideOutToast 0.3s ease-in forwards';
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

// Add toast animation styles
if (!document.querySelector('style[data-toast-styles]')) {
    const style = document.createElement('style');
    style.setAttribute('data-toast-styles', 'true');
    style.textContent = `
        @keyframes slideInToast {
            from { opacity: 0; transform: translateX(100%); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes slideOutToast {
            from { opacity: 1; transform: translateX(0); }
            to { opacity: 0; transform: translateX(100%); }
        }
    `;
    document.head.appendChild(style);
}

document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('task-container');
    const trackedBaseline = document.getElementById('tracked-baseline');
    const trackedLarge = document.getElementById('tracked-time-large');
    const ring = document.querySelector('.ring-progress');
    const statusTextEl = document.getElementById('task-status-text');
    const timerEpochEl = document.getElementById('timer-epoch-text');

        // Helper: format seconds -> HH:MM:SS
        function formatHMS(sec) {
            sec = Number(sec) || 0;
            return new Date(sec * 1000).toISOString().substr(11, 8);
        }

        // Update the activity list section with latest task data
        function updateActivityList(taskObj) {
            const list = document.querySelector('.activity-list');
            if (!list) return;

            const last = Number(taskObj.last_session_seconds || 0) || 0;
            const total = Number(taskObj.total_tracked_seconds || 0) || 0;
            const status = (taskObj.status || container?.dataset?.status || '').toString();

            // If stopped, show total + last session as displayed elsewhere
            const displayTotal = (status === 'stopped') ? (total + last) : total;

            const items = [
                { time: 'آخر جلسة', desc: last ? formatHMS(last) : '-' },
                { time: 'الإجمالي', desc: formatHMS(displayTotal) },
                { time: 'الحالة', desc: (status ? status.charAt(0).toUpperCase() + status.slice(1) : '-') }
            ];

            list.innerHTML = items.map(i => `
                <div class="activity-item">
                    <div class="act-time">${i.time}</div>
                    <div class="act-desc">${i.desc}</div>
                </div>
            `).join('');
        }

        // Update all last-session displays on the page
        function updateLastSessionDisplays(taskObj) {
            const lastFinished = Number(taskObj.last_finished_session_seconds || 0) || 0;
            const lastFinishedFormatted = lastFinished ? formatHMS(lastFinished) : '-';

            // Debug info (safe)
            try { console.log('[updateLastSessionDisplays] lastFinished:', lastFinished, 'formatted:', lastFinishedFormatted); } catch(e) {}

            // Note: quick-metrics now shows remaining-to-complete; do not overwrite it here.

            // Update last-session-card (الكارد تحت المؤقت)
            const lastSessionCard = document.querySelector('.last-session-card');
            if (lastSessionCard) {
                if (lastFinished > 0) {
                    lastSessionCard.style.display = 'block';
                    const cardValue = lastSessionCard.querySelector('.metric-value');
                    if (cardValue) cardValue.textContent = lastFinishedFormatted;
                } else {
                    lastSessionCard.style.display = 'none';
                }
            }

            // Update activity list (ensure activity shows last finished where relevant)
            // Inject last_finished into the object so updateActivityList can use it if needed
            try { taskObj.last_finished_session_seconds = Number(taskObj.last_finished_session_seconds || lastFinished); } catch(e) {}
            updateActivityList(taskObj);
        }

    function parseTimerStart(val) {
        if (!val) return 0;
        const n = Number(val);
        if (!isNaN(n)) return Math.floor(n);
        const d = Date.parse(val);
        return isNaN(d) ? 0 : Math.floor(d/1000);
    }

    function updateActionButtons() {
        const formGroup = document.getElementById('task-container');
        if (!formGroup) return;

        const timerStartNow = parseInt(trackedBaseline?.dataset?.timerStart || '0', 10) || 0;
        const serverStatus = container?.dataset?.status || 'idle';
        const effectiveStatus = timerStartNow > 0 ? 'running' : serverStatus;
        container.dataset.status = effectiveStatus;

        // Check if duration is complete (elapsed >= estimated)
        const est = parseInt(trackedLarge?.dataset?.estimated || 0);
        const baseline = trackedBaseline ? parseInt(trackedBaseline.dataset.totalTracked || '0', 10) : 0;
        const timerStart = parseTimerStart(trackedLarge?.dataset?.timerStart || 0);
        let secondsL = baseline;
        if (effectiveStatus === 'running' && timerStart > 0) {
            const now = Math.floor(Date.now() / 1000);
            secondsL = baseline + (now - timerStart);
        }
        const isDurationComplete = est > 0 && secondsL >= est;

        formGroup.innerHTML = '';

        const createButton = (actionType, label, className, confirmMsg = null) => {
            const form = document.createElement('form');
            form.method = 'POST';
            form.className = 'inline ajax-task-action';
            const routes = {
                start: '{{ route("tasks.start", $task) }}',
                stop: '{{ route("tasks.stop", $task) }}',
                finish: '{{ route("tasks.finish", $task) }}',
                cancel: '{{ route("tasks.cancel", $task) }}'
            };
            form.action = routes[actionType] || '';
            const csrf = document.querySelector('meta[name="csrf-token"]');
            const svgs = {
                start: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 3v18l15-9L5 3z" fill="currentColor"/></svg>`,
                stop: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 6h4v12H6zM14 6h4v12h-4z" fill="currentColor"/></svg>`,
                finish: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" fill="currentColor"/></svg>`,
                cancel: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18.3 5.71L12 12l6.3 6.29-1.41 1.42L10.59 13.41 4.29 19.71 2.88 18.3 9.18 12 2.88 5.71 4.29 4.29 10.59 10.59 16.88 4.29z" fill="currentColor"/></svg>`
            };

            const labelText = label.replace(/[▶⏸✓✕]/g, '').trim();
            const icon = svgs[actionType] || '';
            form.innerHTML = `
                ${csrf ? `<input type="hidden" name="_token" value="${csrf.content}">` : ''}
                <button type="submit" class="action-btn ${className}">
                    <span class="btn-icon" aria-hidden="true">${icon}</span>
                    <span class="btn-label">${labelText}</span>
                </button>
            `;
            if (confirmMsg) {
                form.querySelector('button').onclick = () => confirm(confirmMsg);
            }
            form.addEventListener('submit', handleFormSubmit);
            formGroup.appendChild(form);
        };

        // If duration is complete, show completion message instead of buttons
        if (isDurationComplete) {
            const msgDiv = document.createElement('div');
            msgDiv.className = 'action-btn action-btn-complete'
                + ' flex items-center gap-2'
                + ' py-2 px-4 rounded-lg'
                + ' bg-green-500/20 border border-green-500/50'
                + ' text-green-500 font-medium';
            msgDiv.innerHTML = `
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" fill="currentColor"/></svg>
                <span>اكتملت المهمة! ✓</span>
            `;
            formGroup.appendChild(msgDiv);
        } else if (['idle', 'stopped'].includes(effectiveStatus)) {
            createButton('start', 'بدء', 'action-btn-start');
        }
        if (effectiveStatus === 'running' && !isDurationComplete) {
            createButton('stop', 'إيقاف', 'action-btn-stop');
        }
        if (effectiveStatus === 'stopped' && !isDurationComplete) {
            createButton('finish', 'إنهاء', 'action-btn-finish');
        }
        if (['running', 'stopped'].includes(effectiveStatus) && !isDurationComplete) {
            createButton('cancel', 'إلغاء', 'action-btn-cancel', 'إلغاء الجلسة الحالية؟');
        }
    }

    async function handleFormSubmit(e) {
        e.preventDefault();
        const action = this.action;
        const formData = new FormData(this);
        let displayedBeforeStart = null;

        if (action && action.includes('/start') && trackedBaseline) {
            const parts = trackedBaseline.textContent.trim().split(':').map(Number);
            if (parts.length === 3 && parts.every(p => !isNaN(p))) {
                displayedBeforeStart = parts[0]*3600 + parts[1]*60 + parts[2];
            }
            container.dataset.sessionOriginal = String(parseInt(trackedBaseline.dataset.totalTracked || '0', 10) || 0);
        }

        try {
            if (action && action.includes('/stop') && trackedBaseline) {
                const timerStartNow = parseInt(trackedBaseline.dataset.timerStart) || 0;
                const baselineNow = parseInt(trackedBaseline.dataset.totalTracked) || 0;
                if (timerStartNow > 0) {
                    const nowSec = Math.floor(Date.now() / 1000);
                    const elapsed = Math.max(0, nowSec - timerStartNow);
                    const displaySec = baselineNow + elapsed;
                    trackedBaseline.dataset.preStopValue = String(displaySec);
                    trackedBaseline.dataset.timerStart = 0;
                    trackedBaseline.textContent = new Date(displaySec * 1000).toISOString().substr(11,8);
                    container.dataset.status = 'stopped';
                }
            }

            const res = await fetch(action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });

            const json = await res.json().catch(() => ({}));

            if (!res.ok || json.success === false) {
                showToast(json.message || 'حدث خطأ', 'error');
                return;
            }

            if (json.task && container) {
                const t = json.task;
                const status = t.status ?? container.dataset.status;
                container.dataset.status = status;

                if (trackedBaseline) {
                    const timerStart = parseTimerStart(t.timer_started_at ?? t.timer_started_at_timestamp ?? trackedBaseline.dataset.timerStart);
                    let total = Number(t.total_tracked_seconds ?? trackedBaseline.dataset.totalTracked) || 0;
                    const last = Number(t.last_session_seconds ?? 0) || 0;

                    if (json.action === 'start' && displayedBeforeStart !== null) {
                        total = displayedBeforeStart;
                    }

                    if (json.action === 'stop') {
                        const serverTotal = Number(t.total_tracked_seconds || 0);
                        const serverLast = Number(t.last_session_seconds || 0);
                        const lastFinished = Number(t.last_finished_session_seconds || 0);
                        const newBaseline = serverTotal + serverLast;
                        trackedBaseline.dataset.totalTracked = String(newBaseline);
                        trackedBaseline.dataset.lastSession = String(serverLast);
                        trackedBaseline.dataset.lastFinishedSession = String(lastFinished);
                        trackedBaseline.dataset.timerStart = 0;
                        trackedBaseline.textContent = new Date(newBaseline * 1000).toISOString().substr(11,8);
                        delete trackedBaseline.dataset.preStopValue;
                    } else if (json.action === 'cancel') {
                        const orig = Number(container.dataset.sessionOriginal || trackedBaseline.dataset.totalTracked) || 0;
                        const lastFinished = Number(t.last_finished_session_seconds || 0);
                        trackedBaseline.dataset.totalTracked = orig;
                        trackedBaseline.dataset.lastSession = '0';
                        trackedBaseline.dataset.lastFinishedSession = String(lastFinished);
                        trackedBaseline.dataset.timerStart = 0;
                        trackedBaseline.textContent = new Date(orig * 1000).toISOString().substr(11,8);
                        delete container.dataset.sessionOriginal;
                    } else {
                        const lastFinished = Number(t.last_finished_session_seconds || 0);
                        trackedBaseline.dataset.timerStart = timerStart || 0;
                        trackedBaseline.dataset.totalTracked = total;
                        trackedBaseline.dataset.lastSession = String(last);
                        trackedBaseline.dataset.lastFinishedSession = String(lastFinished);
                        let displayTotal = total;
                        if (status === 'stopped') {
                            displayTotal = total + last;
                        }
                        trackedBaseline.textContent = new Date(displayTotal * 1000).toISOString().substr(11,8);
                    }
                }

                if (trackedLarge) {
                    const timerStartL = parseTimerStart(t.timer_started_at ?? trackedLarge.dataset.timerStart);
                    let totalL = Number(t.total_tracked_seconds ?? trackedLarge.dataset.totalTracked) || 0;
                    const lastL = Number(t.last_session_seconds ?? 0) || 0;

                    if (json.action === 'stop') {
                        const serverTotal = Number(t.total_tracked_seconds || 0);
                        const serverLast = Number(t.last_session_seconds || 0);
                        const lastFinished = Number(t.last_finished_session_seconds || 0);
                        const newBaseline = serverTotal + serverLast;
                        trackedLarge.dataset.totalTracked = String(newBaseline);
                        trackedLarge.dataset.lastSession = String(serverLast);
                        trackedLarge.dataset.lastFinishedSession = String(lastFinished);
                        trackedLarge.dataset.timerStart = 0;
                        trackedLarge.textContent = new Date(newBaseline * 1000).toISOString().substr(11,8);
                    } else if (json.action === 'cancel') {
                        const orig = Number(container.dataset.sessionOriginal || trackedLarge.dataset.totalTracked) || 0;
                        const lastFinished = Number(t.last_finished_session_seconds || 0);
                        trackedLarge.dataset.totalTracked = orig;
                        trackedLarge.dataset.lastSession = '0';
                        trackedLarge.dataset.lastFinishedSession = String(lastFinished);
                        trackedLarge.dataset.timerStart = 0;
                        trackedLarge.textContent = new Date(orig * 1000).toISOString().substr(11,8);
                    } else {
                        const lastFinished = Number(t.last_finished_session_seconds || 0);
                        trackedLarge.dataset.timerStart = timerStartL || 0;
                        trackedLarge.dataset.totalTracked = totalL;
                        trackedLarge.dataset.lastSession = String(lastL);
                        trackedLarge.dataset.lastFinishedSession = String(lastFinished);
                        trackedLarge.dataset.taskStatus = status;
                        let displayTotalL = totalL;
                        if (status === 'stopped') displayTotalL = totalL + lastL;
                        trackedLarge.textContent = new Date(displayTotalL * 1000).toISOString().substr(11,8);
                    }
                }
                // Refresh all last-session displays to reflect latest values
                try {
                    // Prefer server-provided `last_finished_session_seconds`.
                    // Fallback to `last_session_seconds` or any pre-stop computed value.
                    const lastFinishedFromServer = Number(t.last_finished_session_seconds ?? 0) || 0;
                    const lastSessionFromServer = Number(t.last_session_seconds ?? 0) || 0;
                    const preStop = Number(trackedBaseline?.dataset?.preStopValue ?? trackedBaseline?.dataset?.lastSession ?? trackedLarge?.dataset?.lastSession ?? 0) || 0;

                    // If server gave a last finished value, use it. Otherwise on finish use last_session or preStop.
                    const lastFinished = lastFinishedFromServer || (json.action === 'finish' ? (lastSessionFromServer || preStop) : lastFinishedFromServer);

                    if (trackedBaseline) trackedBaseline.dataset.lastFinishedSession = String(lastFinished);
                    if (trackedLarge) trackedLarge.dataset.lastFinishedSession = String(lastFinished);

                    // Ensure the task object we pass to the UI updater contains the resolved value
                    t.last_finished_session_seconds = lastFinished;
                    updateLastSessionDisplays(t);
                    // also update remaining field after server response
                    try { const remEl = document.getElementById('remaining-to-complete'); if (remEl) { const est = parseInt(trackedLarge?.dataset?.estimated || remEl.dataset?.initialRemaining || '0', 10) || 0; const total = Number(trackedLarge?.dataset?.totalTracked || trackedBaseline?.dataset?.totalTracked || 0); const lastFromServer = Number(t.total_tracked_seconds || total); const remaining = Math.max(0, est - lastFromServer); remEl.textContent = est ? new Date(remaining * 1000).toISOString().substr(11,8) : '-'; } } catch(e) {}
                } catch(e) { /* ignore */ }
            }

            const toastMessages = {
                start: 'تم بدء المهمة',
                stop: 'تم إيقاف المهمة',
                finish: 'تم إنهاء المهمة',
                cancel: 'تم إلغاء الجلسة'
            };

            showToast(toastMessages[json.action] || json.message || 'تمت العملية', 'success');
            updateActionButtons();

            if (json.action === 'finish') {
                setTimeout(() => location.reload(), 1500);
                return;
            }
        } catch (err) {
            console.error(err);
            showToast('خطأ في الشبكة. حاول مجدداً.', 'error');
        }
    }

    // Start local tick for elapsed time and status updates
    (function startTick() {
        const tick = () => {
            const status = container?.dataset?.status || trackedLarge?.dataset?.taskStatus || 'idle';
            const timerStart = parseTimerStart(trackedLarge?.dataset?.timerStart || 0);
            const baseline = trackedBaseline ? parseInt(trackedBaseline.dataset.totalTracked || '0', 10) : 0;
            const est = parseInt(trackedLarge?.dataset?.estimated || 0);
            let secondsL = baseline;

            if (status === 'running' && timerStart > 0) {
                const now = Math.floor(Date.now() / 1000);
                secondsL = baseline + (now - timerStart);
            }

            // Auto-stop timer if duration is complete (when elapsed >= estimated)
            if (status === 'running' && est > 0 && secondsL >= est) {
                console.log('[Auto-Stop] Duration complete. Stopping timer.');
                // Call the stop endpoint to stop the timer on the server
                fetch(window.location.pathname + '/stop', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' }
                }).then(r => r.json()).then(json => {
                    if (json.task) {
                        container.dataset.status = 'stopped';
                        trackedLarge.dataset.taskStatus = 'stopped';
                        showToast('اكتملت المهمة! العداد توقف تلقائياً.', 'success');
                    }
                }).catch(e => console.error('Auto-stop failed:', e));
                return;
            }

            if (trackedLarge) {
                trackedLarge.textContent = new Date(secondsL * 1000).toISOString().substr(11, 8);
            }

            // Update remaining-to-complete display (live)
            try {
                const remEl = document.getElementById('remaining-to-complete');
                if (remEl) {
                    const remaining = Math.max(0, est - secondsL);
                    remEl.textContent = est ? new Date(remaining * 1000).toISOString().substr(11,8) : '-';
                }
            } catch(e) { /* ignore */ }

            if (statusTextEl) {
                let html = '';
                const dot = (c) => `<svg width="12" height="12" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle; margin-inline-end:6px"><circle cx="6" cy="6" r="6" fill="${c}"/></svg>`;

                if (status === 'running') {
                    html = `${dot('#22c55e')}<span style="color: #22c55e; font-weight:700">جاري</span>`;
                } else if (status === 'stopped') {
                    html = `${dot('#eab308')}<span style="color: #eab308; font-weight:700">موقوف</span>`;
                } else if (status === 'completed') {
                    html = `${dot('#3b82f6')}<span style="color: #3b82f6; font-weight:700">منتهي</span>`;
                } else {
                    html = `${dot('rgba(255,255,255,0.7)')}<span style="color: var(--text-color); opacity:0.8">معلق</span>`;
                }
                statusTextEl.innerHTML = html;
            }

            if (timerEpochEl) {
                if (status === 'running') {
                    timerEpochEl.textContent = 'جاري الآن';
                } else if (status === 'stopped') {
                    timerEpochEl.textContent = 'موقوف';
                } else if (status === 'completed') {
                    timerEpochEl.textContent = 'منتهي';
                } else {
                    timerEpochEl.textContent = 'معلق';
                }
            }

            if (ring) {
                const totalLen = 326.7256;
                if (est > 0) {
                    const pct = Math.min(1, secondsL / est);
                    const offset = Math.max(0, totalLen * (1 - pct));
                    ring.style.strokeDashoffset = String(offset);
                    ring.classList.remove('indeterminate');
                } else {
                    ring.style.strokeDashoffset = String(totalLen * 0.65);
                    ring.classList.add('indeterminate');
                }
            }
        };

        tick();
        setInterval(tick, 1000);
    })();

    // Focus toggle with persistence per task
    const focusBtn = document.getElementById('focus-toggle');
    const pageRoot = document.querySelector('.task-show-page');
    const taskId = trackedLarge?.dataset?.taskId || trackedLarge?.getAttribute('data-task-id') || null;
    const focusKey = taskId ? `task_focus_${taskId}` : 'task_focus_global';
    if (focusBtn && pageRoot) {
        // apply saved state if any
        try {
            const saved = localStorage.getItem(focusKey);
            if (saved === '1') {
                pageRoot.classList.add('focus-mode');
                document.body.classList.add('focus-mode');
                focusBtn.textContent = '🔓 خروج من التركيز';
            } else {
                pageRoot.classList.remove('focus-mode');
                document.body.classList.remove('focus-mode');
                focusBtn.textContent = '🔒 وضع التركيز';
            }
        } catch (e) {
            // ignore storage errors
        }

        focusBtn.addEventListener('click', () => {
            const isNow = pageRoot.classList.toggle('focus-mode');
            // also mark on body so global elements (navbar/footer) can be hidden by CSS
            if (isNow) document.body.classList.add('focus-mode'); else document.body.classList.remove('focus-mode');
            focusBtn.textContent = isNow ? '🔓 خروج من التركيز' : '🔒 وضع التركيز';
            try { localStorage.setItem(focusKey, isNow ? '1' : '0'); } catch (e) {}
        });
    }

    // Initialize buttons on load
    updateActionButtons();

    // Initialize activity list on load
    if (trackedLarge) {
        const initialTaskData = {
            last_finished_session_seconds: Number(trackedLarge.dataset.lastFinishedSession) || 0,
            last_session_seconds: Number(trackedLarge.dataset.lastSession) || 0,
            total_tracked_seconds: Number(trackedLarge.dataset.totalTracked) || 0,
            status: trackedLarge.dataset.taskStatus || 'idle'
        };
        updateLastSessionDisplays(initialTaskData);
    }

    // Handle priority change via select dropdown
    const prioritySelector = document.getElementById('priority-selector');
    if (prioritySelector) {
        prioritySelector.addEventListener('change', async (e) => {
            const newPriority = e.target.value;
            const taskId = prioritySelector.dataset.taskId;

            try {
                const csrf = document.querySelector('meta[name="csrf-token"]');
                const response = await fetch(`/tasks/${taskId}/update-priority`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        _token: csrf.content,
                        priority: newPriority
                    })
                });

                const json = await response.json();

                if (response.ok && json.success) {
                    // Update priority selector styling
                    prioritySelector.className = `priority-selector priority-${newPriority}`;

                    // Update other priority displays on the page
                    const priorityPills = document.querySelectorAll('.priority-pill');
                    priorityPills.forEach(pill => {
                        pill.className = `priority-pill priority-${newPriority}`;
                        pill.textContent = newPriority.charAt(0).toUpperCase() + newPriority.slice(1);
                    });

                    const priorityDots = document.querySelectorAll('.priority-dot');
                    priorityDots.forEach(dot => {
                        dot.className = `priority-dot priority-${newPriority}`;
                    });

                    const priorityLabels = document.querySelectorAll('.priority-label');
                    priorityLabels.forEach(label => {
                        label.textContent = `أولوية: ${newPriority.charAt(0).toUpperCase() + newPriority.slice(1)}`;
                    });

                    showToast('تم تحديث الأولوية بنجاح', 'success');
                } else {
                    showToast(json.message || 'فشل تحديث الأولوية', 'error');
                    // Reset selector
                    prioritySelector.value = e.target.dataset.oldValue;
                }
            } catch (err) {
                console.error(err);
                showToast('خطأ في تحديث الأولوية', 'error');
            }
        });
    }
});
</script>
@endpush
