<?php

namespace App\Http\Controllers;

use App\Models\Meta;
use App\Models\Conta;
use App\Models\Categoria;
use App\Charts\MetaFinanceiraChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MetaController extends Controller
{
    public function index()
    {
        $dados = Meta::with(['conta', 'categoria'])->orderBy('id', 'desc')->get();

        return view('meta.list')->with(['dados' => $dados]);
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
            'titulo' => 'required',
            'valor_meta' => 'required|numeric|min:0.01',
            'valor_atual' => 'nullable|numeric|min:0',
            'data_limite' => 'required|date',
            'conta_id' => 'required|exists:contas,id',
            'categoria_id' => 'required|exists:categorias,id',
            'imagem' => 'nullable|image|mimes:png,jpg,jpeg',
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
            $diretorio = "imagem/meta/";
            $imagem->storeAs($diretorio, $nome_imagem, 'public');
            $data['imagem'] = $diretorio . $nome_imagem;
        }

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
        $imagem = $request->file('imagem');

        if ($imagem) {
            // Se já existia uma imagem anterior, remove o arquivo físico antigo
            if (!empty($meta->imagem) && Storage::disk('public')->exists($meta->imagem)) {
                Storage::disk('public')->delete($meta->imagem);
            }

            $nome_imagem = date('YmdiHs') . "." . $imagem->getClientOriginalExtension();
            $diretorio = "imagem/meta/";
            $imagem->storeAs($diretorio, $nome_imagem, 'public');
            $data['imagem'] = $diretorio . $nome_imagem;
        }

        $meta->update($data);

        return redirect('meta')->with("success", 'Registro Atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $meta = Meta::find($id);

        if ($meta) {
            // Exclui o arquivo físico ao deletar o registro (Requisito do Edital)
            if (!empty($meta->imagem) && Storage::disk('public')->exists($meta->imagem)) {
                Storage::disk('public')->delete($meta->imagem);
            }

            Meta::destroy($id);
        }

        return redirect('meta')->with("success", 'Registro removido com sucesso!');
    }

    public function search(Request $request)
    {
        if (!empty($request->valor)) {
            $tipo = $request->tipo;
            $valor = $request->valor;

            if ($tipo === 'categoria') {
                $dados = Meta::with(['conta', 'categoria'])
                    ->whereHas('categoria', function ($q) use ($valor) {
                        $q->where('nome_categoria', 'like', "%$valor%");
                    })->get();
            } elseif ($tipo === 'conta') {
                $dados = Meta::with(['conta', 'categoria'])
                    ->whereHas('conta', function ($q) use ($valor) {
                        $q->where('nome_instituicao', 'like', "%$valor%");
                    })->get();
            } else {
                $dados = Meta::with(['conta', 'categoria'])
                    ->where('titulo', 'like', "%$valor%")
                    ->get();
            }
        } else {
            $dados = Meta::with(['conta', 'categoria'])->orderBy('id', 'desc')->get();
        }

        return view('meta.list', compact('dados'));
    }

    public function chart(MetaFinanceiraChart $chart)
    {
        return view('meta.chart', ['chart' => $chart->build()]);
    }
}

