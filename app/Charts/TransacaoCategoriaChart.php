<?php

namespace App\Charts;

use App\Models\Categoria;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class TransacaoCategoriaChart
{
    public function build(): \ArielMejiaDev\LarapexCharts\PieChart
    {
        $categorias = Categoria::withSum('transacoes', 'valor_transacao')->get();

        $nomesCategorias = [];
        $valoresGastos = [];

        foreach ($categorias as $item) {
            $nomesCategorias[] = $item->nome_categoria;
            $valoresGastos[] = (float) $item->transacoes_sum_valor_transacao;
        }

        return (new LarapexChart)->pieChart()
            ->setTitle('Gastos por Categoria')
            ->setSubtitle('Total de Despesas (R$)')
            ->addData($valoresGastos)
            ->setLabels($nomesCategorias);
    }
}
