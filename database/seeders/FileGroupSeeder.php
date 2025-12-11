<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FileGroup;

class FileGroupSeeder extends Seeder
{
    public function run()
    {
        FileGroup::insert([
            [
                'name' => 'Certificados',
                'description' => 'Certificados oficiales emitidos por la organización.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Actas de Matrimonio',
                'description' => 'Documentación legal de matrimonios registrados.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Documentos Personales',
                'description' => 'Archivos privados de miembros.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
