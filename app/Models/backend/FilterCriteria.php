<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilterCriteria extends Model
{
    use HasFactory;
    protected $fillable = ['notification_id', 'field_id', 'filter_operator', 'filter_value'];

    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id');
    }
    public function notification()
    {
        return $this->belongsTo(Notification::class, 'notification_id');
    }
}
