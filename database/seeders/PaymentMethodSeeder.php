<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['id' => 1, 'code' => 'PM001', 'name' => 'Cash'],
            ['id' => 2, 'code' => 'PM002', 'name' => 'Card'],
            ['id' => 3, 'code' => 'PM003', 'name' => 'Mpesa'],
            ['id' => 4, 'code' => 'PM004', 'name' => 'Bank Transfer'],
        ];

        foreach ($data as $method) {
            \App\Models\PaymentMethod::firstOrCreate(
                ['code' => $method['code']],
                ['name' => $method['name']]
            );
        }
    }
}
