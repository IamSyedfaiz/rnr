<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluateRule extends Model
{
    use HasFactory;
    protected $fillable = ['evaluate_content_id', 'field_id', 'filter_operator', 'filter_value'];
    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id');
    }
}
