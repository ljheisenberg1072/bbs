<?php

namespace Database\Factories;

use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Topic>
 */
class TopicFactory extends Factory
{
    protected $model = Topic::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'body' => fake()->text(),
            'excerpt' => fake()->sentence(),
            'user_id' => fake()->randomElement([1,2,3,4,5,6,7,9,9,10]),
            'category_id' => fake()->randomElement([1,2,3,4]),
        ];
    }
}
