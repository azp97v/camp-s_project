@extends('layouts.main')
@section('title',$task->title)
@section('page-title',$task->title)

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Task Details -->
        <div class="lg:col-span-2">
            <div class="glass p-8 rounded-2xl animate-on-load card-hover border border-white/20 shadow-lg">
                <div class="mb-6">
                    <h2 class="text-3xl font-bold text-slate-900 mb-2">✅ {{ $task->title }}</h2>
                    @if($task->description)
                        <p class="text-slate-600 text-lg">{{ $task->description }}</p>
                    @endif
                </div>

                <!-- Status and Time Tracking -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4">
                        <div class="text-sm text-slate-600 mb-1">الحالة</div>
                        <div class="text-2xl font-bold">
                            @if($task->status === 'running')
                                <span class="text-green-600">🟢 جاري</span>
                            @elseif($task->status === 'stopped')
                                <span class="text-yellow-600">🟡 موقوف</span>
                            @elseif($task->status === 'completed')
                                <span class="text-blue-600">✓ منتهي</span>
                            @else
                                <span class="text-slate-600">⚪ معلق</span>
                            @endif
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4">
                        <div class="text-sm text-slate-600 mb-1">الوقت المتتبع</div>
                        @php
                            $displayed_total = $task->total_tracked_seconds + ($task->status === 'stopped' ? ($task->last_session_seconds ?? 0) : 0);
                        @endphp
                        {{-- JS hook: #tracked-time
                             - data-task-status: current status used by the inline timer
                             - data-timer-start: epoch seconds when current session started (0 if not running)
                             - data-total-tracked: canonical baseline seconds (server)
                             The inline script reads and updates this element to show elapsed time.
                        --}}
                        <div id="tracked-time" class="text-2xl font-bold text-blue-700" data-task-status="{{ $task->status }}" data-timer-start="{{ $task->timer_started_at?->timestamp ?? 0 }}" data-total-tracked="{{ $task->total_tracked_seconds }}">
                            {{ gmdate('H:i:s', $displayed_total) }}
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-4">
                        <div class="text-sm text-slate-600 mb-1">المدة المقدرة</div>
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
                        <div class="text-2xl font-bold text-indigo-700">{{ $estText ?: '-' }}</div>
                    </div>
                </div>

                <!-- Last Session Info -->
                @if($task->last_session_seconds > 0)
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl p-4 mb-8 border border-amber-200/50">
                        <div class="text-sm text-slate-700 mb-1">آخر جلسة</div>
                        <div class="text-lg font-semibold text-slate-900">{{ gmdate('H:i:s', $task->last_session_seconds) }}</div>
                    </div>
                @endif

                <!-- Action Buttons -->
                {{-- Container used by the task action JS to update status and restore baselines on cancel/resume --}}
                <div id="task-container" data-status="{{ $task->status }}" class="flex flex-wrap gap-3 mb-8">
                    @if(in_array($task->status, ['idle', 'stopped']))
                            {{-- Form class `ajax-task-action` is intercepted by the inline script to perform XHR actions
                                and update the `#tracked-time` element without a full page reload. --}}
                            <form method="POST" action="{{ route('tasks.start', $task) }}" class="inline ajax-task-action">
                            @csrf
                            <button type="submit" class="px-6 py-3 rounded-lg bg-gradient-to-r from-green-500 to-emerald-600 text-white font-medium hover:shadow-lg transition-all transform hover:scale-105">
                                {{ $task->status === 'stopped' ? '▶️ استئناف' : '▶️ بدء' }}
                            </button>
                        </form>
                    @endif

                    @if($task->status === 'running')
                        <form method="POST" action="{{ route('tasks.stop', $task) }}" class="inline ajax-task-action">
                            @csrf
                            <button type="submit" class="px-6 py-3 rounded-lg bg-gradient-to-r from-yellow-500 to-amber-600 text-white font-medium hover:shadow-lg transition-all transform hover:scale-105">
                                ⏸️ إيقاف
                            </button>
                        </form>
                    @endif

                    @if($task->status === 'stopped')
                        <form method="POST" action="{{ route('tasks.finish', $task) }}" class="inline ajax-task-action">
                            @csrf
                            <button type="submit" class="px-6 py-3 rounded-lg bg-gradient-to-r from-blue-500 to-cyan-600 text-white font-medium hover:shadow-lg transition-all transform hover:scale-105">
                                ✓ إنهاء
                            </button>
                        </form>
                    @endif

                    @if(in_array($task->status, ['running', 'stopped']))
                        <form method="POST" action="{{ route('tasks.cancel', $task) }}" class="inline ajax-task-action">
                            @csrf
                            <button type="submit" class="px-6 py-3 rounded-lg bg-gradient-to-r from-red-500 to-rose-600 text-white font-medium hover:shadow-lg transition-all transform hover:scale-105" onclick="return confirm('إلغاء الجلسة الحالية؟')">
                                ✕ إلغاء
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Additional Info -->
                <div class="border-t border-slate-200/30 pt-6">
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        @if($task->deadline)
                            <div>
                                <div class="text-sm text-slate-600">الموعد النهائي</div>
                                <div class="text-lg font-semibold text-slate-900">{{ \Carbon\Carbon::parse($task->deadline)->format('d/m/Y H:i') }}</div>
                            </div>
                        @endif
                        <div>
                            <div class="text-sm text-slate-600">التاريخ الإنشاء</div>
                            <div class="text-lg font-semibold text-slate-900">{{ $task->created_at->format('d/m/Y') }}</div>
                        </div>
                    </div>

                    <a href="{{ route('goals.show', $task->goal) }}" class="px-4 py-2 text-blue-600 hover:text-blue-700 font-medium inline-flex items-center gap-2 rounded-lg hover:bg-blue-50 transition-all">
                        ← العودة للهدف
                    </a>
                </div>
            </div>
        </div>

        <!-- Delete Form Sidebar -->
        <div>
            <div class="glass p-6 rounded-2xl sticky top-24 border border-white/20 shadow-lg">
                <h3 class="text-lg font-bold text-slate-900 mb-4">⚙️ الإجراءات</h3>

                <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="mt-6">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full px-4 py-3 rounded-lg bg-gradient-to-r from-red-500 to-rose-600 text-white font-medium hover:shadow-lg transition-all transform hover:-translate-y-0.5" onclick="return confirm('حذف هذه المهمة بشكل نهائي؟')">
                        🗑️ حذف المهمة
                    </button>
                </form>

                <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="text-xs text-slate-600 mb-2">
                        <strong>نصيحة:</strong> استخدم الأزرار أعلاه لتتبع الوقت المستغرق في هذه المهمة.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('task-container');
    const tracked = document.getElementById('tracked-time');

    function parseTimerStart(val) {
        if (!val) return 0;
        const n = Number(val);
        if (!isNaN(n)) return Math.floor(n);
        const d = Date.parse(val);
        return isNaN(d) ? 0 : Math.floor(d/1000);
    }

    document.querySelectorAll('form.ajax-task-action').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            // allow built-in confirm() on buttons with onclick to run first
            const action = form.action;
            const formData = new FormData(form);
            // capture displayed seconds before starting so resume uses exactly what's shown
            let displayedBeforeStart = null;
            if (action && action.includes('/start') && tracked) {
                const parts = tracked.textContent.trim().split(':').map(Number);
                if (parts.length === 3 && parts.every(p => !isNaN(p))) {
                    displayedBeforeStart = parts[0]*3600 + parts[1]*60 + parts[2];
                }
                // store original baseline before session so we can revert on cancel
                container.dataset.sessionOriginal = String(parseInt(tracked.dataset.totalTracked || '0', 10) || 0);
            }

            try {
                // If this is a stop action, compute and display the stopped time immediately
                if (action && action.includes('/stop') && tracked) {
                    const timerStartNow = parseInt(tracked.dataset.timerStart) || 0;
                    const baselineNow = parseInt(tracked.dataset.totalTracked) || 0;
                    if (timerStartNow > 0) {
                        const nowSec = Math.floor(Date.now() / 1000);
                        const elapsed = Math.max(0, nowSec - timerStartNow);
                        const displaySec = baselineNow + elapsed;
                        // mark pre-stop value so response handler can align
                        tracked.dataset.preStopValue = String(displaySec);
                        // stop the ticking immediately
                        tracked.dataset.timerStart = 0;
                        // update visible text
                        tracked.textContent = new Date(displaySec * 1000).toISOString().substr(11,8);
                        // optimistically set status
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
                    alert(json.message || 'حدث خطأ');
                    return;
                }

                // Update local UI state
                if (json.task && container) {
                    // normalize task fields
                    const t = json.task;
                    const status = t.status ?? container.dataset.status;
                    container.dataset.status = status;
                    if (tracked) {
                        const timerStart = parseTimerStart(t.timer_started_at ?? t.timer_started_at_timestamp ?? tracked.dataset.timerStart);
                        // use server total as baseline, but if this was a start action use the displayedBeforeStart value
                        let total = Number(t.total_tracked_seconds ?? t.total_tracked ?? tracked.dataset.totalTracked) || 0;
                        const last = Number(t.last_session_seconds ?? 0) || 0;

                        if (json.action === 'start' && displayedBeforeStart !== null) {
                            // prefer the exact displayed seconds the user saw as the resume baseline
                            total = displayedBeforeStart;
                        }

                        // Handle specific actions
                        if (json.action === 'stop') {
                            // when stopping, prefer server values for baseline
                            const serverTotal = Number(t.total_tracked_seconds || 0);
                            const serverLast = Number(t.last_session_seconds || 0);
                            const newBaseline = serverTotal + serverLast;
                            tracked.dataset.totalTracked = String(newBaseline);
                            tracked.dataset.timerStart = 0;
                            const hhStopped = new Date(newBaseline * 1000).toISOString().substr(11,8);
                            tracked.textContent = hhStopped;
                            // clear any preStop marker
                            delete tracked.dataset.preStopValue;
                        } else if (json.action === 'cancel') {
                            // revert to original baseline saved when starting
                            const orig = Number(container.dataset.sessionOriginal || tracked.dataset.totalTracked) || 0;
                            tracked.dataset.totalTracked = orig;
                            tracked.dataset.timerStart = 0;
                            tracked.textContent = new Date(orig * 1000).toISOString().substr(11,8);
                            // clear saved original
                            delete container.dataset.sessionOriginal;
                        } else {
                            // Keep baseline total in dataset (used when running to add elapsed)
                            tracked.dataset.timerStart = timerStart || 0;
                            tracked.dataset.totalTracked = total;

                            // For stopped state, display total + last session so it doesn't revert
                            let displayTotal = total;
                            if (status === 'stopped') {
                                displayTotal = total + last;
                            }

                            // display H:i:s
                            const hh = new Date((displayTotal) * 1000).toISOString().substr(11,8);
                            tracked.textContent = hh;
                        }
                    }
                }

                if (json.action === 'finish') {
                    // user requested finish -> reload to reflect goal deduction
                    location.reload();
                    return;
                }

                // otherwise show simple success and let UI reflect changes
                alert(json.message || 'تمت العملية');
            } catch (err) {
                console.error(err);
                alert('خطأ في الشبكة. حاول مجدداً.');
            }
        });
    });

    // if running, start local tick to show elapsed time
    (function startTick() {
        if (!tracked) return;
        const tick = () => {
            const status = container?.dataset.status;
            const timerStart = parseInt(tracked.dataset.timerStart) || 0;
            const total = parseInt(tracked.dataset.totalTracked) || 0;
            if (status === 'running' && timerStart > 0) {
                const now = Math.floor(Date.now() / 1000);
                const seconds = total + (now - timerStart);
                tracked.textContent = new Date(seconds * 1000).toISOString().substr(11,8);
            }
        };
        tick();
        setInterval(tick, 1000);
    })();
});
</script>
@endpush
