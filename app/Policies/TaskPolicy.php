<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * TaskPolicy
     * --------------------------------------------------------
     * Arabic: سياسة وصول للمهمات تتأكد أن صاحب الهدف هو نفسه المستخدم الجاري.
     * English: Ensures only the owner of the parent Goal may view/update/delete tasks.
     */
    /**
     * Determine whether the user can view the task.
     */
    public function view(User $user, Task $task): bool
    {
        return $user->id === $task->goal->user_id;
    }

    /**
     * Determine whether the user can delete the task.
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->id === $task->goal->user_id;
    }

    /**
     * Determine whether the user can update the task.
     */
    public function update(User $user, Task $task): bool
    {
        return $user->id === $task->goal->user_id;
    }
}
