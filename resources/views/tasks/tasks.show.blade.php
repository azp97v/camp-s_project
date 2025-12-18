<!-- resources/views/tasks/show.blade.php -->
<h2>{{ $task->title }}</h2>
<p>Status: {{ $task->status }}</p>
<p>Total tracked: <span id="totalTracked">{{ $task->total_tracked_seconds }}</span> s</p>

@if($task->status === 'running')
    <p>جلسة بدأت في: {{ $task->timer_started_at }}</p>
@elseif($task->status === 'stopped')
    <p>آخر جلسة موقوفة: {{ $task->last_session_seconds }} ثانية</p>
@endif

<div style="margin: 1rem 0;">
    <span>واجهة المؤقّت (عرض فقط): </span>
    <span id="liveTimer">0</span> s
</div>

<form action="{{ route('tasks.start', $task) }}" method="POST" style="display:inline;">
    @csrf
    <button type="submit" {{ $task->status !== 'idle' ? 'disabled' : '' }}>بدء</button>
</form>

<form action="{{ route('tasks.stop', $task) }}" method="POST" style="display:inline;">
    @csrf
    <button type="submit" {{ $task->status !== 'running' ? 'disabled' : '' }}>إيقاف</button>
</form>

<form action="{{ route('tasks.finish', $task) }}" method="POST" style="display:inline;">
    @csrf
    <button type="submit" {{ $task->status !== 'stopped' ? 'disabled' : '' }}>إنهاء الجلسة</button>
</form>

<form action="{{ route('tasks.cancel', $task) }}" method="POST" style="display:inline;">
    @csrf
    <button type="submit" {{ !in_array($task->status, ['running','stopped']) ? 'disabled' : '' }}>إلغاء</button>
</form>

<script>
  // عرض المؤقّت الحي في الواجهة (للتجربة/المزامنة البصرية)
  // إن كانت الحالة running، نحتسب الفرق منذ timer_started_at
  (function() {
    const status = "{{ $task->status }}";
    const liveTimerEl = document.getElementById('liveTimer');

    if (status === 'running') {
      const startedAt = new Date("{{ optional($task->timer_started_at)->format('c') }}").getTime();
      function tick() {
        const now = Date.now();
        const seconds = Math.floor((now - startedAt) / 1000);
        liveTimerEl.textContent = seconds;
      }
      tick();
      setInterval(tick, 1000);
    } else if (status === 'stopped') {
      liveTimerEl.textContent = "{{ $task->last_session_seconds }}";
    } else {
      liveTimerEl.textContent = 0;
    }
  })();
</script>


<!-- resources/views/tasks/show.blade.php -->
<h3>{{ $task->title }}</h3>
<p>الحالة: {{ $task->status }}</p>
<p>المدة التراكمية: {{ $task->total_tracked_seconds }} ثانية</p>

<form method="POST" action="{{ route('tasks.start', $task) }}">
    @csrf
    <button type="submit" {{ $task->status !== 'idle' ? 'disabled' : '' }}>بدء</button>
</form>

<form method="POST" action="{{ route('tasks.stop', $task) }}">
    @csrf
    <button type="submit" {{ $task->status !== 'running' ? 'disabled' : '' }}>إيقاف</button>
</form>

<form method="POST" action="{{ route('tasks.finish', $task) }}">
    @csrf
    <button type="submit" {{ $task->status !== 'stopped' ? 'disabled' : '' }}>إنهاء</button>
</form>

<form method="POST" action="{{ route('tasks.cancel', $task) }}">
    @csrf
    <button type="submit" {{ !in_array($task->status, ['running','stopped']) ? 'disabled' : '' }}>إلغاء</button>
</form>

<div>
    <strong>المؤقّت الحي:</strong> <span id="liveTimer">0</span> ثانية
</div>

<script>
    (function() {
        const status = "{{ $task->status }}";
        const liveTimerEl = document.getElementById('liveTimer');

        if (status === 'running') {
            const startedAt = new Date("{{ optional($task->timer_started_at)->format('c') }}").getTime();
            function tick() {
                const now = Date.now();
                const seconds = Math.floor((now - startedAt) / 1000);
                liveTimerEl.textContent = seconds;
            }
            tick();
            setInterval(tick, 1000);
        } else if (status === 'stopped') {
            liveTimerEl.textContent = "{{ $task->last_session_seconds }}";
        }
    })();
</script>
