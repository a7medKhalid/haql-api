<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ContributionController extends Controller
{
    public function create(User $user, Project $project, $title, $description, $link, $license, $license_url)
    {
        $contribution = Contribution::create([
            'title' => $title,
            'description' => $description,
            'link' => $link,
            'license' => $license,
            'license_url' => $license_url,
        ]);

        $project->contributions()->save($contribution);
        $user->contributions()->save($contribution);

        return $contribution;
    }

    public function delete(User $user, Contribution $contribution)
    {

        if ($user->cannot('delete', $contribution)) {
            abort(403);
        }

        $contribution->delete();
    }

}
