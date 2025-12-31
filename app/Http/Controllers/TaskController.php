<?php

namespace App\Http\Controllers;
use App\Models\Goal;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


/**
 * TaskController
 *
 * Responsible for CRUD and time-tracking actions for `Task` resources.
 * Key actions:
 *  - `start`  : begin or resume a time-tracking session for a task
 *  - `stop`   : stop the currently running session and record last session seconds
 *  - `finish` : commit last session to the parent Goal and mark task idle
 *  - `cancel` : discard last session and reset task to idle
 *
 * This controller returns JSON for AJAX/XHR requests and redirects for normal web requests.
 */
class TaskController extends Controller
{
    use AuthorizesRequests;
    // ---------------------------------------------------------------------------------
    // TaskController
    // ---------------------------------------------------------------------------------
    // هذا الكنترولر مسؤول عن إدارة المهام (Tasks): CRUD + إجراءات المؤقّت (start/stop/finish/cancel)
    // This controller manages Task CRUD and timer-related transitions. It returns JSON
    // for AJAX requests and redirects for normal web interactions.

    public function store(Request $request, Goal $goal)
    {
        $this->authorize('update', $goal);

        $data = $request->validate([
            'title' => 'required|string|max:255|unique:tasks,title,NULL,id,goal_id,' . $goal->id,
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'priority' => 'nullable|in:low,medium,high',
            // مدة المهمة إجبارية
            'estimated_duration_input' => 'required|numeric|min:1',
            'estimated_unit' => 'required|in:minutes,hours',
        ], [
            'title.unique' => 'هذا الاسم موجود بالفعل في نفس الهدف. يرجى اختيار اسم آخر.',
        ]);

        $estimatedSeconds = match($data['estimated_unit']) {
            'minutes' => (int)$data['estimated_duration_input'] * 60,
            'hours' => (int)$data['estimated_duration_input'] * 3600,
        };

        // Create the task under the parent goal. Keep estimated seconds normalized.
        $payload = [
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'deadline' => $data['deadline'] ?? null,
            'priority' => $data['priority'] ?? 'medium',
            'estimated_duration_seconds' => $estimatedSeconds,
        ];
        $task = $goal->tasks()->create($payload);

        if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['message' => 'تم إنشاء المهمة.', 'task' => $task]);
        }

        return redirect()->route('tasks.show', $task)->with('success', 'تم إنشاء المهمة.');
    }

    public function toggle(Request $request, Task $task)
    {
        $this->authorize('update', $task->goal);

        // If the task is already completed, do not allow unchecking it.
        if ($task->status === 'completed') {
            if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن التراجع عن وضع "مكتملة" بعد تأكيدها.'
                ], 403);
            }

            return back()->withErrors(['status' => 'لا يمكن التراجع عن وضع مكتملة بعد تأكيدها.']);
        }

        // If attempting to mark as completed, verify the task is actually finished
        $canMarkCompleted = false;

        // A task is considered finished when it has an estimated duration and
        // the total tracked seconds is greater or equal to that estimate.
        if ($task->estimated_duration_seconds && $task->total_tracked_seconds >= $task->estimated_duration_seconds) {
            $canMarkCompleted = true;
        }

        if (! $canMarkCompleted) {
            if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن وضع علامة "مكتملة" إلا بعد إتمام المدة المسجلة للمهمة.'
                ], 422);
            }

            return back()->withErrors(['status' => 'لا يمكن وضع علامة مكتملة إلا بعد إتمام المهمة.']);
        }

        // Mark as completed
        $task->update(['status' => 'completed']);

        if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true, 'message' => 'تم تأكيد إنجاز المهمة.', 'task' => $task]);
        }

        return back()->with('success', 'تم تحديث حالة المهمة.');
    }

    // Display tasks for a specific goal
    public function indexByGoal(Goal $goal)
    {
        // Ensure the current user can view this goal
        $this->authorize('view', $goal);

        // Redirect to the unified goal show page which now contains the tasks
        return redirect()->route('goals.show', $goal);
    }

    public function show(Task $task)
    {
        // Ensure the current user may view the task's parent goal, then show task.
        $this->authorize('view', $task->goal);
        return view('tasks.show', compact('task'));
    }

    // List all tasks for current user
    public function index()
    {
        // Display all tasks from all user's goals
        $user = auth()->user();
        $allTasks = Task::whereHas('goal', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('goal')->latest()->get();

        return view('tasks-all', compact('allTasks'));
    }

    // Render edit form for a task
    public function edit(Task $task)
    {
        // Render edit form; policy ensures user may update the parent goal.
        $this->authorize('update', $task->goal);
        return view('tasks.edit', compact('task'));
    }

    // Update task metadata (title, description, deadline, estimated duration)
    public function update(Request $request, Task $task)
    {
        // Validate and apply updates to task metadata.
        $this->authorize('update', $task->goal);

        $data = $request->validate([
            'title' => 'required|string|max:255|unique:tasks,title,' . $task->id . ',id,goal_id,' . $task->goal_id,
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'estimated_duration_input' => 'required|numeric|min:1',
            'estimated_unit' => 'required|in:minutes,hours',
            'priority' => 'nullable|in:low,medium,high',
        ], [
            'title.unique' => 'هذا الاسم موجود بالفعل في نفس الهدف. يرجى اختيار اسم آخر.',
        ]);

        $estimatedSeconds = match($data['estimated_unit']) {
            'minutes' => (int)$data['estimated_duration_input'] * 60,
            'hours' => (int)$data['estimated_duration_input'] * 3600,
        };

        // Update model fields (no behavioral change)
        $task->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'deadline' => $data['deadline'] ?? null,
            'estimated_duration_seconds' => $estimatedSeconds,
            'priority' => $data['priority'] ?? ($task->priority ?? 'medium'),
        ]);

        if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true, 'message' => 'تم تحديث المهمة.', 'task' => $task]);
        }

        return redirect()->route('tasks.show', $task)->with('success', 'تم تحديث المهمة.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task->goal);
        $task->delete();
        return back()->with('success', 'تم حذف المهمة.');
    }

    // بدء المؤقّت (أو استئناف من stopped)
    public function start(Request $request, Task $task)
    {
        // Ensure the current user can update the parent goal
        $this->authorize('update', $task->goal);

        // Allow start only when task is idle (new) or stopped (resume)
        // Reject when already running or completed.
        if (!in_array($task->status, ['idle', 'stopped'])) {
            return back()->with('error', 'لا يمكن البدء: المهمة في حالة ' . $task->status . '. يجب أن تكون معلقة أو موقوفة.');
        }

        try {
            // If a last session was left (user stopped but didn't finish), roll it
            // into the task's total so the resumed timer starts from the correct baseline.
            $add = (int)($task->last_session_seconds ?? 0);
            $task->update([
                'status' => 'running',
                'timer_started_at' => Carbon::now(),
                'last_session_seconds' => 0,
                'total_tracked_seconds' => $task->total_tracked_seconds + $add,
            ]);

            $task->refresh();
            $message = $task->wasChanged(['timer_started_at']) ? 'تم استئناف المؤقّت.' : 'تم بدء المؤقّت.';

            if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json(['success' => true, 'message' => $message, 'task' => $task, 'action' => 'start']);
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء بدء المؤقّت: ' . $e->getMessage()], 500);
            }

            return back()->with('error', 'حدث خطأ أثناء بدء المؤقّت: ' . $e->getMessage());
        }
    }

    // إيقاف المؤقّت (لا يخصم من الهدف بعد، فقط يحسب مدة الجلسة)
    public function stop(Request $request, Task $task)
    {
        $this->authorize('update', $task->goal);

        // التحقق من أن المهمة جارية وأن هناك وقت بدء محفوظ
        if ($task->status !== 'running') {
            return back()->with('error', 'لا يمكن الإيقاف: المهمة ليست قيد التشغيل حالياً.');
        }

        if (is_null($task->timer_started_at)) {
            return back()->with('error', 'لا يمكن الإيقاف: لا توجد جلسة مسجلة.');
        }

        try {
            // حساب الفرق بالثواني (استخدام timestamp لضمان قيمة موجبة)
            $startTime = Carbon::parse($task->timer_started_at);
            $now = Carbon::now();
            $seconds = (int)($now->timestamp - $startTime->timestamp);

            // التأكد من أن القيمة موجبة
            if ($seconds < 0) {
                $seconds = 0;
            }

            $task->update([
                'status' => 'stopped',
                'last_session_seconds' => $seconds,
                'timer_started_at' => null,
            ]);

            $task->refresh();

            if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json(['success' => true, 'message' => "تم إيقاف المؤقّت. مدة الجلسة: {$seconds} ثانية.", 'task' => $task, 'action' => 'stop']);
            }

            return back()->with('success', "تم إيقاف المؤقّت. مدة الجلسة: {$seconds} ثانية.");
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء إيقاف المؤقّت: ' . $e->getMessage()], 500);
            }

            return back()->with('error', 'حدث خطأ أثناء إيقاف المؤقّت: ' . $e->getMessage());
        }
    }

    // إنهاء الجلسة: يخصم مدة الجلسة الموقوفة من الهدف ويضيفها للتراكمي
    public function finish(Request $request, Task $task)
    {
        $this->authorize('update', $task->goal);

        // التحقق من أن المهمة موقوفة
        if ($task->status !== 'stopped') {
            return back()->with('error', 'لا يمكن الإنهاء: يجب إيقاف المؤقّت أولاً.');
        }

        // التحقق من وجود جلسة موقوفة بمدة صحيحة
        if ($task->last_session_seconds <= 0) {
            return back()->with('error', 'لا يمكن الإنهاء: لا توجد جلسة موقوفة لحساب مدتها.');
        }

        try {
            $goal = $task->goal;
            $deduct = min($task->last_session_seconds, $goal->remaining_duration_seconds);

            DB::transaction(function () use ($task, $goal, $deduct) {
                // خصم من الهدف
                $goal->decrement('remaining_duration_seconds', $deduct);

                // تحديث المهمة
                $task->update([
                    'status' => 'idle',
                    'total_tracked_seconds' => $task->total_tracked_seconds + $deduct,
                    'last_finished_session_seconds' => $deduct,
                    'last_session_seconds' => 0,
                ]);
            });

            $task->refresh();

            if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json(['success' => true, 'message' => "تم إنهاء الجلسة وخصم {$deduct} ثانية من الهدف.", 'task' => $task, 'action' => 'finish']);
            }

            return back()->with('success', "تم إنهاء الجلسة وخصم {$deduct} ثانية من الهدف.");
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء إنهاء الجلسة: ' . $e->getMessage()], 500);
            }

            return back()->with('error', 'حدث خطأ أثناء إنهاء الجلسة: ' . $e->getMessage());
        }
    }

    // إلغاء الجلسة: لا خصم ولا إضافة (تفريغ آخر مدة فقط)
    public function cancel(Request $request, Task $task)
    {
        $this->authorize('update', $task->goal);

        // التحقق من أن المهمة إما جارية أو موقوفة
        if (!in_array($task->status, ['running', 'stopped'])) {
            return back()->with('error', 'لا يمكن الإلغاء: لا توجد جلسة قيد العمل أو موقوفة.');
        }

        try {
            $task->update([
                'status' => 'idle',
                'timer_started_at' => null,
                'last_session_seconds' => 0,
            ]);

            $task->refresh();

            if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json(['success' => true, 'message' => 'تم إلغاء الجلسة بدون أي تغييرات على المدة.', 'task' => $task, 'action' => 'cancel']);
            }

            return back()->with('success', 'تم إلغاء الجلسة بدون أي تغييرات على المدة.');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء إلغاء الجلسة: ' . $e->getMessage()], 500);
            }

            return back()->with('error', 'حدث خطأ أثناء إلغاء الجلسة: ' . $e->getMessage());
        }
    }

    // Update task priority via AJAX
    public function updatePriority(Request $request, Task $task)
    {
        $this->authorize('update', $task->goal);

        $data = $request->validate([
            'priority' => 'required|in:low,medium,high',
        ]);

        try {
            $task->update([
                'priority' => $data['priority'],
            ]);

            $task->refresh();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الأولوية بنجاح',
                'task' => $task,
                'priority' => $task->priority,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الأولوية: ' . $e->getMessage(),
            ], 500);
        }
    }
}


