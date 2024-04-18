<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpdateContent extends Model
{
    use HasFactory;
    protected $fillable = ['application_id', 'workflow_id', 'task_id', 'name', 'data'];
    public function application()
    {
        return $this->belongsTo('App\Models\Application');
    }

    public function workflow()
    {
        return $this->belongsTo('App\Models\Workflow');
    }

    public function task()
    {
        return $this->belongsTo('App\Models\Task');
    }
}
