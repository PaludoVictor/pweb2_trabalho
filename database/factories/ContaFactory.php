<?php

namespace Database\Factories;

use App\Models\Conta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Conta>
 */
class ContaFactory extends Factory
{
    protected $model = Conta::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bancos = ['Banco do Brasil', 'Caixa Econômica', 'Itaú Unibanco', 'Bradesco', 'Santander', 'Nubank', 'Inter', 'Sicredi'];

        return [
            'nome_instituicao' => fake()->randomElement($bancos),
            'agencia_numero' => (string) fake()->numberBetween(1000, 9999),
            'numero_conta' => fake()->unique()->numerify('#####-#'),
            'saldo_atual' => fake()->randomFloat(2, 500, 25000),
        ];
    }
}
