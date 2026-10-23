<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Storage;
use App\Rules\CpfValido;

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
            'nome' => 'required|string|max:100',
            'cpf' => ['required', new CpfValido],
            'telefone' => 'required|string|max:20',
            'email' => 'required|email|max:100|' . $uniqueEmail,
            'login' => 'required|string|max:50|' . $uniqueLogin,
            'imagem' => 'nullable|image|mimes:png,jpg,jpeg|max:4096',
        ];

        if (!$id || !empty($request->senha)) {
            $rules['senha'] = 'required|string|min:8|max:255';
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
            'senha.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'imagem.image' => 'O arquivo de foto deve ser uma imagem válida.',
            'imagem.mimes' => 'A foto deve ser das extensões: PNG, JPEG, JPG.',
        ]);
    }

    public function store(Request $request)
    {
        $this->validateForm($request);

        $data = $request->only(['nome', 'cpf', 'telefone', 'email', 'login', 'senha']);
        $imagem = $request->file('imagem');

        if ($imagem) {
            $data['imagem'] = $imagem->store('imagem/usuario', 'public');
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
        $dados = $request->only(['nome', 'cpf', 'telefone', 'email', 'login']);

        if (!empty($request->senha)) {
            $dados['senha'] = $request->senha;
        }

        $imagem = $request->file('imagem');
        if ($imagem) {
            $dados['imagem'] = $imagem->store('imagem/usuario', 'public');
        }

        $oldImage = $usuario->imagem;
        $usuario->update($dados);

        if ($imagem && $oldImage && !Usuario::where('imagem', $oldImage)->exists()) {
            Storage::disk('public')->delete($oldImage);
        }

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
            $oldImage = $usuario->imagem;
            Usuario::destroy($id);

            if ($oldImage && !Usuario::where('imagem', $oldImage)->exists()) {
                Storage::disk('public')->delete($oldImage);
            }
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
