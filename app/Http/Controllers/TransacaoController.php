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
        $contas = Conta::orderBy('nome_instituicao')->get();
        $categorias = Categoria::orderBy('nome_categoria')->get();

        return view('transacao.list', compact('dados', 'contas', 'categorias'));
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
            'descricao_movimento' => 'required|string|max:150',
            'valor_transacao' => 'required|numeric|min:0.01|max:99999999.99',
            'data_competencia' => 'required|date',
            'metodo_pagamento' => 'required|string|max:50',
            'conta_id' => 'required|exists:contas,id',
            'categoria_id' => 'required|exists:categorias,id',
            'imagem' => 'nullable|image|mimes:png,jpg,jpeg|max:4096',
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

        $data = $request->only(['descricao_movimento', 'valor_transacao', 'data_competencia', 'metodo_pagamento', 'observacao', 'conta_id', 'categoria_id']);
        $imagem = $request->file('imagem');

        if ($imagem) {
            $data['imagem'] = $imagem->store('imagem/transacao', 'public');
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
        $data = $request->only(['descricao_movimento', 'valor_transacao', 'data_competencia', 'metodo_pagamento', 'observacao', 'conta_id', 'categoria_id']);
        $imagem = $request->file('imagem');

        if ($imagem) {
            $data['imagem'] = $imagem->store('imagem/transacao', 'public');
        }

        $oldImage = $transacao->imagem;
        $transacao->update($data);

        if ($imagem && $oldImage && !Transacao::where('imagem', $oldImage)->exists()) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect('transacao')->with('success', 'Registro Atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $transacao = Transacao::find($id);

        if ($transacao) {
            $oldImage = $transacao->imagem;
            Transacao::destroy($id);

            if ($oldImage && !Transacao::where('imagem', $oldImage)->exists()) {
                Storage::disk('public')->delete($oldImage);
            }
        }

        return redirect('transacao')->with('success', 'Registro removido com sucesso!');
    }

    public function search(Request $request)
    {
        $query = Transacao::with(['conta', 'categoria']);

        if (!empty($request->valor)) {
            $tipo = $request->tipo;
            $valor = $request->valor;

            if ($tipo === 'nome_categoria') {
                $query->whereHas('categoria', fn ($q) => $q->where('nome_categoria', 'like', "%$valor%"));
            } elseif ($tipo === 'nome_instituicao') {
                $query->whereHas('conta', fn ($q) => $q->where('nome_instituicao', 'like', "%$valor%"));
            } else {
                $campo = in_array($tipo, ['descricao_movimento', 'metodo_pagamento']) ? $tipo : 'descricao_movimento';
                $query->where($campo, 'like', "%$valor%");
            }
        }

        if ($request->filled('conta_id')) {
            $request->validate(['conta_id' => 'exists:contas,id']);
            $query->where('conta_id', $request->conta_id);
        }
        if ($request->filled('categoria_id')) {
            $request->validate(['categoria_id' => 'exists:categorias,id']);
            $query->where('categoria_id', $request->categoria_id);
        }

        $dados = $query->orderBy('data_competencia', 'desc')->get();
        $contas = Conta::orderBy('nome_instituicao')->get();
        $categorias = Categoria::orderBy('nome_categoria')->get();

        return view('transacao.list', compact('dados', 'contas', 'categorias'));
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
        return view('transacao.chart', ['chart' => $chart->build(), 'temDados' => Transacao::exists()]);
    }
}
