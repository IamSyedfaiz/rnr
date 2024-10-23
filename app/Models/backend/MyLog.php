<?php

namespace App\Models\backend;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyLog extends Model
{
    use HasFactory;
    protected $fillable = ['workflow_id', 'name', 'user_id', 'number'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}