<?php

namespace Database\Factories;

use App\Enums\GameSessionStateEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameSession>
 */
class GameSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'memo_test_id' => fake()->numberBetween(1, 2),
            'state' => GameSessionStateEnum::STARTED,
            'retries' => 0,
            'number_of_pairs' => 0,
        ];
    }
}
