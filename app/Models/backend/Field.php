<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;
    protected $guarded = [];

    public static function getExpectedColumnsCount($applicationId)
    {
        return self::where('application_id', $applicationId)
            ->where('status', 1)
            ->count();
    }
}
