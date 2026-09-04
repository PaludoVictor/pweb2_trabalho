<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    public function index()
    {
        $dados = Categoria::all();

        return view('categoria.list')->with(['dados' => $dados]);
    }

    public function create()
    {
        return view('categoria.form');
    }

    public function validateForm(Request $request)
    {
        $request->validate([
            'nome_categoria' => 'required',
            'tipo_despesa' => 'required',
            'limite_orcamento' => 'required|numeric|min:0',
            'cor_identificacao' => 'required',
        ], [
            'nome_categoria.required' => 'O nome da categoria é obrigatório.',
            'tipo_despesa.required' => 'O tipo de despesa é obrigatório.',
            'limite_orcamento.required' => 'O limite de orçamento é obrigatório.',
            'limite_orcamento.numeric' => 'O limite de orçamento deve ser um número.',
            'cor_identificacao.required' => 'A cor de identificação é obrigatória.',
        ]);
    }

    public function store(Request $request)
    {
        $this->validateForm($request);

        Categoria::create($request->all());

        return redirect('categoria');
    }

    public function edit($id)
    {
        $data = Categoria::find($id);

        if (!$data) {
            return redirect('categoria')->with('error', 'Categoria não encontrada.');
        }

        return view('categoria.form', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $this->validateForm($request);

        $cat = Categoria::find($id);
        if ($cat) {
            $cat->update($request->all());
        }

        return redirect('categoria');
    }

    public function destroy($id)
    {
        Categoria::destroy($id);

        return redirect('categoria');
    }

    public function search(Request $request)
    {
        if (!empty($request->valor)) {
            $dados = Categoria::where(
                'nome_categoria',
                'like',
                "%$request->valor%"
            )->get();
        } else {
            $dados = Categoria::all();
        }

        return view('categoria.list', compact('dados'));
    }
}
