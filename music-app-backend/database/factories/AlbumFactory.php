<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Album>
 */
class AlbumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3), // Generates a random album title
            'artist' => $this->faker->name(), // Generates a random artist name
            'cover_image' => 'default_cover.jpg', // Placeholder image
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
