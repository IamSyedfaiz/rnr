<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrlExportImport extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'excel_file', 'file_name', 'start_time', 'start_day', 'column_mappings', 'key_field', 'recurring', 'scheduled_time', 'scheduled_day', 'selected_week_day', 'application_id', 'user_id'];
}
