<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TriggerMail extends Model
{
    use HasFactory;
    protected $fillable = ['notification_id', 'Workflow_id', 'application_id', 'task_id'];
}
