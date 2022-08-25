<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function issues()
    {
        return $this->hasMany(Issue::class, 'project_id');
    }

    public function goals()
    {
        return $this->hasMany(Goal::class, 'project_id');
    }

    public function contributions()
    {
        return $this->hasMany(Contribution::class, 'project_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'commented_id')->where('commented_type', 'project');
    }
}
