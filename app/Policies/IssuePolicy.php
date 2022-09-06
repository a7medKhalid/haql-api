<?php

namespace App\Policies;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IssuePolicy
{
    use HandlesAuthorization;

    public function update(User $user, Issue $issue)
    {
        return $user->id === $issue->project->owner_id;
    }
}
