<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomWorkflow extends Model
{
    use HasFactory;
    protected $table = 'custom_workflows';

    protected $fillable = ['name', 'application_id'];

    protected $guarded = [];
    public function __construct(array $attributes = [])
    {
        $this->table = config('workflows.db_prefix') . $this->table;
        parent::__construct($attributes);
    }

    public function tasks()
    {
        return $this->hasMany('the42coders\Workflows\Tasks\Task');
    }

    public function triggers()
    {
        return $this->hasMany('the42coders\Workflows\Triggers\Trigger');
    }

    public function logs()
    {
        return $this->hasMany('the42coders\Workflows\Loggers\WorkflowLog');
    }

    public function getTriggerByClass($class)
    {
        return $this->triggers()->where('type', $class)->first();
    }
}
