<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // ============================
        // 1. Crear Roles
        // ============================
        $roles = [
            'admin',
            'editor',
            'apostol',
            'estudiante',
            'usuario',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // ============================
        // 2. Crear Permisos base
        // ============================
        $permissions = [
            'posts.create',
            'posts.edit',
            'posts.delete',
            'posts.publish',
            'posts.view',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // ============================
        // 3. Asignar permisos
        // ============================

        // Admin tiene todos
        Role::findByName('admin')->givePermissionTo(Permission::all());

        // Editor puede crear, editar, publicar y ver
        Role::findByName('editor')->givePermissionTo([
            'posts.create',
            'posts.edit',
            'posts.publish',
            'posts.delete',
            'posts.view',
        ]);

        // Apostol → permisos de editor + delete
        Role::findByName('apostol')->givePermissionTo([
            'posts.create',
            'posts.edit',
            'posts.publish',
            'posts.delete',
            'posts.view',
        ]);

        // Estudiante → solo puede ver posts
        Role::findByName('estudiante')->givePermissionTo([
            'posts.view',
        ]);

        // Usuario → también solo ver posts
        Role::findByName('usuario')->givePermissionTo([
            'posts.view',
        ]);
    }
}
