<?php

namespace App\Charts;

use App\Models\Meta;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class MetaFinanceiraChart
{
    public function build(): \ArielMejiaDev\LarapexCharts\BarChart
    {
        $metas = Meta::all();

        $nomesMetas = [];
        $valoresMeta = [];
        $valoresAtuais = [];

        foreach ($metas as $meta) {
            $nomesMetas[] = $meta->titulo;
            $valoresMeta[] = (float) $meta->valor_meta;
            $valoresAtuais[] = (float) $meta->valor_atual;
        }

        if (empty($nomesMetas)) {
            $nomesMetas = ['Exemplo'];
            $valoresMeta = [1000];
            $valoresAtuais = [500];
        }

        return (new LarapexChart)->barChart()
            ->setTitle('Progresso das Metas Financeiras')
            ->setSubtitle('Valor Alvo da Meta vs. Valor Já Acumulado (R$)')
            ->addData($valoresMeta, 'Valor da Meta (Alvo)')
            ->addData($valoresAtuais, 'Valor Atual (Acumulado)')
            ->setXAxis($nomesMetas);
    }
}

