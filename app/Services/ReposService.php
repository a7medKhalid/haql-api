<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReposService
{

    private $directory;
    private $full_path;

    public function __construct($directory)
    {
        $this->directory = $directory;
        $this->full_path = Storage::path($this->directory);
        Log::write("debug", $this->full_path);

        //add this to repo

    }

    public function create(){
        //create dir
        Storage::makeDirectory($this->directory);

        $full_path = Storage::path($this->directory);

        //git init
        $command = "cd " . escapeshellarg($full_path) . " && git init";
        $output = shell_exec($command);
        Log::write("debug", $output);
        Log::write("debug", $command);

        //add safe directory
        $command = "git config --global --add safe.directory " . escapeshellarg($full_path);
        $output = shell_exec($command);
        Log::write("debug", $output);
        Log::write("debug", $command);

        //create main branch
        $command = "cd " . escapeshellarg($full_path) . " && git checkout -b master";
        $output = shell_exec($command);
        Log::write("debug", $output);
        Log::write("debug", $command);

        //add first commit
        $command = "cd " . escapeshellarg($full_path) . " && echo 'initial' > initial.txt && git add . && git commit -m 'first commit'";
        $output = shell_exec($command);
        Log::write("debug", $output);
        Log::write("debug", $command);

        return $output;

    }

    public function uploadFiles($commit_name, $files)
    {
        //TODO: block all other operations while this is running
        //create branch
        shell_exec("cd $this->full_path && git checkout -b $commit_name");
        Log::write("debug", "cd $this->full_path && git checkout -b $commit_name");


        //delete all files in branch
        shell_exec("cd $this->full_path && git rm -rf .");
        Log::write("debug", "cd $this->full_path && git rm -rf .");

        //upload files to branch

        foreach ($files as $file) {
            $filename = $file->getClientOriginalName();
            Storage::putFileAs($this->directory,$file, $filename );
            Log::write("debug", "$filename");
        }

        //commit
        shell_exec("cd $this->full_path && git add . && git commit -m '$commit_name'");
        Log::write("debug", "cd $this->full_path && git add . && git commit -m '$commit_name'");

        //return to master
        shell_exec("cd $this->full_path && git checkout master");
    }

    public function merge($branch_name){
        //merge branch to master
        shell_exec("cd $this->full_path && git merge $branch_name");
        Log::write("debug", "cd $this->full_path && git merge $branch_name");

        //later return if conflict

    }


}
