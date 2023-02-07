<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Temperature>
 */
class TemperatureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'value' => fake()->numberBetween($int1 = 5, $int2 = 70),
            'device_id' => fake()->numberBetween($int1 = 1, $int2 = 4),
            'created_at' => fake()->dateTime(),
        ];
    }
}
