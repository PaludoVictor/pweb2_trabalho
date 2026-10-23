<?php

namespace App\Http\Controllers;

use App\Charts\TransacaoCategoriaChart;
use App\Charts\MetaFinanceiraChart;
use App\Models\Meta;
use App\Models\Transacao;

class DashboardController extends Controller
{
    public function index(TransacaoCategoriaChart $graficoGastos, MetaFinanceiraChart $graficoMetas)
    {
        return view('dashboard', [
            'graficoGastos' => $graficoGastos->build(),
            'graficoMetas' => $graficoMetas->build(),
            'temGastos' => Transacao::exists(),
            'temMetas' => Meta::exists(),
        ]);
    }
}
