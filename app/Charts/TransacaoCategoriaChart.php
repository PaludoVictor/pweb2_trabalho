<?php

namespace App\Charts;

use App\Models\Categoria;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class TransacaoCategoriaChart
{
    public function build(): \ArielMejiaDev\LarapexCharts\PieChart
    {
        $categorias = Categoria::all();

        $nomesCategorias = [];
        $valoresGastos = [];

        foreach ($categorias as $item) {
            $nomesCategorias[] = $item->nome_categoria;
            $valoresGastos[] = (float) $item->transacoes->sum('valor_transacao');
        }

        // Se nao houver gastos cadastrados, exibe valores ilustrativos
        if (empty(array_filter($valoresGastos))) {
            $valoresGastos = [100, 200, 150, 80, 50];
            $nomesCategorias = ['Alimentação', 'Moradia', 'Transporte', 'Lazer', 'Estudos'];
        }

        return (new LarapexChart)->pieChart()
            ->setTitle('Gastos por Categoria')
            ->setSubtitle('Total de Despesas (R$)')
            ->addData($valoresGastos)
            ->setLabels($nomesCategorias);
    }
}
