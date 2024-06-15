<?php

namespace App\Models\backend;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'alias', 'type', 'active', 'description', 'active', 'layout', 'report_id', 'access', 'user_list', 'group_list', 'user_id'];
    // protected $casts = [
    //     'user_list' => 'array',
    //     'group_list' => 'array',
    // ];

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}