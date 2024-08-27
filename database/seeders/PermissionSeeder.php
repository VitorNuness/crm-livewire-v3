<?php

namespace Database\Seeders;

use App\Enums\ECan;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        Permission::query()->create([
            'key' => ECan::BE_AN_ADMIN,
        ]);
    }
}
