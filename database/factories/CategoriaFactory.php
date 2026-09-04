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
            ['nome' => 'Alimentação & Supermercado', 'cor' => '#10b981', 'tipo' => 'Variável'],
            ['nome' => 'Moradia & Contas', 'cor' => '#3b82f6', 'tipo' => 'Fixa'],
            ['nome' => 'Transporte & Mobilidade', 'cor' => '#f59e0b', 'tipo' => 'Variável'],
            ['nome' => 'Saúde & Cuidados', 'cor' => '#ef4444', 'tipo' => 'Fixa'],
            ['nome' => 'Educação & Cursos', 'cor' => '#8b5cf6', 'tipo' => 'Fixa'],
            ['nome' => 'Lazer & Entretenimento', 'cor' => '#ec4899', 'tipo' => 'Variável'],
            ['nome' => 'Investimentos & Reserva', 'cor' => '#06b6d4', 'tipo' => 'Variável'],
            ['nome' => 'Vestuário & Compras', 'cor' => '#84cc16', 'tipo' => 'Variável'],
            ['nome' => 'Assinaturas & Serviços', 'cor' => '#6366f1', 'tipo' => 'Fixa'],
        ];

        $item = fake()->randomElement($categorias);

        return [
            'nome_categoria' => $item['nome'],
            'tipo_despesa' => $item['tipo'],
            'limite_orcamento' => fake()->randomFloat(2, 300, 3000),
            'cor_identificacao' => $item['cor'],
        ];
    }
}
