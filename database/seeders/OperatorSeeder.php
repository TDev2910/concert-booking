<?php

namespace Database\Seeders;

use App\Models\Operator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OperatorSeeder extends Seeder
{
    public function run(): void
    {
        Operator::create([
            'name' => 'Admin System',
            'email' => 'admin@concert.com',
            'password_hash' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }
}
