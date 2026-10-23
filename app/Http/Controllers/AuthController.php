<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

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
            'login' => 'required|string|max:50',
            'senha' => 'required',
        ], [
            'login.required' => 'O usuário/login é obrigatório.',
            'senha.required' => 'A senha é obrigatória.',
        ]);

        $usuario = Usuario::where('login', $request->login)->first();

        if ($usuario && Hash::check($request->senha, $usuario->senha)) {
            $request->session()->regenerate();
            session([
                'usuario_logado' => true,
                'usuario_id' => $usuario->id,
                'usuario_nome' => $usuario->nome,
                'usuario_login' => $usuario->login,
                'usuario_email' => $usuario->email,
                'usuario_imagem' => $usuario->imagem,
            ]);

            return redirect('/');
        }

        return back()->with('error', 'Login ou senha incorretos!')->withInput();
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
