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
        $contas = Conta::orderBy('nome_instituicao')->get();

        return view('cartao.list', compact('dados', 'contas'));
    }

    public function create()
    {
        $contas = Conta::doesntHave('cartoes')->orderBy('nome_instituicao')->get();

        return view('cartao.form')->with(compact('contas'));
    }

    public function validateForm(Request $request, ?int $id = null)
    {
        $request->validate([
            'nome_cartao' => 'required|string|max:100',
            'bandeira' => 'required|string|max:50',
            'limite_credito' => 'required|numeric|min:0.01|max:9999999999.99',
            'dia_vencimento' => 'required|integer|min:1|max:31',
            'conta_id' => ['required', 'exists:contas,id', \Illuminate\Validation\Rule::unique('cartoes', 'conta_id')->ignore($id)],
            'imagem' => 'nullable|image|mimes:png,jpg,jpeg|max:4096',
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
            'conta_id.unique' => 'Esta conta já possui um cartão.',
            'imagem.image' => "O arquivo de :attribute deve ser uma imagem válida",
            'imagem.mimes' => "A imagem deve ser das extensões: PNG, JPEG, JPG",
        ]);
    }

    public function store(Request $request)
    {
        $this->validateForm($request);

        $data = $request->only(['nome_cartao', 'bandeira', 'limite_credito', 'dia_vencimento', 'conta_id']);
        $imagem = $request->file('imagem');

        if ($imagem) {
            $data['imagem'] = $imagem->store('imagem/cartao', 'public');
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

        $contas = Conta::whereDoesntHave('cartoes', fn ($query) => $query->whereKeyNot($data->id))
            ->orderBy('nome_instituicao')->get();

        return view('cartao.form')->with(compact('data', 'contas'));
    }

    public function update(Request $request, $id)
    {
        $this->validateForm($request, (int) $id);

        $cartao = Cartao::findOrFail($id);
        $data = $request->only(['nome_cartao', 'bandeira', 'limite_credito', 'dia_vencimento', 'conta_id']);
        $imagem = $request->file('imagem');

        if ($imagem) {
            $data['imagem'] = $imagem->store('imagem/cartao', 'public');
        }

        $oldImage = $cartao->imagem;
        $cartao->update($data);

        if ($imagem && $oldImage && !Cartao::where('imagem', $oldImage)->exists()) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect('cartao')->with("success", 'Registro Atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $cartao = Cartao::find($id);

        if ($cartao) {
            $oldImage = $cartao->imagem;
            Cartao::destroy($id);

            if ($oldImage && !Cartao::where('imagem', $oldImage)->exists()) {
                Storage::disk('public')->delete($oldImage);
            }
        }

        return redirect('cartao')->with("success", 'Registro removido com sucesso!');
    }

    public function search(Request $request)
    {
        $query = Cartao::with('conta');

        if (!empty($request->valor)) {
            $tipo = $request->tipo;
            $valor = $request->valor;

            if ($tipo === 'conta') {
                $query->whereHas('conta', fn ($q) => $q->where('nome_instituicao', 'like', "%$valor%"));
            } elseif ($tipo === 'bandeira') {
                $query->where('bandeira', 'like', "%$valor%");
            } else {
                $query->where('nome_cartao', 'like', "%$valor%");
            }
        }

        if ($request->filled('conta_id')) {
            $request->validate(['conta_id' => 'exists:contas,id']);
            $query->where('conta_id', $request->conta_id);
        }

        $dados = $query->orderBy('id', 'desc')->get();
        $contas = Conta::orderBy('nome_instituicao')->get();

        return view('cartao.list', compact('dados', 'contas'));
    }
}

