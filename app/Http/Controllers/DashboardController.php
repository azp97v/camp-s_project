<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goal;
use App\Models\Task;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $goals = Goal::with('tasks')->where('user_id', $user->id)->get();
        $goalsCount = $goals->count();

        $totalTasks = Task::whereHas('goal', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        $completedTasks = Task::whereHas('goal', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('status', 'completed')->count();

        $activeTasks = Task::whereHas('goal', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('status', '!=', 'completed')->count();

        $overdueTasks = Task::whereHas('goal', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->whereNotNull('deadline')->where('deadline', '<', now())->where('status', '!=', 'completed')->count();

        $completionRate = $totalTasks ? (int) round(($completedTasks / $totalTasks) * 100) : 0;

        $goalsList = $goals->map(function (Goal $g) {
            // Keep task counts for display
            $tasksCount = $g->tasks()->count();
            $completedTasks = $g->tasks()->where('status', 'completed')->count();

            // Compute progress based on durations (same as goals.index view)
            $totalDuration = $g->total_duration_seconds ?? 0;
            $remaining = $g->remaining_duration_seconds ?? 0;
            $completedDuration = max(0, $totalDuration - $remaining);
            $progress = $totalDuration > 0 ? (int) round(($completedDuration / $totalDuration) * 100) : 0;

            $nextDeadline = $g->tasks()->whereNotNull('deadline')->orderBy('deadline')->first();
            $deadline = $nextDeadline && $nextDeadline->deadline ? $nextDeadline->deadline->format('d M Y') : 'قادم';

            return [
                'id' => $g->id,
                'name' => $g->title ?? ($g->name ?? 'الهدف'),
                'tasks' => $completedTasks,
                'total_tasks' => $tasksCount,
                'progress' => $progress,
                'deadline' => $deadline,
            ];
        })->toArray();

        $recentTasks = Task::whereHas('goal', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('goal')->latest()->take(6)->get();

        $recentActivities = $recentTasks->map(function ($t) {
            $icon = $t->status === 'completed' ? '✓' : '📋';
            $title = $t->status === 'completed' ? 'أكملت مهمة: "' . ($t->title ?? 'مهمة') . '"' : ($t->title ?? 'مهمة جديدة');
            $goalId = $t->goal ? $t->goal->id : null;
            $taskId = $t->id;
            $taskUrl = $goalId ? route('goals.show', $goalId) . '#task-' . $taskId : null;

            return [
                'icon' => $icon,
                'title' => $title,
                'time' => $t->updated_at ? $t->updated_at->diffForHumans() : '',
                'url' => $taskUrl,
                'goal_id' => $goalId,
                'task_id' => $taskId,
            ];
        })->toArray();

        $newGoalsThisMonth = Goal::where('user_id', $user->id)->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();

        $weekDelta = 0;

        // average goal progress
        $avgGoalProgress = $goalsCount ? (int) round(collect($goalsList)->avg('progress')) : 0;

        // upcoming deadlines (next 5 tasks) - return nearest deadlines and include both hours and days
        $upcomingDeadlines = Task::whereHas('goal', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('goal')
            ->whereNotNull('deadline')
            ->where('deadline', '>=', now())
            ->where('status', '!=', 'completed')
            ->orderBy('deadline')
            ->take(5)
            ->get()
            ->map(function ($t) {
                $deadline = $t->deadline ? $t->deadline : null;
                // Use hours for finer-grained urgency and days for longer horizons
                $hoursUntil = $deadline ? now()->diffInHours($deadline) : null;
                $daysUntil = $deadline ? now()->diffInDays($deadline) : null;
                $isUrgent = $deadline && $hoursUntil !== null && $hoursUntil <= 12; // urgent if 12 hours or less
                $isSoon = $deadline && $hoursUntil !== null && $hoursUntil <= 24 && $hoursUntil > 12; // soon if within 24 hours but not urgent

            return [
                'id' => $t->id,
                'title' => $t->title ?? 'مهمة',
                'goal' => $t->goal ? ($t->goal->title ?? 'هدف') : null,
                'goal_id' => $t->goal ? $t->goal->id : null,
                'deadline' => $deadline ? $deadline->format('d M Y H:i') : null,
                'deadline_date' => $deadline,
                'hours_until' => $hoursUntil,
                'is_urgent' => $isUrgent,
                'is_soon' => $isSoon,
                'priority' => $t->priority ?? 'medium',
            ];
        })->toArray();

        return view('dashboard', compact(
            'goalsCount', 'activeTasks', 'completionRate', 'overdueTasks',
            'goalsList', 'recentActivities', 'completedTasks', 'newGoalsThisMonth', 'weekDelta',
            'totalTasks', 'avgGoalProgress', 'upcomingDeadlines'
        ));
    }
}
