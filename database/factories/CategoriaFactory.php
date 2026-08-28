<?php

namespace Database\Factories;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Categoria>
 */
class CategoriaFactory extends Factory
{
    protected $model = Categoria::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categorias = [
            ['nome' => 'Alimentação', 'cor' => '#10b981', 'tipo' => 'Variável'],
            ['nome' => 'Moradia', 'cor' => '#3b82f6', 'tipo' => 'Fixa'],
            ['nome' => 'Transporte', 'cor' => '#f59e0b', 'tipo' => 'Variável'],
            ['nome' => 'Saúde', 'cor' => '#ef4444', 'tipo' => 'Fixa'],
            ['nome' => 'Educação', 'cor' => '#8b5cf6', 'tipo' => 'Fixa'],
            ['nome' => 'Lazer', 'cor' => '#ec4899', 'tipo' => 'Variável'],
            ['nome' => 'Investimentos', 'cor' => '#06b6d4', 'tipo' => 'Variável'],
        ];

        $item = fake()->randomElement($categorias);

        return [
            'nome_categoria' => $item['nome'] . ' ' . fake()->unique()->numberBetween(1, 999),
            'tipo_despesa' => $item['tipo'],
            'limite_orcamento' => fake()->randomFloat(2, 200, 3000),
            'cor_identificacao' => $item['cor'],
        ];
    }
}
