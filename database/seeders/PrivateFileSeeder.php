<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FileCategory;
use App\Models\PrivateFile;
use App\Models\User;

class PrivateFileSeeder extends Seeder
{
    public function run()
    {
        $user = User::first(); // Usuario admin

        $categories = FileCategory::all();

        foreach ($categories as $category) {
            PrivateFile::create([
                'file_category_id' => $category->id,
                'title'            => "Documento de ejemplo: {$category->name}",
                'description'      => "Documento generado como muestra para la categoría {$category->name}.",
                'uploaded_by'      => $user->id,
            ]);
        }
    }
}
