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

        $staffUser = \App\Models\User::updateOrCreate(
            ['email' => 'staff@dinetrack.com'],
            [
                'role_id' => CustomConstants::ROLE_STAFF,
                'status_id' => CustomConstants::STATUS_ACTIVE,
                'name' => 'Staff User',
                'password' => Hash::make('password'),
            ]
        );

        \App\Models\Staff::updateOrCreate(
            ['user_id' => $staffUser->id],
            [
                'business_id' => 1,
                'name' => $staffUser->name,
                'email' => $staffUser->email,
                'status_id' => CustomConstants::STATUS_ACTIVE,
                'created_by' => 1,
                'updated_by' => 1,
            ]
        );
    }
}
