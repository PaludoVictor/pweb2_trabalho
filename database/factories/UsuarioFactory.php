<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Usuario>
 */
class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => fake()->name(),
            'cpf' => '529.982.247-25',
            'telefone' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'login' => fake()->unique()->userName(),
            'senha' => 'SenhaTeste123',
        ];
    }
}
