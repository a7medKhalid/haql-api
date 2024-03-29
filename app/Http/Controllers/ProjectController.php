<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\ReposService;

class ProjectController extends Controller
{
    public function getProjects()
    {
        $projects = Project::latest()->paginate(10)->through(function ($project) {
            $contributionsCount = $project->contributions()->count();
            $issuesCount = $project->issues()->count();

            $project->contributionsCount = $contributionsCount;
            $project->issuesCount = $issuesCount;
            $project->ownerUsername = $project->owner->username;

            return $project;
        });

        return $projects;
    }

    public function getPersonalProjects($user)
    {
        $projects = $user->projects()->paginate(10)->through(function ($project) {
            $contributionsCount = $project->contributions()->count();
            $issuesCount = $project->issues()->count();

            $project->contributionsCount = $contributionsCount;
            $project->issuesCount = $issuesCount;

            return $project;
        });

        return $projects;
    }

    public function getTrendingProjects()
    {

        //get all projects sorted by child contributions count
        $projects = Project::withCount('contributions')->orderBy('contributions_count', 'desc')->paginate(10)->through(function ($project) {
            $contributionsCount = $project->contributions()->count();
            $issuesCount = $project->issues()->count();

            $project->contributionsCount = $contributionsCount;
            $project->issuesCount = $issuesCount;
            $project->ownerUsername = $project->owner->username;

            return $project;
        });

        return $projects;
    }

//    public function getRelatedProjects($user){
//        //get projects where tasks
//        $projects = $user->projects()->paginate(10);
//        return $projects;
//    }

    public function getProject($project_id)
    {
        $project = Project::find($project_id);

        return $project;
    }

    public function getProjectGoals($project_id)
    {
        $project = Project::find($project_id);
        //Todo: use through without pagination
        $goals = $project->goals()->paginate(100)->through(function ($goal) {
            $goal->tasks = $goal->tasks()->get();

            return $goal;
        });

        return $goals;
    }

    public function getProjectIssues($project_id)
    {
        $project = Project::find($project_id);
        $issues = $project->issues()->paginate(10)->through(function ($issue) {
            $issue->issuerName = $issue->user->name;

            return $issue;
        });

        return $issues;
    }

    public function getProjectTrendingIssues($project_id)
    {
        $project = Project::find($project_id);
        $issues = $project->issues()->withCount('comments')->orderBy('comments_count', 'desc')->paginate(10)->through(function ($issue) {
            $issue->issuerName = $issue->user->name;

            return $issue;
        });

        return $issues;
    }

    public function getProjectContributions($project_id, $status = null)
    {
        $project = Project::find($project_id);

        if ($status == null) {
            $contributions = $project->contributions()->paginate(10)->through(function ($contribution) {
                $contribution->contributorName = $contribution->contributor->name;

                return $contribution;
            });
        } else {
            $contributions = $project->contributions()->where('status', $status)->paginate(10)->through(function ($contribution) {
                $contribution->contributorName = $contribution->contributor->name;

                return $contribution;
            });
        }

        return $contributions;
    }

    public function getProjectComments($project_id)
    {
        $project = Project::find($project_id);
        $project->comments = $project->comments()->paginate(10)->through(function ($comment) {
            $comment->commenterName = $comment->user->name;
            $comment->commenterUsername = $comment->user->username;

            $comment->replysCount = $comment->comments()->count();

            return $comment;
        });

        return $project;
    }

    public function getProjectContributors($project_id)
    {
        $project = Project::find($project_id);

        //use distinct to remove duplicates
        $contributors = $project->contributions()->distinct()->paginate(10)->through(function ($contribution) {
            $contribution->contributorName = $contribution->contributor->name;
            $contribution->contributorUsername = $contribution->contributor->username;

            return $contribution;
        });
        $contributors = $project->contributors()->distinct()->paginate(10)->through(function ($contributor) use ($project_id) {

            $contributor->contributionsCount = $contributor->contributions()->where('project_id', $project_id )->count();

            return $contributor;
        });

        return $contributors;
    }

    public function create($user, $name, $description)
    {
        $project = Project::create([
            'name' => $name,
            'description' => $description,
        ]);

        $user->projects()->save($project);

        $project->directory = $project->id;

        $project->save();

        $ReposService = new ReposService($project->id);

        $ReposService->create();

        return $project;
    }

    public function update($user, $project_id, $name = null, $description = null)
    {
        $project = Project::find($project_id);

        if ($user->cannot('update', $project)) {
            abort(403, 'You are not authorized to perform this action.');
        }

        $project->update([
            'name' => $name,
            'description' => $description,
        ]);

        return $project;
    }

    public function delete($user, $project_id)
    {
        $project = Project::find($project_id);

        if ($user->cannot('delete', $project)) {
            abort(403, 'You are not authorized to perform this action.');
        }

        //if project has contributions, abort

        if ($project->contributions()->count() > 0) {
            abort(403, 'You cannot delete a project that has contributions.');
        }

        $project->delete();

        return $project;
    }

    public function getFiles($project_id)
    {
        $project = Project::find($project_id);

        $ReposService = new ReposService($project->directory);

        $filesPath = $ReposService->download('master');

        return $filesPath;

    }
}
