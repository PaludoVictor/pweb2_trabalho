<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transacao;
use App\Models\Conta;
use App\Models\Categoria;

class TransacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contas = Conta::all();
        $categorias = Categoria::all();

        if ($contas->isEmpty() || $categorias->isEmpty()) {
            return;
        }

        $c1 = $contas->first();
        $c2 = $contas->count() > 1 ? $contas[1] : $c1;

        $catAlim = $categorias->where('nome_categoria', 'Alimentação')->first() ?? $categorias->first();
        $catMora = $categorias->where('nome_categoria', 'Moradia & Contas')->first() ?? $categorias->first();
        $catEstu = $categorias->where('nome_categoria', 'Estudos e Cursos')->first() ?? $categorias->first();
        $catLaze = $categorias->where('nome_categoria', 'Lazer & Cultura')->first() ?? $categorias->first();

        $transacoes = [
            [
                'descricao_movimento' => 'Supermercado Central',
                'valor_transacao' => 450.80,
                'data_competencia' => date('Y-m-d', strtotime('-5 days')),
                'metodo_pagamento' => 'Cartão de Débito',
                'conta_id' => $c1->id,
                'categoria_id' => $catAlim->id,
            ],
            [
                'descricao_movimento' => 'Aluguel Apartamento',
                'valor_transacao' => 1500.00,
                'data_competencia' => date('Y-m-d', strtotime('-10 days')),
                'metodo_pagamento' => 'Pix',
                'conta_id' => $c2->id,
                'categoria_id' => $catMora->id,
            ],
            [
                'descricao_movimento' => 'Livros Técnicos Laravel',
                'valor_transacao' => 189.90,
                'data_competencia' => date('Y-m-d', strtotime('-2 days')),
                'metodo_pagamento' => 'Pix',
                'conta_id' => $c1->id,
                'categoria_id' => $catEstu->id,
            ],
            [
                'descricao_movimento' => 'Cinema e Lanche',
                'valor_transacao' => 95.00,
                'data_competencia' => date('Y-m-d', strtotime('-1 day')),
                'metodo_pagamento' => 'Cartão de Crédito',
                'conta_id' => $c2->id,
                'categoria_id' => $catLaze->id,
            ],
        ];

        foreach ($transacoes as $t) {
            Transacao::create($t);
        }
    }
}
