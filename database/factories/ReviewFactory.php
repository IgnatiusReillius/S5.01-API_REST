<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => User::factory(),
            'id_book' => Book::factory(),
            'add_date' => fake()->date(),
            'read_date' => fake()->optional()->date(),
            'comment' => fake()->optional()->paragraph(),
            'rating' => fake()->numberBetween(1, 5),
        ];
    }
}
