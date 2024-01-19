<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\backend\Role;
use Illuminate\Support\Facades\Schema;

class Application extends Model
{
    use HasFactory;
    protected $fillable = ['fields', 'user_id', 'name', 'access', 'groups', 'status', 'updated_by', 'description', 'attachments'];

    protected $guarded = [];

    public function username1()
    {
        return $this->hasOne(User::class);
    }

    public function rolestable()
    {
        return $this->hasMany(Role::class);
    }
    public static function getTableColumns()
    {
        return Schema::getColumnListing((new self())->getTable());
    }
}
