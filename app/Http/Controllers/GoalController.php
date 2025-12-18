<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goal;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
// Using Blade views instead of Inertia

class GoalController extends Controller
{
    use AuthorizesRequests;

    // Controller responsible for CRUD operations on user goals.
    // This app uses Blade views (not Inertia) so methods return view() responses.

    public function index()
    {
        // List goals belonging to the authenticated user, newest first.
        $goals = auth()->user()->goals()->latest()->get();
        return view('goals.index', compact('goals'));
    }

    public function create()
    {
        // Render a simple form to create a new goal
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
        $this->authorize('view', $goal);
        $tasks = $goal->tasks()->latest()->get();
        return view('goals.show', compact('goal','tasks'));
    }

    public function destroy(Goal $goal)
    {
        $this->authorize('delete', $goal);
        $goal->delete();
        return redirect()->route('goals.index')->with('success', 'تم حذف الهدف.');
    }
}
