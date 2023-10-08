<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => 'بلاگ - '.$this->faker->numerify(),
            'blog_category_id' => $this->faker->randomElement(
                [
                    $this->faker->numberBetween(1, 10),
                    null,
                ]),
            'body' => $this->faker->paragraph,
        ];
    }
}
