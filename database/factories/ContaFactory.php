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

    private const CONTAS_INICIAIS = [
        [
            'nome_instituicao' => 'Banco do Brasil',
            'agencia_numero' => '1234',
            'numero_conta' => '5678-9',
            'saldo_atual' => 4500.00,
        ],
        [
            'nome_instituicao' => 'Itaú Unibanco',
            'agencia_numero' => '0002',
            'numero_conta' => '123123-1',
            'saldo_atual' => 8200.50,
        ],
        [
            'nome_instituicao' => 'Nubank',
            'agencia_numero' => '0001',
            'numero_conta' => '987654-3',
            'saldo_atual' => 3150.75,
        ],
        [
            'nome_instituicao' => 'Inter',
            'agencia_numero' => '0001',
            'numero_conta' => '554433-2',
            'saldo_atual' => 12300.00,
        ],
    ];

    public function comContasIniciais(): static
    {
        return $this->forEachSequence(...self::CONTAS_INICIAIS);
    }

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
