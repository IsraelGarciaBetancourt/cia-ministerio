<?php

namespace Database\Seeders;

use App\Models\PrivateFile;
use Illuminate\Database\Seeder;

class PrivateFileSeeder extends Seeder
{
    public function run()
    {
        PrivateFile::create([
            'file_name' => 'documento-demo.pdf',
            'file_path' => 'private/files/example.pdf',
            'mime_type' => 'application/pdf',
            'uploaded_by' => 1,
        ]);
    }
}
