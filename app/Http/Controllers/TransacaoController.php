<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transacao;
use App\Models\Conta;
use App\Models\Categoria;

class TransacaoController extends Controller
{
    public function index()
    {
        $dados = Transacao::with(['conta', 'categoria'])->orderBy('data_competencia', 'desc')->get();

        return view('transacao.list')->with(['dados' => $dados]);
    }

    public function create()
    {
        $contas = Conta::orderBy('nome_instituicao')->get();
        $categorias = Categoria::orderBy('nome_categoria')->get();

        return view('transacao.form', compact('contas', 'categorias'));
    }

    public function validateForm(Request $request)
    {
        $request->validate([
            'descricao_movimento' => 'required',
            'valor_transacao' => 'required|numeric|min:0.01',
            'data_competencia' => 'required|date',
            'metodo_pagamento' => 'required',
            'conta_id' => 'required|exists:contas,id',
            'categoria_id' => 'required|exists:categorias,id',
        ], [
            'descricao_movimento.required' => 'A descrição da transação é obrigatória.',
            'valor_transacao.required' => 'O valor da transação é obrigatório.',
            'valor_transacao.numeric' => 'O valor deve ser numérico.',
            'valor_transacao.min' => 'O valor deve ser maior que zero.',
            'data_competencia.required' => 'A data de competência é obrigatória.',
            'data_competencia.date' => 'A data de competência deve ser uma data válida.',
            'metodo_pagamento.required' => 'O método de pagamento é obrigatório.',
            'conta_id.required' => 'Selecione uma conta bancária.',
            'conta_id.exists' => 'A conta selecionada é inválida.',
            'categoria_id.required' => 'Selecione uma categoria.',
            'categoria_id.exists' => 'A categoria selecionada é inválida.',
        ]);
    }

    public function store(Request $request)
    {
        $this->validateForm($request);

        Transacao::create($request->all());

        return redirect('transacao');
    }

    public function edit($id)
    {
        $data = Transacao::find($id);

        if (!$data) {
            return redirect('transacao')->with('error', 'Transação não encontrada.');
        }

        $contas = Conta::orderBy('nome_instituicao')->get();
        $categorias = Categoria::orderBy('nome_categoria')->get();

        return view('transacao.form', compact('data', 'contas', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $this->validateForm($request);

        $transacao = Transacao::find($id);
        if ($transacao) {
            $transacao->update($request->all());
        }

        return redirect('transacao');
    }

    public function destroy($id)
    {
        Transacao::destroy($id);

        return redirect('transacao');
    }

    public function search(Request $request)
    {
        if (!empty($request->valor)) {
            $tipo = $request->tipo;
            $valor = $request->valor;

            if ($tipo === 'nome_categoria') {
                $dados = Transacao::with(['conta', 'categoria'])
                    ->whereHas('categoria', function ($q) use ($valor) {
                        $q->where('nome_categoria', 'like', "%$valor%");
                    })
                    ->orderBy('data_competencia', 'desc')
                    ->get();
            } elseif ($tipo === 'nome_instituicao') {
                $dados = Transacao::with(['conta', 'categoria'])
                    ->whereHas('conta', function ($q) use ($valor) {
                        $q->where('nome_instituicao', 'like', "%$valor%");
                    })
                    ->orderBy('data_competencia', 'desc')
                    ->get();
            } else {
                $campo = in_array($tipo, ['descricao_movimento', 'metodo_pagamento']) ? $tipo : 'descricao_movimento';
                $dados = Transacao::with(['conta', 'categoria'])
                    ->where($campo, 'like', "%$valor%")
                    ->orderBy('data_competencia', 'desc')
                    ->get();
            }
        } else {
            $dados = Transacao::with(['conta', 'categoria'])->orderBy('data_competencia', 'desc')->get();
        }

        return view('transacao.list', compact('dados'));
    }
}
