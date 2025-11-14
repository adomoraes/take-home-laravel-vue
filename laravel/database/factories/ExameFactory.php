<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Exame;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exame>
 */
class ExameFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Exame::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'laterality' => $this->faker->randomElement(['OD', 'OE', 'AO']),
            'comment' => $this->faker->sentence(),
            'group' => $this->faker->randomElement(['Individual', 'Grupo 1', 'Grupo 2', 'Grupo 3', 'Grupo 4', 'Grupo 5']),
        ];
    }
}
