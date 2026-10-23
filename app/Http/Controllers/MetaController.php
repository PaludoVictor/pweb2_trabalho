<?php

namespace App\Http\Controllers;

use App\Models\Meta;
use App\Models\Conta;
use App\Models\Categoria;
use App\Charts\MetaFinanceiraChart;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class MetaController extends Controller
{
    public function index()
    {
        $dados = Meta::with(['conta', 'categoria'])->orderBy('id', 'desc')->get();
        $contas = Conta::orderBy('nome_instituicao')->get();
        $categorias = Categoria::orderBy('nome_categoria')->get();

        return view('meta.list', compact('dados', 'contas', 'categorias'));
    }

    public function create()
    {
        $contas = Conta::orderBy('nome_instituicao')->get();
        $categorias = Categoria::orderBy('nome_categoria')->get();

        return view('meta.form')->with(compact('contas', 'categorias'));
    }

    public function validateForm(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:150',
            'valor_meta' => 'required|numeric|min:0.01|max:9999999999.99',
            'valor_atual' => 'nullable|numeric|min:0|max:9999999999.99',
            'data_limite' => 'required|date',
            'conta_id' => 'required|exists:contas,id',
            'categoria_id' => 'required|exists:categorias,id',
        ], [
            'titulo.required' => "O :attribute é obrigatório",
            'valor_meta.required' => "O :attribute é obrigatório",
            'valor_meta.numeric' => "O :attribute deve ser numérico",
            'valor_meta.min' => "O :attribute deve ser maior que zero",
            'valor_atual.numeric' => "O :attribute deve ser numérico",
            'data_limite.required' => "A :attribute é obrigatória",
            'data_limite.date' => "A :attribute deve ser uma data válida",
            'conta_id.required' => "A conta vinculada é obrigatória",
            'conta_id.exists' => "A conta selecionada é inválida",
            'categoria_id.required' => "A categoria vinculada é obrigatória",
            'categoria_id.exists' => "A categoria selecionada é inválida",
        ]);
    }

    public function store(Request $request)
    {
        $this->validateForm($request);

        $data = $request->all();

        if (empty($data['valor_atual'])) {
            $data['valor_atual'] = 0.00;
        }

        Meta::create($data);

        return redirect('meta')->with("success", 'Registro Salvo com sucesso!');
    }

    public function edit($id)
    {
        $data = Meta::find($id);

        if (!$data) {
            return redirect('meta')->with("error", 'Meta não encontrada.');
        }

        $contas = Conta::orderBy('nome_instituicao')->get();
        $categorias = Categoria::orderBy('nome_categoria')->get();

        return view('meta.form')->with(compact('data', 'contas', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $this->validateForm($request);

        $meta = Meta::findOrFail($id);
        $data = $request->all();

        $meta->update($data);

        return redirect('meta')->with("success", 'Registro Atualizado com sucesso!');
    }

    public function destroy($id)
    {
        Meta::destroy($id);

        return redirect('meta')->with("success", 'Registro removido com sucesso!');
    }

    public function search(Request $request)
    {
        $query = Meta::with(['conta', 'categoria']);

        if (!empty($request->valor)) {
            $tipo = $request->tipo;
            $valor = $request->valor;

            if ($tipo === 'categoria') {
                $query->whereHas('categoria', fn ($q) => $q->where('nome_categoria', 'like', "%$valor%"));
            } elseif ($tipo === 'conta') {
                $query->whereHas('conta', fn ($q) => $q->where('nome_instituicao', 'like', "%$valor%"));
            } else {
                $query->where('titulo', 'like', "%$valor%");
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

        $dados = $query->orderBy('id', 'desc')->get();
        $contas = Conta::orderBy('nome_instituicao')->get();
        $categorias = Categoria::orderBy('nome_categoria')->get();

        return view('meta.list', compact('dados', 'contas', 'categorias'));
    }

    public function chart(MetaFinanceiraChart $chart)
    {
        return view('meta.chart', ['chart' => $chart->build(), 'temDados' => Meta::exists()]);
    }
}
