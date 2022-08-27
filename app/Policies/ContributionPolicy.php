<?php

namespace App\Policies;

use App\Models\Contribution;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContributionPolicy
{
    use HandlesAuthorization;


    public function delete(User $user, Contribution $contribution)
    {
        if ($contribution->isAccepted) {
            return false;
        }

        return $user->id === $contribution->contributor_id;
    }

    public function update(User $user, Contribution $contribution)
    {
        return $user->id === $contribution->project->owner_id;
    }

}
