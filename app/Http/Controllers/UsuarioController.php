<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Storage;

class UsuarioController extends Controller
{
    public function index()
    {
        $dados = Usuario::all();

        return view('usuario.list')->with(['dados' => $dados]);
    }

    public function create()
    {
        return view('usuario.form');
    }

    public function validateForm(Request $request, $id = null)
    {
        $uniqueEmail = 'unique:usuarios,email' . ($id ? ",$id" : '');
        $uniqueLogin = 'unique:usuarios,login' . ($id ? ",$id" : '');

        $rules = [
            'nome' => 'required',
            'cpf' => 'required',
            'telefone' => 'required',
            'email' => 'required|email|' . $uniqueEmail,
            'login' => 'required|' . $uniqueLogin,
            'imagem' => 'nullable|image|mimes:png,jpg,jpeg',
        ];

        if (!$id || !empty($request->senha)) {
            $rules['senha'] = 'required|min:3';
        }

        $request->validate($rules, [
            'nome.required' => 'O nome completo é obrigatório.',
            'cpf.required' => 'O CPF é obrigatório.',
            'telefone.required' => 'O telefone é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Informe um endereço de e-mail válido.',
            'email.unique' => 'Este e-mail já está em uso.',
            'login.required' => 'O login é obrigatório.',
            'login.unique' => 'Este login já está em uso.',
            'senha.required' => 'A senha é obrigatória.',
            'senha.min' => 'A senha deve ter pelo menos 3 caracteres.',
            'imagem.image' => 'O arquivo de foto deve ser uma imagem válida.',
            'imagem.mimes' => 'A foto deve ser das extensões: PNG, JPEG, JPG.',
        ]);
    }

    public function store(Request $request)
    {
        $this->validateForm($request);

        $data = $request->all();
        $imagem = $request->file('imagem');

        if ($imagem) {
            $nome_imagem = date('YmdiHs') . "." . $imagem->getClientOriginalExtension();
            $diretorio = "imagem/usuario/";
            $imagem->storeAs($diretorio, $nome_imagem, 'public');
            $data['imagem'] = $diretorio . $nome_imagem;
        }

        Usuario::create($data);

        return redirect('usuario')->with('success', 'Registro Salvo com sucesso!');
    }

    public function edit($id)
    {
        $data = Usuario::find($id);

        if (!$data) {
            return redirect('usuario')->with('error', 'Usuário não encontrado.');
        }

        return view('usuario.form', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $this->validateForm($request, $id);

        $usuario = Usuario::findOrFail($id);
        $dados = $request->except(['senha']);

        if (!empty($request->senha)) {
            $dados['senha'] = $request->senha;
        }

        $imagem = $request->file('imagem');
        if ($imagem) {
            // Se já existia uma imagem anterior, remove o arquivo físico antigo
            if (!empty($usuario->imagem) && Storage::disk('public')->exists($usuario->imagem)) {
                Storage::disk('public')->delete($usuario->imagem);
            }

            $nome_imagem = date('YmdiHs') . "." . $imagem->getClientOriginalExtension();
            $diretorio = "imagem/usuario/";
            $imagem->storeAs($diretorio, $nome_imagem, 'public');
            $dados['imagem'] = $diretorio . $nome_imagem;
        }

        $usuario->update($dados);

        // Se o usuário atualizou seu próprio perfil, atualiza a sessão
        if (session('usuario_id') == $usuario->id) {
            session([
                'usuario_nome' => $usuario->nome,
                'usuario_login' => $usuario->login,
                'usuario_email' => $usuario->email,
                'usuario_imagem' => $usuario->imagem,
            ]);
        }

        return redirect('usuario')->with('success', 'Registro Atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $usuario = Usuario::find($id);

        if ($usuario) {
            // Exclui o arquivo físico ao deletar o registro (Requisito Item 4 do PDF)
            if (!empty($usuario->imagem) && Storage::disk('public')->exists($usuario->imagem)) {
                Storage::disk('public')->delete($usuario->imagem);
            }

            Usuario::destroy($id);
        }

        return redirect('usuario')->with('success', 'Registro removido com sucesso!');
    }

    public function search(Request $request)
    {
        if (!empty($request->valor)) {
            $campo = in_array($request->tipo, ['nome', 'cpf', 'telefone', 'email', 'login']) ? $request->tipo : null;

            if ($campo) {
                $dados = Usuario::where($campo, 'like', "%$request->valor%")->get();
            } else {
                $dados = Usuario::where('nome', 'like', "%$request->valor%")
                    ->orWhere('cpf', 'like', "%$request->valor%")
                    ->orWhere('email', 'like', "%$request->valor%")
                    ->orWhere('login', 'like', "%$request->valor%")
                    ->get();
            }
        } else {
            $dados = Usuario::all();
        }

        return view('usuario.list', compact('dados'));
    }
}
