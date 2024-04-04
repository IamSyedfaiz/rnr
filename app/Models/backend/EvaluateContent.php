<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluateContent extends Model
{
    use HasFactory;
    protected $fillable = ['application_id', 'Workflow_id', 'task_id', 'name', 'description', 'active', 'alias', 'type', 'advanced_operator_logic'];
    public function evaluateRules()
    {
        return $this->hasMany(EvaluateRule::class, 'evaluate_content_id');
    }
}
