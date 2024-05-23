<?php

namespace App\Models;

use App\Models\backend\Group;
use App\Models\backend\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use the42coders\Workflows\Triggers\WorkflowObservable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, WorkflowObservable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $guarded = [];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function applications()
    {
        return $this->roles()->join('role_permission', 'roles.id', '=', 'role_permission.role_id')->join('applications', 'role_permission.application_id', '=', 'applications.id')->select('applications.*')->distinct();
    }
}