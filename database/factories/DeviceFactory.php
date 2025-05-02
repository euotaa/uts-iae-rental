<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Device>
 */
class DeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word() . ' ' . $this->faker->randomElement(['Pro', 'Max', 'Mini', 'Lite']),
            'price' => $this->faker->numberBetween(1000000, 30000000),
            'type' => $this->faker->randomElement(['Iphone', 'Digital Camera', 'Analog Camera', 'MacBook']),
            'duration' => $this->faker->numberBetween(1, 30), // diasumsikan durasi dalam hari
        ];
    }
}
