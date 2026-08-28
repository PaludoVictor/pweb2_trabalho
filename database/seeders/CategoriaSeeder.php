<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            [
                'nome_categoria' => 'Alimentação',
                'tipo_despesa' => 'Variável',
                'limite_orcamento' => 1200.00,
                'cor_identificacao' => '#10b981'
            ],
            [
                'nome_categoria' => 'Moradia & Contas',
                'tipo_despesa' => 'Fixa',
                'limite_orcamento' => 2000.00,
                'cor_identificacao' => '#3b82f6'
            ],
            [
                'nome_categoria' => 'Transporte',
                'tipo_despesa' => 'Variável',
                'limite_orcamento' => 600.00,
                'cor_identificacao' => '#f59e0b'
            ],
            [
                'nome_categoria' => 'Estudos e Cursos',
                'tipo_despesa' => 'Fixa',
                'limite_orcamento' => 800.00,
                'cor_identificacao' => '#8b5cf6'
            ],
            [
                'nome_categoria' => 'Lazer & Cultura',
                'tipo_despesa' => 'Variável',
                'limite_orcamento' => 500.00,
                'cor_identificacao' => '#ec4899'
            ],
        ];

        foreach ($categorias as $cat) {
            Categoria::firstOrCreate(['nome_categoria' => $cat['nome_categoria']], $cat);
        }
    }
}
