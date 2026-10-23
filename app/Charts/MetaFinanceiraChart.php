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

        foreach ($metas as $item) {
            $nomesMetas[] = $item->titulo;
            $valoresMeta[] = (float) $item->valor_meta;
            $valoresAtuais[] = (float) $item->valor_atual;
        }

        return (new LarapexChart)->barChart()
            ->setTitle('Metas Financeiras')
            ->setSubtitle('Valor Alvo vs. Economizado (R$)')
            ->addData($valoresMeta, 'Valor Meta')
            ->addData($valoresAtuais, 'Valor Atual')
            ->setXAxis($nomesMetas);
    }
}
