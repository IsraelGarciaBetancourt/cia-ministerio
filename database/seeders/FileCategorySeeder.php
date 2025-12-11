<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FileCategory;
use App\Models\FileGroup;

class FileCategorySeeder extends Seeder
{
    public function run()
    {
        $certificados = FileGroup::where('name', 'Certificados')->first();
        $matrimonio   = FileGroup::where('name', 'Actas de Matrimonio')->first();
        $personales   = FileGroup::where('name', 'Documentos Personales')->first();

        FileCategory::insert([
            [
                'file_group_id' => $certificados->id,
                'name' => 'Certificados de Bautizo',
                'description' => 'Documentos de bautizo de miembros.',
            ],
            [
                'file_group_id' => $certificados->id,
                'name' => 'Certificados de Clase',
                'description' => 'Certificados obtenidos por cursos internos.',
            ],
            [
                'file_group_id' => $matrimonio->id,
                'name' => 'Actas 2024',
                'description' => 'Actas emitidas durante el año 2024.',
            ],
            [
                'file_group_id' => $personales->id,
                'name' => 'Documentación de Identidad',
                'description' => 'Documentos privados y confidenciales.',
            ],
        ]);
    }
}
