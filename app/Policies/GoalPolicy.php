<?php

namespace App\Policies;

use App\Models\Goal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GoalPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Goal $goal)
    {
        return $user->id === $goal->project->owner_id;
    }

    public function delete(User $user, Goal $goal)
    {
        return $user->id === $goal->project->owner_id;
    }
}
