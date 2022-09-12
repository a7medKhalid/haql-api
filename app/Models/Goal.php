<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

    //project relationship
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    //tasks relationship
    public function tasks()
    {
        return $this->hasMany(Task::class, 'goal_id');
    }
}
