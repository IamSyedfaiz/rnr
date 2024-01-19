<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\backend\Application;

class Role extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function role_applicationname()
    {
        return $this->hasOne(Application::class, 'id', 'application_id');
    }
}
