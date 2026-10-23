<?php

namespace Database\Factories;

use App\Models\Conta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cartao>
 */
class CartaoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $contaId = Conta::factory();

        return [
            'nome_cartao' => fake()->randomElement([
                'Cartão Platinum Principal',
                'Cartão Black Cashback',
                'Cartão Gold Internacional',
                'Cartão Universitário',
                'Cartão Virtual Compras Online'
            ]),
            'bandeira' => fake()->randomElement(['Mastercard', 'Visa', 'Elo', 'American Express']),
            'limite_credito' => fake()->randomFloat(2, 1000, 15000),
            'dia_vencimento' => fake()->randomElement([5, 10, 15, 20, 25]),
            'imagem' => null,
            'conta_id' => $contaId,
        ];
    }
}

