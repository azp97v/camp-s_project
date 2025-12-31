<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goal;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
// Using Blade views instead of Inertia

class GoalController extends Controller
{
    use AuthorizesRequests;

    // ---------------------------------------------------------------------------------
    // GoalController
    // ---------------------------------------------------------------------------------
    // هذا الكنترولر مسؤول عن عمليات CRUD على نماذج الأهداف (Goals) للمستخدم.
    // This controller manages CRUD operations for user `Goal` records.
    // It returns Blade views for HTML responses and JSON for AJAX requests.

    public function index()
    {
        // List goals belonging to the authenticated user, newest first.
        // سرد الأهداف المملوكة للمستخدم الحالي مرتبة حسب الأحدث
        $goals = auth()->user()->goals()->latest()->get();
        return view('goals.index', compact('goals'));
    }

    public function create()
    {
        // عرض نموذج إنشاء هدف جديد
        return view('goals.create');
    }

    public function store(Request $request)
    {
        // Validate incoming goal data and normalize duration to seconds
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            // للأهداف: المدة تُدخَل بالساعات أو الأيام
            'total_duration_input' => 'required|numeric|min:0',
            'total_unit' => 'required|in:hours,days',
        ]);

        // تحويل المدة إلى ثوانٍ حسب الوحدة (ساعات أو أيام)
        // Convert goal duration to seconds (hours or days)
        $seconds = match($data['total_unit']) {
            'hours' => (int)$data['total_duration_input'] * 3600,
            'days'  => (int)$data['total_duration_input'] * 86400,
        };

        // Create the goal owned by the current user. Store both total and remaining seconds.
        $goal = Goal::create([
            'user_id' => auth()->id(),
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'total_duration_seconds' => $seconds,
            'remaining_duration_seconds' => $seconds,
        ]);

        if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['message' => 'تم إنشاء الهدف.', 'goal' => $goal]);
        }

        return redirect()->route('goals.show', $goal)->with('success', 'تم إنشاء الهدف.');
    }

    public function show(Goal $goal)
    {
        // تأكيد صلاحية العرض للمستخدم الحالي ثم جلب مهام الهدف
        $this->authorize('view', $goal);
        $tasks = $goal->tasks()->latest()->get();
        return view('goals.show', compact('goal','tasks'));
    }

    public function edit(Goal $goal)
    {
        // عرض صفحة التعديل مع التحقق من الصلاحيات
        $this->authorize('update', $goal);
        return view('goals.edit', compact('goal'));
    }

    public function update(Request $request, Goal $goal)
    {
        // التحقق من الصلاحية والتحقق من صحة البيانات
        $this->authorize('update', $goal);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_duration_input' => 'required|numeric|min:0',
            'total_unit' => 'required|in:hours,days',
        ]);

        // تحويل المدة إلى ثوانٍ
        // Convert goal duration to seconds (hours or days)
        $seconds = match($data['total_unit']) {
            'hours' => (int)$data['total_duration_input'] * 3600,
            'days'  => (int)$data['total_duration_input'] * 86400,
        };

        // حساب الفرق في المدة وتعديل المتبقي وفقاً لذلك
        $durationDifference = $seconds - $goal->total_duration_seconds;

        // تحديث السجّل دون تغيير المنطق
        $goal->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'total_duration_seconds' => $seconds,
            'remaining_duration_seconds' => $goal->remaining_duration_seconds + $durationDifference,
        ]);

        if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['message' => 'تم تحديث الهدف.', 'goal' => $goal]);
        }

        return redirect()->route('goals.show', $goal)->with('success', 'تم تحديث الهدف.');
    }

    public function destroy(Goal $goal)
    {
        $this->authorize('delete', $goal);
        $goal->delete();
        return redirect()->route('goals.index')->with('success', 'تم حذف الهدف.');
    }
}
