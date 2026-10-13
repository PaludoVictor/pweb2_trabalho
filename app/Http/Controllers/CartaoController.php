<?php

namespace App\Http\Controllers;

use App\Models\Cartao;
use App\Models\Conta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CartaoController extends Controller
{
    public function index()
    {
        $dados = Cartao::with('conta')->orderBy('id', 'desc')->get();

        return view('cartao.list')->with(['dados' => $dados]);
    }

    public function create()
    {
        $contas = Conta::orderBy('nome_instituicao')->get();

        return view('cartao.form')->with(compact('contas'));
    }

    public function validateForm(Request $request)
    {
        $request->validate([
            'nome_cartao' => 'required',
            'bandeira' => 'required',
            'limite_credito' => 'required|numeric|min:0.01',
            'dia_vencimento' => 'required|integer|min:1|max:31',
            'conta_id' => 'required|exists:contas,id',
            'imagem' => 'nullable|image|mimes:png,jpg,jpeg',
        ], [
            'nome_cartao.required' => "O :attribute é obrigatório",
            'bandeira.required' => "A :attribute é obrigatória",
            'limite_credito.required' => "O :attribute é obrigatório",
            'limite_credito.numeric' => "O :attribute deve ser numérico",
            'limite_credito.min' => "O :attribute deve ser maior que zero",
            'dia_vencimento.required' => "O :attribute é obrigatório",
            'dia_vencimento.integer' => "O :attribute deve ser um número inteiro",
            'dia_vencimento.min' => "O :attribute deve ser entre 1 e 31",
            'dia_vencimento.max' => "O :attribute deve ser entre 1 e 31",
            'conta_id.required' => "A conta bancária é obrigatória",
            'conta_id.exists' => "A conta bancária selecionada é inválida",
            'imagem.image' => "O arquivo de :attribute deve ser uma imagem válida",
            'imagem.mimes' => "A imagem deve ser das extensões: PNG, JPEG, JPG",
        ]);
    }

    public function store(Request $request)
    {
        $this->validateForm($request);

        $data = $request->all();
        $imagem = $request->file('imagem');

        if ($imagem) {
            $nome_imagem = date('YmdiHs') . "." . $imagem->getClientOriginalExtension();
            $diretorio = "imagem/cartao/";
            $imagem->storeAs($diretorio, $nome_imagem, 'public');
            $data['imagem'] = $diretorio . $nome_imagem;
        }

        Cartao::create($data);

        return redirect('cartao')->with("success", 'Registro Salvo com sucesso!');
    }

    public function edit($id)
    {
        $data = Cartao::find($id);

        if (!$data) {
            return redirect('cartao')->with("error", 'Cartão não encontrado.');
        }

        $contas = Conta::orderBy('nome_instituicao')->get();

        return view('cartao.form')->with(compact('data', 'contas'));
    }

    public function update(Request $request, $id)
    {
        $this->validateForm($request);

        $cartao = Cartao::findOrFail($id);
        $data = $request->all();
        $imagem = $request->file('imagem');

        if ($imagem) {
            // Se já existia uma imagem anterior, remove o arquivo físico antigo
            if (!empty($cartao->imagem) && Storage::disk('public')->exists($cartao->imagem)) {
                Storage::disk('public')->delete($cartao->imagem);
            }

            $nome_imagem = date('YmdiHs') . "." . $imagem->getClientOriginalExtension();
            $diretorio = "imagem/cartao/";
            $imagem->storeAs($diretorio, $nome_imagem, 'public');
            $data['imagem'] = $diretorio . $nome_imagem;
        }

        $cartao->update($data);

        return redirect('cartao')->with("success", 'Registro Atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $cartao = Cartao::find($id);

        if ($cartao) {
            // Exclui o arquivo físico ao deletar o registro (Requisito do Edital)
            if (!empty($cartao->imagem) && Storage::disk('public')->exists($cartao->imagem)) {
                Storage::disk('public')->delete($cartao->imagem);
            }

            Cartao::destroy($id);
        }

        return redirect('cartao')->with("success", 'Registro removido com sucesso!');
    }

    public function search(Request $request)
    {
        if (!empty($request->valor)) {
            $tipo = $request->tipo;
            $valor = $request->valor;

            if ($tipo === 'conta') {
                $dados = Cartao::with('conta')
                    ->whereHas('conta', function ($q) use ($valor) {
                        $q->where('nome_instituicao', 'like', "%$valor%");
                    })->get();
            } elseif ($tipo === 'bandeira') {
                $dados = Cartao::with('conta')
                    ->where('bandeira', 'like', "%$valor%")
                    ->get();
            } else {
                $dados = Cartao::with('conta')
                    ->where('nome_cartao', 'like', "%$valor%")
                    ->get();
            }
        } else {
            $dados = Cartao::with('conta')->orderBy('id', 'desc')->get();
        }

        return view('cartao.list', compact('dados'));
    }
}

