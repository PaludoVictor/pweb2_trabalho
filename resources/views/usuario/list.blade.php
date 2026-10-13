@extends('main')

@section('titulo', 'Gerenciar Usuários - FinanSys')

@section('conteudo')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gerenciar Usuários</h2>
    <a href="{{ url('usuario/create') }}" class="btn btn-success">+ Novo Usuário</a>
</div>

<form action="{{ route('usuario.search') }}" method="POST" class="mb-4">
    @csrf
    <div class="row g-2 align-items-center">
        <div class="col-md-3">
            <select name="tipo" class="form-select">
                <option value="nome">Nome</option>
                <option value="login">Login</option>
                <option value="email">E-mail</option>
            </select>
        </div>
        <div class="col-md-6">
            <input type="text" name="valor" class="form-control" placeholder="Buscar usuário por nome, login ou e-mail...">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-magnifying-glass me-1"></i> Pesquisar</button>
            <a href="{{ url('usuario') }}" class="btn btn-outline-secondary">Limpar</a>
        </div>
    </div>
</form>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th>Login</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dados as $u)
                <tr>
                    <td>{{ $u->id }}</td>
                    <td class="fw-semibold">
                        <i class="fa-solid fa-user-circle text-secondary me-1"></i> {{ $u->nome }}
                    </td>
                    <td>{{ $u->telefone }}</td>
                    <td>{{ $u->email }}</td>
                    <td><code>{{ $u->login }}</code></td>
                    <td>
                        <a href="{{ route('usuario.edit', $u->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('usuario.destroy', $u->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir este usuário?');">Excluir</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-3 text-muted">Nenhum usuário cadastrado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop
