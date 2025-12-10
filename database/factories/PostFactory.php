<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => fake()->sentence(5),
            'content' => fake()->paragraph(5),
            'post_date' => fake()->date(),
            'cover_image' => null,
        ];
    }
}
