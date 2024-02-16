<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Report extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'permissions', 'application_id', 'user_list', 'group_list', 'dropdowns', 'fieldNames', 'fieldStatisticsNames', 'fieldIds'];
    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
