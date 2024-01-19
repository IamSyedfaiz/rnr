<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = ['name', 'description', 'type', 'active', 'subject', 'body', 'user_cc', 'recurring', 'scheduled_time', 'scheduled_day', 'selected_week_day', 'group_list', 'user_list', 'advanced_operator_logic', 'updated_by', 'application_id', 'user_id'];
    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }
    public function filterCriterias()
    {
        return $this->hasMany(FilterCriteria::class);
    }
}
