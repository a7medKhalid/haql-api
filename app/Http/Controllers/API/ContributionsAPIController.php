<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ContributionController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ContributionsAPIController extends Controller
{
    public function createContribution(Request $request){

        $request->validate([
            'project_id' => 'required|integer',
            'title' => 'required|string',
            'description' => 'required|string',
            'link' => 'required|string',

        ]);

        $user = Auth::user();

        $contributions_controller = new ContributionController;

        $contribution = $contributions_controller->create($user, $request->project_id, $request->title, $request->description, $request->link);


        return response()->json($contribution);

    }

    public function updateContributionStatus(Request $request){

        $request->validate([
            'contribution_id' => 'required|integer',
            'status' => ['required', Rule::in(['open', 'accepted', 'rejected', 'archived'])],
        ]);

        $user = Auth::user();

        $contributions_controller = new ContributionController;
        $contribution = $contributions_controller->update($user, $request->contribution_id, $request->status);

        return response()->json($contribution);

    }

    public function deleteContribution(Request $request){

        $request->validate([
            'contribution_id' => 'required|integer',
        ]);

        $user = Auth::user();

        $contributions_controller = new ContributionController;

        $contribution = $contributions_controller->delete($user, $request->contribution_id);

        return response()->json($contribution);

    }

    public function getContribution(Request $request){

        $request->validate([
            'contribution_id' => 'required|integer',
        ]);

        $user = Auth::user();

        $contributions_controller = new ContributionController;

        $contribution = $contributions_controller->getContribution($request->contribution_id);

        return response()->json($contribution);

    }
}
