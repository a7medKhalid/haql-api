<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

    protected $guarded = [];

    public function goal()
    {
        return $this->belongsTo(Goal::class, 'goal_id');
    }

    //many to many relationship with specialties
    public function specialties()
    {
        return $this->belongsToMany(Specialty::class, 'task_has_specialties');
    }
}
