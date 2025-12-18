<!-- resources/views/goals/show.blade.php -->
<h1>{{ $goal->title }}</h1>
<p>المدة الأساسية: {{ $goal->total_duration_seconds }} ثانية</p>
<p>المتبقي: {{ $goal->remaining_duration_seconds }} ثانية</p>

<hr>

<form action="{{ route('tasks.store', $goal) }}" method="POST">
    @csrf
    <input type="text" name="title" placeholder="اسم المهمة" required>
    <input type="text" name="description" placeholder="وصف المهمة">
    <input type="datetime-local" name="deadline">
    <button type="submit">إضافة مهمة</button>
</form>

<ul>
    @foreach($tasks as $task)
        <li>
            <a href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a>
            (Tracked: {{ $task->total_tracked_seconds }}s, Status: {{ $task->status }})
        </li>
    @endforeach
</ul>
<h2>{{ $goal->title }}</h2>
<p>{{ $goal->description }}</p>
<p>المتبقي: {{ $goal->remaining_duration_seconds }} ثانية</p>

<form method="POST" action="{{ route('tasks.store', $goal) }}">
    @csrf
    <input type="text" name="title" placeholder="اسم المهمة" required>
    <input type="datetime-local" name="deadline">
    <button type="submit">إضافة مهمة</button>
</form>

@foreach($tasks as $task)
    <div>
        <h4>{{ $task->title }}</h4>
        <p>الحالة: {{ $task->status }}</p>
        <p>المدة التراكمية: {{ $task->total_tracked_seconds }} ثانية</p>
        <a href="{{ route('tasks.show', $task) }}">إدارة التايمر</a>
    </div>
@endforeach
