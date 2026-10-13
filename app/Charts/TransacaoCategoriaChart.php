<?php

namespace App\Charts;

use App\Models\Categoria;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class TransacaoCategoriaChart
{
    public function build(): \ArielMejiaDev\LarapexCharts\PieChart
    {
        $categorias = Categoria::with('transacoes')->get();

        $nomesCategorias = [];
        $valoresTotais = [];

        foreach ($categorias as $item) {
            $total = (float) $item->transacoes->sum('valor_transacao');
            // Se nao houver valor, usamos a quantidade ou 0
            if ($total > 0 || $item->transacoes->count() > 0) {
                $nomesCategorias[] = $item->nome_categoria;
                $valoresTotais[] = $total > 0 ? $total : 1.0;
            }
        }

        if (empty($nomesCategorias)) {
            $nomesCategorias = ['Sem dados'];
            $valoresTotais = [1];
        }

        return (new LarapexChart)->pieChart()
            ->setTitle('Despesas por Categoria')
            ->setSubtitle('Distribuição dos Gastos Cadastrados (R$)')
            ->addData($valoresTotais)
            ->setLabels($nomesCategorias);
    }
}

