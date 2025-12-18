<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Task;
use App\Models\User;

class Goal extends Model
{
    /**
     * Goal model aggregates Tasks and holds overall duration tracking.
     *
     * Fields:
     * - `total_duration_seconds`: total budget for the goal
     * - `remaining_duration_seconds`: remaining seconds deductable when tasks finish
     */
    protected $fillable = [
        'user_id','title','description',
        'total_duration_seconds','remaining_duration_seconds',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
