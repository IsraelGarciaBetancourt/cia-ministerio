<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Crear usuario administrador si no existe
        $admin = User::firstOrCreate(
            ['email' => 'admin@cia.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('12345678'),
            ]
        );

        // Asegurar que el rol "admin" existe
        Role::firstOrCreate(['name' => 'admin']);

        // Asignar rol admin al usuario
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }
    }
}
