<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'bio'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    //many to many relationship with specialties
    public function specialties()
    {
        return $this->belongsToMany(Specialty::class, 'user_has_specialties');
    }

    //has many relationship with projects
    public function projects()
    {
        return $this->hasMany(Project::class, 'owner_id');
    }

    //has many relationship with contributions
    public function contributions()
    {
        return $this->hasMany(Contribution::class, 'contributor_id');
    }

    //has many relationship with issues
    public function issues()
    {
        return $this->hasMany(Issue::class, 'user_id');
    }




}
