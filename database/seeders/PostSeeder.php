<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run()
    {
        Post::factory()->count(5)->create([
            'created_by' => 1 // usuario admin
        ]);
    }
}
