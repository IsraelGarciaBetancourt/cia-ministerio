<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\Post;
use Illuminate\Database\Seeder;

class MediaSeeder extends Seeder
{
    public function run()
    {
        $posts = Post::all();

        foreach ($posts as $post) {

            // Imagen simulada
            Media::create([
                'post_id' => $post->id,
                'type' => 'image',
                'file_path' => 'media/example.webp',
                'original_filename' => 'example.webp',
                'mime_type' => 'image/webp',
                'is_external' => false,
            ]);

            // Video externo de YouTube
            Media::create([
                'post_id' => $post->id,
                'type' => 'video',
                'is_external' => true,
                'external_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            ]);
        }
    }
}
