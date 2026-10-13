<?php

namespace Database\Factories;

use App\Models\Conta;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meta>
 */
class MetaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $contaId = Conta::inRandomOrder()->first()?->id ?? Conta::factory();
        $categoriaId = Categoria::inRandomOrder()->first()?->id ?? Categoria::factory();
        $valorMeta = fake()->randomFloat(2, 500, 20000);
        $valorAtual = fake()->randomFloat(2, 0, $valorMeta);

        return [
            'titulo' => fake()->randomElement([
                'Reserva de Emergência',
                'Comprar Novo Notebook',
                'Viagem de Férias',
                'Trocar de Carro',
                'Curso de Especialização',
                'Reforma do Quarto',
                'Fundo de Aposentadoria'
            ]),
            'valor_meta' => $valorMeta,
            'valor_atual' => $valorAtual,
            'data_limite' => fake()->dateTimeBetween('now', '+2 years')->format('Y-m-d'),
            'imagem' => null,
            'conta_id' => $contaId,
            'categoria_id' => $categoriaId,
        ];
    }
}

