<?php

namespace App\Models\backend;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'alias', 'type', 'active', 'description', 'active', 'layout', 'report_id', 'access', 'user_list', 'group_list', 'user_id'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}