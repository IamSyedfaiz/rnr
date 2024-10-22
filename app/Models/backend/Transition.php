<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use the42coders\Workflows\Tasks\Task;

class Transition extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'application_id', 'workflow_id', 'task_id', 'condition', 'child_id', 'parent_id'];
    public function parentTask()
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    // Define relationship for child task (child_id)
    public function childTask()
    {
        return $this->belongsTo(Task::class, 'child_id');
    }
}