<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mitarbeiter>
 */
class MitarbeiterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'personalnr' => fake()->unique()->buildingNumber(),
            'anrede' => 'Herr',
            'vorname' => fake()->firstName(),
            'name' => fake()->name(),
            'gebdatum' => fake()->dateTimeThisMonth(),
            'stelle' => 1,
            'kennwort' => fake()->password(),
            'anstellung' => 1,
            'besoldung' => 'A10',
            'berechtigung' => 'guest',
            'abgabedatum' => fake()->dateTimeThisYear(),
        ];
    }
}
