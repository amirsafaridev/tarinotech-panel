<?php

namespace Modules\Content\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Content\app\Models\Blog;

class BlogFactoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Blog::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => 'بلاگ - '.$this->faker->numerify(),
            'blog_category_id' => $this->faker->numberBetween(1, 10),
            'body' => $this->faker->paragraph,
        ];
    }
}
