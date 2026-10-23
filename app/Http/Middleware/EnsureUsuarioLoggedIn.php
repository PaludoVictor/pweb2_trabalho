<?php

namespace App\Http\Middleware;

use App\Models\Usuario;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUsuarioLoggedIn
{
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->session()->get('usuario_id');

        if (!$id || !Usuario::whereKey($id)->exists()) {
            $request->session()->forget(['usuario_logado', 'usuario_id', 'usuario_nome', 'usuario_login', 'usuario_email', 'usuario_imagem']);

            return redirect()->route('login');
        }

        return $next($request);
    }
}
