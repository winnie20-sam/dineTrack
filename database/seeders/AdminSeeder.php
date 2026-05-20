<?php

namespace Database\Seeders;

use App\Models\Shared\CustomConstants;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = \App\Models\User::updateOrCreate(
            ['email' => 'admin@dinetrack.com'],
            [
                'role_id' => CustomConstants::ROLE_ADMIN,
                'status_id' => CustomConstants::STATUS_ACTIVE,
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

    }
}
