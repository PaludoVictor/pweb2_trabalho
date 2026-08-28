<?php

namespace Database\Factories;

use App\Models\Transacao;
use App\Models\Conta;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transacao>
 */
class TransacaoFactory extends Factory
{
    protected $model = Transacao::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $descricoes = [
            'Supermercado Mensal', 'Aluguel do Imóvel', 'Conta de Energia Elétrica',
            'Abastecimento Posto de Gasolina', 'Assinatura Streaming', 'Farmácia e Remédios',
            'Jantar em Restaurante', 'Livros e Cursos', 'Internet Fibra', 'Manutenção Automotiva'
        ];

        $metodos = ['Pix', 'Cartão de Crédito', 'Cartão de Débito', 'Dinheiro', 'Boleto'];

        return [
            'descricao_movimento' => fake()->randomElement($descricoes),
            'valor_transacao' => fake()->randomFloat(2, 15, 1200),
            'data_competencia' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'metodo_pagamento' => fake()->randomElement($metodos),
            'conta_id' => Conta::factory(),
            'categoria_id' => Categoria::factory(),
        ];
    }
}
