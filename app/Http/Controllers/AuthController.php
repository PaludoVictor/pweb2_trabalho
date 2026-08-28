<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (session('usuario_logado')) {
            return redirect('/');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'senha' => 'required',
        ], [
            'login.required' => 'O usuário/login é obrigatório.',
            'senha.required' => 'A senha é obrigatória.',
        ]);

        $usuario = Usuario::where('login', $request->login)
            ->where('senha', $request->senha)
            ->first();

        if ($usuario) {
            session([
                'usuario_logado' => true,
                'usuario_id' => $usuario->id,
                'usuario_nome' => $usuario->nome,
                'usuario_login' => $usuario->login,
                'usuario_email' => $usuario->email,
            ]);

            return redirect('/');
        }

        return back()->with('error', 'Login ou senha incorretos!')->withInput();
    }

    public function showRegisterForm()
    {
        if (session('usuario_logado')) {
            return redirect('/');
        }

        return view('auth.registro');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'telefone' => 'required',
            'email' => 'required|email|unique:usuarios,email',
            'login' => 'required|unique:usuarios,login',
            'senha' => 'required|min:3',
        ], [
            'nome.required' => 'O nome completo é obrigatório.',
            'telefone.required' => 'O telefone é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido.',
            'email.unique' => 'Este e-mail já está cadastrado.',
            'login.required' => 'O login é obrigatório.',
            'login.unique' => 'Este login já está cadastrado.',
            'senha.required' => 'A senha é obrigatória.',
            'senha.min' => 'A senha deve ter no mínimo 3 caracteres.',
        ]);

        Usuario::create($request->all());

        return redirect('/login');
    }

    public function logout()
    {
        session()->forget(['usuario_logado', 'usuario_id', 'usuario_nome', 'usuario_login', 'usuario_email']);
        session()->flush();

        return redirect('/login');
    }
}
