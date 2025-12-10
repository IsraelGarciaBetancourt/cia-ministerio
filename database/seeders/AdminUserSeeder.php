<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::firstOrCreate(
            ['email' => 'admin@cia.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('12345678'),
            ]
        );
    }
}
