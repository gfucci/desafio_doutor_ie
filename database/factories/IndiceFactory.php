<?php

namespace Database\Factories;

use App\Models\Livro;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Indice>
 */
class IndiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'livro_id' => Livro::factory(),
            'indice_pai_id' => null,
            'titulo' => $this->faker->name(),
            'pagina' => $this->faker->randomDigitNot(0)
        ];
    }
}
