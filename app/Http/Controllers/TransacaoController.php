<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transacao;
use App\Models\Conta;
use App\Models\Categoria;
use App\Charts\TransacaoCategoriaChart;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

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
            'imagem' => 'nullable|image|mimes:png,jpg,jpeg',
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
            'imagem.image' => 'O comprovante deve ser um arquivo de imagem.',
            'imagem.mimes' => 'O comprovante deve ser das extensões: PNG, JPEG, JPG.',
        ]);
    }

    public function store(Request $request)
    {
        $this->validateForm($request);

        $data = $request->all();
        $imagem = $request->file('imagem');

        if ($imagem) {
            $nome_imagem = date('YmdiHs') . "." . $imagem->getClientOriginalExtension();
            $diretorio = "imagem/transacao/";
            $imagem->storeAs($diretorio, $nome_imagem, 'public');
            $data['imagem'] = $diretorio . $nome_imagem;
        }

        Transacao::create($data);

        return redirect('transacao')->with('success', 'Registro Salvo com sucesso!');
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

        $transacao = Transacao::findOrFail($id);
        $data = $request->all();
        $imagem = $request->file('imagem');

        if ($imagem) {
            if (!empty($transacao->imagem) && Storage::disk('public')->exists($transacao->imagem)) {
                Storage::disk('public')->delete($transacao->imagem);
            }

            $nome_imagem = date('YmdiHs') . "." . $imagem->getClientOriginalExtension();
            $diretorio = "imagem/transacao/";
            $imagem->storeAs($diretorio, $nome_imagem, 'public');
            $data['imagem'] = $diretorio . $nome_imagem;
        }

        $transacao->update($data);

        return redirect('transacao')->with('success', 'Registro Atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $transacao = Transacao::find($id);

        if ($transacao) {
            // Exclui o arquivo do comprovante se existir
            if (!empty($transacao->imagem) && Storage::disk('public')->exists($transacao->imagem)) {
                Storage::disk('public')->delete($transacao->imagem);
            }

            Transacao::destroy($id);
        }

        return redirect('transacao')->with('success', 'Registro removido com sucesso!');
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

    public function report()
    {
        $transacoes = Transacao::with(['conta', 'categoria'])->orderBy('data_competencia', 'desc')->get();

        $data = [
            'titulo' => 'Relatório Listagem Geral de Transações',
            'date' => date('d/m/Y'),
            'dados' => $transacoes
        ];

        $pdf = Pdf::loadView('transacao.report', $data);

        return $pdf->download('relatorio_transacoes.pdf');
    }

    public function reportCategorias()
    {
        $categorias = Categoria::with('transacoes.conta')->orderBy('nome_categoria')->get();

        $data = [
            'titulo' => 'Relatório de Transações por Categoria',
            'date' => date('d/m/Y'),
            'dados' => $categorias
        ];

        $pdf = Pdf::loadView('transacao.reportCategorias', $data);

        return $pdf->download('relatorio_transacoes_por_categoria.pdf');
    }

    public function chart(TransacaoCategoriaChart $chart)
    {
        return view('transacao.chart', ['chart' => $chart->build()]);
    }
}
