<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PrivateFile;
use App\Models\PrivateFileAttachment;
use Illuminate\Support\Facades\Storage;

class PrivateFileAttachmentSeeder extends Seeder
{
    public function run()
    {
        $files = PrivateFile::all();

        foreach ($files as $file) {

            // Crear archivo falso
            $fakePath = "private/files/demo_{$file->id}.txt";
            Storage::disk('local')->put($fakePath, "Contenido de prueba para el documento {$file->id}");

            PrivateFileAttachment::create([
                'private_file_id' => $file->id,
                'file_path' => $fakePath,
                'original_filename' => "archivo_demo_{$file->id}.txt",
                'mime_type' => 'text/plain',
                'size' => Storage::disk('local')->size($fakePath),
            ]);
        }
    }
}
