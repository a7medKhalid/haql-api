<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    use HasFactory;

    protected $guarded = [];

    //many to many relationship with users
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_has_specialties');
    }

    //many to many relationship with tasks
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_has_specialties');
    }
}
