<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission')->withPivot('application_id');
    }
    public function applications()
    {
        return $this->belongsToMany(Application::class, 'role_permission', 'permission_id', 'application_id')->withPivot('role_id'); // Assuming role_id is a pivot column
    }
}