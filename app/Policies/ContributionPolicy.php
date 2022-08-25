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

        return $user->id === $contribution->user_id;
    }

}
