<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stelle>
 */
class StelleFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kennzeichen' => $this->faker->unique()->randomElement(['leitender_mitarbeiter', 'abteilungsleiter', 'teamleiter']),
            'bezeichnung' => $this->faker->jobTitle,
            'ebene' => $this->faker->numberBetween(1, 5),
            'uebergeordnet' => null,
            'fuehrungskompetenz' => $this->faker->boolean(),
            'l' => null,
            'r' => null,
        ];
    }
}
