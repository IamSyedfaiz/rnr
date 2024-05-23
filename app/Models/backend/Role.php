<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\backend\Application;
use App\Models\User;

class Role extends Model
{
    use HasFactory;
    protected $guarded = [];
    // protected $casts = [
    //     'user_list' => 'json',
    //     'group_list' => 'json',
    // ];

    protected $fillable = ['name', 'user_list', 'group_list', 'user_id'];
    public function role_applicationname()
    {
        return $this->hasOne(Application::class, 'id', 'application_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission')->withPivot('application_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function applications()
    {
        return $this->belongsToMany(Application::class, 'role_permission')
            ->withPivot('permission_id')
            ->withTimestamps();
    }
}