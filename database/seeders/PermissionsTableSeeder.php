<?php

namespace Database\Seeders;

use App\Models\backend\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ['name' => 'import'],
            ['name' => 'create'],
            ['name' => 'read'],
            ['name' => 'update'],
            ['name' => 'delete'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}