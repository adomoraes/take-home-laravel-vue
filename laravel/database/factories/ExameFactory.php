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
     * O nome do model correspondente.
     *
     * @var string
     */
    protected $model = Exame::class;

    /**
     * Define o estado padrão do model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Pega os valores permitidos dos enums
        $gruposPermitidos = ['Individual', 'Grupo 1', 'Grupo 2', 'Grupo 3', 'Grupo 4', 'Grupo 5'];
        $lateralidadesPermitidas = ['OD', 'OE', 'AO'];

        return [
            'name' => $this->faker->words(3, true) . ' (Teste)',
            'comment' => $this->faker->sentence(),

            // Pega um valor aleatório dos arrays permitidos
            'laterality' => $this->faker->randomElement($lateralidadesPermitidas),
            'group' => $this->faker->randomElement($gruposPermitidos),
        ];
    }
}
