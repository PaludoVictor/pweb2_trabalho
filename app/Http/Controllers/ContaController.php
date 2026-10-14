<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conta;
use Barryvdh\DomPDF\Facade\Pdf;

class ContaController extends Controller
{
    public function index()
    {
        $dados = Conta::all();

        return view('conta.list')->with(['dados' => $dados]);
    }

    public function create()
    {
        return view('conta.form');
    }

    public function validateForm(Request $request, $id = null)
    {
        $uniqueRule = 'unique:contas,numero_conta' . ($id ? ",$id" : '');

        $request->validate([
            'nome_instituicao' => 'required',
            'agencia_numero' => 'required',
            'numero_conta' => 'required|' . $uniqueRule,
            'saldo_atual' => 'required|numeric',
        ], [
            'nome_instituicao.required' => 'A instituição financeira é obrigatória.',
            'agencia_numero.required' => 'O número da agência é obrigatório.',
            'numero_conta.required' => 'O número da conta é obrigatório.',
            'numero_conta.unique' => 'Este número de conta já está cadastrado.',
            'saldo_atual.required' => 'O saldo atual é obrigatório.',
            'saldo_atual.numeric' => 'O saldo atual deve ser um valor numérico válido.',
        ]);
    }

    public function store(Request $request)
    {
        $this->validateForm($request);

        Conta::create($request->all());

        return redirect('conta')->with('success', 'Registro Salvo com sucesso!');
    }

    public function edit($id)
    {
        $data = Conta::find($id);

        if (!$data) {
            return redirect('conta')->with('error', 'Conta não encontrada.');
        }

        return view('conta.form', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $this->validateForm($request, $id);

        $conta = Conta::findOrFail($id);
        $conta->update($request->all());

        return redirect('conta')->with('success', 'Registro Atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $conta = Conta::findOrFail($id);

        if ($conta->transacoes->count() > 0 || $conta->cartoes->count() > 0 || $conta->metas->count() > 0) {
            return redirect('conta')->with("error", "Não é possível remover a conta {$conta->nome_instituicao}, pois existem dados associados a ela!");
        }

        Conta::destroy($id);

        return redirect('conta')->with('success', 'Registro removido com sucesso!');
    }

    public function search(Request $request)
    {
        if (!empty($request->valor)) {
            $campo = in_array($request->tipo, ['nome_instituicao', 'agencia_numero', 'numero_conta']) ? $request->tipo : 'nome_instituicao';

            $dados = Conta::where(
                $campo,
                'like',
                "%$request->valor%"
            )->get();
        } else {
            $dados = Conta::all();
        }

        return view('conta.list', compact('dados'));
    }

    public function report()
    {
        $contas = Conta::orderBy('nome_instituicao')->get();

        $data = [
            'titulo' => 'Relatório Listagem de Contas Bancárias',
            'date' => date('d/m/Y'),
            'dados' => $contas
        ];

        $pdf = Pdf::loadView('conta.report', $data);

        return $pdf->download('relatorio_contas.pdf');
    }
}
