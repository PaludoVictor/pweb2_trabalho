<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - FinanSys</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f3f4f6; font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }
    </style>
</head>
<body class="d-flex align-items-center vh-100">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <h3 class="text-center mb-4 text-success fw-bold">Criar Conta FinanSys</h3>
                        
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register.post') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nome Completo</label>
                                <input type="text" name="nome" class="form-control" value="{{ old('nome') }}" required placeholder="Digite seu nome completo">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Telefone</label>
                                <input type="text" name="telefone" class="form-control" value="{{ old('telefone') }}" required placeholder="(49) 99999-9999">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">E-mail</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="seuemail@exemplo.com">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Usuário (Login)</label>
                                <input type="text" name="login" class="form-control" value="{{ old('login') }}" required placeholder="Escolha um nome de usuário">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Senha</label>
                                <input type="password" name="senha" class="form-control" required placeholder="Escolha uma senha segura">
                            </div>
                            <button type="submit" class="btn btn-success w-100 py-2 mb-3">Registrar</button>
                            <div class="text-center">
                                <a href="{{ route('login') }}" class="text-decoration-none text-muted small">Já possui uma conta? Entrar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
