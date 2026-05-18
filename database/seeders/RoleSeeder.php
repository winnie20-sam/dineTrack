<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['id' => 1, 'code' => 'RL001', 'name' => 'Admin'],
            ['id' => 2, 'code' => 'RL002', 'name' => 'Owner'],
            ['id' => 3, 'code' => 'RL003', 'name' => 'Manager'],
            ['id' => 4, 'code' => 'RL004', 'name' => 'Staff'],
        ];

        foreach ($data as $role) {
            \App\Models\Role::updateOrCreate(
                ['id' => $role['id']],
                ['code' => $role['code'], 'name' => $role['name']]
            );
        }
    }
}
