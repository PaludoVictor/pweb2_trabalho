<?php

namespace App\Http\Controllers;

use App\Charts\TransacaoCategoriaChart;
use App\Charts\MetaFinanceiraChart;

class DashboardController extends Controller
{
    public function index(TransacaoCategoriaChart $graficoGastos, MetaFinanceiraChart $graficoMetas)
    {
        return view('dashboard', [
            'graficoGastos' => $graficoGastos->build(),
            'graficoMetas' => $graficoMetas->build(),
        ]);
    }
}
