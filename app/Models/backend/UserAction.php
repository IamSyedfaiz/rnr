<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAction extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'task_id'];
}
