<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyLog extends Model
{
    use HasFactory;
    protected $fillable = ['workflow_id', 'name'];
}
