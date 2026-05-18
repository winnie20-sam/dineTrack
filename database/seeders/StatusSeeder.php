<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['id' => 1,  'code' => 'ST001', 'name' => 'Active'],
            ['id' => 2,  'code' => 'ST002', 'name' => 'Inactive'],
            ['id' => 3,  'code' => 'ST003', 'name' => 'Deleted'],
        ];

        foreach ($data as $status) {
            \App\Models\Status::updateOrCreate(
                ['id' => $status['id']],
                ['code' => $status['code'], 'name' => $status['name']]
            );
        }
    }
}
