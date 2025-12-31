<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Task extends Model
{
    /**
     * Task model represents a unit of work belonging to a Goal.
     *
     * نموذج المهمة يمثل وحدة عمل مرتبطة بهدف.
     * Important fields / الحقول المهمة:
     * - `status`               : idle|running|stopped|completed
     * - `timer_started_at`     : timestamp when current running session began
     * - `last_session_seconds` : seconds recorded when the user stopped a session
     * - `total_tracked_seconds`: accumulated seconds tracked for the task
     */
    protected $fillable = [
        'goal_id','title','description','deadline',
        'status','timer_started_at','last_session_seconds','total_tracked_seconds','estimated_duration_seconds',
        'priority',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'timer_started_at' => 'datetime',
    ];

    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }
}
