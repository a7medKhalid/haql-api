<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $guarded = [];

    //comment belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //comment has many comments
    public function comments()
    {
        return $this->hasMany(Comment::class, 'commented_id')->where('commentedType', 'comment');
    }





}
