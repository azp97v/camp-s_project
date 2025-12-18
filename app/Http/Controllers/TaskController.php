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

    public function store(Request $request, Goal $goal)
    {
        $this->authorize('view', $goal);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            // للمهمة: مدتها تُدخَل بالدقائق أو الساعات
            'estimated_duration_input' => 'nullable|numeric|min:0',
            'estimated_unit' => 'nullable|in:minutes,hours',
        ]);
        $estimatedSeconds = 0;
        if (!empty($data['estimated_duration_input']) && !empty($data['estimated_unit'])) {
            $estimatedSeconds = match($data['estimated_unit']) {
                'minutes' => (int)$data['estimated_duration_input'] * 60,
                'hours' => (int)$data['estimated_duration_input'] * 3600,
            };
        }

        $task = $goal->tasks()->create(array_merge($data, ['estimated_duration_seconds' => $estimatedSeconds]));

        if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['message' => 'تم إنشاء المهمة.', 'task' => $task]);
        }

        return redirect()->route('tasks.show', $task)->with('success', 'تم إنشاء المهمة.');
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task->goal);
        return view('tasks.show', compact('task'));
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
}
