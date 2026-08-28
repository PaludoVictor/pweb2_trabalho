<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use App\Models\Categoria;
use App\Models\Transacao;

class DashboardController extends Controller
{
    public function index()
    {
        $totalContas = Conta::sum('saldo_atual');
        $totalTransacoes = Transacao::sum('valor_transacao');
        $totalOrcamento = Categoria::sum('limite_orcamento');
        $saldoRestante = max(0, $totalOrcamento - $totalTransacoes);

        $categorias = Categoria::withSum('transacoes', 'valor_transacao')->get();

        $dadosGrafico = $categorias->map(function ($cat) {
            return [
                'nome_categoria' => $cat->nome_categoria,
                'cor_identificacao' => $cat->cor_identificacao,
                'total' => (float) ($cat->transacoes_sum_valor_transacao ?? 0),
                'limite' => (float) $cat->limite_orcamento,
            ];
        })->filter(function ($item) {
            return $item['total'] > 0 || $item['limite'] > 0;
        })->values();

        $ultimasTransacoes = Transacao::with(['conta', 'categoria'])
            ->orderBy('data_competencia', 'desc')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalContas',
            'totalTransacoes',
            'totalOrcamento',
            'saldoRestante',
            'dadosGrafico',
            'ultimasTransacoes'
        ));
    }
}
