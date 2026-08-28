<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

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
            'telefone' => 'required',
            'email' => 'required|email|' . $uniqueEmail,
            'login' => 'required|' . $uniqueLogin,
        ];

        if (!$id || !empty($request->senha)) {
            $rules['senha'] = 'required|min:3';
        }

        $request->validate($rules, [
            'nome.required' => 'O nome completo é obrigatório.',
            'telefone.required' => 'O telefone é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Informe um endereço de e-mail válido.',
            'email.unique' => 'Este e-mail já está em uso.',
            'login.required' => 'O login é obrigatório.',
            'login.unique' => 'Este login já está em uso.',
            'senha.required' => 'A senha é obrigatória.',
            'senha.min' => 'A senha deve ter pelo menos 3 caracteres.',
        ]);
    }

    public function store(Request $request)
    {
        $this->validateForm($request);

        Usuario::create($request->all());

        return redirect('usuario');
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

        $usuario = Usuario::find($id);
        if ($usuario) {
            $dados = $request->except(['senha']);
            if (!empty($request->senha)) {
                $dados['senha'] = $request->senha;
            }
            $usuario->update($dados);
        }

        return redirect('usuario');
    }

    public function destroy($id)
    {
        Usuario::destroy($id);

        return redirect('usuario');
    }

    public function search(Request $request)
    {
        if (!empty($request->valor)) {
            $campo = in_array($request->tipo, ['nome', 'email', 'login']) ? $request->tipo : 'nome';

            $dados = Usuario::where(
                $campo,
                'like',
                "%$request->valor%"
            )->get();
        } else {
            $dados = Usuario::all();
        }

        return view('usuario.list', compact('dados'));
    }
}
