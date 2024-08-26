<?php

namespace Database\Seeders;

use App\Enums\ECan;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()
            ->withPermission(ECan::BE_AN_ADMIN)
            ->create([
                'name'  => 'CRM Admin',
                'email' => 'admin@crm.com',
            ]);
    }
}
